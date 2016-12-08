<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 管理员主界面控制类
 * Date: 2016/11/24
 * Time: 19:55
 */
defined("APP") or die("error");

class AdminMainCtrl
{
    private $user;
    private $noticeManage;

    public function __construct()
    {
        session_start();
        if (empty($_SESSION["userName"])) {
            header("Location:./index.php");
        }
        
        try {
            $userName = $_SESSION['userName'];
            $this->user = ModelFactory::adminFactory($userName);//识别和创建管理员model类对象
            $this->noticeManage = $this->user->getNoticeManage();//公告管理对象
        } catch (ClassNotFoundException $e) {
            header("Location:./index.php?c=LoginAdmin");        //如果用户未登录而又想靠地址进入，则阻挡且跳到登页面
            die();
        }
    }

    public function exec()//默认功能实现
    {
        require_once VIEW_PATH . "admin/index.html";//载入管理界面
    }


    ////////////////////
    ///通用的方法。
    ///在BaseNotice中
    ///////////////////
    /**
     * 根据传过来的id获得一条公告的信息
     */
    public function getNoticeById()
    {
        if (!empty($_POST['nid'])) {//传过来一个id， “0”不可以

            $nid = (int)$_POST['nid'];//将json转为int
            $notice = $this->noticeManage->getNoticeById($nid);
            echo json_encode($notice);
        } else {
            echo json_encode(false);
        }
    }

    public function refresh()
    {
        if (isset($_COOKIE["history"]) || !empty($_COOKIE["history"])) {
            date_default_timezone_set("PRC");//设置时区
            setcookie("history", "", time() - 3600);
            $_COOKIE["history"] = "";
        }
        $this->getSendNotices();

    }

    /**
     * 将公告未读的状态设为已读
     * 返回bool
     */
    public function setNoticeRead()
    {
        if (!empty($_POST['nid'])) {//公告id
            $nid = $_POST['nid'];//可以不用转为int
            $sussess = $this->noticeManage->setNoticeRead($nid);
            echo json_encode($sussess);
        } else {
            echo json_encode(false);
        }
    }

    /**
     * 删除用户自己的公告
     * 这里只有社团管理员删除校社联发来的公告时需要用到
     */
    public function deleteUserNotice()
    {
        if (!empty($_POST['noticeIds'])) {//要删除的公告数组id
            $nid = json_decode($_POST['noticeIds'], true);
            $sussess = $this->noticeManage->deleteUserNotice($nid);
            echo json_encode($sussess);
        } else {
            echo json_encode(false);
        }
    }

    /**
     * 由登陆页面跳转到管理页面时需要用到
     *
     * 获得用户的头像名字等信息
     * 默认无信息返回null
     * 'headImgName':图片名字
     * 'name':用户的名字
     *
     */
    public function getUserInfo()
    {
        $userinfo = $this->user->getUserInfo();//getUserInfo()在BaseUser中
        echo json_encode($userinfo);
    }
    ///////////////////
    ///管理员
    ////////////////////
    /**
     * 获得管理员发布的公告
     *
     * @return [type] [description]
     */
    public function getSendNotices()
    {
        if (isset($_COOKIE["history"]) && !empty($_COOKIE["history"]) && !empty($_POST['limit'])) {

            $limit = json_decode($_POST['limit'], true);
            $title = $_COOKIE["history"];//还没转义
            $l = (int)$limit['l'];//拿到第l+1行到第r行的公告
            $r = (int)$limit['r'];
            $notices = $this->noticeManage->searchSendNoticesByTitle($title, $l, $r);
            die(json_encode($notices));//成功数组，失败false
        }

        if (!empty($_POST['limit'])) {//限制获得的公告范围
            $limit = json_decode($_POST['limit'], true);
            $l = (int)$limit['l'];//拿到第l+1行到第r行的公告
            $r = (int)$limit['r'];
            $notices = $this->noticeManage->getSendNotices($l, $r);//得到公告信息
            echo json_encode($notices);//没有公告数组notices为空，查询失败为false
        } else {
            echo json_encode(false);
        }

    }

    /**
     * 管理员删除公告
     * @return [type] [description]
     */
    public
    function deleteNotices()
    {
        if (!empty($_POST['noticeIds'])) {

            $nid = json_decode($_POST['noticeIds'], true);//json转为php对象(stdClass)
            // var_dump($nid);
            $sussess = $this->noticeManage->deleteNotice($nid);//根据id删除公告，成功返回true失败返回false
            echo json_encode($sussess);

        } else {
            echo json_encode(false);
        }

    }

    /**
     * 新建公告
     */
    public
    function addNotice()
    {
        if (!empty($_POST['notice'])) { //'time'=>时间，'text'=>内容，'title'=>标题
            $notice = json_decode($_POST['notice'], true);
            $sussess = $this->noticeManage->addNotice($notice);
            echo json_encode($sussess);
        } else {
            echo json_encode(false);
        }
        return;
    }

    /**
     * 搜索发布的公告
     * @return [type] [description]
     */
    public
    function searchNotices()
    {
        if (!empty($_POST['search'])) {//传数组过来

            $notice = json_decode($_POST['search'], true);
            $title = $notice["title"];//还没转义
            setcookie("history", $title);
            $l = (int)$notice['l'];
            $r = (int)$notice['r'];
            $notices = $this->noticeManage->searchSendNoticesByTitle($title, $l, $r);
            echo json_encode($notices);//成功数组，失败false

        } else {
            echo json_encode(false);
        }
    }

////////////////////
///社团管理员
////////////////////

    /**
     * 社团管理员收到的公告，即校社联公告
     */
    public
    function getSauNotices()
    {
        if (!empty($_POST['limit'])) {

            $limit = json_decode($_POST['limit'], true);
            $l = (int)$limit['l'];//限制获得的公告数目
            $r = (int)$limit['r'];

            // $l = 0;$r = 10;  ////测试用

            //得到公告信息
            $notices = $this->noticeManage->getSauNotices($l, $r);
            echo json_encode($notices);//成功数组，失败false
        } else {
            echo json_encode(false);

        }
    }

    /**
     * 搜索社团管理员收到的公告
     */
    public
    function searchSauNotices()
    {
        if (!empty($_POST['search'])) {//传数组过来
            //“title”：搜索内容
            //“l”：限制获得的公告的数目，左边界
            //“r”：右边界
            $notice = json_decode($_POST['search'], true);
            $title = $notice["title"];//还没转义

            $l = (int)$notice['l'];
            $r = (int)$notice['r'];
            $notices = $this->noticeManage->searchSauNoticesByTitle($title, $l, $r);
            echo json_encode($notices);
        } else {
            echo json_encode(false);
        }
    }

}