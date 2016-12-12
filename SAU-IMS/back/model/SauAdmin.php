<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 校社联管理员类
 * Date: 2016/11/6
 * Time: 0:47
 */
defined("APP") or die("error");

class SauAdmin extends BaseUser
{
	/*公告管理对象，用来调用与公告相关的函数*/
	private $noticeManage;
  	/*任务管理对象，用来调用与任务相关的函数*/
  	private $taskManage;
	public function __construct($userName = ""){
		parent::__construct($userName);
		$userinfo = array(
			'id'=>$this->getId(),
			'clubId'=>$this->getClubId(),
			'userName'=>$this->getUserName(),
			'sauId'=>$this->getSauId()
		);

		$this->noticeManage = new SauNotice($userinfo);
		$this->taskManage = new SauTask($userinfo);
	}
	public function getNoticeManage(){
		return $this->noticeManage;
	}
	public function getTaskManage(){
    	return $this->taskManage;
	}
}
