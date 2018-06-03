<?php
namespace app\index\controller;



use think\Controller;

class User extends Controller
{
    //登录
    public function login()
    {
        $user = session('o2o_user','','o2o');
        if($user && $user->id){
            $this->redirect('/');
        }
        return $this->fetch();
    }

    public function logincheck(){
        // 判断
        if(!request()->post()){
            $this->error('提交不合法');
        }
        $data = input('post.');
        // validate
        try{
            $user = model('User')->getUserByUsername($data['username']);
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }

        if(!$user || $user->status != 1){
            $this->error('该用户不存在');
        }
        // 判断密码
        if(md5($data['password']).$user->code != $user->password){
            $this->error('密码错误');
        }
        // 登陆成功
        model('User')->where('id',$user->id)->update(['last_loin_time'=>time()]);
        // 用户信息 session
        session('o2o_user',$user,'o2o');

        $this->success('登陆成功','/');
    }
    public function logout(){
        session(null, 'o2o');
        $this->redirect('user/login');
    }
    /**
     * 注册
     * @return mixed|\think\response\Json
     */
    public function register()
    {
        if(request()->isPost()){
            $data = input('post.');
            if(!captcha_check($data['verifyCode'])) {
                return json([
                    'status'=> false,
                    'msg'  => '验证码不正确',
                ]);
            }
            // 严格校验 validate
            $validate = validate('User');
            if(!$validate->scene('register')->check($data)){
                return json([
                    'status'=> false,
                    'msg'  => $validate->getError(),
                ]);
            }

            // 密码
            $data['code'] = mt_rand(100,1000);
            $data['password'] = md5($data['password']).$data['code'];

            try{
                $res = model('User')->add($data);
                //SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'chen' for key 'username'
            }catch(\Exception $e){
                $str = '用户名 或 邮箱已经存在';
                $str = $e->getMessage();
                return json([
                    'status'=> false,
                    'msg'  => $str,
                ]);
            }

            if($res){
                //$this->success('注册成功');
                return json([
                    'status'=> true,
                    'msg'  => '注册成功',
                ]);
            }else{
                //$this->error('注册失败');
                return json([
                    'status'=> false,
                    'msg'  => '服务出错了 注册失败',
                ]);
            }
        }else{
            // Page
            return $this->fetch();
        }
    }

}
