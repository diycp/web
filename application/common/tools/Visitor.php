<?php
namespace app\common\tools;

/**
 * 获取受访者信息
 */
class Visitor
{

    /**
     * 获取浏览器 
     * @return   [type]                   [description]
     */
    public static function getBrowser()
    {
        $agent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($agent, 'MSIE') !== false || strpos($agent, 'rv:11.0')) //ie11判断
        {
            return "ie";
        } else if (strpos($agent, 'Firefox') !== false) {
            return "firefox";
        } else if (strpos($agent, 'Chrome') !== false) {
            return "chrome";
        } else if (strpos($agent, 'Opera') !== false) {
            return 'opera';
        } else if ((strpos($agent, 'Chrome') == false) && strpos($agent, 'Safari') !== false) {
            return 'safari';
        } else if (strpos($agent, 'MicroMessenger') !== false) {
            return 'weixin';
        } else {
            return 'unknown';
        }

    }

    /**
     * 获取浏览器版本号 
     * @return   [type]                   [description]
     */
    public static function getBrowserVer()
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            //当浏览器没有发送访问者的信息的时候
            return 'unknow';
        }
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif (preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif (preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif (preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif ((strpos($agent, 'Chrome') == false) && preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs)) {
            return $regs[1];
        } else {
            return 'unknow';
        }

    }

    /**
     * 获取ip 
     * @return   [type]                   [description]
     */
    public static function getIP()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 获取用户系统 
     * @return   [type]                   [description]
     */
    public static function getOs()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $os    = false;

        if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
            $os = 'Windows 95';
        } else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
            $os = 'Windows ME';
        } else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)) {
            $os = 'Windows 98';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)) {
            $os = 'Windows Vista';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)) {
            $os = 'Windows 7';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)) {
            $os = 'Windows 8';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)) {
            $os = 'Windows 10'; #添加win10判断
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)) {
            $os = 'Windows XP';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)) {
            $os = 'Windows 2000';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)) {
            $os = 'Windows NT';
        } else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)) {
            $os = 'Windows 32';
        } else if (preg_match('/linux/i', $agent)) {
            $os = 'Linux';
        } else if (preg_match('/unix/i', $agent)) {
            $os = 'Unix';
        } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'SunOS';
        } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'IBM OS/2';
        } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
            $os = 'Macintosh';
        } else if (preg_match('/PowerPC/i', $agent)) {
            $os = 'PowerPC';
        } else if (preg_match('/AIX/i', $agent)) {
            $os = 'AIX';
        } else if (preg_match('/HPUX/i', $agent)) {
            $os = 'HPUX';
        } else if (preg_match('/NetBSD/i', $agent)) {
            $os = 'NetBSD';
        } else if (preg_match('/BSD/i', $agent)) {
            $os = 'BSD';
        } else if (preg_match('/OSF1/i', $agent)) {
            $os = 'OSF1';
        } else if (preg_match('/IRIX/i', $agent)) {
            $os = 'IRIX';
        } else if (preg_match('/FreeBSD/i', $agent)) {
            $os = 'FreeBSD';
        } else if (preg_match('/teleport/i', $agent)) {
            $os = 'teleport';
        } else if (preg_match('/flashget/i', $agent)) {
            $os = 'flashget';
        } else if (preg_match('/webzip/i', $agent)) {
            $os = 'webzip';
        } else if (preg_match('/offline/i', $agent)) {
            $os = 'offline';
        } else {
            $os = 'unknow';
        }
        return $os;
    }
}
