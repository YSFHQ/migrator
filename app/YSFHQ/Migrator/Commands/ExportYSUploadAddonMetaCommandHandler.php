<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Illuminate\Support\Facades\Queue,
    Laracasts\Commander\CommandHandler;
use YSFHQ\Infrastructure\Clients\YSUploadClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;

class ExportYSUploadAddonMetaCommandHandler implements CommandHandler {

    private $ysupload;

    public function __construct(YSUploadClient $ysupload)
    {
        $this->ysupload = $ysupload;
    }

    /**
     * Handle the command.
     *
     * @param object $command
     * @return void
     */
    public function handle($command)
    {
        $page = 1;
        while ($addons = $this->ysupload->getFileMeta($page)) {
            foreach ($addons as $addon) {
                $post = new Post;
                $post->legacy_id = $addon->id;
                $post->source = 'ysupload';
                $post->type = 'addon';
                $post->username = $addon->uploader_username;
                $post->subject = '['.strtoupper($addon->meta_category).'] '.$addon->meta_name;

                $addon->meta_desc = BBCodeHelper::convertHtmlToBBCode($addon->meta_desc);

                $post->body = <<<EOT
[size=150]$addon->meta_name[/size]
[i]Category: $addon->meta_category / Version: $addon->version[/i]

[img]$addon->image_url[/img]

$addon->meta_desc

---

Originally posted by $addon->uploader_username

License/Credits: $addon->modPerms

[size=150][url=$addon->filename]DOWNLOAD[/url][/size]
EOT;

                $post->forum_id = 279;
                switch (strtolower($addon->meta_category)) {
                    case 'aircraft':
                    case 'challenge':
                        $post->forum_id = 275;
                        break;
                    case 'maps':
                    case 'scenery':
                        $post->forum_id = 277;
                        break;
                    case 'applications':
                        $post->forum_id = 280;
                        break;
                    case 'miscellaneous':
                    case 'weapons':
                        $post->forum_id = 278;
                        break;
                    case 'the dump':
                        $post->forum_id = 283;
                        break;
                }
                $post->posted_on = $addon->upload_timestamp;

                if ($post_id = Post::findYSUploadForumPost($addon->id)) {
                    $post->phpbb_id = $post_id;
                }

                $post->save();
                if (!isset($post->phpbb_id)) {
                    Queue::push('YSFHQ\Migrator\Tasks\PostTasks@makePost', ['id' => $post->id]);
                }
            }
            echo 'Page '.$page.' complete'.PHP_EOL;
            $page++;
        }
    }

}
