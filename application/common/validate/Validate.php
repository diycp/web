<?php
namespace app\common\validate;

use \think\Db;

trait Validate
{
    /**
     * 验证是否存在！
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-04-19T09:48:57+0800
     * @param    [type]                   $roleId [description]
     * @return   [type]                           [description]
     */
    public function exist($value, $rule, $data)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        $db    = Db::table($rule[0]);
        $field = isset($rule[1]) ? $rule[1] : 'id';
        $map   = [$field => $value];

        if ($db->where($map)->field($field)->find()) {
            return true;
        }
        return false;
    }

    /**
     * 验证PID是否存在 并且不等于本身
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-06-03T16:47:36+0800
     * @param    [type]                   $value [description]
     * @param    [type]                   $rule  [description]
     * @param    [type]                   $data  [description]
     * @return   [type]                          [description]
     */
    public function existPid($value, $rule, $data)
    {
        if (intval($value) === 0) {
            return true;
        }
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }

        $db    = Db::table($rule[0]);
        $field = $db->getPk($rule[0]);
        if (isset($data[$field]) && $data[$field] == $value) {
            return false;
        }
        if ($db->where([$field => $value])->field($field)->find()) {
            return true;
        }
        return false;
    }

}
