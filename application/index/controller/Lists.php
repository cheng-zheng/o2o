<?php
namespace app\index\controller;



class Lists extends Base
{
    public function index()
    {
        $firstCatIds = [];
        $data = [];
        // 首先需要一级栏目
        $categorys = model('Category')->getNormalCategoryByParentId();
        foreach ($categorys as $category) {
            $firstCatIds[] = $category->id;
        }

        $id = input('id', 0, 'intval');
        // id=0 一级分类 还是 二级分类
        // 如果$id在$firstCatIds的数组里面 就是 一级分类
        if(in_array($id, $firstCatIds)){
            // todo
            $categoryParentId = $id;
            $data['category_id'] = $id;
        }elseif($id){
            // 二级分类
            $category = model('Category')->get($id);
            if(!$category || $category->status != 1){
                $this->error('数据不合法');
            }
            $categoryParentId  = $category->parent_id;
            $data['se_category_id'] = $id;
        }else{
            $categoryParentId = 0;
        }
        // 获取父类下的所有 子分类
        $sedcategorys = [];
        if($categoryParentId){
            $sedcategorys = model('Category')->getNormalCategoryByParentId($categoryParentId);
        }
        // 排序数据获取的逻辑
        $orders = [];
        $order_sales = input('order_sales','');
        $order_price = input('order_price','');
        $order_time = input('order_time','');
        if(!empty($order_sales)) {
            $orderflag = 'order_sales';
            $orders['order_sales'] = $order_sales;
        }elseif(!empty($order_price)) {
            $orderflag = 'order_price';
            $orders['order_price'] = $orderflag;//这个地方默认写错了。注意修改下哦
        }elseif(!empty($order_time)) {
            $orderflag = 'order_time';
            $orders['order_time'] = $order_time;
        }else{
            $orderflag = '';
        }
        //Log::write('o2o-log-list-id'.$id, 'log');
        //trace('o2o-log-list-id'.$id, 'log');
        $data['city_id'] = $this->city->id; // add
        // 根据上面条件来查询商品列表数据
        $deals = model('Deal')->getDealByConditions($data, $orders);

        return $this->fetch('',[
            'id'                => $id,
            'categorys'         => $categorys,
            'sedcategorys'      => $sedcategorys,
            'categoryParentId'  => $categoryParentId,
            'orderflag'         => $orderflag,
            'deals'             => $deals,
        ]);
    }

}
