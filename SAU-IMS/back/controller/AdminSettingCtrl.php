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

    private $userName;

    /**
     * 构造函数
     * AdminSettingCtrl constructor.
     */
    public function __construct()
    {
        session_start();                                        //打开session
        $this->userName = $_SESSION['userName'];                      //获取管理员用户名
        try {
            $this->user = ModelFactory::adminFactory($this->userName);//识别和创建管理员model类对象
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
        if (!empty($_FILES["p"]["name"]) && !empty($_POST)) {
            $pic_info = $_FILES["p"];
            $portrait = new Portrait($pic_info["tmp_name"]);
            $portrait->upload($this->userName);
            $name = isset($_POST["name"]) ? htmlspecialchars(strtolower($_POST["name"])) : $this->user->getName();
            $email = isset($_POST["email"]) ? htmlspecialchars(strtolower($_POST["email"])) : $this->user->getEmail();
            $this->user->editUserBasicInfo($name,$email);
        }else if(!empty($_FILES["p"]["name"])){
            $pic_info = $_FILES["p"];
            $portrait = new Portrait($pic_info["tmp_name"]);
            $portrait->upload($this->userName);
        }else{
            $name = isset($_POST["name"]) ? htmlspecialchars(strtolower($_POST["name"])) : $this->user->getName();
            $email = isset($_POST["email"]) ? htmlspecialchars(strtolower($_POST["email"])) : $this->user->getEmail();
            $this->user->editUserBasicInfo($name,$email);
        }

        $userName = $this->userName;
        $name = $this->user->getName();//姓名
        $email = $this->user->getEmail();//邮箱
        $porPath = PORTRAIT_PATH . $userName . Portrait::PNG;
        require_once VIEW_PATH . "settings/index.html";            //加载管理界面
    }

    public function getPortrait()
    {
        $imgPath = PORTRAIT_PATH . $this->userName . "jpg";
        $portrait = new Portrait($imgPath);
        $portrait->getPortrait();
    }
}


/**
 * 修改信息json类
 * Class JsonAS
 */
class JsonAS
{

    /**
     * 是否设置成功
     * @var bool
     */
    public $success = true;

    /**
     * 错误信息
     * @var string
     */
    public $message = "";
}
