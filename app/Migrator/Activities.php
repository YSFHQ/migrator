<?php

namespace YSFHQ\Migrator;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Queue;

use YSFHQ\Migrator\Commands\FixBbcodeCommand,
    YSFHQ\Migrator\Commands\ExportDrupalScreenshotsCommand,
    YSFHQ\Migrator\Commands\ExportDrupalVideosCommand,
    YSFHQ\Migrator\Commands\ExportDrupalStoriesCommand,
    YSFHQ\Migrator\Commands\ExportDrupalAddonsCommand,
    YSFHQ\Migrator\Commands\ExportYSUploadAddonMetaCommand,
    YSFHQ\Migrator\Commands\ImportPostCommand,
    YSFHQ\Migrator\Commands\UpdateImportedPostCommand,
    YSFHQ\Migrator\Commands\PopulateFileCommand,
    YSFHQ\Migrator\Commands\TransferFileCommand,
    YSFHQ\Migrator\Commands\UpdateDownloadLinkCommand;

class Activities
{
    use DispatchesJobs;

    public function fixAlignBBCode()
    {
        return $this->dispatch(new FixBbcodeCommand(88, 130));
    }

    public function exportScreenshotsFromDrupal()
    {
        return $this->dispatch(new ExportDrupalScreenshotsCommand());
    }

    public function exportVideosFromDrupal()
    {
        return $this->dispatch(new ExportDrupalVideosCommand());
    }

    public function exportStoriesFromDrupal()
    {
        return $this->dispatch(new ExportDrupalStoriesCommand());
    }

    public function exportAddonsFromDrupal()
    {
        return $this->dispatch(new ExportDrupalAddonsCommand());
    }

    public function exportAddonMetaFromYSUpload()
    {
        return $this->dispatch(new ExportYSUploadAddonMetaCommand());
    }

    public function importPost($post_id = null)
    {
        if (isset($post_id)) {
            $post = Post::find($post_id);
            $phpbb_post_id = $this->dispatch(new ImportPostCommand($post));
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
                return $this->dispatch(new UpdateImportedPostCommand($post->phpbb_id, $post->username, strtotime($post->posted_on)));
            }
        }
        return false;
    }

    public function transferYSUploadFiles()
    {
        $posts = Post::where('source', 'ysupload')->where('phpbb_id', '>', 0)->get();
        foreach ($posts as $post) {
            if (!$post->attachment) {
                $id = $this->dispatch(new PopulateFileCommand($post));
                if ($id)
                    Queue::push('YSFHQ\Migrator\Tasks\FileTasks@createAttachment', ['id' => $id]);
            }
        }
    }

    public function copyYSUploadFilesToPhpbb($file_id = null)
    {
        if ($file_id && $file = Attachment::find($file_id)) {
            if ($this->dispatch(new TransferFileCommand($file))) {
                $this->updateDownloadLinksOnPostsWithAttachment($file_id);
                return $file->post_msg_id;
            }
        }
        return false;
    }

    public function updateDownloadLinksOnPostsWithAttachment($file_id = null)
    {
        if ($file_id) {
            $files = [Attachment::find($file_id)];
        } else {
            $files = Attachment::where('phpbb_attachment_id', '>', 0)->get();
        }
        foreach ($files as $file) {
            $this->dispatch(new UpdateDownloadLinkCommand($file->post_msg_id, $file->phpbb_attachment_id));
        }
    }

}
