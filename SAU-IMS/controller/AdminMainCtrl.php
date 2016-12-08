<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 管理员主界面控制类
 * Date: 2016/11/24
 * Time: 19:55
 */
require_once FRAME_PATH."ModelFactory.php";
class AdminMainCtrl
{

	private $user;
	public function __construct()
    {
    	session_start();
			if (empty($_SESSION["userName"])) {
				header("Location:./index.php");
			}
    	$userName = $_SESSION['userName'];
    	$this->user = ModelFactory::adminFactory($userName);//创建管理员model类对象
    	$this->user->init($userName);//初始化usermodel对象

    }
    public function exec(){//默认功能实现

        require_once VIEW_PATH."admin/index.html";//载入管理界面
        return;
    }

    //其他的方法
    //跟前端交互
    /**
     * 获得管理员发布的公告
     *
     * @return [type] [description]
     */
    public function getSendNotices(){


 		if(isset($_POST['limit'])  && !empty($_POST['limit'])){


 			$limit = json_decode($_POST['limit'],true);
			$l = (int)$limit['l'];//限制获得的公告数目
			$r = (int)$limit['r'];

			// $l = 0;$r = 10;	////测试用

			//得到公告信息
			$notices = $this->user -> getSendNotices($l,$r);
			echo json_encode($notices);//没有公告或查询失败，数组notices为空，
		}else{
			echo json_encode(false);

		}
		return;



    }
    /**
     * 管理员删除公告
     * @return [type] [description]
     */
    public function deleteNotices(){
    	if(isset($_POST['noticeIds']) && !empty($_POST['noticeIds'])){//判断要删除的公告id是否传过来了



    		$nid = json_decode($_POST['noticeIds'],true);//json转为php对象(stdClass)
    		var_dump($nid);
    		$sussess = $this->user -> deleteNotice($nid);//根据id删除公告，成功返回true失败返回false
    		echo json_encode($sussess);

    	}else{
    		echo json_encode(false);var_dump("d");
    	}
    	return;
    }
    /**
     * 新建公告
     */
    public function addNotice(){
    	if(isset($_POST['notice']) && !empty($_POST['notice'])){
    		$notice = json_decode($_POST['notice'],true);
    		$sussess = $this->user -> addNotice($notice);//调用SauAdmin类的添加公告的方法
    		echo json_encode($sussess);
    	}else{
    		echo json_encode(false);
    	}
    	return;
    }
    /**
     * 搜索发布的公告
     * @return [type] [description]
     */
    public function searchNotices(){
    	if(isset($_POST['search']) && !empty($_POST['search'])){//传数组过来
    		//“title”：搜索内容
    		//“l”：限制获得的公告的数目，左边界
    		//“r”：右边界
    		$notice = json_decode($_POST['search'],true);
    		$title = $notice["title"];//还没转义
    		$l = (int)$notice['l'];
    		$r = (int)$notice['r'];
    		$notices = $this->user -> searchSendNoticesByTitle($title,$l,$r);
    		echo json_encode($notices);
    	}else{
    		echo json_encode(false);
    	}
    }
    /**
     * 根据传过来的id获得一条公告的信息
     */
    public function getNoticeById(){
    	if(isset($_POST['nid']) && !empty($_POST['nid'])){

    		$nid = (int)$_POST['nid'];
    		$notice = $this->user -> getNoticeById($nid);
    		echo json_encode($notice);
    	}else{
    		echo json_encode(false);
    	}
    }
    /**
     * 社团管理员收到的公告，即校社联公告
     */
    public function getSauNotices(){
    	if(isset($_POST['limit']) && !empty($_POST['limit'])){


 			$limit = json_decode($_POST['limit'],true);
			$l = (int)$limit['l'];//限制获得的公告数目
			$r = (int)$limit['r'];

			// $l = 0;$r = 10;	////测试用

			//得到公告信息
			$notices = $this->user -> getSauNotices($l,$r);//ClubAdmin特有的方法
			echo json_encode($notices);//没有公告或查询失败，数组notices为空，
		}else{
			echo json_encode(false);

		}
    }
    /**
     * 搜索社团管理员收到的公告
     */
    public function searchSauNotices(){
    	if(isset($_POST['search']) && !empty($_POST['search'])){//传数组过来
    		//“text”：搜索内容
    		//“l”：限制获得的公告的数目，左边界
    		//“r”：右边界
    		$notice = json_decode($_POST['search'],true);
    		$text = $notice["text"];//还没转义

    		$l = (int)$notice['l'];
    		$r = (int)$notice['r'];
    		$notices = $this->user -> searchSauNoticesByTitle($text,$l,$r);//ClubAdmin特有的方法
    		echo json_encode($notices);
    	}else{
    		echo json_encode(false);
    	}
    }
    /**
     * 将公告未读的状态设为已读
     *
     */
    public function setNoticeRead(){
    	if(isset($_POST['nid']) && !empty($_POST['nid'])){//公告id
    		$nid = $_POST['nid'];//可以不用转为int
    		$sussess = $this->user->setNoticeRead($nid);
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
  		if(isset($_POST['noticeIds']) && !empty($_POST['noticeIds'])){
  			$nid = json_decode($_POST['noticeIds'],true);
  			$sussess = $this->user->deleteUserNotice($nid);
  			echo json_encode($sussess);
  		}else{
  			echo json_encode(false);
  		}
    }

}
