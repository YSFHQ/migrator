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
    protected $name = 'drupal:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate content from Drupal into phpBB.';

    private $migrator;

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
        $this->migrator = new MigratorActivities();

        if ($only = $this->option('only')) {
            if (in_array($only, ['addon', 'screenshot', 'video', 'story'])) {
                $this->{'export'.ucwords($this->option('only'))}();
            } else {
                $this->error('Invalid option value.');
            }
        } else {
            $this->exportAddon();
            $this->exportScreenshot();
            $this->exportVideo();
            $this->exportStory();
        }
    }

    private function exportAddon()
    {
        $this->info('Starting export of addons from Drupal...');
        $this->migrator->exportAddonsFromDrupal();
        $this->info('Addon export complete.');
    }

    private function exportScreenshot()
    {
        $this->info('Starting export of screenshots from Drupal...');
        $this->migrator->exportScreenshotsFromDrupal();
        $this->info('Screenshot export complete.');
    }

    private function exportVideo()
    {
        $this->info('Starting export of videos from Drupal...');
        $this->migrator->exportVideosFromDrupal();
        $this->info('Video export complete.');
    }

    private function exportStory()
    {
        $this->info('Starting export of stories from Drupal...');
        $this->migrator->exportStoriesFromDrupal();
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
            array('only', 'o', InputOption::VALUE_OPTIONAL, 'Only import one datatype, either: addon, screenshot, video, story', null),
        );
    }

}
