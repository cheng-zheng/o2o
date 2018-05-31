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
        sleep(1000);    // 客户交钱后优化#滑稽
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
        // 总店相关信息校验

        // 账户相关的信息校验

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
            'logo'         => $data['logo'],
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
        if(!$bisId){
            $this->error('申请失败');
        }
        // 总店相关信息入库
        $data['cat'] = '';
        if(!empty($data['category_id'])){
            $data['cat'] = implode('|',$data['se_cat']);
        }
        $locationData = [
            'bis_id'            => $bisId,
            'name'              => $data['name'],
            'logo'              => $data['logo'],
            'tel'               => $data['tel'],
            'contact'           => $data['contact'],
            'category_id'       => $data['category_id'],
            'category_path'     => $data['category_id'].','.$data['cat'],
            'city_id'           => $data['city_id'],
            'city_path'         => empty($data['se_city_id']) ? '' : $data['city_id'].','.$data['se_city_id'],
            'address'           => $data['address'],
            'open_time'         => $data['open_time'],
            'open_time'         => $data['open_time'],
            'content'           => empty($data['content']) ? '' : $data['content'],
            'is_main'           => 1,   // 代表的是总店信息
            'xpoint'           => empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
            'ypoint'           => empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lng'],
        ];
        $locationId = model('BisLocation')->add($locationData);
        if(!$locationId){
            $this->error('申请失败');
        }
        // 账户相关的信息
        // 自动生成 密码的加盐字符串、
        $data['code'] = mt_rand(100,10000);
        $accountData = [
            'bis_id'    => $bisId,
            'username'  => $data['username'],
            'code'      => $data['code'],
            'password'  => md5($data['password']).$data['code'],
            'is_main'   => 1,       //代表总店管理员
        ];
        $accountId = model('BisAccount')->add($accountData);
        if(!$accountId){
            $this->error('申请失败');
        }

        // 发送邮件
        $url = request()->domain().url('bis/register/waiting', ['id'=>$bisId]);
        $title = 'o2o入驻申请 通知';
        $content = '您提交的入驻申请需要等待平台方审核，您可以通过点击链接<a href="'.$url.'" target="_blank">查看链接</a> 查看审核状态';
        send_email($data['email'],'o2o',$title, $content);

        $this->success('申请成功');
    }

    public function waiting(){

    }
}