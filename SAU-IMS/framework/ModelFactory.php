<?php
/**
 * Created by PhpStorm.
 * User: APone
 * Date: 2016/10/31
 * 单例工厂模式，加入类名即可返回对象，类放在model文件夹
 * Time: 23:52
 */
defined("APP") or die("error");

class ModelFactory
{
    /**
     * 以实例化的类存储数组
     * @var array
     */
    private static $classGroup = array();

    /**
     * 获得模型类(作用不大)
     * @param $name string 类名
     * @return mixed 返回实例
     * @throws ClassNotFoundException 无法找到类异常
     */
    public static function factory($name)
    {
        $classFile = MODEL_PATH . "$name.php";

        if (file_exists($classFile)) {//检测modei文件夹是否存在该类
            require_once $classFile;
        } else {
            throw new ClassNotFoundException("can't found this class");//无则抛出异常
        }

        if (!isset(self::$classGroup[$name]) || !(self::$classGroup[$name] instanceof $name)) {//检测是否已存在该类实例或不对货
            return self::$classGroup[$name] = new $name();
        }
        return self::$classGroup[$name];//返回该实例
    }

    /**
     * 利用用户名和密码生成相应的用户类
     * 1---社团管理员
     * 2---校社联管理员
     * 由于是直接生成类没有传入参数进构造函数，需使用setUserName()传入用户名
     * @param $userName string
     * @return mixed 用户类
     * @throws ClassNotFoundException 找不到类异常
     */
    public static function adminFactory($userName)
    {
        $right = BaseUser::getUserIdentify($userName)["right"];//获取权限标识

        switch ($right) {//形成不同管理员类
            case 1:
                $userType = "ClubAdmin";
                break;
            case 2:
                $userType = "SauAdmin";
                break;
            default:
                throw new ClassNotFoundException("can't found this class");
                break;
        }

        return new $userType($userName);//返回该实例
    }
}

/**
 * 找不到类异常类
 * Class ClassNotFoundException
 */
class ClassNotFoundException extends Exception
{
    /**
     * @var string 错误信息
     */
    private $error;

    /**
     * ClassNotFoundException constructor.
     * @param string $message 错误信息
     */
    public function __construct($message)
    {
        $this->error = $message;
    }

    /**
     * 获得错误信息
     * @return string 错误信息
     */
    public function getError()
    {
        return $this->error;
    }
}


