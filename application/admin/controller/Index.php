<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        return view();
    }
    public function test(){
        $res = \Map::getLngLat('广东省惠州市');
        print_r($res);
        exit();
        return 'singwa';
    }
    public function map()
    {
        return \Map::staticimage('北京昌平沙河地铁');
    }
    public function welcome()
    {
        $toemail='965950134@qq.com';
        $name='965950134';
        $subject='QQ邮件发送测试';
        $content='恭喜你，邮件测试成功。';
        //send_email($toemail,$name,$subject,$content);
        return 'o2o后台';
    }
}
