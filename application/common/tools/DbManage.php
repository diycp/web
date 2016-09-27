<?php
namespace app\common\tools;

use \think\Config;
use \think\Db;

/**
 *
 */
class DbManage
{
    // 换行符
    private static $ds = "\n";
    // 一次循环条数
    private static $size = 20;
    // 每条sql语句的结尾符
    private static $sqlEnd = ";";

 
    public static function backup($table, $dir = "backupMysql")
    {
        $dir = RUNTIME_PATH . $dir . DS . date('Y-m-d') . DS;
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = $table . ".sql";

        $sql = self::_startBackup($table);

        return file_put_contents($dir . $filename, $sql);
    }
    /**
     * 备份数据表
 
     * @param    [type]                   $table [description]
     * @param    [type]                   $dir   [description]
     * @param    [type]                   $size  [description]
     * @return   [type]                          [description]
     */
    private static function _startBackup($table)
    {
        try {
            $tableColumns = Db::execute("SHOW COLUMNS FROM `{$table}`");
        } catch (\PDOException $e) {
            return false;
        }

        // 插入dump信息
        $sql = self::_retrieve();

        $sql .= self::_insertTableStructure($table);

        // 数据总条数
        $count = Db::table($table)->count();
        if ($count == 0) {

            return $sql;
        }

        // 数据总页数
        $pageSize = ceil($count / self::$size);

        //数据备份
        $sql .= self::_insertRecord($table, $pageSize);

        return $sql;

    }

    /**
     * 插入数据库备份基础信息
 
     * @return   [type]                   [description]
     */
    private static function _retrieve()
    {
        $version = self::get('select VERSION() as v');

        $value = '';
        $value .= '--' . self::$ds;
        $value .= '-- MySQL database dump' . self::$ds;
        $value .= '-- Created by DbManage class, Power By luffyzhao. ' . self::$ds;
        $value .= '-- https://github.com/lovezhao311 ' . self::$ds;
        $value .= '--' . self::$ds;
        $value .= '-- 主机: ' . Config::get('database.hostname') . self::$ds;
        $value .= '-- 生成日期: ' . date('Y') . ' 年  ' . date('m') . ' 月 ' . date('d') . ' 日 ' . date('H:i') . self::$ds;
        $value .= '-- MySQL版本: ' . $version['v'] . self::$ds;
        $value .= '-- PHP 版本: ' . phpversion() . self::$ds;
        $value .= self::$ds;
        $value .= '--' . self::$ds;
        $value .= '-- 数据库: `' . Config::get('database.database') . '`' . self::$ds;
        $value .= '--' . self::$ds . self::$ds;
        $value .= '-- -------------------------------------------------------';
        $value .= self::$ds . self::$ds;
        return $value;
    }

    /**
     * 插入表结构
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    private static function _insertTableStructure($table)
    {
        $sql = '';
        $sql .= "--" . self::$ds;
        $sql .= "-- 表的结构" . $table . self::$ds;
        $sql .= "--" . self::$ds . self::$ds;

        // 如果存在则删除表
        $sql .= self::dropTableIfExists($table);

        $res = self::get("SHOW CREATE TABLE `user`;");

        $sql .= $res['Create Table'] . self::$sqlEnd . self::$ds;

        $sql .= self::$ds;
        $sql .= "--" . self::$ds;
        $sql .= "-- 转存表中的数据 " . $table . self::$ds;
        $sql .= "--" . self::$ds;
        $sql .= self::$ds;
        return $sql;

    }

    /**
     * [_insertRecord description]
     * @param    [type]                   $record [description]
     * @return   [type]                           [description]
     */
    private static function _insertRecord($table, $pageSize)
    {
        $sql = "INSERT INTO `{$table}` VALUES " . self::$ds;

        for ($i = 0; $i < $pageSize; $i++) {
            $rows       = Db::table($table)->limit($i, self::$size)->select();
            $delimiter1 = "";
            foreach ($rows as $row) {
                $sql .= "{$delimiter1}(";
                $delimiter2 = "";
                foreach ($row as $value) {
                    $sql .= "{$delimiter2}" . Db::quote($value);
                    $delimiter2 = ",";
                }
                $sql .= ")" . self::$ds;
                $delimiter1 = ",";
            }
        }

        $sql .= self::$sqlEnd;

        return $sql;
    }

    /**
     * 如果存在则删除表 sql 语句 
     * @param    [type]                   $table [description]
     * @return   [type]                          [description]
     */
    private static function dropTableIfExists($table)
    {
        return "DROP TABLE IF EXISTS `" . $table . '`' . self::$sqlEnd . self::$ds;
    }

    /**
     * [get description] 
     * @param    [type]                   $sql [description]
     * @return   [type]                        [description]
     */
    private static function get($sql)
    {
        $query = Db::query($sql);
        return $query[0];
    }
}
