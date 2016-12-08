<?php
/**
 * Created by PhpStorm.
 * 用户抽象类，一切其他权限用户类必须继承该类
 * User: APone
 * Date: 2016/11/5
 * Time: 12:15
 */

require_once "./framework/Database.php";
//***************问题****************
//把Database::getInstance()赋给私有变量
//**************************************
abstract class BaseUser
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
     * 若是数据库校社联的id有改动，可以直接改这个值
     * @var int 校社联id
     */
    private $sauId = 1;
    /**
     * 构造函数
     * BaseUser constructor.
     * @param $userName string 用户名
     */
    public function __construct($userName = "")
    {
        //已经登陆成功后保存了session文件，所以可以用session给这些属性赋值
        //但是如果用户禁用cookie就找不到服务器中的session文件
        //所以还是从数据库中查询出信息给其赋值，
        //但是每次请求都会新建一个user对象，即每次请求都会先查询一次数据库。。。。。。
        //不明白为什么形参userName要为空
        $this->userName = $userName;

        
    }
    /**
     * 初始化user
     * @param $userName string 用户名
     * 
     */
    public function init($userName){
        $this->userName = $userName;
        $userinfo = $this->getUserIdentify($userName);
        $this->id = $userinfo['id'];
        $this->clubId = $userinfo['club_id'];
        
    }

    /**
     *获取用户标识，包括用户id,以及用户所在的组织
     * @return int 权限
     */
    public function getIdentify()
    {
        $sql = "select `id`,`club_id` from `user` where `username`=?";
        $conn = Database::getInstance();//获取接口
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $this->userName);//绑定参数
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     *获取用户标识，包括用户id,权限,以及用户所在的组织
     * @param $userName string 用户名
     * @return int 权限
     */
    public static function getUserIdentify($userName)
    {
        $sql = "select `id`,`right`,`club_id` from `user` where `username`=?";
        $conn = Database::getInstance();//获取接口
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $userName);//绑定参数
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 检查账号是否正确，登陆用
     * @param $password string 密码
     * @return bool 是否正确
     */
    public function checkAccount($password)
    {
        $sql = "select `username` from `user` where `username`=? and `password`=?";
        $conn = Database::getInstance();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $this->userName);
        $stmt->bindParam(2, $password);

        $stmt->execute();

        return $stmt->rowCount() > 0 ? true : false;
    }

    /**
     * 获取用户key
     * @return string key
     */
    public function getKey()
    {
        $sql = "select `salt` from `user` where `username`=? ";
        $conn = Database::getInstance();//获取接口
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $this->userName);//绑定参数
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['salt'];
    }

    /**
     * 修改密码
     * @param $oldPassword string 新密码
     * @param $newPassword string 旧密码
     * @return bool 是否成功修改
     */
    public function editPassword($oldPassword, $newPassword)
    {
        $sql = "update `user` set `password`=? where `username`=? and `password`=?";
        $conn = Database::getInstance();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $newPassword);
        $stmt->bindParam(2, $this->userName);
        $stmt->bindParam(3, $oldPassword);
        $stmt->execute();

        return $stmt->rowCount() > 0 ? true : false;
    }

    /**
     * 创建密码
     * @param $newPassword string 新密码
     * @return bool 是否创建成功
     */
    public function createPassword($newPassword)
    {
        $sql = "update `user` set `password`=? where `username`=?";
        $conn = Database::getInstance();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $newPassword);
        $stmt->bindParam(2, $this->userName);
        $stmt->execute();

        return $stmt->rowCount() > 0 ? true : false;
    }

    /**
     * 通过用户名获取邮箱
     * @return string 邮箱
     */
    public function getEmail()
    {
        $sql = "select `email` from `userInfo` where `username`=?";
        $conn = Database::getInstance();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $this->userName);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['email'];
    }


    /**
     * 该用户是否存在
     * @return bool
     */
    public function isExits()
    {
        $sql = "select `username` from `user` where `username`=?";
        $conn = Database::getInstance();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $this->userName);
        $stmt->execute();

        return $stmt->rowCount() > 0 ? true : false;
    }

    /**
     *通过邮箱获取用户名
     * @param $email string 邮箱
     * @return string 用户名
     */
    public static function getNameByUserEmail($email)
    {
        $sql = "select `username` from `userInfo` where `email`=?";
        $conn = Database::getInstance();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['username'];
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

            return true;
        }catch(PDOException $e){
            echo "出错信息：".$e->getMessage();
            return false;
        }
       

    }

    /**
     * 删除该用户的公告
     * 根据用户id和公告id删除该用户的公告
     * 
     * @param int[] $nid 公告id数组
     * @return bool true：删除成功；flase：删除失败
     */
    public function deleteUserNotice($nid){
        $sql = "delete from `user_notice` where `user_id` = ? and notice_id = ?";
        $conn = Database::getInstance();

        try{
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$this->id);//用户id 
            foreach ($nid as $value) {
               
                $stmt -> bindParam(2,$nid);//公告id
                $stmt -> execute();
            }
            
            return true;
        }catch(PDOException $e){
            echo "出错信息：".$e->getMessage();
            return false;
        }
    }
    /**
     * 根据公告（notice）的id获得公告信息
     * @param int $nid 公告id 
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
            
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo "出错信息：".$e->getMessage();
            return false;
        }
    }
    
    

    /**
     * 注册
     * @param $content array 用户注册信息
     * @return bool 是否注册成功
     */
    abstract public function register($content);

    /**
     * 显示信息
     * @param $usreName string 用户名
     * @return mixed 用户信息
     */
    abstract public function showInfo($usreName);

    /**
     * 编辑信息
     * @param $userName string 用户名
     * @param $content array or string 内容
     * @return bool 是否修改成功
     */
    abstract public function editInfo($userName, $content);

    /**
     * 获取类名
     * @return mixed 类名
     */
    public function getName()
    {
        return "BaseUser";
    }

    /**
     * 设置用户名
     * @param $userName string 用户名
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * 获取用户名
     * @return string 用户名
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * 设置用户id
     * @param $id int 用户id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 获取用户id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 设置用户组织标识
     * @param $clubId int
     */
    public function setClubId($clubId)
    {
        $this->clubId=$clubId;
    }

    /**获取用户组织标识
     * @return int
     */
    public function getClubId()
    {
        return $this->clubId;
    }

     /**
     * 获得校社联的id
     * @return int 校社联id
     */
    public function getSauId(){
        return $sauId;
    }
}


