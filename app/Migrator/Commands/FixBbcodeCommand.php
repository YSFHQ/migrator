<?php

namespace YSFHQ\Migrator\Commands;

use YSFHQ\Infrastructure\Clients\PhpbbClient,
    YSFHQ\Infrastructure\Helpers\BitfieldHelper;
use YSFHQ\Infrastructure\Command;

class FixBbcodeCommand extends Command
{
    private $old_bbcode_id, $new_bbcode_id;

    /**
     * FixBbcodeCommand constructor.
     * @param $old_bbcode_id
     * @param $new_bbcode_id
     */
    public function __construct($old_bbcode_id, $new_bbcode_id)
    {
        $this->old_bbcode_id = $old_bbcode_id;
        $this->new_bbcode_id = $new_bbcode_id;
    }

    /**
     * @param PhpbbClient $phpbb
     */
    public function handle(PhpbbClient $phpbb)
    {
        $page = 1;
        while ($posts = $phpbb->getPosts($page)) {
            foreach ($posts as $post) {
                if ($post->bbcode_bitfield) {
                    $bitfield = new BitfieldHelper($post->bbcode_bitfield);

                    if (in_array($this->old_bbcode_id, $bitfield->get_all_set())) {
                        $bitfield->clear($this->old_bbcode_id);
                        $bitfield->set($this->new_bbcode_id);
                        $new_bitfield = $bitfield->get_base64();

                        if ($phpbb->updatePost($post->post_id, ['bbcode_bitfield' => $new_bitfield])) {
                            echo 'Updated BBCode of post ID ' . $post->post_id . PHP_EOL;
                        } else {
                            echo 'Error trying to update post ID ' . $post->post_id . PHP_EOL;
                        }
                    }
                }
            }

            echo 'Page ' . $page . ' complete' . PHP_EOL;
            $page++;
        }
    }

}
