<?php

/**
 * 管理员主界面任务的控制类
 */
defined("APP") or die("error");

class UserTaskCtrl
{
    private $user;
    private $taskManage;
 
    public function __construct(){
        session_start();
        if(empty($_SESSION["userName"])){
            die("error");
        }

        $userName = $_SESSION['userName'];
        //******捕获异常************
        $this->user = new GeneralUser($userName);//创建普通成员model类对象

        $this->taskManage = $this->user->getTaskManage();//任务管理对象
       
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
     * 根据传过来的id获得一条任务的信息
     * 索引：`id`,`title`,`time`,`name`,`text`,
     * 
     * 
     */
    public function getTaskById(){
        if(!empty($_POST['tid'])){//传过来一个id， “0”不可以
            
            $tid = (int)$_POST['tid'];//将json转为int
            $task = $this->taskManage -> getTaskById($tid);
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
     * 
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
    /**
     * 收到的任务
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
     * 搜索收到的任务
     */
    public function searchTasks(){
        if(!empty($_POST['search'])){//传数组过来
            //“title”：搜索内容
            //“l”：限制获得的任务的数目，左边界
            //“r”：右边界
            $Task = json_decode($_POST['search'],true);
            $title = $Task["title"];//还没转义
            
            $l = (int)$Task['l'];
            $r = (int)$Task['r'];
            $Tasks = $this->taskManage -> searchTasksByTitle($title,$l,$r);
            echo json_encode($tasks);
        }else{
            echo json_encode(false);
        }
    }

}