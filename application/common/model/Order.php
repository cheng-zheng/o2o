<?php
namespace app\common\model;


class Order extends BaseModel
{
    /**
     * 注册 入库
     * @param $data
     * @return false|int
     */
    public function add($data){
        $data['status'] = 1;

        $result = $this->save($data);

        return $result;
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