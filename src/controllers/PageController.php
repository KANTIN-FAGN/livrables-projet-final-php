<?php

namespace App\Controllers;


class PageController
{

    public static function home()
    {
        include_once '../views/home/home.php';
    }

    /**
     * @return void
     */
    public static function register()
    {
        include_once '../views/auth/register/Register-form.php';
    }

    /**
     * @return void
     */
    public static function registerService()
    {
        include_once '../views/auth/register/services/registerService.php';
    }

    /**
     * @return void
     */
    public static function login()
    {
        include_once '../views/auth/login/Login-form.php';
    }

    /**
     * @return void
     */
    public static function loginService()
    {
        include_once '../views/auth/login/services/loginService.php';
    }
}