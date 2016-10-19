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
		$authId = Session::get(self::$auth_key.'.id');//获取用户id
		return $authId;
	}	


	public static function getNodes()
	{
		$authId = self::authId();
		$role_id = Db::table(self::$table_role_user)
		 			->where('user_id',$authId)
		 			->column('role_id');
		foreach ($role_id as $id) {
			$node = Db::table(self::$table_role)
					->where('id',$id)
					->column('node_id');
			$nodes[] = $node[0];
		}
		//将多个身份的node_id合并 一维数组
		$nodes = implode(",", $nodes);
		$tmp = explode(",", $nodes);
		unset($nodes);
		$data = array_unique($tmp);//节点id 取出pid


 		return $data;
	}

	public static function getMenuByNodes()
	{
		$nodes = self::getNodes();

		$pid = Db::table(self::$table_node)
				->where('id','in',$nodes)
				->select();
		return $pid;
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


