<?php
namespace app\admin\controller;


use think\Controller;

class Base extends Controller
{
    public $admin;
    public function _initialize()
    {
        // 判断用户是否登陆
        $isLogin = $this->isLogin();
        if(!$isLogin){
            return $this->redirect(url('login/index'));
        }
    }

    public function status(){
        // 获取值
        $data = input('get.');
        // 获取控制器
        $model = request()->controller();
        // validate     id|status
        $validate = validate($model);
        if( !$validate->scene('status')->check($data) ){
            return $this->error($validate->getError());
        }
        if(empty($data['id'])){
            $this->error('id不合法');
        }
        if(!is_numeric($data['status'])){
            $this->error('status不合法');
        }


        $res = model($model)->where('id',$data['id'])->update([
            'status'=> $data['status']
        ]);
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }

    // 判断是否登陆
    public function isLogin(){
        $user = $this->getLoginUser();
        if($user && $user->is_main){
            return true;
        }
        return false;
    }

    public function getLoginUser(){
        if(!$this->admin){
            $this->admin  = session('bisAccount', '', 'bis');
        }

        return  $this->admin;
    }
}
