<?php
namespace app\admin\controller;
use think\Controller;

class Login extends Controller
{
    public function index()
    {
        if(request()->isPost()){
            // 登陆逻辑
            // 获取相关的数据
            $data = input('post.');
            // 通过用户名 获取 用户信息
            // 严格判定

            $ret = model('BisAccount')->get([
                'username'=> $data['username'],
                'is_main' => 1,
            ]);

            if(!$ret || $ret->status != 1){
                $this->error('该用户不存在！');
            }
            if( $ret->password != md5($data['password']).$ret->code ){
                $this->error('密码不正确');
            }
            model('BisAccount')->updateById([
                'last_login_time'=>time()
            ],$ret->id);
            // session 保存用户信息
            session('bisAccount', $ret, 'bis');

            return $this->success('登陆成功','/admin');
        }else{
            // 获取session
            $account = session('bisAccount','','bis');
            if($account && $account->id){
                return $this->redirect(url('index/index'));
            }
            return $this->fetch();
        }
    }


    public function logout(){
        // 清除 session
        session(null,'bis');
        $this->redirect('login/index');
    }
}