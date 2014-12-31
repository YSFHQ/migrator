<?php namespace YSFHQ\Migrator\Commands;

use Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\PhpbbClient,
    YSFHQ\Infrastructure\Helpers\BitfieldHelper;

class FixBbcodeCommandHandler implements CommandHandler
{
    private $client;

    public function __construct(PhpbbClient $client)
    {
        $this->client = $client;
    }

    public function handle($command)
    {
        $page = 1;
        while ($posts = $this->client->getPosts($page)) {
            foreach ($posts as $post) {
                if ($post['bbcode_bitfield']) {

                    $bitfield = new BitfieldHelper($post['bbcode_bitfield']);

                    if (in_array($command->old_bbcode_id, $bitfield->get_all_set())) {
                        $bitfield->clear($command->old_bbcode_id);
                        $bitfield->set($command->new_bbcode_id);
                        $new_bitfield = $bitfield->get_base64();
                        echo $new_bitfield;
                        // update
                    }
                }
            }
            $page++;
        }
    }

}
