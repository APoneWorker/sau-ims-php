<?php
/**
 * 
 * 公告基础类
 * 放用户能共用的函数
 */
defined("APP") or die("error");

abstract class BaseNotice 
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
     * 将公告未读的状态设为已读
     * 根据用户id和公告id修改该用户公告的已读未读状态
     *
     * @param int $nid 公告id
     * @return bool true：修改成功；flase：修改失败
     */
    public function setNoticeRead($nid){
        $sql = "update `user_notice`  
                set `read` = 1
                where `user_id` = ? and `notice_id` = ?";
        $conn = Database::getInstance();

        try{
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$this->id);//用户id
            $stmt -> bindParam(2,$nid);//公告id
            $stmt -> execute();

            return $stmt->rowCount() > 0 ? true : false;
        }catch(PDOException $e){
           // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错
        }
       

    }

    /**
     * 删除该用户的公告
     * 根据用户id和公告id删除该用户的公告
     * 
     * @param string[] $nid 公告id数组
     * @return bool
     */
    public function deleteUserNotice($nid){
        try{
            $sql = "delete from `user_notice` where `user_id` = ? and notice_id = ?";
            $conn = Database::getInstance();

            $conn -> beginTransaction;
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$this->id);//用户id 
            foreach ($nid as $value) {
                $value = (int)$value;
                $stmt -> bindParam(2,$value);//公告id
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
     * 根据公告（notice）的id获得公告信息
     * @param int $nid 公告id
     * @return bool
     */
    public function getNoticeById($nid){
        $sql = "select n.id `id`,`title`,`time`,c.name `name`,`text`
                from `notice` n
                join `clubinfo` c on n.club_id = c.club_id
                where n.id = ? ";
        $conn = Database::getInstance();

        try{
            $stmt = $conn -> prepare($sql);  
            $stmt -> bindParam(1,$nid,PDO::PARAM_INT);//公告id
            $stmt -> execute();
     
            return $stmt->fetch(PDO::FETCH_ASSOC);//失败返回false
        }catch(PDOException $e){
            // echo "出错信息：".$e->getMessage();//测试用
            return false;//sql语句出错           
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