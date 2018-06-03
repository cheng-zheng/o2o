<?php
namespace app\common\model;


class Featured extends BaseModel
{
    /**
     * 根据类型来获取列表数据
     * @param $type
     * @return \think\Paginator
     */
    public function getFeaturedsByType($type){
        $data = [
            'type'  => $type,
            // 不等于-1
            'status'=> ['neq',-1],
        ];
        $order  = ['id'=>'desc'];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }
}