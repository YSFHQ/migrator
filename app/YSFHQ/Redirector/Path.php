<?php namespace YSFHQ\Redirector;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Path extends Eloquent {

    protected $table = 'paths';

    public function post()
    {
        return $this->belongsTo('Post', 'post_id');
    }

}
