<?php

/**
 * Created by PhpStorm.
 * User: APone
 * 校社联管理员类
 * Date: 2016/11/6
 * Time: 0:47
 */

class SauAdmin extends BaseUser
{

    /**
     * 获得校社联管理员自己发布的公告（不需要已读未读的功能）
     * id =>公告id
     * title => 公告标题
     * time => 公告时间
     * name => 该公告的社团名字
     * text => 内容
     *
     * @param int $limitL
     * @param int $limitR 获得第limitL+1到第limitR行数据,前端实现下拉滚动条即时刷新效果
     * @return array() 公告详细信息
     */
    public function getSendNotices($limitL,$limitR){

        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`
                from notice n
                join clubinfo c on c.club_id = n.club_id
                where n.club_id = ?
                order by `time` desc
                limit ?,?";
        $conn = Database::getInstance();
        try{

            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getClubId());//社团id    //参数类型默认为string
            $stmt ->bindParam(2,$limitL,PDO::PARAM_INT);//左边界
            $stmt ->bindParam(3,$limitR,PDO::PARAM_INT);//右边界
            $stmt -> execute();

            $notices = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $notices[] = $row;
            }
            return $notices;//没查询到信息则返回的是空数组
            //查询失败返回false
        }catch(PDOException $e){
           echo "出错信息：".$e->getMessage();
            return false;
        }

    }

    //******事务？存储函数?**********

    //********问题*************
    //向服务器请求响应传出的数据会不会被修改
    //*******************************

    /**
     * 删除发布的公告（可在数据库中加触发器）
     * 管理员根据公告id删除公告，需要把其他表中有涉及到该公告的行也删除
     * @param  int[] $nid 公告id数组
     * @return bool true：删除成功；flase：删除失败
     */
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
                $stmt1 -> bindParam(1,$value);//用户id
                $stmt2 -> bindParam(1,$value);//用户id
                $stmt1 -> execute();
                $stmt2 -> execute();
            }
            $conn -> commit();//提交事务
            return true;
        }catch(PDOException $e){
            echo "出错信息：".$e->getMessage();
            $conn -> rollBack();
            return false;
        }

    }
    //********问题*********
    //session会不会被修改
    //***********************

    /**
     * 向数据库添加公告（不可以设置触发器）
     * 数组索引只能是text，time，title，
     * @param array() $notice 公告信息
     *
     */
    public function addNotice($notice){
        $sql1 = "insert into `notice`(`text`,`time`,`title`,`club_id`) values(?,?,?,?)";//向notice表中插入数据
        $sql2 = "select id from user";//所有用户都可以接收到该公告
        $sql3 = "insert into `user_notice`(`user_id`,`notice_id`) values(?,?)";//向user_notice 表中插入数据
        $conn = Database::getInstance();
        try{
            $success = true;
            $conn -> beginTransaction();//开始事务处理
            //向notice表中插入数据
            $stmt1 = $conn -> prepare($sql1);
            $stmt1 -> bindParam(1,$notice['text']);
            $stmt1 -> bindParam(2,$notice['time']);
            $stmt1 -> bindParam(3,$notice['title']);
            $stmt1 -> bindParam(4,$this->getClubId());
            $success = $stmt1 -> execute()?true:false;

            $nid = $conn -> lastInsertId();//最后一行插入的数据的id，即添加的公告的id

            $stmt2 = $conn -> prepare($sql2);
            if(!$stmt2 -> execute()){//获得所有用户的id
                $success = false;
            }
            $stmt3 = $conn -> prepare($sql3);
            while($uid = $stmt2->fetch(PDO::FETCH_ASSOC)['id']){

                $stmt3 -> bindParam(1,$uid);
                $stmt3 -> bindParam(2,$nid);
                if(!($stmt3 -> execute())){//向user_notice 表中插入数据
                    $success = false;
                }
            }

           if(! $success){
                $conn -> rollBack();
                return false;
           }
           $conn -> commit();//提交事务
            return $nid;
        }catch(PDOException $e){
            echo "出错信息：".$e->getMessage();
            ;//若出错就回滚
            return false;
        }
    }
    //****校社联管理员没有未读公告
    //
    /**
     * 根据搜索内容获得公告
     *
     *
     * @param string $text 搜索内容
     * @param int $limitL
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 公告详细信息
     */
    public function searchSendNoticesByTitle($text,$limitL,$limitR){//转义。。%等
        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`
                from notice n
                join clubinfo c on c.club_id = n.club_id
                where n.club_id = ? and `title` like ?
                order by `time`
                limit ?,?";
        $conn = Database::getInstance();
        try{
            $text = "%".$text."%";

            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getClubId());//社团id
            $stmt ->bindParam(2,$text);//搜索内容
            $stmt ->bindParam(3,$limitL,PDO::PARAM_INT);//左边界
            $stmt ->bindParam(4,$limitR,PDO::PARAM_INT);//右边界
            $stmt -> execute();

            $notices = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $notices[] = $row;
            }
            return $notices;//没查询到信息则返回的是空数组
            //查询失败返回false
        }catch(PDOException $e){
           echo "出错信息：".$e->getMessage();
            return false;
        }

    }
    public function register($content)
    {

    }

    public function showInfo($usreName)
    {

    }

    public function editInfo($userName, $content)
    {

    }

    public function getName()
    {
        return "SauAdmin";
    }
}
