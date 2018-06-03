<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/1/2018
 * Time: 9:45 PM
 */

namespace app\bis\controller;


use think\Controller;

class Base extends Controller
{
    public $account ;
    public function _initialize()
    {
        // 判断用户是否登陆
        $isLogin = $this->isLogin();
        if(!$isLogin){
            return $this->redirect(url('login/index'));
        }
    }

    // 判断是否登陆
    public function isLogin(){
        $user = $this->getLoginUser();
        if($user && $user->id){
            return true;
        }
        return false;
    }

    public function getLoginUser(){
        if(!$this->account){
            $this->account  = session('bisAccount', '', 'bis');
        }

        return  $this->account;
    }
}