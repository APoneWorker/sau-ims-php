<?php
/**
 * 头像
 * Created by PhpStorm.
 * User: APone
 * Date: 2016/11/8
 * Time: 22:27
 */
defined("APP") or die("error");

class Portrait{

	const JPG="jpg";

	const PNG="png";

	public function upload($fileName,$type){

		list($width, $height) = getimagesize($pic_info['tmp_name']);//获取原图图像大小
		$maxWidth = $maxHeight= 90;//设置缩略图的最大宽度和高度
		if($width > $height){//自动计算缩略图的宽和高
			$newWidth = $maxWidth;//缩略图的宽等于$maxwidth
			$newHeight = round($newWidth*$height/$width);//计算缩略图的高度
		}else{
			$newHeight = $maxHeight;//缩略图的高等于$maxwidth
			$newWidth = round($newHeight*$width/$height);//计算缩略图的高度
		}

		$thumb = imagecreatetruecolor($newWidth,$newHeight);//绘制缩略图的画布
		$source = imagecreatefromjpeg($pic_info['tmp_name']);//依据原图创建一个与原图一样的新的图像
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);//依据原图创建缩略图
		$new_file = '../'.$info['id'].'.jpg';//设置缩略图保存路径
		imagejpeg($thumb,$new_file,100);//保存缩略图到指定目录
	}
}