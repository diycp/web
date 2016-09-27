<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use think\Config;
use think\Cache;

/**
* 
*/
class Menu extends AdminBase
{
    private $menu;    
    function __construct()
    {
        parent::__construct();
        $this->menu = Config::get('AUTH_TABLE_MENU');
    }

    public function index()
    {

        // dump(Cache::get('menu'));die;
        return view('index');
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
        

        
        // if(!IS_AJAX){
            $this->success('缓存已更新！', 'javascript:window.history.back();');
        // }
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






   
}