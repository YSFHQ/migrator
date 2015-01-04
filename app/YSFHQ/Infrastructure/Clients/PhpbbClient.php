<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;
use GuzzleHttp\Client as HttpClient,
    GuzzleHttp\Cookie\CookieJar,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Log;
use YSFHQ\Migrator\Post;

class PhpbbClient extends DatabaseClient
{

    private $http;
    private $cookies;

    public function __construct($per_page = 1000, $page = 1)
    {
        $this->cookies = new CookieJar(false, Config::get('services.ysfhq.cookies'));
        $this->http = new HttpClient([
            'base_url' => Config::get('services.ysfhq.phpbb_url'),
            'defaults' => [
                'headers' => [
                    'User-Agent' => Config::get('services.ysfhq.user_agent')
                ]
            ]
        ]);
        parent::__construct($per_page, $page);
        // if (!$this->login(Config::get('services.ysfhq.phpbb_username'), Config::get('services.ysfhq.phpbb_password'))) {
        //     Log::error('Failed login to phpBB.');
        // }
    }

    private function login($username = '', $password = '')
    {
        $request = $this->http->createRequest('POST', 'ucp.php', [
            'cookies' => $this->cookies,
            'allow_redirects' => true,
            'query' => ['mode' => 'login'],
            'body' => [
                'username' => $username,
                'password' => $password,
                'autologin' => 1,
                'redirect' => 'index.php',
                'login' => 'Login'
            ]
        ]);
        $response = $this->http->send($request);
        // we should instead check to see if cookie has been set
        return $response->getStatusCode() == 200;
    }

    private function logout()
    {
        return false;
    }

    public function getUserIdByUsername($username = '')
    {
        return $this->getConnection('phpbb')
            ->table('phpbb_users')
            ->where('username', $username)
            ->orWhere('username_clean', strtolower($username))
            ->pluck('user_id');
    }

    public function getPosts($page = 1)
    {
        return $this->getConnection('phpbb')
            ->table('phpbb_posts')
            ->skip($this->per_page * ($page - 1))->take($this->per_page)
            ->get();
    }

    public function getPostDataFromId($id = null)
    {
        return $this->getConnection('phpbb')
            ->table('phpbb_posts')
            ->where('post_id', $id)
            ->first();
    }

    public function updatePost($id = null, $attributes = [])
    {
        return $this->getConnection('phpbb')
            ->table('phpbb_posts')
            ->where('post_id', $id)
            ->update($attributes);
    }

    public function makePost($attributes = [])
    {
        if (count($attributes)) {
            if ($attributes['topic_id']) {
                $response = $this->postReply(
                    $attributes['forum_id'],
                    $attributes['topic_id'],
                    $attributes['subject'],
                    $attributes['body']
                );
                $url = $response->getEffectiveUrl();
                if (strpos($url, '#p')) {
                    // now we get the post id from the URL
                    $post_id = substr($url, strpos($url, '#p')+2);
                    return intval($post_id);
                }
            } else {
                $response = $this->postNewTopic($attributes['forum_id'], $attributes['subject'], $attributes['body']);
                $url = $response->getEffectiveUrl();
                if (strpos($url, 'viewtopic.php')) {
                    // get topic or post id from result body
                    $post_id = $response->getBody();
                    $post_id = substr($post_id, strpos($post_id, '<div id="post_content')+21);
                    $post_id = substr($post_id, 0, strpos($post_id, '">'));
                    return intval($post_id);
                }
            }
        }
        return -1;
    }

    private function postNewTopic($forum_id, $topic_title, $message)
    {
        $fields = [
            'subject' => $topic_title,
            'addbbcode20' => 100,
            'message' => $message,
            'lastclick' => time()-30,
            'post' => 'Submit',
            'topictype' => 0,
            // 'disable_bbcode' => false,
            // 'disable_smilies' => false,
            // 'disable_magic_url' => false,
            // 'attach_sig' => false,
            'topic_time_limit' => 0,
            'show_panel' => 'options-panel',
            'creation_time' => time()-60,
            'form_token' => sha1(time()), // random string, our modified phpBB does not check tokens
            'poll_title' => '',
            'poll_option_text' => '',
            'poll_max_options' => 1,
            'poll_length' => 0
        ];

        $request = $this->http->createRequest('POST', 'posting.php', [
            'cookies' => $this->cookies,
            'allow_redirects' => true,
            'query' => ['mode' => 'post', 'f' => $forum_id],
            'body' => $fields
        ]);

        return $this->http->send($request);
    }

    private function postReply($forum_id, $topic_id, $topic_title, $message)
    {
        $fields = [
            'subject' => $topic_title,
            'addbbcode20' => 100,
            'message' => $message,
            // 'topic_cur_post_id' => 1,
            'lastclick' => time()-30,
            'post' => 'Submit',
            // 'disable_bbcode' => false,
            // 'disable_smilies' => false,
            // 'disable_magic_url' => false,
            // 'attach_sig' => false,
            'topic_time_limit' => 0,
            'show_panel' => 'options-panel',
            'creation_time' => time()-60,
            'form_token' => sha1(time()) // random string, our modified phpBB does not check tokens
        ];

        $request = $this->http->createRequest('POST', 'posting.php', [
            'cookies' => $this->cookies,
            'allow_redirects' => true,
            'query' => ['mode' => 'reply', 'f' => $forum_id, 't' => $topic_id],
            'body' => $fields
        ]);

        return $this->http->send($request);
    }

    public function saveAttachment($file = null)
    {
        if ($file) {
            $id = $this->getConnection('phpbb')->table('phpbb_attachments')->insertGetId([
                'post_msg_id'           => $file->post_msg_id,
                'topic_id'              => $file->topic_id,
                'in_message'            => '0',
                'poster_id'             => $file->poster_id,
                'is_orphan'             => '0',
                'physical_filename'     => $file->physical_filename,
                'real_filename'         => $file->real_filename,
                'download_count'        => $file->download_count,
                'attach_comment'        => '',
                'extension'             => $file->extension,
                'mimetype'              => $file->mimetype,
                'filesize'              => $file->filesize,
                'filetime'              => $file->filetime,
                'thumbnail'             => '0'
            ]);
            if ($id) {
                $this->getConnection('phpbb')->table('phpbb_posts')
                    ->where('post_id', $file->post_msg_id)->update(['post_attachment' => '1']);
                return $id;
            }
        }

        return null;
        // INSERT INTO `phpbb`.`phpbb_attachments` (`attach_id`, `post_msg_id`, `topic_id`, `in_message`, `poster_id`, `is_orphan`, `physical_filename`, `real_filename`, `download_count`, `attach_comment`, `extension`, `mimetype`, `filesize`, `filetime`, `thumbnail`)
        //      VALUES (NULL, '82703', '7512', '0', '78', '1', '78_5b1d21ee26decf67229149f27797c5d8', 'Tri-Angel.zip', '74', '', 'zip', 'application/zip', '1029631', '1420403353', '0');
        // UPDATE  `phpbb`.`phpbb_posts` SET  `post_attachment` =  '1' WHERE  `phpbb_posts`.`post_id` =82703;
    }

}
