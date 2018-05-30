<?php
namespace app\bis\controller;
use think\Controller;
use think\Validate;

class Register extends Controller
{
    public function index()
    {
        //获取一级城市的数据
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级栏目的数据
        $category = model('Category')->getNormalCategoryByParentId();
        return view('index',[
            'citys' => $citys,
            'categorys' => $category
        ]);
    }

    public function add(){
        if(!request()->isPost()){
            $this->error('请求错误');
        }
        //获取表单值
        $data = input('post.');
        //检验数据
        $vaildate = Validate('Bis');
        if(!$vaildate->scene('add')->check($data)){
            $this->error($vaildate->getError());
        }
        // 获取经纬度
        $lnglat = \Map::getLngLat($data['address']);
        if(empty($lnglat) || $lnglat['status']!=0 || $lnglat['result']['precise']!=1){
            $this->error('无法获取数据，或者匹配地址不精准');
        }
        //商户基本信息入库
        $bisData = [
            'name'          => $data['name'],
            'city_id'       => $data['city_id'],
            'city_path'     => empty($data['se_city_id']) ? '' : $data['city_id'].','.$data['se_city_id'],
            'lo,go'         => $data['logo'],
            'licence_logo'  => $data['licence_logo'],
            'description'   => empty($data['description']) ? '' : $data['description'],
            'bank_info'     => $data['bank_info'],
            'bank_user'     => $data['bank_user'],
            'bank_name'     => $data['bank_name'],
            'faren'         => $data['faren'],
            'faren_tel'     => $data['faren_tel'],
            'email'         => $data['email'],
        ];
        $bisId = model('Bis')->add($bisData);


        //总店相关信息检验

        // 账户相关学校检验
    }
}