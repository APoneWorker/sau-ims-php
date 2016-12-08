<?php

/**
 * Created by PhpStorm.
 * User: APone
 * Date: 2016/12/6
 * 登陆错误控制类
 * Time: 14:53
 */
class LoginErrorCtrl
{
    /**
     * 默认加载页面
     */
    public function exec(){
        require_once VIEW_PATH."";                      //加载验证码界面
    }

    public function getCode(){
        $code=new PINCode();                            //实例化验证码类
        $code->showCodeImg();                           //抛出最新的验证码
        session_start();                                //打开session
        $_SESSION["code"]=$code->getLoginCurrentCode(); //储存该用户即将输入的验证码
    }
}