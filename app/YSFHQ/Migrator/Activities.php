<?php namespace YSFHQ\Migrator;

use \Exception;
use Illuminate\Support\Facades\Log;
use Laracasts\Commander\CommanderTrait;

use YSFHQ\Migrator\Commands\FixBbcodeCommand,
    YSFHQ\Migrator\Commands\ExportDrupalScreenshotsCommand,
    YSFHQ\Migrator\Commands\ExportDrupalVideosCommand,
    YSFHQ\Migrator\Commands\ExportDrupalStoriesCommand,
    YSFHQ\Migrator\Commands\ExportDrupalAddonsCommand,
    YSFHQ\Migrator\Commands\ExportYSUploadAddonMetaCommand,
    YSFHQ\Migrator\Commands\ExportYSUploadAddonDataCommand;

class Activities
{
    use CommanderTrait;

    public function fixAlignBBCode()
    {
        return $this->execute(
            FixBbcodeCommand::class,
            ['old_bbcode_id' => 88, 'new_bbcode_id' => 130]
        );
    }

    public function exportScreenshotsFromDrupal()
    {
        return $this->execute(
            ExportDrupalScreenshotsCommand::class,
            []
        );
    }

    public function importScreenshotsToPhpbb()
    {
        throw new Exception("Unimplemented method");
    }

    public function exportVideosFromDrupal()
    {
        return $this->execute(
            ExportDrupalVideosCommand::class,
            []
        );
    }

    public function importVideosToPhpbb()
    {
        throw new Exception("Unimplemented method");
    }

    public function exportStoriesFromDrupal()
    {
        return $this->execute(
            ExportDrupalStoriesCommand::class,
            []
        );
    }

    public function importStoriesToPhpbb()
    {
        throw new Exception("Unimplemented method");
    }

    public function exportAddonsFromDrupal()
    {
        return $this->execute(
            ExportDrupalAddonsCommand::class,
            []
        );
    }

    public function exportAddonMetaFromYSUpload()
    {
        return $this->execute(
            ExportYSUploadAddonMetaCommand::class,
            []
        );
    }

    public function exportAddonDataFromYSUpload()
    {
        return $this->execute(
            ExportYSUploadAddonDataCommand::class,
            []
        );
    }

    public function importAddonsToPhpbb()
    {
        throw new Exception("Unimplemented method");
    }

}
