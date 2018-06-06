<?php

namespace app\index\controller;

use think\Controller;

class Order extends Base
{
    private $user;
    public function _initialize()
    {
        parent::_initialize();
        $this->user =  $this->getLoginUser();
        if(! $this->user){
            $this->error('请登录','user/login');
        }
    }

    public function index(){
        $id = input('get.id', 0 , 'intval');
        if(!$id){
            $this->error('参数不合法');
        }

        $dealCount = input('get.deal_count', 0 ,'intval');
        $totalPrice = input('get.total_price', 0 ,'intval');

        $deal = model('Deal')->find($id);
        if(!$deal || $deal->status != 1){
            $this->error('商品不存在');
        }

        if(empty($_SERVER['HTTP_REFERER'])){//请求url
            $this->error('请求不合法');
        }
        // 数据 入库
        $data = [
            'out_trade_no'  => setOrderSn(),//生成订单号
            'user_id'       => $this->user->id,
            'username'      => $this->user->username,
            'deal_id'       => $id,
            'deal_count'    => $dealCount,
            'total_price'   => $totalPrice,
            'referer'       => $_SERVER['HTTP_REFERER'],
        ];
        try{
            $orderId = model('Order')->add($data);
        }catch(\Exception $e){
            $this->error('订单处理失败');
        }
        $this->redirect(url('pay/index',['id'=>$orderId]));
    }
    /**
     * 订单确认
     *
     * @return \think\Response
     */
    public function confirm(){
        //
        $id = input('id', 0 , 'intval');
        if(!$id){
            $this->error('参数不合法');
        }
        $count = input('get.count', 1 ,'intval');

        $deal = model('Deal')->find($id);

        if(!$deal || $deal->status != 1){
            $this->error('商品不存在');
        }
        $deal = $deal->toArray();

        return $this->fetch('',[
            'title'     => '订单确认',
            'controller'=> 'pay',
            'count'     => $count,
            'deal'      => $deal,
        ]);
    }

}
