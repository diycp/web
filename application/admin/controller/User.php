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
    // private $user;
    function __construct()
    {
        parent::__construct();
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
            $data['password'] = md6($data['password']);
            $res = Db::table('users')->insert($data);
            if($res == 1){
                return info('添加成功！',1);
            }else{
                return info('添加失败！',0);
            }
        }
        return $this->fetch();
    }

    public function edit($id = 0)
    {
        $data = request()->param();
        $id = intval($data['id']);
        if(request()->isPost()){
            $data['password'] = md6($data['password']);
            $res = Db::table('users')->update($data);
            if($res >= 0){
                return info('修改成功！',1);
            }else{
                return info('修改失败！',0);
            }
        }
        
        $data = Db::table('users')->where('id',$id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }






}