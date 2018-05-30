<?php
namespace app\admin\controller;


use think\Controller;

class Category extends Controller
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('Category');
    }

    public function index()
    {
        $parentId = input('get.parent_id', 0, 'intval');//获取子栏目
        $category = $this->obj->getFirstCategory($parentId);
        $map = \Map::staticimage('惠州');
        return view('index',['category' =>  $category,'map'=>$map]);
    }

    public function add()
    {
        $category =  $this->obj->getNormalFirstCategory();
        return view('add',['category'   => $category]);
    }

    public function save(){
        /**
         * 做下严格判定
         */
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $data = input('post.');//dump($data);
        $validate = validate('Category');
        if( !$validate->scene('add')->check($data) ){
            return $this->error($validate->getError());
        }
        //更新分类
        if( !empty($data['id']) ){
            return $this->_update($data);
        }
        //把$data 提交model层
        $res =  $this->obj->add($data);
        if($res){
            $this->success('新增成功');
        }else{
            $this->error('新增失败');
        }
    }

    /**
     * 编辑
     * @param int $id
     * @return \think\response\View
     */
    public function edit($id = 0){
        if(intval($id)<1){
            $this->error('参数不合法');
        }
        $categoryEdit = $this->obj->get($id);
        $category =  $this->obj->getNormalFirstCategory();
        return view('edit',[
            'category'   => $category,
            'categoryEdit' => $categoryEdit
        ]);
    }

    /**
     * @param $data
     */
    public function _update($data)
    {
        $res = $this->obj->save($data, ['id'=>intval($data['id'])]);
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }
    /**
     * 排序
     * @param $id
     * @param $listorder
     */
    public function listorder($id, $listorder)
    {
        $res = $this->obj->update(['listorder'=>$listorder, 'id'=>$id ]);

        if($res){
            // 返回json
            $this->result($_SERVER['HTTP_REFERER'], 1, 'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, 'error');
        }
    }

    /**
     * 状态
     */
    public function status()
    {
        $data = input('get.');//dump($data);
        $validate = validate('Category');
        if( !$validate->scene('status')->check($data) ){
            return $this->error($validate->getError());
        }

        $res = $this->obj->update([
            'status'=>$data['status'],
            'id'=>$data['id']
        ]);

        if($res){
            $this->success('状态更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }

}
