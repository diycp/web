<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Session;
use think\Request;
use think\Loader;

/**
* 
*/
class Role extends AdminBase
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
       
         
        return $this->fetch();
    }

    public function test()
    {
        $this->success('test');
    }

}