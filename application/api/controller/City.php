<?php
namespace app\api\controller;


use think\Controller;

class City extends Controller
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('City');
    }

    public function getCitysByParentId()
    {
        $id = input('post.id');
        if(!$id){
            $this->error('ID不合法');
        }
        $citys = $this->obj->getNormalCitysByParentId($id);
        return show(1, 'success', $citys);
    }


}
