<?php namespace YSFHQ\Migrator\Commands;

use Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\PhpbbClient,
    YSFHQ\Infrastructure\Helpers\BitfieldHelper;

class FixBbcodeCommandHandler implements CommandHandler
{
    private $phpbb;

    public function __construct(PhpbbClient $phpbb)
    {
        $this->phpbb = $phpbb;
    }

    public function handle($command)
    {
        $page = 1;
        while ($posts = $this->phpbb->getPosts($page)) {
            foreach ($posts as $post) {
                if ($post->bbcode_bitfield) {
                    $bitfield = new BitfieldHelper($post->bbcode_bitfield);

                    if (in_array($command->old_bbcode_id, $bitfield->get_all_set())) {
                        $bitfield->clear($command->old_bbcode_id);
                        $bitfield->set($command->new_bbcode_id);
                        $new_bitfield = $bitfield->get_base64();

                        if ($this->phpbb->updatePost($post->post_id, ['bbcode_bitfield' => $new_bitfield])) {
                            echo 'Updated BBCode of post ID '.$post->post_id.PHP_EOL;
                        } else {
                            echo 'Error trying to update post ID '.$post->post_id.PHP_EOL;
                        }
                    }
                }
            }

            echo 'Page '.$page.' complete'.PHP_EOL;
            $page++;
        }
    }

}
