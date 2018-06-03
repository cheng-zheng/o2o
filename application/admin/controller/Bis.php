<?php
namespace app\admin\controller;


use think\Controller;

class Bis extends Controller
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('Bis');
    }
    /**
     * 正常的商户列表
     * @return mixed
     */
    public function index(){
        $bis = $this->obj->getBisByStatus(1);
        return $this->fetch('', [
            'bis'   => $bis,
        ]);
    }
    /**
     * apply 商家入驻申请页面
     * @return mixed
     */
    public function apply(){
        $bis = $this->obj->getBisByStatus();
        return $this->fetch('', [
            'bis'   => $bis,
        ]);
    }

    public function detail(){
        $id = input('get.id');
        if(!$id){
            return $this->error('ID错误');
        }
        //获取一级城市的数据
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级栏目的数据
        $category = model('Category')->getNormalCategoryByParentId();
        // 获取商户数据
        $bisData = model('Bis')->get($id);
        $locationData = model('BisLocation')->get(['bis_id'=>$id, 'is_main'=>1]);
        $accountData = model('BisAccount')->get(['bis_id'=>$id, 'is_main'=>1]);
        return $this->fetch('',[
            'citys'         => $citys,
            'categorys'     => $category,
            'bisData'       => $bisData,
            'locationData'  => $locationData,
            'accountData'   => $accountData
        ]);
    }
    /**
     * 状态
     */
    public function status()
    {

        $data = input('get.');
        $validate = validate('Bis');
        if( !$validate->scene('status')->check($data) ){
            return $this->error($validate->getError());
        }

        $res = $this->obj->where('id',$data['id'])->update([
            'status'=>$data['status']
        ]);
        $location = model('BisLocation')->where('bis_id',$data['id'])->update([
            'status'=>$data['status'],
            'is_main'=>1
        ]);
        $account = model('BisAccount')->where('bis_id',$data['id'])->update([
            'status'=>$data['status'],
            'is_main'=>1
        ]);
        if($res && $location && $account){
            // 发送邮件
            // status:1审核通过，2不通过，-1删除
            // send_eamil($to, $title, $content);
            $this->sendStatusEmail($data['id'], $data['status']);
            $this->success('状态更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }

    private function sendStatusEmail($id, $status){
        $email = $this->obj->get( ['id'=>$id] )['email'];
        $str = '';
        switch($status){
            case '1':
                $str = '审核通过';
                break;
            case '2':
                $str = '不通过';
                break;
            case '-1':
                $str = '审核删除';
                break;
        }
        send_email($email,'入驻申请结果',$str);
    }
}
