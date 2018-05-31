<?php
namespace app\api\controller;


use think\Controller;
use think\Request;
use think\File;

class Image extends Controller
{
    public function upload(){
        // $file = Request::instance()->file('file');
        $file = $this->request->file('file');
        // 给定一个目录
        $info = $file->move('upload');
        // 当对象存在 并且 图片也有
        if($info && $info->getPathname()){
            // 图片上传成功
            return show(1, 'success', '/'.$info->getPathname());
        }
        return show(0, 'upload error');
    }
}