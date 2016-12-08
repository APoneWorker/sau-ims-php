<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 忘记密码控制类
 * Date: 2016/12/3
 * Time: 1:05
 */
defined("APP") or die("error");

class ForgetUserPassCtrl
{
    /**
     * 默认执行函数这里只加载页面
     */
    public function exec()
    {
        require_once VIEW_PATH . "";//加载页面
    }

    /**
     * 获取编辑密码的链接
     */
    public function forgetPass()
    {

        if (!empty($_POST)) {//获取忘记密码的验证码
            $userName = isset($_POST['userName']) ? htmlspecialchars(trim($_POST['userName'], " ")) : "";//获得用户名
            $forgetPassword = new ForgetPassword();

            if (empty($userName)) {//账号不能为空
                $forgetPassword->message = "账号不能为空";
                die(json_encode($forgetPassword));
            }

            $user = new GeneralUser($userName);//实例化普通用户类

            if (!$user->isExits()) {//输入的用户是否存在
                $forgetPassword->message = "用户名不存在";
                die(json_encode($forgetPassword));
            }

            session_start();//启动session保存链接中hash，用于阻止链接二次有效
            date_default_timezone_set("PRC");//设置时区
            $time = time();//获取链接时的时间，做过期验证
            $pin = new PINCode();//实例化验证码类
            $code = $pin->createMailCode();//获取验证码
            $_SESSION["userName"] = $user->getUserName();//记录用户名
            $_SESSION["time"] = $time;//记录时间
            $_SESSION["code"] = $code;//记录验证码

            $content = "请按照以下的验证码进行输入，切勿告诉他人<br><h2>$code</h2>如果不是您申请修改密码，请及时登陆您的账号并修改<br>校社联 " . date("Y-m-d H:i:s", $time);//邮件其他的内容
            $email = $user->getEmail();
            $success = Email::send($email, "【校社联】密码修改,", $content);//此处将修改密码的链接发送到邮箱

            if ($success) {//成功发送则转到成功页面
                $forgetPassword->success = true;
                $forgetPassword->url = "?c=ForgetPass&a=sendSuccess";
            } else {
                $forgetPassword->message = "邮件发送失败，请重新或稍后再试";
            }

            die(json_encode($forgetPassword));
        }
    }

    /**
     * 加载成功页面
     */
    public function sendSuccess()
    {
        require_once VIEW_PATH . "";
    }
}

/**
 * Class ForgetPassword
 * 用作json传数据用
 */
class ForgetPassword
{
    /**
     * @var string 登陆是否成功
     */
    public $success = false;

    /**
     * @var bool 相关提示信息（错误用）
     */
    public $message = "";

    /**
     * @var string 登陆成功的跳转页面
     */
    public $url = "";
}