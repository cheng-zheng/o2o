<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;
    public function add($data)
    {
        $data['status'] = 1;
        //$data['create_time'] = time();
        return $this->save($data);
    }

    public function getNormalFirstCategory()
    {
        $data = [
            'status'    => 1,
            'parent_id' => 0,
        ];
        $order = [
            'id'    => 'desc'
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }
    //获取一级栏目
    public function getFirstCategory( $parentId = 0 )
    {
        $data = [
            'parent_id' =>  $parentId,
            'status'    =>  ['neq',-1]
        ];
        $order = [
            'listorder'=>'desc',
            'id'    =>  'desc'
        ];
        $result =   $this->where($data)
            ->order($order)
            ->paginate(2);
        //echo $this->getLastSql();
        return $result;
    }
    //
    public function getNormalCategoryByParentId($parentId = 0)
    {
        $data = [
            'status'    => 1,
            'parent_id' => $parentId,
        ];
        $order = [
            'id'    => 'desc'
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }
}