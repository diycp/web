<?php
	
return [
	'WEBSITE_NAME'          		=>  'Aierui后台',
	//模板布局
	'template'  =>  [
	    'layout_on'     			=>  true,
	    'layout_name'   			=>  'layout',
	],
	// 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  	=> APP_PATH  . 'admin/view/' .DS. 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    	=> APP_PATH  . 'admin/view/' .DS. 'dispatch_jump.tpl',


    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => RUNTIME_PATH.'data/',
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],


    'USER_AUTH_KEY'             =>  'authId',   // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'        =>  'administrator',


    'USER_TABLE_NAME'       =>  'users',    // 用户表名称
    'AUTH_TABLE_MENU'       =>  'bs_menu',    // 菜单表名称
    'AUTH_TABLE_NODE'       =>  'bs_node',    // 权限节点表名称
    'AUTH_TABLE_ROLE'       =>  'bs_role',    // 角色表名称
];