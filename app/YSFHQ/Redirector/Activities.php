<?php namespace YSFHQ\Redirector;

use Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Input,
    Illuminate\Support\Facades\Request,
    Illuminate\Support\Facades\Redirect;

use YSFHQ\Migrator\Attachment,
    YSFHQ\Migrator\Post,
    YSFHQ\Redirector\Path;

class Activities
{

    public function getRedirectUrl($path)
    {
        $domain = Request::root();
        $route = strtolower(str_replace($domain, '', Request::fullUrl()));

        if (strpos($domain, 'drupal.ysfhq.com')!==false) {
            $domain = 'drupal';
            if (starts_with($route, '/node/')) {
                $nid = substr($route, strrpos($route, '/')+1);
                $post = Post::where('source', 'drupal')->where('legacy_id', $nid)->first();
            }
        }
        if (strpos($domain, 'ysupload.com')!==false) {
            $domain = 'ysupload';
            if (starts_with($route, '/download.php') || starts_with($route, '/getfile.php')) {
                $id = Input::get('id');
                if ($id) {
                    $post = Post::where('source', 'ysupload')->where('legacy_id', $id)->first();
                }
            }
            if (isset($post) && starts_with($route, '/getfile.php') && $file = $post->attachment) {
                if ($file->phpbb_attachment_id) {
                    $dest = 'http://forum.ysfhq.com/download/file.php?id='.$file->phpbb_attachment_id;
                }
            }
        }

        if (!isset($dest) && isset($post) && $post->phpbb_id) {
            $dest = 'http://forum.ysfhq.com/viewtopic.php?p='.$post->phpbb_id.'#p'.$post->phpbb_id;
        }

        if (!isset($dest)) {
            $path = Path::where('domain', $domain)->where('source', $route)->first();
            if ($path) {
                $dest = $path->dest;
            } else {
                Log::error('No redirect found for URL: '.Request::fullUrl());
                if ($domain == 'ysupload') {
                    $dest = 'http://forum.ysfhq.com/viewforum.php?f=234';
                } else {
                    $dest = 'http://forum.ysfhq.com/';
                }
            }
        }

        return $dest;
    }

}
