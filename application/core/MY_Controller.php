<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller
{
    /* Admin */
    public function __construct()
    {
        parent::__construct();
        $auth = new Auth('admins');
        if(!$auth->is_authenticated()){
            redirect('login/admin');
        }
    }
}

class  UserController extends CI_Controller
{
    /* User */
    public function __construct()
    {
        parent::__construct();
    }
}




