<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\DrupalClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;

class ExportDrupalStoriesCommandHandler implements CommandHandler {

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
        while ($stories = $this->drupal->getStories($page)) {
            foreach ($stories as $story) {
                $story->body = BBCodeHelper::convertHtmlToBBCode($story->body);
                $story->teaser = BBCodeHelper::convertHtmlToBBCode($story->teaser);

                $post = new Post;
                $post->legacy_id = $story->nid;
                $post->source = 'drupal';
                $post->poster_username = $story->name;
                $post->post_subject = $story->title;
                $post->post_text = <<<EOT
$story->teaser

[u][size=150]$story->title[/size][/u]
[i]by $story->name[/i]

$story->body
EOT;
                $post->topic_id = null;
                $post->forum_id = 282;
                $post->posted_on = Carbon::createFromTimeStamp($story->created)->toDateTimeString();
                $post->save();
            }
            echo 'Page '.$page.' complete'.PHP_EOL;
            $page++;
        }
    }

}
