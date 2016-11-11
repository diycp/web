<?php
namespace app\common\controller;

use think\Controller;
use think\Session;
use think\Request;
use think\Url;
use think\Config;

// use 引入的类文件需要首字母大写

/**
* 
*/
class AdminBase extends Controller
{
	protected $request;
	private $module_name;

	function __construct()
	{
		parent::__construct();
		$this->request = Request::instance();
		$this->module_name = Request::instance()->module();

		if($this->module_name == "admin"){
			$this->checkAccess();
		}	
	 	//获取session
	 	$userId = Session::get(Config::get('USER_AUTH_KEY').'.id');

	 	if(is_null($userId)){
	 		$this->goLogin();
	 	}
	}


	public function checkAccess()
	{


		// echo "string";
		$uid = $this->key();
		// $test = new Permission();//实例化后多了一个换行//
		// $data = $test->test();
  

		if(is_null($uid)){
			$this->goLogin();
			return false;
		}

		
		
	}

	public function key()
	{
		$user = Session::get(Config::get('USER_AUTH_KEY'));
		if(is_null($user)){
			$this->goLogin();
		}
		if(empty($user)){
			$this->goLogin();
		}
		$user = reset($user);
		return false; $user;
	}

	public function goLogin()
	{
		Session::clear();
		$redirect = '/admin/login/'; 
		$this->redirect(Url::build($redirect));
	}


	protected function fetch($template = '', $vars = [], $replace = [], $config = [])
	{
		if (request()->isPost() || request()->isAjax()) {
			Config::set('layout_on',false);
		}
		// var_dump(321);die;
		parent::fetch($template, $vars, $replace, $config);
		exit();
	}






}

