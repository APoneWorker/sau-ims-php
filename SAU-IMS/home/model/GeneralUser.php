<?php

/**
 * Created by PhpStorm.
 * 普通用户类
 * User: APone
 * Date: 2016/11/21
 * Time: 0:54
 */

defined("APP") or die("error");

class GeneralUser extends BaseUser
{
    /**
     * 构造函数
     * GeneralUser constructor.
     * @param string $userName
     */
    public function __construct($userName)
    {
        parent::__construct($userName);
    }
}