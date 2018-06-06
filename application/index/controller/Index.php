<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        $this->featured();
        $this->deal();
        return $this->fetch();
    }

    /**
     * 获取首页大图数据
     * 获取广告位相关的数据
     *
     */
    private function featured(){

        $order = [
            'listorder' => 'desc',
            //'id'        => 'desc'
        ];
        $featured = model('Featured')->where('status',1)->order($order)->select();
        $feature = $side = [];

        foreach($featured as $item){
            if($item->type==1){
                $side[] = $item;
            }else{
                $feature[] = $item;
            }

        }

        $this->assign([
            'feature'  => $feature,
            'side'  => $side[0],
        ]);
    }
    //
    /**
     * 商品分类 数据-美食 推荐的数据
     * 获取4个子分类
     */
    private function deal(){
        //$datas = model('Deal')->getNormalDealByCategoryCityId(14,2 );
        //商品分类 数据-美食 推荐的数据
        $datas = model('Deal')->getNormalDealByCategoryCityId(14,$this->city->id );

        // 获取4个子分类
        $fourSonCategory = model('Category')->getNormalRecommendCategoryByParentId();
        $this->assign([
            'datas' => $datas,
            'fourSonCategory'=>$fourSonCategory
        ]);
    }
}
