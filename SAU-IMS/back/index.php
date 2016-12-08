<?php
/**
 * Created by PhpStorm.
 * User: APone
 * 后台应用入口
 * Date: 2016/11/23
 * Time: 12:50
 */

define("APP", "SAU-IMS");//防止网址直接跳转,所有php和html必须存在

require_once "../urlConfig.php";        //加载所需路径变量
require_once "../autoLoader.php";       //加载自动加载类函数

if (!empty($_GET)) {//index.php后是否有参数,有则运行

    if (isset($_GET["c"])) {//获取controller参数
        $controller = htmlspecialchars(strtolower($_GET["c"])) . "Ctrl"; //加上Ctrl形成完整的类名,如："LoginAdmin" + "Ctrl" = "LoginAdminCtrl"

        if (!isClassExits($controller)) {//类是否存在
            die();//不存在停止运行脚本
        }

        $platform = new $controller();//实例化控制类

        if (isset($_GET["a"])) {//取特殊应用功能执行，如delete,add等
            $action = htmlspecialchars(strtolower($_GET["a"]));
            $platform->$action();
        } else {//如没有特殊要求默认运行exec()函数,所有控制类都应有exec()函数
            $platform->exec();
        }
    }

} else {//没有默认输出登陆界面（后台管理要什么轮播图首页 =_= ）

    $from = new LoginAdminCtrl();//实例化登陆控制类
    $from->exec();//运行并展示登陆页面
}

