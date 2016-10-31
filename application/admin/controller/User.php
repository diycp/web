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

            // $userModel = Loader::model('User');
            // $result = $userModel->add($data);

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
        // echo "string";die();
        
            $data = request()->param();

            $id = intval($data['id']);
            // var_dump($id);die;
        if(request()->isPost()){
            
        }
        
        $data = Db::table('users')->where('id',$id)->find();

        // var_dump($data);die;

        $this->assign('data',$data);
        return $this->fetch();
    }



}