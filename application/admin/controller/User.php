<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Session;
use think\Request;
use think\Loader;
use think\Db;

/**
* 
*/
class User extends AdminBase
{
    private $users;
    function __construct()
    {
        parent::__construct();
        $this->users = Db::table('users');
    }

    public function index()
    {
        if(request()->isAjax()){

            $data = request()->param();
            
            $userModel = Loader::model('User');
            $index = $userModel->index($data);
            return $index;
        }
        return view();
    }

    public function add()
    {   
        if( request()->isPost() ){
            $data = request()->param();
            
            $userModel = Loader::model('User');
            $add = $userModel->add($data);
            return $add;
        }
        return $this->fetch('edit');
    }

    public function edit($id = 0)
    {
        $data = request()->param();
        $id = intval($data['id']);
        if(empty($id)){
            return info('数据ID异常',0);
        }
        if(request()->isPost()){
            $userModel = Loader::model('User');
            $edit = $userModel->edit($data);
            return $edit;
        }
        $data = $this->users->where('id',$id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function delete($id = 0){
        if(empty($id)){
            return info('删除项不能为空！',0);
        }
        $result = $this->users->delete($id);
        if ($result > 0) {
            return info('删除成功！',1);            
        }        
    }

    




}