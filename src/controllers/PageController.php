<?php

namespace App\Controllers;


class PageController
{
    public static function register()
    {
        include_once '../views/auth/register/Register-form.php';
    }

    public static function login()
    {
        include_once '../views/auth/login/Login-form.php';
    }
}