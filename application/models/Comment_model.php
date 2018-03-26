<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Comment_model extends Eloquent {

    protected $table = "comments";

    public function admin()
    {
        return $this->belongsTo('Admin_model',"admin_id");
    }

    public function user()
    {
        return $this->belongsTo('User_model',"user_id");
    }

    public function post()
    {
        return $this->belongsTo('Post_model',"post_id");
    }







}