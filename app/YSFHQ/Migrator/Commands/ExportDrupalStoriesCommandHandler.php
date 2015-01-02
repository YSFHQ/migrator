<?php namespace YSFHQ\Migrator\Commands;

use Laracasts\Commander\CommandHandler,
    YSFHQ\Infrastructure\Clients\DrupalClient;

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

    }

}
