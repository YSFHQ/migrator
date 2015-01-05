<?php namespace YSFHQ\Migrator;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Attachment extends Eloquent {

    protected $table = 'files';

    public function post()
    {
        return $this->belongsTo('Post', 'post_id');
    }

}
