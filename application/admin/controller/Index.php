<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Session;

/**
* @author aierui github  https://github.com/Aierui
* @version 1.0 
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