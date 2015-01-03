<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\DrupalClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;

class ExportDrupalVideosCommandHandler implements CommandHandler {

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
        while ($videos = $this->drupal->getVideos($page)) {
            foreach ($videos as $video) {
                $video->body = BBCodeHelper::convertHtmlToBBCode($video->body);
                $video->teaser = BBCodeHelper::convertHtmlToBBCode($video->teaser);
                $minutes = floor($video->field_vid_duration / 60);
                $seconds = $video->field_vid_duration - ($minutes*60);
                $embedded = '';
                if ($video->field_vid_provider=='youtube' && !empty($video->field_vid_value)) {
                    $embedded = "\n[youtube]http://www.youtube.com/watch?v=$video->field_vid_value[/youtube]\n";
                }

                $post = new Post;
                $post->legacy_id = $video->nid;
                $post->source = 'drupal';
                $post->poster_username = $video->name;
                $post->post_subject = $video->title;
                $post->post_text = <<<EOT
[url=$video->field_vid_embed][size=150]$video->title [$minutes:$seconds][/size][/url]
[i]by $video->name[/i]$embedded
$video->body
EOT;
                $post->new_topic = false;
                $post->forum_id = 281;
                $post->posted_on = Carbon::createFromTimeStamp($video->created)->toDateTimeString();
                $post->save();
            }
            echo 'Page '.$page.' complete'.PHP_EOL;
            $page++;
        }
    }

}
