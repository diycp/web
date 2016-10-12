<?php
namespace app\common\controller;

use think\Session;
use think\Config;
use think\Cache;
use think\Db;

/**
* 
*/


class Permission
{
	private static $menuData;
	private static $table_menu;
	private static $table_node;
	private static $table_role;
	private static $table_role_user;
	private static $auth_key;

	/**
	 * 构造方法
	 */
	public function __construct() 
	{
		self::$table_menu = Config::get('AUTH_TABLE_MENU');
		self::$table_node = Config::get('AUTH_TABLE_NODE');
		self::$table_role = Config::get('AUTH_TABLE_ROLE');
		self::$table_role_user = Config::get('AUTH_TABLE_ROLE_USER');
		self::$auth_key = Config::get('USER_AUTH_KEY');
	}

 
	public static function getAllMenu()
	{
	
		if(is_null(self::$menuData)){
			self::$menuData = Cache::get('menu');
		}
		return self::$menuData;
	}
 	
 
	public static function authId()
	{
		$authId = Session::get(self::$auth_key.'.id');//
		echo "<pre>";

		$role_id = Db::table(self::$table_role_user)
		 			->where('user_id',$authId)
		 			->column('role_id');


		foreach ($role_id as $id) {
			$node[] = Db::table(self::$table_role)
					->where('id',$id)
					->column('node_id');

		}
		 	var_dump($node);die;
 		return $data;
	}


	/**
	 * 析构方法
	 */
	public function __destruct()
	{
		self::$table_menu = null;
	}






}
?>


