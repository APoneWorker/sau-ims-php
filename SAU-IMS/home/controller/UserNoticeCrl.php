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
 
    public function __construct(){
        session_start();
        if(empty($_SESSION["userName"])){
            die("error");
        }

        $userName = $_SESSION['userName'];
        $this->user = new GeneralUser($userName);//创建普通成员model类对象

        $this->noticeManage = $this->user->getNoticeManage();//任务管理对象
       
    }
    public function exec()//默认功能实现
    {
        // session_start();
        if(empty($_SESSION["userName"])){
            die("error");
        }

        require_once VIEW_PATH . "";//载入主界面
    }

    /**
     * 根据传过来的id获得一条公告的信息
     */
    public function getNoticeById(){
        if(!empty($_POST['nid'])){//传过来一个id， “0”不可以
            
            $nid = (int)$_POST['nid'];//将json转为int
            $notice = $this->noticeManage -> getNoticeById($nid);
            echo json_encode($notice);
        }else{
            echo json_encode(false);
        }
    }
    /**
     * 将公告未读的状态设为已读
     * 返回bool
     */
    public function setNoticeRead(){
        if(!empty($_POST['nid'])){//公告id
            $nid = $_POST['nid'];//可以不用转为int
            $sussess = $this->noticeManage->setNoticeRead($nid);
            echo json_encode($sussess);
        }else{
            echo json_encode(false);
        }
    }
    /**
     * 删除用户自己的公告
     * 这里只有社团管理员删除校社联发来的公告时需要用到
     */
    public function deleteUserNotice(){
        if(!empty($_POST['noticeIds'])){//要删除的公告数组id
            $nid = json_decode($_POST['noticeIds'],true);
            $sussess = $this->noticeManage->deleteUserNotice($nid);
            echo json_encode($sussess);
        }else{
            echo json_encode(false);
        }
    }
    /**
     * 收到的公告
     */
    public function getNotices(){
        if(!empty($_POST['limit'])){
        

            $limit = json_decode($_POST['limit'],true); 
            $l = (int)$limit['l'];//限制获得的任务数目
            $r = (int)$limit['r'];
            
            // $l = 0;$r = 10;  ////测试用
            
            //得到任务信息
            $notices = $this->noticeManage -> getNotices($l,$r);

            echo json_encode($notices);//成功数组，失败false
        }else{
            echo json_encode(false);
           
        }
    }
    /**
     * 搜索收到的公告
     */
    public function searchNotices(){
        if(!empty($_POST['search'])){//传数组过来
            //“title”：搜索内容
            //“l”：限制获得的任务的数目，左边界
            //“r”：右边界
            $notice = json_decode($_POST['search'],true);
            $title = $notice["title"];
            
            $l = (int)$notice['l'];
            $r = (int)$notice['r'];
            $notices = $this->noticeManage -> searchNoticesByTitle($title,$l,$r);
            echo json_encode($notices);
        }else{
            echo json_encode(false);
        }
    }

}