<?php

namespace YSFHQ\Console\Commands;

use Illuminate\Console\Command;
use YSFHQ\Migrator\Activities as MigratorActivities;

class BbcodePhpbbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpbb:fixbbcode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix old BBCode, replacing the bitfield on each post.';

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
        $this->info('Starting migration...');
        $migrator->fixAlignBBCode();
    }
}
