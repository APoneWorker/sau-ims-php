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


      /*公告管理对象，用来调用与公告相关的函数*/ 
	  private $noticeManage; 
	  /*任务管理对象，用来调用与任务相关的函数*/ 
	  private $taskManage;
      /**
     * 构造函数
     * GeneralUser constructor.
     * @param string $userName 用户名
     */ 
	  public function __construct($userName = ""){ 
	    parent::__construct($userName); 
	    $userinfo = array( 
	      'id'=>$this->getId(), 
	      'clubId'=>$this->getClubId(), 
	      'userName'=>$this->getUserName(), 
	      'sauId'=>$this->getSauId() 
	    ); 
	     
	    $this->noticeManage = new GeneralUserNotice($userinfo); 
	    $this->taskManage = new GeneralUserTask($userinfo); 
	  } 
	  public function getNoticeManage(){ 
	    return $this->noticeManage; 
	  }   
	  public function getTaskManage(){ 
	    return $this->taskManage; 
	  } 
}