<?php
namespace app\index\controller;

use think\Controller;

class Wxpay extends Controller
{
    public function notyfy()
    {
        $wxData = file_get_contents("php://input");
        file_put_contents('/tmp/2.txt',$wxData, FILE_APPEND);
    }

}
