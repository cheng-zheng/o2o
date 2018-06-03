<?php
namespace app\bis\controller;
use think\Controller;
use app\common\model\Deal as DealModel;
class Deal extends Base
{
    /**
     * 商户中心的 deal列表页
     * @return mixed
     */
    public function index(DealModel $deal)
    {
        $deals = $deal->getNormalDealByBisId($this->getLoginUser()->bis_id);
        return $this->fetch('',['deal'=>$deals]);
    }

    /**
     * 团购商品添加
     * @return mixed
     */
    public function add(){
        $bisId = $this->getLoginUser()->bis_id;
        if(request()->isPost()){
            // 走插入逻辑
            $data = input('post.');
            // 严格校验 validate
            $location = model('BisLocation')->get($data['location_ids'][0]);
            $deals = [
                'bis_id'            => $bisId,
                'name'              => $data['name'],
                'image'             => $data['image'],
                'category_id'       => $data['category_id'],
                'se_category_id'    => empty($data['se_category_id'])?'':implode(',',$data['se_category_id']),
                'city_id'           => $data['city_id'],
                'location_ids'      => empty($data['location_ids'])?'':implode(',',$data['location_ids']),
                'start_time'        => strtotime($data['start_time']),
                'end_time'          => strtotime($data['end_time']),
                'total_count'       => $data['total_count'],
                'origin_price'      => $data['origin_price'],
                'current_price'     => $data['current_price'],
                'coupons_end_time'  => strtotime($data['coupons_end_time']),
                'notes'             => $data['notes'],
                'description'       => $data['description'],
                'x_point'           => $location->x_point,
                'y_point'           => $location->y_point,
            ];
            $id = model('Deal')->add($deals);
            if($id){
                $this->success('添加成功',url('deal/index'));
            }else{
                $this->error('添加失败');
            }
        }else{
            //获取一级城市的数据
            $citys = model('City')->getNormalCitysByParentId();
            //获取一级栏目的数据
            $category = model('Category')->getNormalCategoryByParentId();
            return $this->fetch('',[
                'citys'         => $citys,
                'categorys'     => $category,
                'bisLocation'   => model('BisLocation')->getNormalLocationByBisId($bisId),
            ]);
        }

    }
}