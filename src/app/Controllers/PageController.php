<?php

namespace App\Controllers;

class PageController extends BaseController
{
    public function login()
    {
        return view('wallet/login');
    }

    public function dashboard()
    {
        return view('wallet/dashboard');
    }

    public function register()
    {
        return view('wallet/register');
    }
}
