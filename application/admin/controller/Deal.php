<?php
namespace app\admin\controller;


use think\Controller;

class Deal extends Base
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('Deal');
    }
    /**
     *  Deal 列表页面 与 查询
     * @return mixed
     */
    public function index(){
        $data = input('get.');//input('get.','','htmlentities'); config了全局转换
        $sdata = [];
        // 时间查询
        if(
            !empty($data['start_time']) && !empty($data['start_time']) &&
            strtotime($data['start_time']) < strtotime($data['end_time'])
        ){
            $sdata['create_time'] = [
                ['gt',strtotime($data['start_time'])],//gt 大于
                ['lt',strtotime($data['end_time'])],//lt 小于
            ];
        }
        // 分类查询
        if(!empty($data['category_id'])){
            $sdata['category_id'] = $data['category_id'];
        }
        // 城市查询
        if(!empty($data['city_id'])){
            $sdata['city_id'] = $data['city_id'];
        }
        // 名字查询
        if(!empty($data['name'])){
            // 模糊查询
            $sdata['name'] = ['like', '%'.$data['name'].'%'];
        }

        $deals = $this->obj->getNormalDeals($sdata);
        $categorys = model('Category')->getNormalCategoryByParentId();
        // 列表优化
        $categoryArrs = [];
        foreach($categorys as $category){
            $categoryArrs[$category->id] = $category->name;
        }
        $citys = model('City')->getNormalCitys();
        // 列表优化
        $cityArrs = [];
        foreach($citys as $city){
            $cityArrs[$city->id] = $city->name;
        }

        return $this->fetch('',[
            'categorys' => $categorys,
            'citys'     => $citys,
            'deals'     => $deals,

            'category_id'   => empty($data['category_id'])?'':$data['category_id'],
            'city_id'       => empty($data['city_id'])?'':$data['city_id'],
            'name'          => empty($data['name'])?'':$data['name'],
            'start_time'    => empty($data['start_time'])?'':$data['start_time'],
            'end_time'      => empty($data['end_time'])?'':$data['end_time'],
            // 列表优化
            'categoryArrs'  => $categoryArrs,
            'cityArrs'      => $cityArrs
        ]);
    }

    public function apply(){
        $data = input('get.');
        $sdata = [];
        if(!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time']) > strtotime($data['start_time'])) {
            $sdata['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        if(!empty($data['category_id'])) {
            $sdata['category_id'] = $data['category_id'];
        }
        if(!empty($data['city_id'])) {
            $sdata['city_id'] = $data['city_id'];
        }
        if(!empty($data['name'])) {
            $sdata['name'] = ['like', '%'.$data['name'].'%'];
        }
        $cityArrs = $categoryArrs = [];
        $categorys = model("Category")->getNormalCategoryByParentId();
        foreach($categorys as $category) {
            $categoryArrs[$category->id] = $category->name;
        }

        $citys = model("City")->getNormalCitys();
        foreach($citys as $city) {
            $cityArrs[$city->id] = $city->name;
        }

        $deals = $this->obj->getApplyDeals($sdata);
        return $this->fetch('', [
            'categorys' => $categorys,
            'citys' => $citys,
            'deals' => $deals,
            'category_id' => empty($data['category_id']) ? '' : $data['category_id'],
            'city_id' => empty($data['city_id']) ? '' : $data['city_id'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'name' => empty($data['name']) ? '' : $data['name'],
            'categoryArrs' => $categoryArrs,
            'cityArrs' => $cityArrs,
        ]);
    }
}
