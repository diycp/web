<?php
namespace app\admin\controller;

class Index
{
    public function index()
    {
        // return "string---admin";
        return view('index');
    }

    public function test()
    {
    	// return "test";
    	echo "<pre>";
    	// Config::get('database');
    	var_dump(config::get('database'));
    }
}
