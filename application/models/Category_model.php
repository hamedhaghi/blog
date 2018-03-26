<?php
use \Illuminate\Database\Eloquent\Model as Eloquent;

class Category_model extends Eloquent {

    protected $table = "categories";

    public function posts(){
        return $this->hasMany('Post_model', 'category_id');
    }
    public function children()
    {
        return $this->hasMany('Category_model', 'parent_id');
    }
}