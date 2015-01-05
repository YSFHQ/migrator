<?php namespace YSFHQ\Migrator;

use \Exception;
use Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Queue,
    Laracasts\Commander\CommanderTrait;

use YSFHQ\Migrator\Commands\FixBbcodeCommand,
    YSFHQ\Migrator\Commands\ExportDrupalScreenshotsCommand,
    YSFHQ\Migrator\Commands\ExportDrupalVideosCommand,
    YSFHQ\Migrator\Commands\ExportDrupalStoriesCommand,
    YSFHQ\Migrator\Commands\ExportDrupalAddonsCommand,
    YSFHQ\Migrator\Commands\ExportYSUploadAddonMetaCommand,
    YSFHQ\Migrator\Commands\ImportPostCommand,
    YSFHQ\Migrator\Commands\UpdateImportedPostCommand,
    YSFHQ\Migrator\Commands\PopulateFileCommand,
    YSFHQ\Migrator\Commands\TransferFileCommand;
use YSFHQ\Migrator\Attachment,
    YSFHQ\Migrator\Post;

class Activities
{
    use CommanderTrait;

    public function fixAlignBBCode()
    {
        return $this->execute(FixBbcodeCommand::class, ['old_bbcode_id' => 88, 'new_bbcode_id' => 130]);
    }

    public function exportScreenshotsFromDrupal()
    {
        return $this->execute(ExportDrupalScreenshotsCommand::class, []);
    }

    public function exportVideosFromDrupal()
    {
        return $this->execute(ExportDrupalVideosCommand::class, []);
    }

    public function exportStoriesFromDrupal()
    {
        return $this->execute(ExportDrupalStoriesCommand::class, []);
    }

    public function exportAddonsFromDrupal()
    {
        return $this->execute(ExportDrupalAddonsCommand::class, []);
    }

    public function exportAddonMetaFromYSUpload()
    {
        return $this->execute(ExportYSUploadAddonMetaCommand::class, []);
    }

    public function importPost($post_id = null)
    {
        if (isset($post_id)) {
            $post = Post::find($post_id);
            $phpbb_post_id = $this->execute(ImportPostCommand::class, ['post' => $post]);
            if ($phpbb_post_id > 0) {
                $post->phpbb_id = $phpbb_post_id;
                if ($post->save()) {
                    return $phpbb_post_id;
                }
            }
        }
        return false;
    }

    public function updatePostAuthor($phpbb_id = null)
    {
        if (isset($phpbb_id)) {
            if ($post = Post::where('phpbb_id', $phpbb_id)->first()) {
                return $this->execute(UpdateImportedPostCommand::class, [
                    'phpbb_id' => $post->phpbb_id,
                    'username' => $post->username,
                    'post_time' => strtotime($post->posted_on),
                ]);
            }
        }
        return false;
    }

    public function transferYSUploadFiles()
    {
        $posts = Post::where('source', 'ysupload')->where('phpbb_id', '>', 0)->get();
        foreach ($posts as $post) {
            if (!$post->attachment()) {
                $id = $this->execute(PopulateFileCommand::class, ['post' => $post]);
                Queue::push('YSFHQ\Migrator\Tasks\FileTasks@createAttachment', ['post_id' => $id]);
            }
        }
    }

    public function copyYSUploadFilesToPhpbb($file_id = null)
    {
        if ($file_id && $file = Attachment::find($file_id)) {
            if ($this->execute(TransferFileCommand::class, ['file' => $file])) {
                return $file->post_msg_id;
            }
        }
        return false;
    }

}
