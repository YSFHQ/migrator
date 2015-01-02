<?php namespace YSFHQ\Migrator;

use Illuminate\Database\Eloquent\Model as Eloquent;

class File extends Eloquent {

    protected $table = 'files';

    public function post()
    {
        return $this->belongsTo('Post', 'post_id');
    }

}
