<?php

namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Illuminate\Support\Facades\Queue;
use YSFHQ\Infrastructure\Clients\DrupalClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;
use YSFHQ\Infrastructure\Command;

class ExportDrupalStoriesCommand extends Command
{

    /**
     * Handle the command.
     *
     * @param DrupalClient $drupal
     * @return void
     */
    public function handle(DrupalClient $drupal)
    {
        $page = 1;
        while ($stories = $drupal->getStories($page)) {
            foreach ($stories as $story) {
                $story->body = BBCodeHelper::convertHtmlToBBCode($story->body);
                $story->teaser = BBCodeHelper::convertHtmlToBBCode($story->teaser);

                $post = new Post;
                $post->legacy_id = $story->nid;
                $post->source = 'drupal';
                $post->type = 'story';
                $post->username = $story->name;
                $post->subject = $story->title;
                $post->body = <<<EOT
[u][size=150]$story->title[/size][/u]
[i]by $story->name[/i]

$story->body
EOT;
                $post->forum_id = 282;
                $post->posted_on = Carbon::createFromTimeStamp($story->created)->toDateTimeString();
                $post->save();
                Queue::push('YSFHQ\Migrator\Tasks\PostTasks@makePost', ['id' => $post->id]);
            }
            echo 'Page ' . $page . ' complete' . PHP_EOL;
            $page++;
        }
    }

}
