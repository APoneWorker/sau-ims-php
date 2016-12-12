<?php

/**
 * 普通用户公告类
 * 只在GeneralUser中被new
 */
defined("APP") or die("error");

class GeneralUserNotice extends BaseNotice
{
	/**
     * 获得用户能收到的公告
     * id =>公告id
     * title => 公告标题
     * time => 公告时间
     * name => 该公告的社团名字
     * text => 内容
     * read => 是否已读---0：未读1：已读
     * 
     * @param int $limitL 
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 公告详细信息 
     */
    public function getNotices($limitL,$limitR){

        $sql = "select t.id `id`,`title`,`time`,c.name `name`,`text`
                from notice t
                join clubinfo c on c.club_id = t.club_id
                join user_notice ut on ut.notice_id = t.id
                where ut.user_id = ? 
                order by `time` desc
                limit ?,?";
        $conn = Database::getInstance();
        try{
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getId());//用户id
            
            $stmt ->bindParam(2,$limitL);//左边界
            $stmt ->bindParam(3,$limitR);//右边界
            if(! $stmt -> execute() ){//查询失败返回false
                return false;
            }
            $notices = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $notices[] = $row;
            }
            return $notices;//没查询到信息则返回的是空数组
            
        }catch(PDOException $e){
            // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错  
        }


    }
    /**
     * 根据搜索内容搜索收到的公告
     * 
     * 
     * @param string $title 搜索内容
     * @param int $limitL 
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 公告详细信息
     */
    public function searchNoticesByTitle($title,$limitL,$limitR){
        
        if(empty($title)){
            return false;
        }
        $title = Database::specialChrtoNormalChr($title);
        $sql = "select n.id `id`,`text`,`time`,c.name `name`,`title`
                from notice n
                join clubinfo c on c.club_id = n.club_id
                join user_notice ut on ut.notice_id = n.id
                where ut.user_id = ? and `title` like ? escape '/'
                order by `time` desc
                limit ?,?";
        $conn = Database::getInstance();
        try{
            $title = "%".$title."%";
            
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getId());//用户id
            $stmt ->bindParam(2,$title);//搜索内容
            $stmt ->bindParam(3,$limitL,PDO::PARAM_INT);//左边界
            $stmt ->bindParam(4,$limitR,PDO::PARAM_INT);//右边界
            if(! $stmt -> execute() ){//查询失败返回false
                return false;
            }
            $notices = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $notices[] = $row;
            }
            return $notices;//没查询到信息则返回的是空数组
            
        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();
            return false;
        }

    }
}