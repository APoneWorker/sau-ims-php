<?php
/**
 * 头像
 * Created by PhpStorm.
 * User: APone
 * Date: 2016/11/8
 * Time: 22:27
 */
//defined("APP") or die("error");

class Portrait
{

    const PNG = ".png";

    const JPG = ".jpg";

    /**
     * @var string 头像路径
     */
    private $fileName = "";

    /**
     * Portrait constructor.
     * @param $fileName string 文件路径
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * 上传图片，缩小，转移
     * @param $newName string 新的文件路径
     */
    public function upload($newName)
    {
        list($width, $height) = getimagesize($this->fileName);//获取原图图像大小
        $maxWidth = $maxHeight = 90;//设置缩略图的最大宽度和高度
        if ($width > $height) {//自动计算缩略图的宽和高
            $newWidth = $maxWidth;//缩略图的宽等于$maxWidth
            $newHeight = round($newWidth * $height / $width);//计算缩略图的高度
        } else {
            $newHeight = $maxHeight;//缩略图的高等于$maxWidth
            $newWidth = round($newHeight * $width / $height);//计算缩略图的高度
        }

        $thumb = imagecreatetruecolor($newWidth, $newHeight);//绘制缩略图的画布
        $source = imagecreatefromjpeg($this->fileName);//依据原图创建一个与原图一样的新的图像
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);//依据原图创建缩略图
        $newFile = PORTRAIT_PATH . $newName . static::PNG;//设置缩略图保存路径
        imagepng($thumb, $newFile, 9);//保存缩略图到指定目录

    }

    /**
     * 获得用户头像
     */
    public function getPortrait()
    {
        header('Content-type:image/png');
        $img = imagecreatefrompng($this->fileName);
        imagepng($img);//输出验证码
        imagedestroy($img);
    }

    /*
    public function jpgToPng()
    {
        $srcFileExt = strtolower(trim(substr(strrchr($this->fileName, '.'), 1)));

        if ($srcFileExt == 'jpg') {
            $dstFile = str_replace('.png', '.jpg', $srcPathName);
            $photoSize = GetImageSize($srcFile);
            $pw = $photoSize[0];
            $ph = $photoSize[1];
            $dstImage = ImageCreateTrueColor($pw, $ph);
            imagecolorallocate($dstImage, 255, 255, 255);
            //读取图片
            $srcImage = ImageCreateFromPNG($srcFile);
            //合拼图片
            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $pw, $ph, $pw, $ph);
            imagedestroy($srcImage);
        }
    }*/

}