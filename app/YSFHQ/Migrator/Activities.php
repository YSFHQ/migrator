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

    public function exportVideosFromDrupal()
    {
        return $this->execute(
            ExportDrupalVideosCommand::class,
            []
        );
    }

    public function exportStoriesFromDrupal()
    {
        return $this->execute(
            ExportDrupalStoriesCommand::class,
            []
        );
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

    public function importPost($post_id = null)
    {
        if (isset($post_id)) {
            $post = Post::find($post_id);
            $phpbb_post_id = $this->execute(
                ImportPostCommand::class,
                ['post' => $post]
            );
            $post->phpbb_id = $phpbb_post_id;
            return $phpbb_post_id;
        }
        return -1;
    }

}
