<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
class Home extends CI_Controller
{

    public function index()
    {



        echo Functions::jalali_to_gregorian('1396/09/28') . ' ' . date('H:i:s', time());
        echo '<br>';
        echo "Home";
    }


}
