



<?php
// if (!defined('HOST')) define('HOST', str_replace('\\', '/', dirname(__FILE__))."/../");//站点目录

// require_once HOST ."/framework/ModelFactory.php";
require "../framework/ModelFactory.php";


session_start();

if(isset($_SESSION['userName'])){

	$username = $_SESSION['userName'];
	$user = ModelFactory::adminFactory($username);//获取model类对象(用户)

} else {
	echo json_encode("未登录！");
	die();

}

if(isset($_POST['id'])){//有参数传过来-->根据id返回公告内容

	$id = $_POST['id'];
	$notice = $user->getNoticeText($id);
	echo json_encode($notice);

} else {				//没有参数-->返回所有公告的信息

	$notices =$user->getNotices($_SESSION['id']);
	echo json_encode($notices);
}



