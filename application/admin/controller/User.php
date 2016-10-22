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

            // $data = $request->param();
            // var_dump($data);
            $userModel = Loader::model('User');
            $index = $userModel->index();
            return json_encode($index);
        }


        $nodeList = \app\common\controller\Permission::getCurrentAccessList();

        echo "<pre>";
        var_dump($nodeList);





        

        // return $index;
        return $this->fetch();
    }

    public function test()
    {
        $this->success('test');
    }

}