<?php

/**
 * Created by PhpStorm.
 * User: ZhangCanLong
 * word文档处理
 * Date: 2016/12/7
 * Time: 14:02
 */
defined("APP") or die("error");

class ChangWordToHtml
{
    /**
     * word文件名称
     * @var string
     */
    private $filePath = "";

    /**
     * html文件名称
     * @var string
     */
    private $htmlPath = "";

    /**
     * 上传word路径
     */
    const WORD_FILE_PATH = AB_HOST . "yearCheck/";

    /**
     * 临时html文件路径
     */
    const HTML_TEMP_PATH = AB_HOST . "yearCheck/tempHtml/";

    /**
     * 构造函数
     * ChangWordToHtml constructor.
     * @param $fileName
     */
    public function __construct($fileName)
    {
        $this->filePath = ChangWordToHtml::WORD_FILE_PATH . $fileName;//合成word文档路径地址
        $this->htmlPath = ChangWordToHtml::HTML_TEMP_PATH . strtolower(str_replace(strrchr("$fileName", "."), ".html", "$fileName"));//html文件路径
    }

    /**
     * 将word转为html,并且返回word的内容,文件名用id或实际文件名的转码不能用中文，我们要预先帮他们转码
     * @param $wordName string 文档名字
     * @param $htmlName string html名字
     * @return string|bool
     */
    public static function word2Html($wordName, $htmlName)
    {
        $wordPath = ChangWordToHtml::WORD_FILE_PATH . $wordName;
        $htmlPath = ChangWordToHtml::HTML_TEMP_PATH . $htmlName;
        $word = new COM("word.application") or die();                         // 建立一个指向新COM组件的索引
        $word->Visible = 0;                                                   // 把它的可见性设置为0（假），如果要使它在最前端打开，使用1（真）
        try {
            $word->Documents->Open($wordPath) or die();
            $word->Documents[1]->SaveAs($htmlPath, 8);                        //把文档保存在目录中
        } catch (Exception $e) {
            return false;
        } finally {
            $word->Quit();                  // 关闭与COM组件之间的连接
            unset($word);                   //销毁资源
        }
        return false;
    }

    /**
     * 将word转为html,并且返回word的内容
     * @return string|bool
     */
    public function wordToHtml()
    {
        $word = new COM("word.application") or die();      // 建立一个指向新COM组件的索引
        $word->Visible = 0;                                // 把它的可见性设置为0（假），如果要使它在最前端打开，使用1（真）
        try {
            $word->Documents->Open($this->filePath) or die();
            $word->Documents[1]->SaveAs($this->htmlPath, 8);              //把文档保存在目录中
        } catch (Exception $e) {
            return false;
        } finally {
            $word->Quit();                  // 关闭与COM组件之间的连接
            unset($word);                   //销毁资源
        }
        return false;
    }

    /**
     * 通过html文件名获取word文档转后的html文件
     * @param $htmlName string 文件名
     * @return string|bool
     */
    public static function GetHtmlContentByHtmlName($htmlName)
    {
        $htmlPath = ChangWordToHtml::HTML_TEMP_PATH . "$htmlName";//html文件路径
        if (file_exists($htmlPath)) {
            $content = file_get_contents($htmlPath);
            $wordValue = @iconv("gb2312", "utf-8//IGNORE", $content);//使用@抵制错误，如果转换字符串中，某一个字符在目标字符集里没有对应字符，
            unset($content);
            return $wordValue;//那么，这个字符之后的部分就被忽略掉了；即结果字符串内容不完整，此时要使用//IGNORE
        } else {
            return false;
        }
    }

    /**
     * 通过html文件名获取word文档转后的html文件
     * @return string|bool
     */
    public function GetHtmlContent()
    {
        if (file_exists($this->htmlPath)) {
            $content = file_get_contents($this->htmlPath);
            $wordValue = @iconv("gb2312", "utf-8//IGNORE", $content);//使用@抵制错误，如果转换字符串中，某一个字符在目标字符集里没有对应字符，
            unset($content);
            return $wordValue;//那么，这个字符之后的部分就被忽略掉了；即结果字符串内容不完整，此时要使用//IGNORE
        } else {
            return false;
        }
    }

    /**
     * @return bool 删除word文件
     */
    public function deleteWord()
    {
        if (file_exists($this->filePath)) {
            return unlink($this->filePath);
        } else {
            return false;
        }
    }

    /**
     * @return bool 删除html文件，包括文件夹
     */
    public function deleteHtml()
    {

    }

    /**
     * 获取word文件路径
     * @return string
     */
    public function getWordPath()
    {
        return $this->filePath;
    }

    /**
     * 获取html文件
     * @return string
     */
    public function getHtmlPath()
    {
        return $this->htmlPath;
    }
}
