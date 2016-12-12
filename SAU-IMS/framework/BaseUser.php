<?php
/**
 * Created by PhpStorm.
 * 用户抽象类，一切其他权限用户类必须继承该类
 * User: APone
 * Date: 2016/11/5
 * Time: 12:15
 */

defined("APP") or die("error");

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
        //不明白为什么形参userName要为空
        $this->userName = $userName;
        $this->getIdentify();//识别用户，无论是否调用checkAccount方法或该用户是否存在
    }

    /**
     *获取用户标识，包括用户id,以及用户所在的组织
     */
    private function getIdentify()
    {
        $sql = "select `id`,`club_id` from `user` where `username`=?";
        $conn = Database::getInstance();//获取接口
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $this->userName);//绑定参数
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);//获取用户信息
        $this->id = isset($info["id"]) ? $info["id"] : 0;
        $this->clubId = isset($info["club_id"]) ? $info["club_id"] : 0;
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

    public function editUserBasicInfo($newName,$newEmail)
    {
        $sql1 = "update `userinfo` set `name`=? where `username`=?";//更改用户名称
        $sql2 = "update `user` set `email`=? where `username`=?";//修改用户邮箱

        $conn = Database::getInstance();
        try {
            $conn->beginTransaction();//开始事务处理
            $stmt1 = $conn->prepare($sql1);
            $stmt2 = $conn->prepare($sql2);

            $stmt1->bindParam(1, $newName);
            $stmt1->bindParam(2, $this->userName);

            $stmt2->bindParam(1, $newEmail);
            $stmt2->bindParam(2, $this->userName);

            if (!$stmt1->execute()) {
                $conn->rollBack();//回滚
                return false;//失败返回false
            }

            if (!$stmt2->execute()) {
                $conn->rollBack();//回滚
                return false;//失败返回false
            }

            $conn->commit();//提交事务
            return ($stmt1->rowCount() != 0 && $stmt2->rowCount()) != 0 ? true : false;
        } catch (PDOException $e) {
            $conn->rollBack();
            return false;
        }
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
        $sql = "select `email` from `user` where `username`=?";
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

     public function getName()
    {
        $sql = "select `name` from `userinfo` where `username` = ?";
        $conn = Database::getInstance();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $this->userName);//用户id
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['name'];
    }



     /**
     * 获得用户的头像名字等信息
     *
     * *****账号|用户名***问题*****
     * 不用用户的名字登陆
     * ****************************
     *
     */
    public function getUserInfo(){
        $sql = "select head_img `headImgName`, `name` from `userinfo` where user_id = ?";
        $conn = Database::getInstance();


        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(1,$this->id);//用户id
        $stmt -> execute();


        return $stmt->fetch(PDO::FETCH_ASSOC);


    }

     /**
     * 获得校社联的id
     * @return int 校社联id
     */
    public function getSauId(){
        return $this->sauId;
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
        return isset($this->userName) ? $this->userName : "";
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
     * 获取用户id(默认0)
     * @return int
     */
    public function getId()
    {
        return isset($this->id) ? $this->id : 0;
    }

    /**
     * 设置用户组织标识
     * @param $clubId int
     */
    public function setClubId($clubId)
    {
        $this->clubId = $clubId;
    }

    /**获取用户组织标识
     * @return int
     */
    public function getClubId()
    {
        return isset($this->clubId) ? $this->clubId : 0;
    }

    public function getRight(){
        return static::getUserIdentify($this->getUserName());
    }
}


