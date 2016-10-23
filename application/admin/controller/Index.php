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
        // $key = $this->key();
        echo "string";
        
        return view('index');
    }

    public function test()
    {
        var_dump( $this->getTest('Aierui') );


        // Session::clear();
        var_dump(Session::get('user'));
    }
}