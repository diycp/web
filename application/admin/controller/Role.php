<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Session;
use think\Request;
use think\Loader;
use think\Db;

/**
* 
*/
class Role extends AdminBase
{
    private $role;
    function __construct()
    {
        parent::__construct();
        $this->role = Db::table('bs_role');
    }

    public function index()
    {
        if(request()->isAjax()){
            $data = $this->role->select();
            return $data;
        }
        return view();
    }


    public function add()
    {
        if (request()->isPost()) {
            $data = request()->param();
            $res = $this->role->insert($data);
            if($res == 1){
                return info('添加成功！',1);
            }else{
                return info('添加失败！',0);
            }
        }
        return $this->fetch('edit');
    }

    public function edit($id = 0)
    {
        if (request()->isPost()) {
            $data = request()->param();
            $res = $this->role->update($data);
            if($res == 1){
                return info('修改成功！',1);
            }else{
                return info('修改失败！',0);
            }
        }

        $data = $this->role->where('id',$id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }




    public function delete($id = 0){
        if(empty($id)){
            return info('删除项不能为空！',0);
        }
        $result = $this->role->delete($id);
        if ($result > 0) {
            return info('删除成功！',1);            
        }        
    }


    public function access_menu($id = 0)
    {
        if (request()->isPost()) {
            $data = request()->param();
            $res = $this->role->insert($data);
            if($res == 1){
                return info('添加成功！',1);
            }else{
                return info('添加失败！',0);
            }
        }
        return $this->fetch();
    }


}