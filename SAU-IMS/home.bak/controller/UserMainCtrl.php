<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 用户主界面
 * Date: 2016/12/8
 * Time: 7:39
 */
class UserMainCtrl
{

    public function __construct()
    {
        session_start();
        if (empty($_SESSION["userName"])) {
            header("Location:./index.php");
        }
    }

    public function exec(){
        require_once VIEW_PATH."user/index.html";//加载普通用户界面
    }
}