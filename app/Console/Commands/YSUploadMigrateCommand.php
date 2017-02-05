<?php

namespace YSFHQ\Console\Commands;

use Illuminate\Console\Command;

class YSUploadMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ysupload:migrate {stage : Export either the metadata or transfer files. Use "meta" for metadata export, or "files" for transferring files onto the forum}';

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
    public function handle()
    {
        $migrator = new MigratorActivities();
        switch ($this->argument('stage')) {
            case 'meta':
                $this->info('Starting addon metadata export process from YSUpload...');
                $migrator->exportAddonMetaFromYSUpload();
                $this->info('Metadata export complete.');
                break;
            case 'files':
                $this->info('Starting addon file transfer process from YSUpload...');
                $migrator->transferYSUploadFiles();
                $this->info('File export complete.');
                break;
            default:
                $this->error('Invalid argument. Choose from "meta" or "files".');
        }
    }
}
