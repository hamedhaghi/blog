<?php
use \Illuminate\Database\Eloquent\Model as Eloquent;

class Tag_model extends Eloquent {

    protected $table = "tags";

    public function posts()
    {
        return $this->belongsToMany('Post_model', 'posts_tags', 'tag_id', 'post_id');
    }
}