<?php
namespace app\bis\controller;
use think\Controller;

class Location extends Base
{
    public $obj;
    public function _initialize()
    {
        $this->obj = model('bisLocation');
    }

    /**
     * 门店列表页
     * @return mixed
     */
    public function index()
    {
        $location = $this->obj->getBisByStatus(1);
        //var_dump($location);exit();
        return $this->fetch('',['location'=>$location]);
    }

    public function add(){
        if(request()->isPost()){
            // 第一点 校验数据 validate
            $data = input('post.');
            // 父类 方法 session里获取
            $bisId = $this->getLoginUser()->bis_id;
            // 门店入库操作
            // 总店相关信息入库
            $data['cat'] = '';
            if(!empty($data['category_id']) && isset($data['se_category_id'])){
                $data['cat'] = implode('|',$data['se_category_id']);
            }
            // 获取经纬度
            $lnglat = json_decode( \Map::getLngLat($data['address'] ) , true);
            if(empty($lnglat) || $lnglat['status']!=0 || $lnglat['result']['precise']!=0){
                $this->error('无法获取数据，或者匹配地址不精准');
            }

            $locationData = [
                'bis_id'            => $bisId,
                'name'              => $data['name'],
                'logo'              => $data['logo'],
                'tel'               => $data['tel'],
                'contact'           => $data['contact'],
                'category_id'       => $data['category_id'],
                'category_path'     => $data['category_id'].','.$data['cat'],
                'city_id'           => $data['city_id'],
                'city_path'         => empty($data['se_city_id']) ? '' : $data['city_id'].','.$data['se_city_id'],
                'api_address'           => $data['address'],
                'open_time'         => $data['open_time'],
                'open_time'         => $data['open_time'],
                'content'           => empty($data['content']) ? '' : $data['content'],
                'is_main'           => 0,   // 代表的是总店信息
                'status'            => 1,
                'x_point'           => empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
                'y_point'           => empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lng'],
            ];
            $locationId = model('BisLocation')->add($locationData);
            if($locationId){
                $this->success('门店申请成功');
            }else{
                $this->error('门店申请失败');
            }
        }else{
            // 门店模板
            // 获取一级城市的数据
            $citys = model('City')->getNormalCitysByParentId();
            // 获取一级栏目的数据
            $category = model('Category')->getNormalCategoryByParentId();
            return $this->fetch('',[
                'citys' => $citys,
                'categorys' => $category
            ]);
        }

    }
}