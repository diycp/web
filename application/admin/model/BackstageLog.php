<?php
namespace app\admin\model;

use \think\Config;
use \think\Model;
use \think\Session;

class BackstageLog extends Model
{
    protected $updateTime = false;
    protected $insert     = ['ip', 'user_id'];
    protected $type       = [
        'create_time' => 'timestamp',
    ];

 
    protected function setIpAttr()
    {
        return \app\common\tools\Visitor::getIP();
    }

 
    protected function setUserIdAttr()
    {
        $user_id = 0;

        if (Session::has(Config::get('USER_AUTH_KEY')) !== false) {

            $user_id = Session::get(Config::get('USER_AUTH_KEY') . '.id');
        }
        return $user_id;
    }
 
 
    public function record($remark)
    {
        $this->save(['remark' => $remark]);
    }

}
