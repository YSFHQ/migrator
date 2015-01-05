<?php namespace YSFHQ\Migrator\Tasks;

use \Exception;
use Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Queue;
use YSFHQ\Migrator\ActivitiesFacade as MigratorActivities,
    YSFHQ\Migrator\Post,
    YSFHQ\Migrator\File;

class FileTasks
{

    public function createAttachment($job, $data = [])
    {
        if (!isset($data['id'])) {
            Log::error('Cannot create attachment, ID is null.');
            $job->delete();
        } else {
            $post_id = MigratorActivities::copyYSUploadFilesToPhpbb($data['id']);
            if ($post_id) {
                Log::info("Updated: http://forum.ysfhq.com/viewtopic.php?t=$post_id#p$post_id");
                $job->delete();
            } else {
                Log::error('Uploading attachment for file ID '.$data['id'].' failed.');
                $job->release(5);
            }
        }
    }

}
