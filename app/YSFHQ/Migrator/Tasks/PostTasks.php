<?php namespace YSFHQ\Migrator\Tasks;

use \Exception;
use Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Queue;
use YSFHQ\Migrator\ActivitiesFacade as MigratorActivities,
    YSFHQ\Migrator\Post;

class PostTasks
{

    public function makePost($job, $data = [])
    {
        if (!isset($data['id'])) {
            Log::error('Cannot create post, ID is null.');
            $job->delete();
        } else {
            $post_id = MigratorActivities::importPost($data['id']);
            if ($post_id) {
                Log::info("Imported: http://forum.ysfhq.com/viewtopic.php?p=$post_id#p$post_id");
                Queue::push('YSFHQ\Migrator\Tasks\PostTasks@reLinkPost', ['phpbb_id' => $post_id]);
                $job->delete();
            } else {
                Log::error('Posting to forum failed.');
                $job->release(5);
            }
        }
    }

    public function reLinkPost($job, $data = [])
    {
        MigratorActivities::updatePostAuthor($data['phpbb_id']);
        $job->delete();
    }

}
