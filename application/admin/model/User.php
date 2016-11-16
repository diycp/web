<?php
namespace app\admin\model;

use think\Db;
use think\Loader;
use think\Model;
use think\Session;
use think\Config;

class User extends Model
{

	

	protected $table = 'users';

	/**
	 *  用户登录
	 */
	public function login(array $data)
	{
		$password = md6($data['password']);
		$map['mobile'] = $data['mobile'];
 
		$userRow = Db::table('users')
					->where($map)
					->find();
		if ($userRow['administrator'] == 1) {
		}elseif ($userRow['administrator'] == 0) {
			$exist = Db::table('bs_role_user')->where('user_id',$userRow['id'])->find();
			if (empty($exist)) {
				$this->error = '该账号未授权，请联系超级管理。';
				return false;
			}
		} 
					// var_dump($userRow);die;
		if( empty($userRow) || $userRow['status'] == 0 || $userRow['password'] != $password ){
			if(empty($userRow)){
				$this->error = '该手机号未注册！';
			}elseif($userRow['status'] == 0){
				$this->error = '该用户已被禁用，请联系管理员！';
			}elseif($userRow['password'] != $password){
				$this->error = '密码错误！';
			}

			//登录失败要记录在日志里
	    	// Loader::model('BackstageLog')->record(" 登录失败, username:[{$data['username']}] password:$password ");
	    	return false;
		}
        unset($userRow['password']);
        Session::set(Config::get('USER_AUTH_KEY'), $userRow,'admin');

        //登录成功要记录在日志里
        // Loader::model('BackstageLog')->record('登录');
		return $userRow;	
	}

	public function index($data = null)
	{
		$join = [['bs_role_user access','u.id = access.user_id','LEFT '],
				 ['bs_role role','access.role_id = role.id','LEFT']
				];
		$data = Db::table('users')
					->alias('u')
					->field('u.id,u.username,u.mobile,u.status,u.create_time,
						GROUP_CONCAT(role.`name`) AS role_name')
					->join($join)
					->group('u.id')
					->order('id desc')
					->select();

		foreach ($data as $key => $value) {
			$data[$key]['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
		}
		return $data;
	}


	public function add(array $data)
	{	
		if($data['password2'] != $data['password']){
            return info('两次密码不一致！',0);
        }
		$data['password'] = md6($data['password']);
		$data['create_time'] = time();
		// $data['administrator'] = 1;
		$user = new User($data); 
		$res = $user->allowField(true)->save();
		if($res == 1){
            return info('添加成功！',1);
        }else{
            return info('添加失败！',0);
        }
	}

	public function edit(array $data)
	{
		if($data['password2'] != $data['password']){
            return info('两次密码不一致！',0);
        }
		$data['password'] = md6($data['password']);
		// $data['create_time'] = time();
		$user = new User; 
		$res = $user->allowField(true)->save($data,['id'=>$data['id']]);
		if($res == 1){
            return info('修改成功！',1);
        }else{
            return info('修改失败！',0);
        }
	}


}
