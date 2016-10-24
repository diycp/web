<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Request;
use think\Url;
use think\Session;

/**
* 
*/
class Login extends Controller
{

	public function index()
	{
		return view();
	}

	public function doLogin()
	{
		$request = Request::instance();

		if($request->isAjax()){
			$data = $request->param();

			$result = $this->validate($data,'User.login');
			
			if($result != true){
				return ['status' => 0, 'data' => $result];
			}

			$userModel = Loader::model('User');
			$userRow = $userModel->login($data);

			if ($userRow === false) {
                return $this->error($userModel->getError());
            }

            return $this->success('登录成功', Url::build('/admin/menu'));

            
		}else{
			return $this->fetch();
		}
	}

	public function out()
	{
		Session::delete('user');
		return $this->success('退出成功！', '/admin/login');
	}
    
}