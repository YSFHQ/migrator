<?php namespace YSFHQ\Migrator;

use \Exception;
use Illuminate\Support\Facades\Log;
use Laracasts\Commander\CommanderTrait;

use YSFHQ\Migrator\Commands\FixBbcodeCommand;

class Activities {

    public function fixAlignBBCode()
    {
        return $this->execute(
            FixBbcodeCommand::class,
            ['old_bbcode_id' => 88, 'new_bbcode_id' => 130]
        );
    }

}
