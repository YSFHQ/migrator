<?php

namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Illuminate\Support\Facades\Queue;
use YSFHQ\Infrastructure\Clients\DrupalClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;
use YSFHQ\Infrastructure\Command;

class ExportDrupalVideosCommand extends Command
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
        while ($videos = $drupal->getVideos($page)) {
            foreach ($videos as $video) {
                $video->body = BBCodeHelper::convertHtmlToBBCode($video->body);
                $video->teaser = BBCodeHelper::convertHtmlToBBCode($video->teaser);
                $minutes = floor($video->field_vid_duration / 60);
                $seconds = $video->field_vid_duration - ($minutes * 60);
                $embedded = '';
                if ($video->field_vid_provider == 'youtube' && !empty($video->field_vid_value)) {
                    $embedded = "\n[youtube]http://www.youtube.com/watch?v=$video->field_vid_value[/youtube]\n";
                }

                $post = new Post;
                $post->legacy_id = $video->nid;
                $post->source = 'drupal';
                $post->type = 'video';
                $post->username = $video->name;
                $post->subject = $video->title;
                $post->body = <<<EOT
[url=$video->field_vid_embed][size=150]$video->title [$minutes:$seconds][/size][/url]
[i]by $video->name[/i]$embedded
$video->body
EOT;
                $post->topic_id = 6927;
                $post->forum_id = 281;
                $post->posted_on = Carbon::createFromTimeStamp($video->created)->toDateTimeString();
                $post->save();
                Queue::push('YSFHQ\Migrator\Tasks\PostTasks@makePost', ['id' => $post->id]);
            }
            echo 'Page ' . $page . ' complete' . PHP_EOL;
            $page++;
        }
    }

}
