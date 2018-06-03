<?php
namespace app\common\model;


class User extends BaseModel
{
    /**
     * 注册 入库
     * @param $data
     * @return false|int
     */
    public function add($data){
        if(!is_array($data)){
            exception('传递的数据不是数组');
        }

        // 因为 unique 查询失败 主键会自增，
        // 所以 看着办吧
        if( $this->where('username',$data['username'])->find() ){
            exception('用户名已存在');
        }
        if( $this->where('email',$data['email'])->find() ){
            exception('邮箱已被注册');
        }


        $data['status'] = 1;

        return $this->data($data)->allowField(true)->save();
    }

    /**
     * 根据用户名获取用户信息
     * @param $username
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getUserByUsername($username){
        if(!$username){
            exception('用户名不合法');
        }
        $data = ['username'=>$username];

        return $this->where($data)->find();
    }
}