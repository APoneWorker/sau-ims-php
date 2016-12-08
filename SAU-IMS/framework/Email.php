<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 邮箱类,这个类仅支持发送简单文本的类，复杂则要直接运用PHPMailer类
 * Date: 2016/11/19
 * Time: 17:20
 */
defined("APP") or die("error");

class Email
{
    /**
     * @var string 配置文件地址
     */
    private static $iniFileName =mailConfig;

    /**
     * @var PHPMailer 邮箱类
     */
    private static $instance;

    /**
     * 加载配置文件信息
     * @return array
     */
    private static function loadConfig()
    {
        if (file_exists(self::$iniFileName)) {//配置文件是否存在
            $info = parse_ini_file(self::$iniFileName, true);//读取文件信息
            return $info;//返回信息组
        } else {
            die("数据库配置文件不存在");
        }
    }

    private static function setConfigToSmtp($info)
    {
        if (!self::$instance instanceof PHPMailer) {//邮箱类是否已经实例化
            self::$instance = static::getInstance();
        }

        self::$instance->isSMTP();                                //设置为smtp服务器
        self::$instance->SMTPAuth = true;                         //开启smtp验证
        self::$instance->SMTPSecure = "ssl";                      //ssl加密

        self::$instance->CharSet = $info["charset"];              //设置字符集
        self::$instance->Host = $info["smtpServer"];              //smtp服务器地址
        self::$instance->Username = $info["smtpEmail"];           //用户账号
        self::$instance->Password = $info["emailPassword"];       //验证密码
        self::$instance->Port = (int)$info["smtpPort"];           //smtp端口

        self::$instance->setFrom($info["smtpEmail"], "校社联管理系统");//发件人
    }

    /**
     * 获得邮箱接口
     * @return PHPMailer
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof PHPMailer) {//如未实例化
            $info = self::loadConfig();             //加载配置文件
            self::$instance = new PHPMailer();      //实例化
            static::setConfigToSmtp($info);         //设置配置信息
        }
        return self::$instance;//返回接口
    }

    /**
     * 发送邮件
     * @param $to string 收件人邮箱
     * @param $subject string 主题
     * @param $body string 主体
     * @param string $altBody 邮箱不支持html时的普通文本
     * @param $isHtml bool 是否发送html形式文本,默认为true
     * @return bool
     */
    public static function send($to, $subject, $body, $altBody = "", $isHtml = true)
    {
        if (!self::$instance instanceof PHPMailer) {//邮箱类是否已经实例化
            self::$instance = static::getInstance();
        }

        self::$instance->isHTML($isHtml);              //将文本设为html格式
        self::$instance->addAddress($to);              //收件人
        self::$instance->Subject = $subject;           //主题
        self::$instance->Body = $body;                 //主体
        self::$instance->AltBody = $altBody;           //如果邮箱不支持html这输出这个文本
        return self::$instance->send();                //发送
    }

    /**
     * 获取错误信息
     * @return string 错误信息
     */
    public static function getError()
    {
        if (self::$instance instanceof PHPMailer) {
            return self::$instance->ErrorInfo;
        }
        return "null";
    }
}

