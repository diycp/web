<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Session;

/**
* 
*/
class User extends AdminBase
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    	// var_dump(Session::delete('user'));
        return view('index');
       // return "你好";
    }

}