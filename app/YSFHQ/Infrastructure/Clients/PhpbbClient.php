<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;
use GuzzleHttp\Client as HttpClient,
    Illuminate\Support\Facades\Config,
    Illuminate\Support\Facades\Log;
use YSFHQ\Migrator\Post;

class PhpbbClient extends DatabaseClient
{

    private $http;

    public function __construct($per_page = 1000, $page = 1)
    {
        $this->http = new HttpClient([
            'base_url' => Config::get('services.ysfhq.phpbb_url'),
            'defaults' => [
                'headers' => [
                    'User-Agent' => Config::get('services.ysfhq.user_agent')
                ]
            ]
        ]);
        parent::__construct($per_page, $page);
        if (!$this->login(Config::get('services.ysfhq.phpbb_username'), Config::get('services.ysfhq.phpbb_password'))) {
            Log::error('Failed login to phpBB.');
        }
    }

    private function login($username = '', $password = '')
    {
        $request = $this->http->createRequest('POST', 'ucp.php', [
            'cookies' => true,
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
        return $response->getStatusCode() == 200;
    }

    private function logout()
    {
        return false;
    }

    public function getPosts($page = 1)
    {
        return $this->getConnection('phpbb')
            ->table('phpbb_posts')
            ->skip($this->per_page * ($page - 1))->take($this->per_page)
            ->get();
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
                $result = $this->postReply($attributes['forum_id'], $attributes['topic_id'], $attributes['subject'], $attributes['body']);
            } else {
                $result = $this->postNewTopic($attributes['forum_id'], $attributes['subject'], $attributes['body']);
            }
            Log::info($result);
            // get topic or post id from result
            return 1; // phpBB post ID
        }
        return -1;
    }

    private function postNewTopic($forum_id, $topic_title, $message)
    {
        $token = $this->getToken($forum_id);
        $request = $this->http->createRequest('POST', 'posting.php', [
            'cookies' => true,
            'allow_redirects' => true,
            'query' => ['mode' => 'post', 'f' => $forum_id],
            'body' => [
                'subject' => $topic_title,
                'addbbcode20' => 100,
                'message' => $message,
                'lastclick' => time(),
                'post' => 'Submit',
                'topictype' => 0,
                'disable_bbcode' => 0,
                'disable_smilies' => 0,
                'disable_magic_url' => 0,
                'attach_sig' => 0,
                'topic_time_limit' => 0,
                'show_panel' => 'options-panel',
                'creation_time' => time(),
                'form_token' => $token,
                'poll_title' => '',
                'poll_option_text' => '',
                'poll_max_options' => 1,
                'poll_length' => 0
            ]
        ]);
        $response = $this->http->send($request);
        return $response->getEffectiveUrl();
    }

    private function postReply()
    {
        return false;
    }

    private function getToken($forum_id, $topic_id = '')
    {
        $response = $this->http->get('posting.php', [
            'query' => [
                'mode' => $topic_id ? 'reply' : 'post',
                'f' => $forum_id,
                't' => $topic_id
            ],
        ]);
        if ($body = $response->getBody()) {
            $token = substr($body, strpos($body, '<input type="hidden" name="form_token" value="')+46);
            $token = substr($token, 0, strpos($token, '"/>'));
            Log::info('token: '.$token);
            return $token;
        }
        return false;
    }

}
