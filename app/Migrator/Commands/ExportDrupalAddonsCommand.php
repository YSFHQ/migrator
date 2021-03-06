<?php

namespace YSFHQ\Migrator\Commands;

use Carbon\Carbon,
    Illuminate\Support\Facades\Queue;
use YSFHQ\Infrastructure\Clients\DrupalClient,
    YSFHQ\Infrastructure\Helpers\BBCodeHelper,
    YSFHQ\Migrator\Post;
use YSFHQ\Infrastructure\Command;

class ExportDrupalAddonsCommand extends Command
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
        while ($addons = $drupal->getAddons($page)) {
            foreach ($addons as $addon) {
                $post = new Post;
                $post->legacy_id = $addon->nid;
                $post->source = 'drupal';
                $post->type = 'addon';
                $post->username = $addon->name;
                $post->subject = '[' . strtoupper($addon->field_modtype_value) . '] ' . $addon->title;

                if (!$addon->field_dl_title)
                    $addon->field_dl_title = 'DOWNLOAD';
                if ($addon->field_blurb_value)
                    $addon->field_blurb_value = "\n" . $addon->field_blurb_value;
                if ($addon->field_prev_title)
                    $addon->field_prev_title .= "\n";
                if (!$addon->name)
                    $addon->name = '(Unknown)';

                $addon->field_modtype_value = ucwords($addon->field_modtype_value);
                $addon->field_desc_value = BBCodeHelper::convertHtmlToBBCode($addon->field_desc_value);
                $addon->field_credit_value = BBCodeHelper::convertHtmlToBBCode($addon->field_credit_value);

                $post->body = <<<EOT
[size=150]$addon->title[/size]
[i]Category: $addon->field_modtype_value[/i]$addon->field_blurb_value

$addon->field_prev_title[img]$addon->field_prev_url[/img]

$addon->field_desc_value

---

Originally posted by $addon->name
[u]Credits:[/u]
$addon->field_credit_value

[size=150][url=$addon->field_dl_url]$addon->field_dl_title[/url][/size]
EOT;

                $post->forum_id = 279;
                switch (strtolower($addon->field_modtype_value)) {
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
                Queue::push('YSFHQ\Migrator\Tasks\PostTasks@makePost', ['id' => $post->id]);
            }
            echo 'Page ' . $page . ' complete' . PHP_EOL;
            $page++;
        }
    }

}
