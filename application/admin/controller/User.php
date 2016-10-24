<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Session;
use think\Request;
use think\Loader;

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
       
        $request = Request::instance();

        if($request->isAjax()){

            $data = $request->param();
            
            $userModel = Loader::model('User');
            $index = $userModel->index($data);
            return $index;
        }

        return $this->fetch();
    }

    public function add()
    {   
        $request = Request::instance();

        if( $request->isPost() ){

            $data = $request->param();
            $userModel = Loader::model('User');
            $result = $userModel->add($data);

            return $result;
        }
        return $this->fetch();
    }



}