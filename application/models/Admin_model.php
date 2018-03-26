<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Admin_model extends Eloquent {

    protected $table = "admins";

    public function posts()
    {
        return $this->hasMany('Post_model', 'admin_id');
    }

    public function comments()
    {
        return $this->hasMany('Comment_model', 'admin_id');
    }
}