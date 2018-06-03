<?php

namespace app\Common\model;

use think\Model;

class City extends Model
{
    //
    public function getNormalCitysByParentId($parentId=0)
    {
        $data = [
            'parent_id' =>  $parentId,
            'status'    =>  1,
        ];
        $order = [
            'id'    =>  'desc'
        ];
        $result =   $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }
    public function getNormalCitys(){
        $data = [
            'status'    => 1,
            'parent_id' => ['gt',0],// 大于0
        ];
        $order = ['id'=>'desc'];

        return $this->where($data)
            ->order($order)
            ->select();
    }
}
