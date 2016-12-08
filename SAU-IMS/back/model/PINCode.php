<?php

/**
 * 登录验证码以及其他验证码
 * Created by PhpStorm.
 * User: APone
 * Date: 2016/11/8
 * Time: 22:27
 */
defined("APP") or die("error");

class PINCode
{
    /**
     * @var int 验证码图片宽
     */
    private $imgWidth = 90;

    /**
     * @var int 验证码图片高
     */
    private $imgHeight = 30;

    /**
     * @var int 验证码长度
     */
    private $charLength = 6;

    /**
     * @var int 验证码字体大小
     */
    private $fontSize = 7;

    /**
     * @var string 创建的验证码
     */
    private $loginCode = "";

    /**
     * @var string 邮箱验证码
     */
    private $emailCode = "";

    /**
     * 创建登陆验证码
     */
    public function createLoginCode()
    {
        $char = array_merge(range("A", "Z"), range("a", "z"), range(1, 9));//验证码数组
        $randChar = array_rand($char, $this->charLength);//随机获取一定长度的键
        if ($this->charLength == 1) {//当验证码字数为1时把它放入数组
            $randChar = array($randChar);
        }
        shuffle($randChar);//打乱随机获取的验证码
        foreach ($randChar as $key) {//拼接
            $this->loginCode .= $char[$key];
        }

        $img = imagecreatetruecolor($this->imgWidth, $this->imgHeight);//生成画布
        $bgColor = imagecolorallocate($img, 0xcc, 0xcc, 0xcc);//为画布生成颜色
        imagefill($img, 0, 0, $bgColor);//设置画布背景色

        for ($i = 0; $i <= 300; $i++) {//在验证码图片上生成干扰点
            $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));//随机颜色
            imagesetpixel($img, mt_rand(0, $this->imgWidth), mt_rand(0, $this->imgHeight), $color);//随机画点
        }

        for ($i = 0; $i <= 10; $i++) {//在验证码上生成干扰线条
            $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));//随机颜色
            imageline($img, mt_rand(0, $this->imgWidth), mt_rand(0, $this->imgHeight), mt_rand(0, $this->imgWidth), mt_rand(0, $this->imgHeight), $color);//随机画线
        }

        $rectColor = imagecolorallocate($img, 0xff, 0xff, 0xff);//验证码边框颜色
        imagerectangle($img, 0, 0, $this->imgWidth - 1, $this->imgHeight - 1, $rectColor);//画验证码边框

        $strColor = imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));//字符串颜色
        $fontWidth = imagefontwidth($this->fontSize);//获取字体的长和宽
        $fontHeight = imagefontheight($this->fontSize);

        $strAllWidth = $fontWidth * $this->charLength;//字符串总长度，单个长度*个数

        imagestring($img, $this->fontSize, ($this->imgWidth - $strAllWidth) / 2, ($this->imgHeight - $fontHeight) / 2, $this->loginCode, $strColor);//将验证码值画入图片

        return $img;//返回验证码
    }

    /**
     * 直接生成验证码//不推荐
     */
    public function showCodeImg()
    {
        header('Content-type:image/png');
        $img = $this->createLoginCode();
        imagepng($img);//输出验证码
        imagedestroy($img);//销毁验证码
    }

    /**
     * 获取当前的验证码
     * @return string
     */
    public function getLoginCurrentCode()
    {
        return $this->loginCode;
    }

    /**
     * 创建邮箱验证码
     * @return string 验证码
     */
    public function createMailCode()
    {
        $char = array_merge(range("A", "Z"), range(1, 9));
        $randChar = array_rand($char, 6);
        shuffle($randChar);
        foreach ($randChar as $key) {
            $this->emailCode .= $char[$key];
        }
        return $this->emailCode;
    }

    /**
     * 获取当前的邮箱验证码
     * @return string
     */
    public function getMailCurrentCode()
    {
        return $this->emailCode;
    }

    /**
     * 设置图片宽度
     * @param $width int 宽度
     */
    public function setImgWidth($width)
    {
        $this->imgWidth = $width;
    }

    /**
     * 设置图片高度
     * @param $height int 高度
     */
    public function setImgHeight($height)
    {
        $this->imgHeight = $height;
    }

    /**
     * 设置验证码长度
     * @param $length int 长度
     */
    public function setCharLength($length)
    {
        $this->charLength = $length;
    }

    /**
     * 设置验证码字体大小
     * @param $size int 字体大小
     */
    public function setFontSize($size)
    {
        $this->fontSize = $size;
    }

    /**
     * 获取图片长度
     * @return int
     */
    public function getImgWidth()
    {
        return $this->imgWidth;
    }

    /**
     * 获取图片高度
     * @return int
     */
    public function getImgHeight()
    {
        return $this->imgHeight;
    }

    /**
     * 获取验证码长度
     * @return int
     */
    public function getCharLength()
    {
        return $this->charLength;
    }

    /**
     * 获取验证码字体大小
     * @return int
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }
}
