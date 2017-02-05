<?php

namespace YSFHQ\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use YSFHQ\Migrator\Post;
use YSFHQ\Redirector\Path;

class Redirector extends Controller
{
    public function redirect(Request $request)
    {
        preg_match('/https?:\/\/(.*)/', $request->root(), $matches);
        $domain = $matches[1];
        $route = strtolower($request->path());
        if ($route != '/') $route = '/'.$route;

        if (in_array($domain, ['drupal.ysfhq.com'])) {
            $domain = 'drupal';
            if (starts_with($route, '/node/')) {
                $nid = substr($route, strrpos($route, '/') + 1);
                $post = Post::where('source', 'drupal')->where('legacy_id', $nid)->first();
            }
        }
        if (in_array($domain, ['ysupload.com', 'www.ysupload.com', 'ysu.ysfhq.com'])) {
            $domain = 'ysupload';
            if (starts_with($route, '/download.php') || starts_with($route, '/getfile.php') || starts_with($route, '/ysu3/getfile.php') || starts_with($route, '/ysu3/download.php')) {
                $id = $request->get('id');
                if ($id) {
                    $post = Post::where('source', 'ysupload')->where('legacy_id', $id)->first();
                }
            }
            if (isset($post) && starts_with($route, '/getfile.php') && $file = $post->attachment) {
                if ($file->phpbb_attachment_id) {
                    $dest = Config::get('services.ysfhq.phpbb_url').'download/file.php?id=' . $file->phpbb_attachment_id;
                }
            }
        }

        if (!isset($dest) && isset($post) && $post->phpbb_id) {
            $dest = Config::get('services.ysfhq.phpbb_url').'viewtopic.php?p=' . $post->phpbb_id . '#p' . $post->phpbb_id;
        }

        if (!isset($dest)) {
            $path = Path::where('domain', $domain)->where('source', $route)->first();
            if ($path) {
                $dest = $path->dest;
            } else {
                Log::error('No redirect found for URL: ' . $request->fullUrl());
                $dest = Config::get('services.ysfhq.phpbb_url');
            }
        }

        return redirect()->to($dest, 301);
    }
}
