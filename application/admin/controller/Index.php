<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Session;

/**
* 
*/
class Index extends AdminBase
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('index');
    }

}