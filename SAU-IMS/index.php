<?php
/**
 * Created by PhpStorm.
 * User: APone
 * 应用入口
 * Date: 2016/11/23
 * Time: 12:50
 */

//*******************
//c---->controller//控制类
//a---->action//功能类
//*******************

require "urlConfig.php";//加载所需路径变量

//************问题**************
//为什么不在开头引入所有类
//*******************************

if (!empty($_GET)) {//index.php后是否有参数,有则运行

    if (isset($_GET["c"])) {//获取controller参数
        $controller= strtolower($_GET["c"]) . "Ctrl";//加上Ctrl形成完整的类名,如："LoginAdmin" + "Ctrl" = "LoginAdminCtrl"
        if(!file_exists(CTRL_PATH."$controller.php")){//文件是否存在
           die();//不存在停止运行脚本
        }

        require_once CTRL_PATH . "$controller.php";//存在即载入文件
        $platform=new $controller();//实例化控制类

        if(isset($_GET["a"])){//取特殊应用功能执行，如delete,add等
            $action = $_GET["a"];
            $platform->$action();

        }else{//如没有特殊要求默认运行exec()函数,所有控制类都应有exec()函数
            $platform->exec();
        }
    }

} else {//没有默认输出登陆界面（后台管理要什么轮播图首页 =_= ）

    if(!file_exists(CTRL_PATH . "LoginAdminCtrl.php")){//登陆文件是否存在
        die();//不存在停止运行脚本
    }

    require_once CTRL_PATH . "LoginAdminCtrl.php";//载入登陆控制类
    $from = new LoginAdminCtrl();//实例化登陆控制类
    $from->exec();//运行并展示登陆页面
}
