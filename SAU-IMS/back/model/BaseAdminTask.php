<?php
/**
 *
 * 公告基础类
 * 放管理员能共用的方法
 */
defined("APP") or die("error");

abstract class BaseAdminTask extends BaseTask
{


    /**
     * 获得管理员自己发布的任务（不需要已读未读的功能）
     * id =>任务id
     * title => 任务标题
     * time => 任务时间
     * name => 该任务的社团名字
     * text => 内容
     *
     * @param int $limitL
     * @param int $limitR 获得第limitL+1到第limitR行数据,前端实现下拉滚动条即时刷新效果
     * @return array() 任务详细信息
     */
    public function getSendTasks($limitL,$limitR){

        $sql = "select t.id `id`,`title`,`time`,c.name `name`,`text`
                from task t
                join clubinfo c on c.club_id = t.club_id
                where t.club_id = ?
                order by `time` desc
                limit ?,?";
        $conn = Database::getInstance();
        try{

            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getClubId());//社团id    //参数类型默认为string
            $stmt ->bindParam(2,$limitL,PDO::PARAM_INT);//左边界
            $stmt ->bindParam(3,$limitR,PDO::PARAM_INT);//右边界

            if(! $stmt -> execute()){
                return false;//失败返回false
            }

            $tasks = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $tasks[] = $row;
            }
            return $tasks;//没查询到信息则返回的是空数组

        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错
        }

    }


    /**
     * 删除(撤销)发布的任务（可在数据库中加触发器）
     * 管理员根据任务id删除任务，需要把其他表中有涉及到该任务的行也删除
     * @param  string[] $nid 任务id数组,json 传过来的是string
     * @return bool true：删除成功；flase：删除失败
     */
    public function deleteTask($nid){
        $sql1 = "delete from `user_task`
                where task_id = ?";//删除用户任务表中的任务信息
        $sql2 = "delete from `task`
                where id = ?";//删除任务表中的信息
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


    /**
     * 向数据库添加任务（不可以设置触发器）
     * 数组索引只能是text，time，title，
     *
     * ids:要发布给他们看的用户id
     *
     * @param array() $task 任务信息
     *
     */
    public function addTask($task,$ids){
        if(empty($ids)){
            return false;
        }

        $sql1 = "insert into `task`(`text`,`time`,`title`,`club_id`) values(?,?,?,?)";//向task表中插入数据
        //只有选择了的用户可以接收到该任务
        $sql2 = "insert into `user_task`(`user_id`,`task_id`) values(?,?)";//向user_task 表中插入数据
        $conn = Database::getInstance();
        try{

            $conn -> beginTransaction();//开始事务处理
            //向task表中插入数据
            $stmt1 = $conn -> prepare($sql1);
            $stmt1 -> bindParam(1,$task['text']);
            $stmt1 -> bindParam(2,$task['time']);
            $stmt1 -> bindParam(3,$task['title']);
            $stmt1 -> bindParam(4,$this->getId());

            if(! $stmt1 -> execute()){
                $conn -> rollBack();//回滚
                return false;//失败返回false
            }

            $tid = $conn -> lastInsertId();//最后一行插入的数据的id，即添加的任务的id


            $stmt2 = $conn -> prepare($sql2);
            foreach($ids as $uid){

                $stmt2 -> bindParam(1,$uid);
                $stmt2 -> bindParam(2,$tid);

                if(!($stmt2 -> execute())){//向user_task 表中插入数据
                    $conn -> rollBack();//回滚
                    return false;//失败返回false
                }
            }


           $conn -> commit();//提交事务
            return $tid;//返回任务id
        }catch(PDOException $e){
            // echo "出错信息：".$e->getMessage();
            $conn -> rollBack();//回滚
            return false;
        }
    }

    /**
     * 根据搜索内容获得发布的任务
     *
     *
     * @param string $title 搜索内容
     * @param int $limitL
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 任务详细信息
     */
    public function searchSendTasksByTitle($title,$limitL,$limitR){

        if(empty($title)){
            return false;
        }
        $title = Database::specialChrtoNormalChr($title);//将"%"和"_"转为"/%"和"/_"
        $sql = "select t.id `id`,`title`,`time`,c.name `name`,`title`
                from task t
                join clubinfo c on c.club_id = t.club_id
                where t.club_id = ? and `title` like ? escape '/'
                order by `time`
                order by time desc
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
            $tasks = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $tasks[] = $row;
            }
            return $tasks;//没查询到信息则返回的是空数组

        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();
            return false;
        }

    }

    /**
     * 获得所有社团成员的id和名字
     * @return [type] [description]
     */
    public function getClubMembers(){
       $sql = "select id,name
                from user u
                join userinfo ui on u.id = ui.user_id
                where u.club_id = ? ";
        $conn = Database::getInstance();
        try{

            $stmt = $conn -> prepare($sql);
            $stmt->bindParam(1,$this->getClubId());
            if(! $stmt -> execute()){
                return false;//失败返回false
            }

            $members = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $members[] = $row;
            }
            return $members;//没查询到信息则返回的是空数组

        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错
        }
    }

    /**
     * 根据任务的id获得该条任务的具体信息
     * 包括收到了任务的用户的名字
     * @param  [type] $tid [description]
     * @return [type]      [description]
     */
    public function getTaskById($tid){
        $sql1 = "select t.id `id`,`title`,`time`,c.name `name`,`text`
                from `task` t
                join `clubinfo` c on t.club_id = c.club_id
                where t.id = ? ";//获得任务的信息
        $sql2 = "select ui.name `recipients`
                from user_task ut
                join userinfo ui on ut.user_id = ui.user_id
                where ut.task_id = ?
                order by recipients";//获得接收到的用户名字
        $conn = Database::getInstance();

        try{
            $stmt1 = $conn -> prepare($sql1);
            $stmt2 = $conn -> prepare($sql2);

            $stmt1 -> bindParam(1,$tid,PDO::PARAM_INT);//任务id
            $stmt2 -> bindParam(1,$tid,PDO::PARAM_INT);//任务id
            if(!$stmt1 -> execute()){

                return false;//失败返回false
            }
            if(!$stmt2 -> execute()){
                return false;//失败返回false
            }
            $task = $stmt1 ->fetch(PDO::FETCH_ASSOC);
            $user = array();
            while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                $user[] = $row['recipients'];//获得接收到的用户名字
            }
            $task['user'] = $user;//将接收到了任务的用户的名字放在task里
            return $task;
        }catch(PDOException $e){
            // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错
        }
    }



}
