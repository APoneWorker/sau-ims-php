<?php

/**
 * Created by PhpStorm.
 * User: APone
 * Date: 2016/12/6
 * Time: 15:26
 */
class CheckFormat
{
    /**
     * 验证用户名（2~16位，只允许英文字母，数字）
     * 注意：只支持验证UTF-8编码
     * @param $userName string
     * @return bool|string
     */
    public static function checkUserName($userName)
    {
        if (!preg_match('/^[\w\x{4e00}-\x{9fa5}]{2,10}$/u', $userName)) {
            return "用户名格式不符合要求";
        }
        return false;
    }

    /**
     * 验证密码（长度6~16位，只允许英文字母，数字，下划线）
     * @param $password string
     * @return bool|string
     */
    public static function checkPassword($password)
    {
        if (!preg_match('/^\w{6,16}$/', $password)) {
            return "密码格式不符合要求";
        }
        return false;
    }

    /**
     * 验证邮箱（不超过40位）
     * @param $email string
     * @return bool|string
     */
    public static function checkEmail($email)
    {
        if (strlen($email) > 40) {
            return "邮箱长度不符合要求";
        } elseif (!preg_match('/^[a-z0-9]+@([a-z0-9]+\.)+[a-z]{2,4}$/i', $email)) {
            return "邮箱格式不符合要求";
        }
        return false;
    }

    /**
     * 验证QQ号（5~20位）
     * @param $qq string
     * @return bool|string
     */
    public static function checkQQ($qq)
    {
        if (!preg_match('/^[1-9][0-9]{4,20}$/', $qq)) {
            return "QQ号码格式不符合要求";
        }
        return false;
    }

    /**
     * 验证手机号码（11位）
     * @param $num string
     * @return bool|string
     */
    public static function checkPhone($num)
    {
        if (!preg_match('/^1[358]\d{9}$/', $num)) {
            return "手机号码不符合要求";
        }
        return false;
    }

    /**
     * 验证URL地址
     * @param $url string
     * @return bool|string
     */
    public static function checkURL($url)
    {
        if (strlen($url) > 200) {
            return "URL长度不符合要求";
        } elseif (!preg_match('/^http:\/\/[a-z\d-]+(\.[\w\/]+)+$/i', $url)) {
            return "URL格式不符合要求";
        }
        return false;
    }

    /**
     * 检查密码长度，目前限定在6位以上
     * @param $password string
     * @return bool|string
     */
    public static function checkPassLength($password)
    {
        if (strlen($password) < 6) {
            return "密码长度不能少于6位";
        }else if(strlen($password)>40){
            return "密码过长";
        }
        return false;
    }
}


