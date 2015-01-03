<?php namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\DrupalClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;

class ExportDrupalAddonsCommandHandler implements CommandHandler {

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
        while ($addons = $this->drupal->getAddons($page)) {
            foreach ($addons as $addon) {
                $addon->field_modtype_value = ucwords($addon->field_modtype_value);
                if (empty($addon->field_dl_title)) $addon->field_dl_title = 'DOWNLOAD';
                if (!empty($addon->field_blurb_value)) $addon->field_blurb_value = "\n" + $addon->field_blurb_value;
                if (!empty($addon->field_prev_title)) $addon->field_prev_title += "\n";
                if (empty($addon->name)) $addon->name = '(Unknown)';
                $addon->field_desc_value = BBCodeHelper::convertHtmlToBBCode($addon->field_desc_value);
                $addon->field_credit_value = BBCodeHelper::convertHtmlToBBCode($addon->field_credit_value);

                $post = new Post;
                $post->legacy_id = $addon->nid;
                $post->source = 'drupal';
                $post->poster_username = $addon->name;
                $post->post_subject = $addon->title;
                $post->post_text = <<<EOT
[size=150]$addon->title[/size]
[i]Category: $addon->field_modtype_value[/i]$addon->field_blurb_value

$addon->field_prev_title[img]$addon->field_prev_url[/img]

$addon->field_desc_value

---

Originally posted by $addon->name

Credits:
$addon->field_credit_value

[size=150][url=$addon->field_dl_url]$addon->field_dl_title[/url][/size]
EOT;
                $post->topic_id = null;
                $post->forum_id = 279;
                switch ($addon->field_modtype_value) {
                    case 'aircraft':
                        $post->forum_id = 275;
                        break;
                    case 'scenery':
                        $post->forum_id = 277;
                        break;
                    case 'weapon':
                        $post->forum_id = 278;
                        break;
                    case 'pack':
                    case 'hqpack':
                        $post->forum_id = 276;
                        break;
                    case 'misc':
                        $post->forum_id = 279;
                        break;
                }
                $post->posted_on = Carbon::createFromTimeStamp($addon->created)->toDateTimeString();
                $post->save();
            }
            echo 'Page '.$page.' complete'.PHP_EOL;
            $page++;
        }
    }

}
