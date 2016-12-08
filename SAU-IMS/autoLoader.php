<?php
/**
 * Created by PhpStorm.
 * User: APone
 * 类自动加载器
 * Date: 2016/11/26
 * Time: 20:50
 */

/**
 * 类自动加载器
 * @param $className string 类名
 */

function SauImsAutoload($className)
{
    if (file_exists(MODEL_PATH . $className . ".php")) {//模型类
        require MODEL_PATH . $className . ".php";
    } else if (file_exists(FRAME_PATH . $className . ".php")) {//基础类
        require FRAME_PATH . $className . ".php";
    } else if (file_exists(CTRL_PATH . $className . ".php")) {//控制类
        require CTRL_PATH . $className . ".php";
    }
}

spl_autoload_register("SauImsAutoload", true, true);//自动加载器注册，以后将自动调用该自动加载器


/**
 * 某类是否存在
 * 适用于模型，控制，基础类中
 * @param $className string 类名
 * @return bool
 */
function isClassExits($className)
{
    if (file_exists(MODEL_PATH . $className . ".php")) {//模型类
        return true;
    } else if (file_exists(FRAME_PATH . $className . ".php")) {//基础类
        return true;
    } else if (file_exists(CTRL_PATH . $className . ".php")) {//控制类
        return true;
    } else {
        return false;
    }
}