<?php

/**
 * Created by PhpStorm.
 * 数据库操作类
 * User: APone
 * Date: 2016/10/29
 * Time: 15:42
 */

defined("APP") or die("error");

class Database
{
    /**
     * @var string 配置文件地址
     */
    private static $iniFileName = dbConfig;

    /**
     * @var PDO 数据库接口
     */
    private static $instance;

    /**
     * 加载配置文件信息
     * @return array
     */
    private static function loadConfig()
    {
        if (file_exists(self::$iniFileName)) {//配置文件是否存在
            $info = parse_ini_file(self::$iniFileName, true);//读取文件信息
            return $info;//返回信息组
        } else {
            die("数据库配置文件不存在");
        }
    }

    /**
     * 获得数据库接口
     * @return PDO
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof PDO) {//如未实例化
            $info = self::loadConfig();//加载配置文件
            $content = "$info[dbms]:host=$info[host];port=$info[port];dbname=$info[dbname];charset=$info[charset]";//获取配置文件数据

            try {
                self::$instance = new PDO($content, $info['username'], $info['password']);//新实例化
            } catch (PDOException $e) {
                die("配置信息错误");
            }
        }
        return self::$instance;//返回接口
    }
}