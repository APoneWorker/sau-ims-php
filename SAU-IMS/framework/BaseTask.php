<?php
/**
 * 
 * 任务基础类
 * 放用户能共用的函数
 */
defined("APP") or die("error");

abstract class BaseTask 
{

    /**
     * @var string 用户名
     */
    private $userName;

    /**
     * @var int 用户id
     */
    private $id;

    /**
     * @var int 组织标识
     */
    private $clubId;
    /**
     * @var int 校社联id
     */
    private $sauId;

    public function __construct($info){
        $this->userName = $info['userName'];
        $this->id = $info['id'];
        $this->clubId = $info['clubId'];
        $this->sauId = $info['sauId'];
    }
    /**
     * 将任务未读的状态设为已读
     * 根据用户id和任务id修改该用户任务的已读未读状态
     *
     * @param int $nid 任务id
     * @return bool true：修改成功；flase：修改失败
     */
    public function setTaskRead($nid){
        $sql = "update `user_task`  
                set `read` = 1
                where `user_id` = ? and `task_id` = ?";
        $conn = Database::getInstance();

        try{
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$this->id);//用户id
            $stmt -> bindParam(2,$nid);//任务id
            $stmt -> execute();

            return $stmt->rowCount() > 0 ? true : false;
        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错
        }
       

    }

    /**
     * 删除该用户的任务
     * 根据用户id和任务id删除该用户的任务
     * 
     * @param string[] $nid 任务id数组
     * @return bool
     */
    public function deleteUserTask($nid){
        try{
            $sql = "delete from `user_task` where `user_id` = ? and task_id = ?";
            $conn = Database::getInstance();

            $conn -> beginTransaction;
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$this->id);//用户id 
            foreach ($nid as $value) {
                $value = (int)$value;
                $stmt -> bindParam(2,$value);//任务id
                if(! $stmt -> execute()){
                    $conn -> rollBack();
                    return false;
                }
            }
            $conn -> commit;
            return true;
        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错
        }
    }
    /**
     * 根据任务（task）的id获得任务信息
     * @param int $nid 任务id 
     */
    public function getTaskById($nid){
        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`
                from `task` n
                join `clubinfo` c on n.club_id = c.club_id
                where n.id = ? 
                ";
        $conn = Database::getInstance();

        try{
            $stmt = $conn -> prepare($sql);  
            $stmt -> bindParam(1,$nid,PDO::PARAM_INT);//任务id
            $stmt -> execute();
     
            return $stmt->fetch(PDO::FETCH_ASSOC);//失败返回false
        }catch(PDOException $e){
            // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错           
        }
    }
    /**
     * 获得用户能收到的任务
     * id =>任务id
     * title => 任务标题
     * time => 任务时间
     * name => 该任务的社团名字
     * text => 内容
     * read => 是否已读---0：未读1：已读
     * 
     * @param int $limitL 
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 任务详细信息 
     */
    public function getReceiveTasks($limitL,$limitR){

        $sql = "select t.id `id`,`title`,`time`,c.name `name`,`text`
                from task t
                join clubinfo c on c.club_id = t.club_id
                join user_task ut on ut.task_id = t.id
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
     * 根据搜索内容搜索收到的任务
     * 
     * 
     * @param string $title 搜索内容
     * @param int $limitL 
     * @param int $limitR 获得第limitL+1到第limitR行数据
     * @return array() 任务详细信息
     */
    public function searchTasksByTitle($title,$limitL,$limitR){
        
        if(empty($title)){
            return false;
        }
        $title = Database::specialChrtoNormalChr($title);
        $sql = "select t.id `id`,`title`,`time`,c.name `name`,`title`
                from task t
                join clubinfo c on c.club_id = t.club_id
                join user_task ut on ut.task_id = t.id
                where ut.user_id = ? and `title` like ? escape '/'
                order by `time` desc
                limit ?,?";
        $conn = Database::getInstance();
        try{
            $title = "%".$title."%";
            
            $stmt = $conn -> prepare($sql);
            $stmt ->bindParam(1,$this->getId());//社团id
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
     * 获得校社联的id
     * @return int 校社联id
     */
    public function getSauId(){
        return $this->sauId;
    }


    /**
     * 获取用户名
     * @return string 用户名
     */
    public function getUserName()
    {
        return isset($this->userName) ? $this->userName : "";
    }



    /**
     * 获取用户id(默认0)
     * @return int
     */
    public function getId()
    {
        return isset($this->id) ? $this->id : 0;
    }


    /**获取用户组织标识
     * @return int
     */
    public function getClubId()
    {
        return isset($this->clubId) ? $this->clubId : 0;
    }
}