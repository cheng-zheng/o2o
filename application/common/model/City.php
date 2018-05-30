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
}
