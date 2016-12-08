<?php

/**
 * Created by PhpStorm.
 * 找回密码，由于是面向全体人员，不分前台和后台，一律用普通用户类
 * User: APone
 * Date: 2016/10/29
 * Time: 11:08
 */
defined("APP") or die("error");

class ForgetPassCtrl
{
    /**
     * 默认执行函数这里加载页面
     */
    public function exec()
    {
        if (!empty($_POST)) {//获取忘记密码的链接
            $userName = isset($_POST["userName"]) ? htmlspecialchars(trim($_POST["userName"], " ")) : "";//获得用户名
            $forgetPassword = new ForgetPassword();

            if (empty($userName)) {//账号不能为空
                $forgetPassword->message = "账号不能为空";

            } else {

                try {
                    $user = ModelFactory::adminFactory($userName);//实例化管理员类
                } catch (ClassNotFoundException $e) {
                    $forgetPassword->message = "用户名不存在";
                    die(json_encode($forgetPassword));
                }

                session_start();//启动session保存链接中hash，用于阻止链接二次有效
                date_default_timezone_set("PRC");//设置时区
                $time = time();//获取链接时的时间，做过期验证
                $key = $user->getKey();//获取用户专用key
                $sessionId = session_id();//获取session的id

                $hash = md5(md5($userName . $time . $key . $sessionId));//两次加密，做是否链接被修改的检验用
                $_SESSION["hash"] = $hash;//储存session id
                $_SESSION["userName"] = $user->getUserName();

                $url = "http://localhost:88/SAU-IMS/back/?c=ResetPass&t=$time&h=$hash&sid=$sessionId";//组成找回密码链接
                $content = "请点击以下链接进行修改密码<br><a href='$url'>$url</a><br>如果不是您申请修改密码，请及时登陆您的账号并修改<br>校社联 " . date("Y-m-d H:i:s", $time);//邮件其他的内容
                $email = $user->getEmail();

                $success = Email::send($email, "【校社联】密码修改,", $content);//此处将修改密码的链接发送到邮箱
                if ($success) {//成功发送则转到成功页面
                    $forgetPassword->success = true;
                    $forgetPassword->url = "?c=ForgetPass&a=sendSuccess";
                } else {
                    $forgetPassword->message = "邮件发送失败，请重新或稍后再试";
                }
            }

            die(json_encode($forgetPassword));
        }

        require_once VIEW_PATH . "forgetPassword/sendEmail.html";//加载页面
    }

    /**
     * 加载成功页面
     */
    public function sendSuccess()
    {
        require_once VIEW_PATH . "forgetPassword/sendConfirm.html";
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


