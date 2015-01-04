<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Illuminate\Support\Facades\Queue,
    Laracasts\Commander\CommandHandler;
use YSFHQ\Infrastructure\Clients\YSUploadClient,
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

                $post->forum_id = 264;
                switch ($addon->meta_category) {
                    case 'Aircraft':
                    case 'Challenge':
                        $post->forum_id = 169;
                        break;
                    case 'Maps':
                    case 'Scenery':
                        $post->forum_id = 170;
                        break;
                    case 'Applications':
                        $post->forum_id = 236;
                        break;
                    case 'Miscellaneous':
                    case 'Weapons':
                        $post->forum_id = 235;
                        break;
                    case 'The Dump':
                        $post->forum_id = 265;
                        break;
                }
                $post->posted_on = $addon->upload_timestamp;
                $post->save();
                Queue::push('YSFHQ\Migrator\Tasks\ImportTasks@makePost', ['id' => $post->id]);
            }
            echo 'Page '.$page.' complete'.PHP_EOL;
            $page++;
        }
    }

}
