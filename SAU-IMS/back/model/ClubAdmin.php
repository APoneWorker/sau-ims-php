<?php

/**
 * Created by PhpStorm.
 * 社团管理员类
 * User: APone
 * Date: 2016/11/21
 * Time: 0:55
 */
defined("APP") or die("error");

class ClubAdmin extends BaseUser
{
	/*公告管理对象，用来调用与公告相关的函数*/
	private $noticeManage;
	public function __construct($userName = ""){
		
		parent::__construct($userName);
		
		$userinfo = array(
			'id'=>$this->getId(),
			'clubId'=>$this->getClubId(),
			'userName'=>$this->getUserName(),
			'sauId'=>$this->getSauId()
		);
		$this->noticeManage = new ClubNotice($userinfo);
	}
	public function getNoticeManage(){
		return $this->noticeManage;
	}
}