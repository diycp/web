<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{

    protected $rule =   [
        'username'              => 'require|length:11',
        'password'              => 'length:6,20',
    ];

    protected $message  =   [
        'username.require'      => '账号必须',
        'username.length'       => '请输入正确手机号！',
        'password.length'       => '密码应在6-20之间',
    ];

    protected $scene = [
        'login'                 =>  ['username','password'],
    ];

}


