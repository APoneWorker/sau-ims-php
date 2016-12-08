<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 管理员设置控制类
 * Date: 2016/12/6
 * Time: 17:46
 */
defined("APP") or die("error");

class AdminSettingCtrl
{
    /**
     * 用户
     * @var mixed
     */
    private $user;

    /**
     * 构造函数
     * AdminSettingCtrl constructor.
     */
    public function __construct()
    {
        session_start();                                        //打开session
        $userName = $_SESSION['userName'];                      //获取管理员用户名
        try {
            $this->user = ModelFactory::adminFactory($userName);//识别和创建管理员model类对象
        } catch (ClassNotFoundException $e) {
            header("Location:./index.php?c=LoginAdmin");        //如果用户未登录而又想靠地址进入，则阻挡且跳到登页面
            die();
        }
    }

    /**
     * 默认功能实现,初始化以及加载页面
     */
    public function exec()
    {
        require_once VIEW_PATH . "settings/index.html";            //加载管理界面
    }

    /**
     * 上传头像
     */
    public function uploadPortrait(){

    }
}


/**
 * 修改信息json类
 * Class JsonAS
 */
class JsonAS{

    /**
     * 是否设置成功
     * @var bool
     */
    public $success=false;

    /**
     * 错误信息
     * @var string
     */
    public $message="";
}