<?php
/**
 * Created by PhpStorm.
 * User: chengzheng
 * Date: 6/5/2018
 * Time: 1:18 PM
 */

namespace app\index\controller;


class Detail extends Base
{
    public function index($id){
        if(!intval($id)){
            $this->error('ID不合法');
        }
        // 根据id查询商品的数据
        $deal = model('Deal')->get($id);
        if( !$deal || $deal->status !=1){
            $this->error('该商品不存在');
        }
        // 获取分类信息
        $category = model('Category')->get($deal->category_id);
        // 获取分店信息
        $locations = model('BisLocation')->getNormalLocationsInId($deal->location_ids);// 1,3,4
        // 团购标识
        $flag = 0;

        if($deal->start_time > time()){
            // 团购开始了没
            $flag = 1;
            $timedata = '';
            $dtime = $deal->start_time - time();
            $day = floor( $dtime/(3600*24) );
            if($day){
                $timedata .= $day.'天';
            }
            $hour = floor( $dtime&(3600*24)/3600 );
            if($hour){
                $timedata .= $hour.'小时';
            }
            $min = floor( $dtime&(3600*24)/3600/60 );
            if($min){
                $timedata .= $min.'分';
            }
            $this->assign('timedata',$timedata);
        }

        return $this->fetch('',[
            // 标题
            'title'     => $deal->name,
            // 商品信息
            'deal'      => $deal,
            // 分类
            'category'  => $category,

            'locations' => $locations,
            // 库存
            'overplus'  => $deal->total_count - $deal->buy_count,

            'flag'      => $flag,

            'mapstr'    => $locations[0]['x_point'].','.$locations[0]['y_point'],
        ]);


    }
}