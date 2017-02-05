<?php

namespace YSFHQ\Console\Commands;

use Illuminate\Console\Command;
use YSFHQ\Migrator\Activities as MigratorActivities;

class DrupalMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drupal:migrate {--only=}';

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
    public function handle()
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
}
