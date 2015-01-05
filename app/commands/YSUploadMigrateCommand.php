<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use YSFHQ\Migrator\Activities as MigratorActivities;

class YSUploadMigrateCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ysupload:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate content from YSUpload into phpBB.';

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
        switch ($this->argument('stage')) {
            case 'meta':
                $this->info('Starting addon metadata export process from YSUpload...');
                $migrator->exportAddonMetaFromYSUpload();
                $this->info('Metadata export complete.');
                break;
            case 'files':
                $this->info('Starting addon file export process from YSUpload...');
                $migrator->exportAddonDataFromYSUpload();
                $this->info('File export complete.');
                break;
            default:
                $this->error('Invalid argument. Choose from "meta" or "files".');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('stage', InputArgument::REQUIRED, 'Export either the metadata or transfer files. Use "meta" for metadata export, or "files" for transferring files onto the forum.'),
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
