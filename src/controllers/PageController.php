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
        include_once '../views/auth/register/register.php';
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
        include_once '../views/auth/login/login.php';
    }

    /**
     * @return void
     */
    public static function loginService()
    {
        include_once '../views/auth/login/services/loginService.php';
    }

    public static function profile()
    {
        include_once '../views/profile/profile.php';
    }
    public static function editProfile()
    {
        include_once '../views/editProfile/editProfile.php';
    }
}