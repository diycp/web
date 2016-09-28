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

	private static $table_menu = Config::get('AUTH_TABLE_MENU');
	private static $table_node = Config::get('AUTH_TABLE_NODE');
	private static $table_role = Config::get('AUTH_TABLE_ROLE');
	private static $table_role_user = Config::get('AUTH_TABLE_ROLE_USER');
	private static $auth_key   = Config::get('USER_AUTH_KEY');

	public static function getAllMenu()
	{
	
		if(is_null(self::$menuData)){
			self::$menuData =	Cache::get('menu');
		}
		return self::$menuData;
	}

	public static function authId()
	{
		$authId = Session::get(self::$auth_key.'.id');

		 // self::getAllMenu();4
		$roles = Db::table(self::$table_role_user)
		 			->where('user_id',$authId)
		 			->select();

 		
	}





}


