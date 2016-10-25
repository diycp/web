<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use think\Config;
use think\Cache;
use think\Controller;


/**
* 
*/
class Menu extends AdminBase
{
    private $menu;    
    private $node;    
    function __construct()
    {
        parent::__construct();
        $this->menu = Config::get('AUTH_TABLE_MENU');
        $this->node = Config::get('AUTH_TABLE_NODE');
    }

    public function index()
    {
        // echo "string";
        // dump(Cache::get('menu'));die;
        return view();
        // return $this->display();
    }

    public function add()
    {
        return view();
    }



    /****************************缓存菜单****************************/
    /**
     * @return 菜单目录
     */
    public function cache()
    {
        //获取菜单
        $menuList = $this->menuList();
        //生成缓存文件
        Cache::set('menu',$menuList,0);

        //获取节点
        $nodeList = $this->nodeList();
        //生成缓存文件
        Cache::set('node',$nodeList,0);
        
        $this->success('缓存已更新！', '/admin/menu/');
    }

    /****************************缓存菜单****************************/


    public function menuList()
    {
        //一级菜单id
        $parent = Db::table($this->menu)
                ->where('status<>0 and pid = 0')
                ->order('id')
                ->column('id');
        $list = array();
        foreach ($parent as $key => $id) {
            $list['button'] = Db::table($this->menu)->where("pid = 0 and id = $id")->column('title');
            $sub = Db::table($this->menu)->where("status<>0 and pid = $id")->order('sort desc,id')->select();
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

    public function nodeList()
    {
        $list = Db::table($this->menu)
                    ->alias('menu')
                    ->join("$this->node node",'node.pid=menu.id')
                    ->field('menu.module, menu.controller, node.*')
                    ->where('menu.status<>0 AND node.access<>-1')
                    ->order('node.`group`,node.sort DESC, node.id')
                    ->select();
        $nodeList = array();
        foreach($list as $index=>$item){
            $nodeList[strtolower($item['module'])][strtolower($item['controller'])][strtolower($item['name'])] = $item;
        }
        return $nodeList;
    }






   
}