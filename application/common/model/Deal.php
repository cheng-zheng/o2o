<?php
namespace app\common\model;


class Deal extends BaseModel
{
    public function getNormalDealByBisId($bisId,$status=1)
    {
        $data = [
            'bis_id'    => $bisId,
            'status'    => $status,
        ];
        return $this->where($data)
            ->order('id','desc')
            ->select();
    }

    public function getNormalDeals($data = []){
        $data['status'] = 1;
        $order = ['id'=>'desc'];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        echo $this->getLastSql();
        return $result;
    }
}