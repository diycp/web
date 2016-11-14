<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use think\Config;
use think\Cache;
use think\View;


/**
* 
*/
class Subject extends AdminBase
{
	function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		return $this->fetch();
	}

}


?>