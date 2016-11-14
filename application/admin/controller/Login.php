<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Request;
use think\Url;
use think\Session;

/**
* 登录
*/
class Login extends Controller
{

	/**
	 * 后台登录首页
	 */
	public function index()
	{
		return view();
	}

	/**
	 * 登录验证
	 */
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
            return $this->success('登录成功', Url::build('/admin/user'));
		}else{
			return $this->fetch();
		}
	}

	/**
	 * 退出登录
	 */
	public function out()
	{
		Session::delete(config('USER_AUTH_KEY'),'admin');
		return $this->success('退出成功~');
	}
    
}