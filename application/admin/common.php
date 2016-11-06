<?php

function info($msg = '', $code = '', $url = '',  $data = '', $wait = 3 )
{
	if (is_numeric($msg)) {
        $code = $msg;
        $msg  = '';
    }
    if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
        $url = $_SERVER["HTTP_REFERER"];
    } elseif ('' !== $url) {
        $url = preg_match('/^(https?:|\/)/', $url) ? $url : Url::build($url);
    }
	$result = [
        'code' => $code,
        'msg'  => $msg,
        'data' => $data,
        'url'  => $url,
        'wait' => $wait,
	];
	return $result;
}


/**
 * 数组排序
 */
function sort_list(&$list, $pid = 0, $index = 0){
    if (empty($list)) {
        return;
    }
    $data = array();
    
    $level = array('一', '二', '三', '四', '五', '六', '七', '八', '九', '十');
    foreach ($list as $key => $value) {
        if ($value['pid'] == $pid) {
            unset($list[$key]);
            if ($pid > 0) {
                $split_str = '├─';
                for ($i = $index - 1; $i > 0; $i --) {
                    $split_str .= '──';
                }
                $value['split'] = $split_str;
                $value['level'] = $level[$index];
            }else{
                $value['split'] = '';
                $value['level'] = $level[0];
            }
            $data[] = $value;
            $children = sort_list($list, $value['id'], $index + 1);
            if(!empty($children)){
                $data = array_merge($data , $children);
            }
        }
    }
    
    // 把没有父节点的数据追加到返回结果中，避免数据丢失
    if($pid == 0 ){
        if(count($list) > 0){
            $data = array_merge($data, $list);
        }
        
        $list = $data;
        return $list;
    }
    return $data;
}


?>