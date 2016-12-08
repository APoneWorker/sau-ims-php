<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * 注册确认控制类
 * Date: 2016/12/6
 * Time: 10:45
 */
defined("APP") or die("error");

class RegisterApplyCtrl
{
    /**
     * 用户点击链接确认注册成功，默认加载成功页面
     */
    public function exec()
    {
        if (empty($_GET)) {//注册确认链接
            $userName = isset($_GET["userName"]) ? htmlspecialchars(trim($_GET["userName"], " ")) : "";//获取用户名
            $hash = isset($_GET["hash"]) ? htmlspecialchars(trim($_GET["hash"], " ")) : "";//获取hash值
            $sessionId = isset($_GET["sid"]) ? htmlspecialchars(trim($_GET["sid"], " ")) : "";//获取sessionId

            session_id($sessionId);//注入sessionId
            session_start();//打开session
            if ($_SESSION["userName"] != $userName || !$_SESSION["hash"] != $hash) {
                session_destroy();//销毁session
                die("确认注册失败,请重新注册或联系管理员");
            } else {
                require_once VIEW_PATH . "";//加载注册成功页面
            }
        }
    }
}