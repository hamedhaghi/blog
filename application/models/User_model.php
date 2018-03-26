<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class User_model extends Eloquent {

    protected $table = "users";

    public function comments()
    {
        return $this->hasMany('Comment_model', 'user_id');
    }
}