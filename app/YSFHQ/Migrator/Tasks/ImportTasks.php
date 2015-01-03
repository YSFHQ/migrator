<?php namespace YSFHQ\Migrator\Tasks;

use \Exception;
use Illuminate\Support\Facades\Log;
use YSFHQ\Migrator\Activities as MigratorActivities,
    YSFHQ\Migrator\Post;

class ImportTasks
{

    public function makePost($job, $data = [])
    {
        if (!isset($data['id'])) {
            Log::error('Cannot create post, ID is null.');
            $job->delete();
        } else {
            $post_id = MigratorActivities::importPost($data['id']);
            if ($post_id) {
                $job->delete();
            } else {
                Log::error('Posting to forum failed.');
                $job->release(5);
            }
        }
    }

    public function linkPost($job, $data = [])
    {
        # code...
    }

}
