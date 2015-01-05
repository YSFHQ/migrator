<?php namespace YSFHQ\Migrator;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent {

    protected $table = 'posts';

    public function attachment()
    {
        return $this->hasOne('Attachment', 'post_id');
    }

    public static function findYSUploadForumPost($ysupload_id = null)
    {
        if ($ysupload_id) {
            $possible_posts = Post::where('source', 'drupal')->where('type', 'addon')->where('phpbb_id', '>', 0)->get();
            foreach ($possible_posts as $post) {
                $matches = [];
                if (preg_match("/\[size=150\]\[url=(.*?)ysupload.com\/(download|getfile)\.php\?id=(\d+)\](.*?)\[\/url\]\[\/size\]/", $post->body, $matches)) {
                    if ($ysupload_id == $matches[3]) return $post->phpbb_id;
                }
            }
        }
        return 0;
    }

}
