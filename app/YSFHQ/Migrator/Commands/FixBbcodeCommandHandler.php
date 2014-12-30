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
        $posts = $this->client->getPosts(1);

        foreach ($posts as $post) {

            if ($post['bbcode_bitfield']) {

                $bitfield = new BitfieldHelper($post['bbcode_bitfield']);

                if (in_array(88, $bitfield->get_all_set())) {
                    $bitfield->clear(88);
                    $bitfield->set(130);
                    $new_bitfield = $bitfield->get_base64();
                    // update
                }
            }

        }
    }

}
