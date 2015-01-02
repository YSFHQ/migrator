<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use YSFHQ\Migrator\Activities as MigratorActivities;

class BbcodePhpbbCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'phpbb:fixbbcode';

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
    public function fire()
    {
        $migrator = new MigratorActivities();
        $this->info('Starting migration...');
        $migrator->fixAlignBBCode();

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
