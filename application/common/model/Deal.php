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

    /**
     * 根据分类 以及 城市来获取 商品数据
     * @param $id           分类
     * @param $cityId       城市
     * @param int $limit    条数
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalDealByCategoryCityId($id, $cityId, $limit=10){
        $data = [
            'end_time'      => ['gt', time()],// 大于当前时间
            'category_id'   => $id,
            'city_id'       => $cityId,
            'status'        => 1
        ];
        $order = [
            'listorder'     => 'desc',
            'id'            => 'desc'
        ];

        $result = $this->where($data)->order($order);

        if($limit){
            $result = $result->limit($limit);
        }
        return $result->select();
    }

    /**
     * @param array $data
     * @return \think\paginator\Collection
     */
    public function getApplyDeals($data = []) {
        $data['status'] = 0;
        $order = ['id'=>'desc'];

        $result = $this->where($data)
            ->order($order)
            ->paginate();

        //echo $this->getLastSql();
        return  $result;
    }

    /**
     * @param array $data
     * @param $orders
     * @return \think\paginator\Collection
     */
    public function getDealByConditions($data=[], $orders) {
        if(!empty($orders['order_sales'])) {
            $order['buy_count'] = 'desc';
        }
        if(!empty($orders['order_price'])) {
            $order['current_price'] = 'desc';
        }
        if(!empty($orders['order_time'])) {
            $order['create_time'] = 'desc';
        }
        $order['id'] = 'desc';


        $datas[] = 'end_time>'.time();
        $datas[] = 'status=1';
        // find_in_set 数据库函数
        if(!empty($data['se_category_id'])) {

            $datas[]="find_in_set(".$data['se_category_id'].",se_category_id)";
        }
        if(!empty($data['category_id'])) {

            $datas[]="category_id = ".$data['category_id'];
        }
        if(!empty($data['city_id'])) {

            $datas[]="city_id = ".$data['city_id'];
        }

        $result = $this->where(implode(' AND ',$datas))
            ->order($order)
            ->paginate();
        /*var_dump($this->getLastSql());
        var_dump($result);
        exit;*/
        return $result;
    }
}