<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('auth/login');
    }

  //  public function index(): string
    //{
      //  return view('auth/register');
    //}

   // public function index(): string
   // {
   //     return view('user/index');
   // }
}
