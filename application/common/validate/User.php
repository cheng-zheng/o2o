<?php
namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username'      => 'require|max:10',
        'email'         => 'require|email',
        'password'      => 'require|length:3,20',
        'repassword'      => 'require|confirm:password',
        'verifyCode'      => 'require',
        'city_id'   => 'require',
        'bank_info' => 'require',
        'bank_name' => 'require',
        'bank_user' => 'require',
        'faren'     => 'require',
        'faren_tel' => 'require'
    ];
    protected $message = [
        'username.require'  => '用户名不能为空',
        'username.max'      => '用户名长度不能大于10',
        'password.length'   => '密码长度要大于3小于20',
    ];
    //场景设置
    protected $scene = [
        'register'  => ['username','email','password','repassword','verifyCode']
    ];
}