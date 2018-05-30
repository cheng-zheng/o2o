<?php
namespace app\index\controller;



class User
{
    //登录
    public function login()
    {
        return view('login');
    }
    //注册
    public function register()
    {
        return view('register');
    }
}
