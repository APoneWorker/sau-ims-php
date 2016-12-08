<?php
/**
 * Created by PhpStorm.
 * User: APone
 * 各种路径的路径配置（注意，要以back后home文件夹里的index.php为视角进行路径编写,不是根目录的index.php）
 * Date: 2016/11/23
 * Time: 20:29
 */
//example: include_once/include/require_once/require MODEL_PATH."BaseUser.php";

//*****************************站点名称****************************//

define("HOST_NAME","SAU-IMS");

//***************************各文件夹路径**************************//

define("MODEL_PATH","./model/");//模型类路径
define("VIEW_PATH","./view/");//视图类路径
define("CTRL_PATH","./controller/");//控制类路径
define("FRAME_PATH","../framework/");//基础类路径

//***************************网址虚拟路径**************************//

define("HOST","http://localhost/SAU-IMS/");//根据需要修改SAU-IMS

//****************************配置文件路径*************************//

define("dbConfig","../config/dbConfig.ini");//数据库配置文件路径
define("mailConfig","../config/mailConfig.ini");//邮箱信息配置文件

//***************************基础类引入***************************//

require_once "../framework/BaseUser.php";//用户基础类
require_once "../framework/Database.php";//数据库类
require_once "../framework/Email.php";//邮件类
require_once "../framework/ModelFactory.php";//模型工厂类
require_once "../framework/PHPMailer/PHPMailerAutoload.php";//邮件实现自动加载类

//***************************站点的绝对路径**********************//

define("AB_HOST",$_SERVER['DOCUMENT_ROOT']."/".HOST_NAME."/");

