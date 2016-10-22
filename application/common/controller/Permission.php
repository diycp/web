<?php
namespace app\common\controller;

use think\Session;
use think\Config;
use think\Cache;
use think\Db;
use think\Request;

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
		// var_dump($data);die;
 		return $data;
	}

	public static function getParentId()
	{
		$nodes = self::getNodes();
		$pid = Db::table(self::$table_node)
				->where('id','in',$nodes)
				->column('pid');
		$pid = array_unique($pid);//pid 就是menu表的id   2 3 4 7 
		$menuId = Db::table(self::$table_menu)
					->where('id','in',$pid)
					->column('pid');
		$parentId = array_unique($menuId);//一级菜单id
		for($i=0;$i<count($parentId);$i++){
			$keys[$i] = $i;
		}
		$parentId = array_combine($keys, $parentId);
		$data = array('parentId' => $parentId,'pid' => $pid);
		return $data;
	}


    public static function menuList()
    {
        //一级菜单id
        $data = self::getParentId();
        $parent = $data['parentId'];
        $list = array();
        foreach ($parent as $key => $id) {
            $list['button'] = Db::table(self::$table_menu)->where("pid = 0 and id = $id")->value('title');
            $list['icon'] = Db::table(self::$table_menu)->where("pid = 0 and id = $id")->value('icon');
            $sub = Db::table(self::$table_menu)->where("status<>0 and pid = $id")->where('id','in',$data['pid'])->order('sort desc,id')->select();
            foreach ($sub as $k => $item) {
                //title
                $sub_button[$k]['title'] = $item['title'];
                // 组合URL
                if(!empty($item['module']) && !empty($item['controller']) && !empty($item['action'])){
                    $sub_button[$k]['url'] = '/'.$item['module'].'/'.$item['controller'];
                    if($item['action'] != 'index'){
                        $sub_button[$k]['url'] .= '/'.$item['action'];
                    }
                    if(!empty($item['params'])){
                        $sub_button[$k]['url'] .= $item['params'];
                    }
                }else{
                    $sub_button[$k]['url'] = '';
                }
                //icon
                $sub_button[$k]['icon'] = $item['icon'];
                //target
                $sub_button[$k]['target'] = $item['target'];
            }
            $list['sub_button']  = $sub_button;
            unset($sub_button);
            $menuList[$key] = $list;
            unset($list);
        }
        return $menuList;
    }


    /**
     * 获取当前菜单的节点
     * 1.获取当前的链接 拿到控制器
     * 2.获取该控制器的id 
     * 3.根据id 在node表pid
     */
    public static function getCurrentAccessList()
    {	
    	$request = Request::instance();
    	$module = $request->module();
    	$controller = $request->controller();
    	$action = $request->action();

    	$controller = strtolower($controller);
    	$map['menu.status'] = ['<>',0];
    	$map['menu.controller'] = ['=',$controller];
    	$map['node.visible'] = ['=',1];
    	$nodes = Db::table(self::$table_menu)
    			->alias('menu')
    			->join(self::$table_node .' node', 'menu.id = node.pid')
    			->field('menu.title,menu.module,menu.controller,menu.action,node.*')
    			->where($map)
    			->order('node.id,sort desc')
    			->select(); 
    	// echo "<pre>";
    	// var_dump($nodes);die;
		return $nodes;
    }

    /**
     * 获取所有得上级父节菜单
     * 1.获取到控制器
     * 2.拿到控制器得父级
     * 3.将操作方法添加到控制器下
     * @return [type] [description]
     */
    public static function getParents()
    {
    	$request = Request::instance();
    	$module = $request->module();
    	$controller = $request->controller();
    	$action = $request->action();

    	$controller = strtolower($controller);
    	$map['menu.controller'] = ['=',$controller];
    	$data = Db::table(self::$table_menu)
    				->alias('menu')
    				->where($map)
    				->find();
    	$ptitle = Db::table(self::$table_menu)
    				->alias('menu')
    				->where('id',$data['pid'])
    				->field('title,icon')
    				->find();
    	$data['ptitle'] = $ptitle['title'];			
    	$data['picon'] = $ptitle['icon'];			
    	return $data;    	
    }

	/**
	 * 析构方法
	 */
	public function __destruct()
	{
		// self::$table_menu = null;
	}






}
?>


