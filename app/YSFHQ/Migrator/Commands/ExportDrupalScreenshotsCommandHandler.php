<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Illuminate\Support\Facades\Queue,
    Laracasts\Commander\CommandHandler;
use YSFHQ\Infrastructure\Clients\DrupalClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;

class ExportDrupalScreenshotsCommandHandler implements CommandHandler {

    private $drupal;

    public function __construct(DrupalClient $drupal)
    {
        $this->drupal = $drupal;
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
        while ($screenshots = $this->drupal->getScreenshots($page)) {
            foreach ($screenshots as $screenshot) {
                $screenshot->body = BBCodeHelper::convertHtmlToBBCode($screenshot->body);
                $screenshot->teaser = BBCodeHelper::convertHtmlToBBCode($screenshot->teaser);

                $post = new Post;
                $post->legacy_id = $screenshot->nid;
                $post->source = 'drupal';
                $post->type = 'screenshot';
                $post->username = $screenshot->name;
                $post->subject = $screenshot->title;
                $post->body = <<<EOT
$screenshot->field_url_title
[url=$screenshot->field_url_url][img]$screenshot->field_url_url[/img][/url]
[size=150]$screenshot->title[/size]
[i]by $screenshot->name[/i]
$screenshot->body
EOT;
                $post->topic_id = 6924;
                $post->forum_id = 281;
                $post->posted_on = Carbon::createFromTimeStamp($screenshot->created)->toDateTimeString();
                $post->save();
                Queue::push('YSFHQ\Migrator\Tasks\ImportTasks@makePost', ['id' => $post->id]);
            }
            echo 'Page '.$page.' complete'.PHP_EOL;
            $page++;
        }
    }

}
