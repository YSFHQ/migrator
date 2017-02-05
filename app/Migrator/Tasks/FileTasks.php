<?php namespace YSFHQ\Migrator\Tasks;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Support\Facades\Log;
use YSFHQ\Migrator\ActivitiesFacade as MigratorActivities;

class FileTasks
{

    public function createAttachment(Job $job, $data = [])
    {
        if (!isset($data['id'])) {
            Log::error('Cannot create attachment, ID is null.');
            $job->delete();
        } else {
            $post_id = MigratorActivities::copyYSUploadFilesToPhpbb($data['id']);
            if ($post_id) {
                Log::info("Uploaded attachment to post: http://forum.ysfhq.com/viewtopic.php?p=$post_id#p$post_id");
                $job->delete();
            } else {
                Log::error('Uploading attachment for file ID '.$data['id'].' failed.');
                $job->release(5);
            }
        }
    }

}
