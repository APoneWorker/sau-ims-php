<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 重置密码控制类
 * Date: 2016/12/3
 * Time: 1:05
 */
defined("APP") or die("error");

class ResetUserPassCtrl
{
    /**
     * 默认执行函数，这里加载页面
     */
    public function exec()
    {
        session_start();                            //打开session
        $time = $_SESSION["time"];                  //获取验证码的有效时间
        date_default_timezone_set("PRC");           //设置时区
        if (time() - $time > 10 * 60) {             //链接有效期，10分钟
            session_destroy();                      //销毁相关信息
            die("链接已过期");
        } else {                                    //否则加载修改密码页面
            require_once VIEW_PATH . "";
        }
    }

    /**
     * 点击链接重置密码
     */
    public function resetPass()
    {
        if (!empty($_POST)) {
            $password = isset($_POST["password"]) ? htmlspecialchars(trim($_POST["password"], " ")) : "";   //获得新用户名
            $again = isset($_POST["again"]) ? htmlspecialchars(trim($_POST["again"], " ")) : "";            //获得再次输入密码
            $code = isset($_POST["code"]) ? htmlspecialchars(trim($_POST["code"]), "") : "";                //获取验证码

            $resetPassword = new ResetPassword();                   //实例化消息类

            if (empty($password)) {                                 //账号不能为空
                $resetPassword->message = "新密码不能为空";
                die(json_encode($resetPassword));
            } else if (empty($again)) {
                $resetPassword->message = "确认密码不能为空";
                die(json_encode($resetPassword));
            } else if ($password != $again) {
                $resetPassword->message = "两次密码不相同，请重新输入";
                die(json_encode($resetPassword));
            } else if (empty($code)) {
                $resetPassword->message = "请输入邮箱获取的验证码";
                die(json_encode($resetPassword));
            }

            session_start();                                                     //打开session
            $userName = $_SESSION["userName"];                                   //将储存用户名取出
            $user = new GeneralUser($userName);                                  //实例化普通用户类
            $resetPassword->success = $user->createPassword(md5(md5($password)));//修改密码，两次md5加密
            if ($resetPassword->success) {                                       //成功则返回修改成功页面
                $resetPassword->url = "?c=ResetPass&a=resetSuccess";
                session_destroy();                                               //如果密码修改成功，销毁验证码有效性
                die(json_encode($resetPassword));
            }
        }
    }

    /**
     * 修改成功页面
     */
    public function resetSuccess()
    {
        require_once VIEW_PATH . "";
    }
}


/**
 * Class ForgetPassword
 * 用作json传数据用
 */
class ResetPassword
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