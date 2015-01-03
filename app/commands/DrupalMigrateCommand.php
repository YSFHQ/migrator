<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use YSFHQ\Migrator\Activities as MigratorActivities;

class DrupalMigrateCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'drupal:migrateall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $migrator = new MigratorActivities();
        $this->info('Starting export of addons from Drupal...');
        $migrator->exportAddonsFromDrupal();
        $this->info('Addon export complete.');

        $this->info('Starting export of screenshots from Drupal...');
        $migrator->exportScreenshotsFromDrupal();
        $this->info('Screenshot export complete.');

        $this->info('Starting export of videos from Drupal...');
        $migrator->exportVideosFromDrupal();
        $this->info('Video export complete.');

        $this->info('Starting export of stories from Drupal...');
        $migrator->exportStoriesFromDrupal();
        $this->info('Story export complete.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            // array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            // array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
