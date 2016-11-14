<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use think\Config;
use think\Cache;
use think\View;


/**
* 菜单管理
*/
class Menu extends AdminBase
{
    private $menu;    
    private $node;    
    function __construct()
    {
        parent::__construct();
        // $this->menu = Db::name(Config::get('AUTH_TABLE_MENU'));
        $this->menu = Db::name('bs_menu');

        $this->node = 'bs_node';
        // $this->node = Config::get('AUTH_TABLE_NODE');
    }

    private function menu_list($all = true){
        if(!$all){
            $this->menu->field("id, pid, title, status");
            $id = input('get.id/d');
            if($id > 0){
                $this->menu->where("id!={$id}");
            }
        }
        $rows = $this->menu->order('pid, sort DESC, id')->select();
        $rows = sort_list($rows);
        return $rows;
    }

    /**
     * 列表
     */
    public function index()
    {
        if (request()->isAjax()) {
            $rows = $this->menu_list(true);
            foreach ($rows as $i => $item) {
                $rows[$i]['title'] = $item['split'].$item['title'];
                $rows[$i]['url'] = (empty($item['module']) ? '' : '/'.$item['module']) . '/' . $item['controller'];
                if(!empty($item['action'])){
                    $rows[$i]['url'] .= '/' . $item['action'];
                }
                if (! empty($item['params'])) {
                    $rows[$i]['url'] .= '?' . $item['params'];
                }
            
            }
           return $rows;
        }
        return view();
    }
    
    
    /**
     * 添加菜单
     */
    public function add(){
        if(request()->isPost()){
            $data = request()->param();
            //模块参数都需要填写 不然无效
            if(empty($data['module']) || empty($data['controller']) || empty($data['action'])){
                unset($data['module']);
                unset($data['controller']);
                unset($data['action']);
            }

            $LastId = $this->menu->insertGetId($data);
            if($LastId >  0){
                // 自动创建菜单
                if(!empty($data['module']) && !empty($data['controller'])){
                    if(empty($data['action'])){
                        $data['action'] = 'index';
                    }
                    $nodes = [
                                ['pid' => $LastId, 'title' => '查看', 'name' => $data['action'], 'icon' => '', 'group' => 1, 'visible' => 0, 'event_type' => 'view', 'target' => '', 'sort' => '100'],
                                ['pid' => $LastId, 'title' => '添加', 'name' => 'add', 'icon' => 'fa-plus', 'group' => 1, 'visible' => 1, 'event_type' => 'view', 'target' => 'modal', 'sort' => '99'],
                                ['pid' => $LastId, 'title' => '编辑', 'name' => 'edit', 'icon' => 'fa-edit', 'group' => 1, 'visible' => 1, 'event_type' => 'view', 'target' => 'modal', 'sort' => '99'],
                                ['pid' => $LastId, 'title' => '删除', 'name' => 'delete', 'icon' => 'fa-trash', 'group' => 1, 'visible' => 1, 'event_type' => 'default', 'target' => 'modal', 'sort' => '99']                                
                            ];
                    Db::name($this->node)->insertAll($nodes);
                    $this->cache();
                }
                return info('添加成功！',1);
            }
            return info('添加失败！',0);
        }       
        $list = $this->menu_list(false);
        $this->assign(array('list' => $list,'data' => array('sort' => 99, 'status' => 1 , 'pid' => 0)));
        return $this->fetch('edit');
    }
    
    /**
     * 编辑
     */
    public function edit($id = 0){
        $data = request()->param();
        $id = intval($data['id']);
        if(request()->isPost()){
            if($id <= 0){
                $this->error('数据ID异常！');
            }            
            if(empty($data['module']) || empty($data['controller']) || empty($data['action'])){
                unset($data['module']);
                unset($data['controller']);
                unset($data['action']);
            }
            $result = $this->menu->update($data);
            if($result >= 0){
                $this->cache();
                return info('修改成功！',1);
            }
            return info('修改失败！',0);
        }
        
        $data = $this->menu->where('id',$id)->find();
        $list = $this->menu_list(true);
        $this->assign(array('data' => $data,'list' => $list ));
        return $this->fetch();
    }


    /**
     * 删除菜单
     */
    public function delete($id = 0){
        if(empty($id)){
            return info('删除项不能为空！',0);
        }
        $result = $this->menu->delete($id);
        if($result > 0){
            Db::table($this->node)->where("pid IN ({$id})")->delete();
            $this->cache();
        }
        return info('删除成功！',1);
    }


    
    /**
     * 工具栏按钮列表
     * @param number $menu_id
     */
    public function toolbar($menu = 0){

        $menu = intval($menu);
        if(request()->isPost()){
            $rows = Db::table('bs_node')->where("pid=".$menu)->order("`group`, sort desc, id")->select();

            $data = array( 'total' => count($rows), 'rows' => $rows );
            return $data;
        }
        $this->assign('menu_id', $menu);
        return $this->fetch();
    }
 
    public function addButton($menu_id = 0)
    {
        if(request()->isPost()){
            $data = request()->param();

            $result = Db::table('bs_node')->insert($data);
            if ($result == 1) {
                $this->cache();
                return info("添加成功！",1);
            }else{
                return info("添加失败！",0);
            }
        }

        $data = array('pid' => $menu_id,'visible' => 1);
        $this->assign('data',$data);
        return $this->fetch('editButton');
    }


    public function editButton($id = 0)
    {
        $data = request()->param();
        $id = intval($data['id']);
        
        if(request()->isPost()){
            $data = request()->param();

            $result = Db::table('bs_node')->update($data);
            if ($result == 1) {
                $this->cache();
                return info("修改成功！",1);
            }else{
                return info("修改失败！",0);
            }
        }
        $data = Db::table('bs_node')->where('id',$id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function deleteButton($id = 0)
    {
        if(empty($id)){
            return info('删除项不能为空！',0);
        }
        $result = Db::table('bs_node')->delete($id);
        if ($result > 0) {
            return info('删除成功！',1);            
        }                
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
        Cache::set('menu',$menuList,'admin');
        //获取节点
        $nodeList = $this->nodeList();
        //生成缓存文件
        Cache::set('node',$nodeList,'admin');
        return info("缓存已更新！",1);
        // return $this->success('缓存已更新！', '/admin/menu/');
    }

    /****************************缓存菜单****************************/


    public function menuList()
    {
        //一级菜单id
        $parent = Db::name(Config::get('AUTH_TABLE_MENU'))
                ->where('status<>0 and pid = 0')
                ->order('sort desc,id')
                ->column('id');
        $list = array();
        foreach ($parent as $key => $id) {
            $list['button'] = Db::name(Config::get('AUTH_TABLE_MENU'))->where("pid = 0 and id = $id")->value('title');
            $list['icon'] = Db::name(Config::get('AUTH_TABLE_MENU'))->where("pid = 0 and id = $id")->value('icon');
            $sub = Db::name(Config::get('AUTH_TABLE_MENU'))->where("status<>0 and pid = $id")->order('sort desc,id')->select();
            $sub_button = array();
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
        $list = Db::name(Config::get('AUTH_TABLE_MENU'))
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