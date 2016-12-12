<?php

/**
 * 管理员主界面任务的控制类
 */
defined("APP") or die("error");

class AdminTaskCtrl
{
    private $user;
    private $taskManage;

    public function __construct(){
        session_start();
        if(empty($_SESSION["userName"])){
            die("error");
        }

        $userName = $_SESSION['userName'];
        $this->user = ModelFactory::adminFactory($userName);//创建管理员model类对象
        $this->taskManage = $this->user->getTaskManage();//任务管理对象

    }
    public function exec()//默认功能实现
    {
        // session_start();
        if(empty($_SESSION["userName"])){
            die("error");
        }
          $userName=$this->user->getUserName();
        $porPath=PORTRAIT_PATH.$userName.Portrait::PNG;
        // var_dump($_SESSION);
        //***************关闭浏览器后session才会被销毁**********
        require_once VIEW_PATH . "admin/index.html";//载入管理界面
    }


    ////////////////////
    ///通用的方法。
    ///在BaseTask中
    ///////////////////
    /**
     * 根据传过来的id获得一条任务的信息
     * 索引：`id`,`title`,`time`,`name`,`text`,`recipients`
     * 其中recipients是索引数组
     *
     */
    public function getTaskById(){
        if(!empty($_POST['tid'])){//传过来一个id， “0”不可以

            $tid = (int)$_POST['tid'];//将json转为int
            $task = $this->taskManage -> getTaskById($tid);
            // var_dump($task);
            echo json_encode($task);
        }else{
            echo json_encode(false);
        }
    }
    /**
     * 将任务未读的状态设为已读
     * 返回bool
     */
    public function setTaskRead(){
        if(!empty($_POST['tid'])){//任务id
            $tid = $_POST['tid'];//可以不用转为int
            $sussess = $this->taskManage->setTaskRead($tid);
            echo json_encode($sussess);
        }else{
            echo json_encode(false);
        }
    }
    /**
     * 删除用户自己的任务
     * 这里只有社团管理员删除校社联发来的任务时需要用到
     */
    public function deleteUserTask(){
        if(!empty($_POST['taskIds'])){//要删除的任务数组id
            $tid = json_decode($_POST['taskIds'],true);
            $sussess = $this->TaskManage->deleteUserTask($tid);
            echo json_encode($sussess);
        }else{
            echo json_encode(false);
        }
    }

    ///////////////////
    ///管理员通用
    ////////////////////
    /**
     * 获得管理员发布的任务
     *
     * @return [type] [description]
     */
    public function getSendTasks(){
        if(!empty($_POST['limit'])){//限制获得的任务范围
            $limit = json_decode($_POST['limit'],true);
            $l = (int)$limit['l'];//拿到第l+1行到第r行的任务
            $r = (int)$limit['r'];

            // $l = 0;$r = 10;  ////测试用

            //得到任务信息
            $tasks = $this->taskManage -> getSendTasks($l,$r);
            echo json_encode($tasks);//没有任务数组Tasks为空，查询失败为false
        }else{
            echo json_encode(false);
        }

    }
    /**
     * 管理员删除任务
     * @return [type] [description]
     */
    public function deleteTasks(){
        if(!empty($_POST['taskIds'])){



            $tid = json_decode($_POST['TaskIds'],true);//json转为php对象(stdClass)
            // var_dump($tid);
            $sussess = $this->taskManage -> deleteTask($tid);//根据id删除任务，成功返回true失败返回false
            echo json_encode($sussess);

        }else{
            echo json_encode(false);
        }

    }
    /**
     * 新建任务
     */
    public function addTask(){
        if(!empty($_POST['task'])&&!empty($_POST['ids'])){ //'time'=>时间，'text'=>内容，'title'=>标题

            $task = json_decode($_POST['task'],true);
            $ids = json_decode($_POST['ids'],true);
            // var_dump($task);
            $sussess = $this->taskManage -> addTask($task,$ids);

            echo json_encode($sussess);
        }else{
            echo json_encode(false);
        }
        return;
    }
    /**
     * 搜索发布的任务
     * @return [type] [description]
     */
    public function searchTasks(){
        if(!empty($_POST['search'])){//传数组过来
            //“title”：搜索内容
            //“l”：限制获得的任务的数目，左边界
            //“r”：右边界
            $task = json_decode($_POST['search'],true);
            $title = $task["title"];//还没转义
            $l = (int)$task['l'];
            $r = (int)$task['r'];
            $tasks = $this->taskManage -> searchSendTasksByTitle($title,$l,$r);
            echo json_encode($tasks);//成功数组，失败false
        }else{
            echo json_encode(false);
        }
    }


    ////
    ///校社联管理员
    ///
    /**
     * 获得校社联能发布公告的用户的id和名字
     * 校社联成员，社团管理员
     *
     * @return [type] [description]
     */
    public function getUserSauCanSendTask(){
        $member = array();
        $club = $this->taskManage ->getAllClubAdmin();
        $sau = $this->taskManage->getClubMembers();
        if($club == false || $sau ==false){
            return json_encode(false);
        }
        $member['club'] = $club;
        $member['sau'] = $sau;

        echo json_encode($member);
        //形式：{
        //       "club":{ 0:{
        //                      "id":"..",
        //                      "name":".."
        //                   }
        //              1:{}
        //              ........
        //             }
        //       "sau":{ 0:{
        //                      "id":"..",
        //                      "name":".."
        //                   }
        //                1:{}
        //             ........
        //            }
        //       }
        // 社团管理员id和名字
        // club=member['club']
        // club['0']['id']
        // club['0']['name']
        // 或用对象：（不知道可不可以）
        //  club=member.club
        //  club[0].id
        //  club[0].name
        // 校社联成员id和名字
        // 同上


    }
    ////
    ///社团管理员
    ///
    /**
     * 获得社团成员的id和名字
     * 校社联成员，社团管理员
     *
     * @return [type] [description]
     */
    public function getClubMember(){


        $member = $this->taskManage->getClubMembers();

        return json_encode($member);
        //形式：{ 0:{
        //              "id":"..",
        //              "name":".."
        //           }
        //        1:{
        //
        //          }
        //         .........
        //       }
        // 社团成员id和名字
        //
        // member['0']['id']
        // member['0']['name']
        // 或用对象：（不知道可不可以）
        //  member[0].id
        //  member[0].name

    }
       /**
     * 收到的任务（社团管理员）
     */
    public function getTasks(){
        if(!empty($_POST['limit'])){


            $limit = json_decode($_POST['limit'],true);
            $l = (int)$limit['l'];//限制获得的任务数目
            $r = (int)$limit['r'];

            // $l = 0;$r = 10;  ////测试用

            //得到任务信息
            $Tasks = $this->TaskManage -> getReceiveTasks($l,$r);
            echo json_encode($tasks);//成功数组，失败false
        }else{
            echo json_encode(false);

        }
    }
    /**
     * 搜索收到的任务（社团管理员）
     */
    public function searchReceiveTasks(){
        if(!empty($_POST['search'])){//传数组过来
            //“title”：搜索内容
            //“l”：限制获得的任务的数目，左边界
            //“r”：右边界
            $Task = json_decode($_POST['search'],true);
            $title = $Task["title"];//还没转义

            $l = (int)$Task['l'];
            $r = (int)$Task['r'];
            $tasks = $this->taskManage -> searchTasksByTitle($title,$l,$r);
            echo json_encode($tasks);
        }else{
            echo json_encode(false);
        }
    }
}
