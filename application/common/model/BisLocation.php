<?php
namespace app\common\model;


class BisLocation extends BaseModel
{
    public function getBisByStatus($status=0){
        $order = [
            'id'    => 'desc',
        ];
        $data = [
            'status'=> $status
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate(1);
        return $result;
    }

    public function getNormalLocationByBisId($bisId)
    {
        $data = [
            'bis_id'    => $bisId,
            'status'    => 1,
        ];
        return $this->where($data)
            ->order('id','desc')
            ->select();
    }
}