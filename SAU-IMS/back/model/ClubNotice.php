<?php

/**
 * 社团管理员公告类
 * 只在ClubAdmin中被new
 */
defined("APP") or die("error");

class SauNotice extends BaseNotice
{
     /**
     * 获得该社团管理员要收到的公告（只有校社联公告）
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
    public function getSauNotices($limitL,$limitR){

        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`,`read` 
            from `user_notice` un
            join `notice` n on n.id = un.notice_id
            join `clubinfo` c on c.club_id = n.club_id
            where un.user_id = ? and n.club_id = ? 
            order by `time`
            limit ?,?";
        $conn = Database::getInstance();
        try{
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getId());//用户id
            $stmt ->bindParam(2,$this->getSauId());//校社联id
            $stmt ->bindParam(3,$limitL);//左边界
            $stmt ->bindParam(4,$limitR;//右边界
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
     * 获得管理员自己发布的公告
     * id =>公告id
     * title => 公告标题
     * time => 公告时间
     * name => 该公告的社团名字
     * text => 内容
     * 
     * @param int $limitL 
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 公告详细信息 
     */
    public function getSendNotices($limitL,$limitR){

        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`
                from notice n
                join clubinfo c on c.club_id = n.club_id
                where n.club_id = ? 
                order by `time`
                limit ?,?";
        $conn = Database::getInstance();
        try{
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getClubId());//社团id

            $stmt ->bindParam(2,$limitL);//左边界
            $stmt ->bindParam(3,$limitR;//右边界
            
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
     * 获得未读的（校社联）公告
     * 
     * @return array() 公告信息，没查询到信息返回空数组，查询失败返回false
     */
    public function getNotReadNotice(){
        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`
                from `user_notice` un
                join `notice` n on n.id = un.notice_id
                join clubinfo c on c.club_id = n.club_id
                where n.user_id = ? and un.club_id = ? and read = ?
                order by `time`";
        $state = 0;//未读的状态
        $conn = Database::getInstance();
        try{
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getId());//用户id

            $stmt ->bindParam(2,$this->getSauId());//校社联id
            $stmt ->bindParam(3,$state);//阅读状态：未读
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
     * 删除发布的公告(跟SauAdmin一样)
     * （可在数据库中加触发器）
     * 管理员根据公告id删除公告，需要把其他表中有涉及到该公告的行也删除
     * @param  string[] $nid 公告id数组,json 传过来的是string
     * @return bool true：删除成功；flase：删除失败
     */
    public function deleteNotice($nid){
    public function deleteNotice($nid){
        $sql1 = "delete from `user_notice`
                where notice_id = ?";//删除用户公告表中的公告信息
        $sql2 = "delete from `notice`
                where id = ?";//删除公告表中的信息
        $conn = Database::getInstance();
        
        try{
        	
            $conn -> beginTransaction();//开始事务处理
            $stmt1 = $conn -> prepare($sql1);
            $stmt2 = $conn -> prepare($sql2);
            foreach ($nid as $value) {
            	$value = (int) $value;
                $stmt1 -> bindParam(1,$value);//用户id
                $stmt2 -> bindParam(1,$value);//用户id
	            if(! $stmt1 -> execute()){
	            	$conn -> rollBack();//回滚
	            	return false;//失败返回false
	            }
	            if(! $stmt2 -> execute()){
	            	$conn -> rollBack();//回滚
	            	return false;//失败返回false
	            }
            }
            $conn -> commit();//提交事务   
            return true;
        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();//测试用
            $conn -> rollBack();
            return false;
        }

    }
    }
    /**
     * 向数据库添加公告（不可以设置触发器）
     * //需要修改,还没测试
     * 数组索引只能是text，time，title，
     * @param array() $notice 公告信息
     * 
     */
    public function addNotice($notice){
        $sql1 = "insert into `notice`(`text`,`time`,`title`,`club_id`) values(?,?,?,?)";//向notice表中插入数据
        $sql2 = "select `user_id` from `user_club` where club_id = ?";//只有社团成员可以接收到该公告
        $sql3 = "insert into `user_notice`(`user_id`,`notice_id`) values(?,?)";//向user_notice 表中插入数据
        $conn = Database::getInstance();
        try{
            $conn -> beginTransaction();//开始事务处理
            
            $stmt1 = $conn -> prepare($sql1);
            $stmt1 -> bindParam(1,$notice['text']);
            $stmt1 -> bindParam(2,$notice['time']);
            $stmt1 -> bindParam(3,$notice['title']);
            $stmt1 -> bindParam(4,$this->getClubId());
            
            if(! $stmt1 -> execute()){//向notice表中插入数据
            	$conn -> rollBack();//回滚
            	return false;//失败返回false
            }
            
            $nid = $conn -> lastInsertId();//最后一行插入的数据的id，即添加的公告的id

            $stmt2 = $conn -> prepare($sql2);
            $stmt2 = bindParam(1,$this->getClubId());
            
            if(!$stmt2 -> execute()){//获得所有用户的id
            	$conn -> rollBack();//回滚
            	return false;//失败返回false
            }

            $stmt3 = $conn -> prepare($sql3);
            while($uid = $stmt2->fetch(PDO::FETCH_ASSOC)['id']){
                
                $stmt3 -> bindParam(1,$uid);
                $stmt3 -> bindParam(2,$nid);
                
                if(!($stmt3 -> execute())){//向user_notice 表中插入数据
            		$conn -> rollBack();//回滚
            		return false;//失败返回false
                }
            }
            $conn -> commit();//提交事务
            // var_dump($notice);
            return $nid;
        }catch(PDOException $e){
            // echo "出错信息：".$e->getMessage();
            $conn -> rollBack();//回滚
            return false;
        }
    }
    /**
     * 
     * 根据搜索内容在收到的公告（即校社联公告）中搜索
     * @param string $text 搜索内容
     * @param int $limitL 
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 公告详细信息
     */
    public function searchSauNoticesByTitle($text,$limitL,$limitR){//转义。。%等
    	if(empty($title)){
    		return false;
    	}

        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`
                from notice n
                join clubinfo c on c.club_id = n.club_id
                where n.club_id = ? and `title` like ?
                order by `time`
                limit ?,?";
        $conn = Database::getInstance();
        try{
            $text = "%".$text."%";
            var_dump($text);
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getSauId());//校社联id
            $stmt ->bindParam(2,$text);//搜索内容
            $stmt ->bindParam(3,$limitL,PDO::PARAM_INT);//左边界
            $stmt ->bindParam(4,$limitR,PDO::PARAM_INT);//右边界
            if(! $stmt -> execute() ){//查询失败返回false
            	return false;
            }
            // var_dump($stmt -> errorinfo());
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
     /**
     * 根据搜索内容搜索发布的公告
     * 跟SauAdmin的一样
     * 
     * @param string $text 搜索内容
     * @param int $limitL 
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 公告详细信息
     */
    public function searchSendNoticesByTitle($text,$limitL,$limitR){//转义。。%等
    	
    	if(empty($title)){
    		return false;
    	}

        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`title`
                from notice n
                join clubinfo c on c.club_id = n.club_id
                where n.club_id = ? and `title` like ?
                order by `time`
                limit ?,?";
        $conn = Database::getInstance();
        try{
            $title = "%".$title."%";
            
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getClubId());//社团id
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
}