<?php

/**
 * Created by PhpStorm.
 * 普通用户类
 * User: APone
 * Date: 2016/11/21
 * Time: 0:54
 */

class GeneralUser extends BaseUser
{
    /**
     * 获得该用户要收到的公告
     * @return [type] [description]
     */
    public function getNotices(){

    }
    /**
     * 获得未读的公告
     * @return [type] [description]
     */
    public function getNotReadNotice(){

    }
    public function register($content)
    {

    }

    public function showInfo($usreName)
    {

    }

    public function editInfo($userName, $content)
    {

    }

    public function getName()
    {
        return "GeneralUser";
    }
}