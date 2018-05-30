<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($s){
    if($s   ==   1){
        $str    =   "<span class='label label-success radius'>正常</span>";
    }elseif($s  ==  0){
        $str    =   "<span class='label label-danger radius'>待审</span>";
    }else{
        $str    =   "<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}
/**
 * 系统邮件发送函数
 * @param string $tomail 接收邮件者邮箱
 * @param string $name 接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $attachment 附件列表
 * @return boolean
 * @author static7 <static7@qq.com>
 */
function send_email($tomail, $name, $subject = '', $body = '', $attachment = null) {
    // set time
    date_default_timezone_set('PRC');
    if(empty($tomail)){
        return false;
    }
    try{
        //实例化PHPMailer对象
        $mail = new PHPMailer();
        //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->CharSet = 'UTF-8';
        // 设定使用SMTP服务
        $mail->IsSMTP();
        // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
        $mail->SMTPDebug = 1;
        // 启用 SMTP 验证功能
        $mail->SMTPAuth = true;
        // 使用安全协议
        $mail->SMTPSecure = 'ssl';
        // SMTP 服务器
        $mail->Host = config('email.host');
        // SMTP服务器的端口号
        $mail->Port = config('email.port');
        // SMTP服务器用户名
        $mail->Username = config('email.username');
        // SMTP服务器密码
        $mail->Password = config('email.password');
        //$mail->SetFrom('905618355@qq.com', '905618355');
        $mail->From = '905618355@qq.com';
        $replyEmail = '';                   //留空则为发件人EMAIL
        $replyName = '';                    //回复名称（留空则为发件人名称）
        $mail->AddReplyTo($replyEmail, $replyName);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($tomail, $name);
        if (is_array($attachment)) { // 添加附件
            foreach ($attachment as $file) {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
        return $mail->Send() ? true : $mail->ErrorInfo;
    }catch(phpmailerException $e){
        require false;
    }
}
/**
 * @param $url
 * @param int $type 0 get 1 post
 * @param array $data
 */
function doCurl($url, $type=0, $data=[]){
    $ch = curl_init();  // 初始化
    // 设置选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//返回 不输出
    curl_setopt($ch, CURLOPT_HEADER, 0);//不需要header输出

    if($type == 1){
        //post
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //执行并获取内容
    $output = curl_exec($ch);
    //dump($output);exit();
    //释放curl句柄
    curl_close($ch);
    return $output;
}