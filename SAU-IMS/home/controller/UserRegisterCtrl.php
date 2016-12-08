<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 普通用户注册类
 * Date: 2016/12/3
 * Time: 1:04
 */
defined("APP") or die("error");

class UserRegisterCtrl
{
    /**
     * 显示注册页面
     */
    public function exec()
    {
        require_once VIEW_PATH . "";//加载注册页面
    }


    public function register()
    {
        if (!empty($_POST)) {
            $userName = isset($_POST["userName"]) ? htmlspecialchars(trim($_POST["userName"], " ")) : "";//用户名
            $password = isset($_POST["password"]) ? htmlspecialchars(trim($_POST["password"], " ")) : "";//密码
            $again = isset($_POST["again"]) ? htmlspecialchars(trim($_POST["again"], " ")) : "";//确认密码
            $email = isset($_POST["email"]) ? htmlspecialchars(trim($_POST["email"], " ")) : "";//邮箱

            //*******************************信息验证**************************//

            $register=new Register();

            //验证各输入是否为为空
            if (empty($userName)) {
                $register->message="用户名不能为空";
                die(json_encode($register));
            } else if (empty($password)) {
                $register->message="密码不能为空";
                die(json_encode($register));
            } else if (empty($again)) {
                $register->message="确认密码不能为空";
                die(json_encode($register));
            } else if (empty($email)) {
                $register->message="邮箱不能为空";
                die(json_encode($register));
            }

            //验证各输入格式是否正确
        }
    }
}

/**
 * Class Register
 * 注册json类
 */
class Register
{

    /**
     * @var bool 是否成功
     */
    public $success = false;

    /**
     * @var string 错误消息
     */
    public $message = "";

    /**
     * @var string 跳转地址
     */
    public $url = "";
}

