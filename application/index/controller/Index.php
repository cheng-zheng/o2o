<?php
namespace app\index\controller;

use app\common\model\Category;
use app\common\model\Deal as DealModel;
use think\Controller;

class Index extends Base
{
    public function index(DealModel $deal, Category $category)
    {
        $this->featured();
        $this->deal($deal, $category);
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
    private function deal($deal, $category){
        //$datas = model('Deal')->getNormalDealByCategoryCityId(14,2 );

        // 获取5个子分类
        $fourSonCategory = $category->getNormalRecommendCategoryByParentId();
        //商品分类 数据-美食 推荐的数据
        $datas = [];
        foreach ($fourSonCategory as $item) {
            $datas[] = $deal->getNormalDealByCategoryCityId($item->id, $this->city->id );
        }
        $this->assign([
            'datas' => $datas,
            'fourSonCategory'=>$fourSonCategory
        ]);
    }
}
