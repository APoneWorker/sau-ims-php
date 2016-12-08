<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 普通用户登陆控制类
 * Date: 2016/12/3
 * Time: 1:01
 */
defined("APP") or die("error");

class LoginUserCtrl
{
    public function exec()
    {
        if (!empty($_POST)) {//是否有post

            $userName = isset($_POST['userName']) ? htmlspecialchars(trim($_POST['userName'], " ")) : "";//获得用户信息
            $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'], " ")) : "";

            $login = new login();//实例化消息对象

            if (empty($userName) || empty($password)) {//用户名和密码是否为空
                $login->message = "用户或密码不能为空";

            } else {

                try {
                    $user = new GeneralUser($userName);//实例化普通用户类
                    $password = md5(md5($password));//两次加密

                    if ($user->checkAccount($password)) {//账号是否正确
                        session_start();//保存用户文件
                        $_SESSION["id"] = $user->getId();//将信息存入session
                        $_SESSION["club_id"] = $user->getClubId();
                        $_SESSION["userName"] = $user->getUserName();

                        $login->success = true;//登陆成功
                        $login->url = "?c=UserMain";//转到管理界面

                    } else {//密码错误
                        $login->message = "用户名或密码错误";
                    }

                } catch (ClassNotFoundException $e) {//是否具有权限,没有则抛出异常（适用于普通用户以及奇怪的人）
                    $login->message = "用户名或密码错误";
                }
            }

            die (json_encode($login));//抛出json
        }

        require VIEW_PATH."login/login.html";//无post默认加载登陆页面
    }
}


/**
 * Class login
 * 用作json传数据用
 */

class login
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