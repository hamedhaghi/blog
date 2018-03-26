<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Post_model extends Eloquent {

    protected $table = "posts";

    public function admin()
    {
        return $this->belongsTo('Admin_model',"admin_id");
    }

    public function category()
    {
        return $this->belongsTo('Category_model',"category_id");
    }

    public function tags()
    {
        return $this->belongsToMany('Tag_model', 'posts_tags', 'post_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany('Comment_model', 'post_id');
    }

}