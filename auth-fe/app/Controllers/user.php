<?php

namespace App\Controllers;

class User extends BaseController{

    function __construct(){
        parent::__construct();
}
    function index(){
        $this->template->load('template','user/index');
    }
}

?>