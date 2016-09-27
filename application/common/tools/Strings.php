<?php
namespace app\common\tools;

/**
 * 获取受访者信息
 */
class Strings
{

    /**
     * 替换字符串中间位置字符为星号
     * @param  [type] $str [description]
     * @return [type] [description]
     */
    public static function replaceToStar($str)
    {
        $len = strlen($str) / 2;

        return substr_replace($str, str_repeat('*', $len), floor(($len) / 2), $len);
    }

    /**
     * 获取文件访问地址 
     * @param string $value [description]
     */
    public static function fileWebLink($realPath)
    {
        $replace = dirname(ROOT_PATH);

        return str_replace($replace, '', $realPath);
    }

    /**
     * 通过文件访问地址获取 文件绝对地址 
     * @param  string $value [description]
     * @return [type] [description]
     */
    public static function fileWebToServer($webLink)
    {
        $replace = dirname(ROOT_PATH);

        return $replace . $webLink;
    }

    /**
     * 删除文件 
     * @param  string $value [description]
     * @return [type] [description]
     */
    public static function deleteFile($filename)
    {
        if (!file_exists($filename)) {
            $filename = self::fileWebToServer($filename);
        }

        if (file_exists($filename) && is_file($filename)) {
            unlink($filename);
        }
    }
    /**
     * [password description]
     * @param  [type] $string [description]
     * @return [type] [description]
     */
    public static function password($string)
    {
        return md5($string);
    }
}
