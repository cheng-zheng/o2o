<?php
namespace app\admin\controller;


use think\Controller;

class Featured extends Base
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('Featured');
    }
    // 推荐位 列表
    public function index(){
        // 获取推荐位类别
        $types = config('featured.featured_type');

        // 默认0，intval转换
        $type = input('get.type',0,'intval');

        $result = $this->obj->getFeaturedsByType($type);

        return $this->fetch('',[
            'typeId'  => (int)$type,
            'types' => $types,
            'result'=>$result,
        ]);
    }
    /**
     * 推荐位 添加
     * @return mixed
     */
    public function add(){
        if(request()->isPost()){
            // 入库逻辑
            $data = input('post.');
            // 数据需要做严格校验 validate

            $id = $this->obj->add($data);
            if($id){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else{
            // 获取推荐位类别
            $types = config('featured.featured_type');
            return $this->fetch('',[
                'types' =>$types,
            ]);
        }
    }
   /* public function status(){
        // 获取值
        $data = input('get.');
        // validate     id|status

        $res = $this->obj->save([
            'status'=> $data['status'],
            'id'    => $data['id']
        ]);
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }*/
}
