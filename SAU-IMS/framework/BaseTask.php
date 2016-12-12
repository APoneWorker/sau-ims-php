<?php
/**
 * 
 * ���������
 * ���û��ܹ��õĺ���
 */
defined("APP") or die("error");

abstract class BaseTask 
{

    /**
     * @var string �û���
     */
    private $userName;

    /**
     * @var int �û�id
     */
    private $id;

    /**
     * @var int ��֯��ʶ
     */
    private $clubId;
    /**
     * @var int У����id
     */
    private $sauId;

    public function __construct($info){
        $this->userName = $info['userName'];
        $this->id = $info['id'];
        $this->clubId = $info['clubId'];
        $this->sauId = $info['sauId'];
    }
    /**
     * ������δ����״̬��Ϊ�Ѷ�
     * �����û�id������id�޸ĸ��û�������Ѷ�δ��״̬
     *
     * @param int $nid ����id
     * @return bool true���޸ĳɹ���flase���޸�ʧ��
     */
    public function setTaskRead($nid){
        $sql = "update `user_task`  
                set `read` = 1
                where `user_id` = ? and `task_id` = ?";
        $conn = Database::getInstance();

        try{
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$this->id);//�û�id
            $stmt -> bindParam(2,$nid);//����id
            $stmt -> execute();

            return $stmt->rowCount() > 0 ? true : false;
        }catch(PDOException $e){
           // echo "������Ϣ��".$e->getMessage();//������
            return false;//sql������
        }
       

    }

    /**
     * ɾ�����û�������
     * �����û�id������idɾ�����û�������
     * 
     * @param string[] $nid ����id����
     * @return bool
     */
    public function deleteUserTask($nid){
        try{
            $sql = "delete from `user_task` where `user_id` = ? and task_id = ?";
            $conn = Database::getInstance();

            $conn -> beginTransaction;
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$this->id);//�û�id 
            foreach ($nid as $value) {
                $value = (int)$value;
                $stmt -> bindParam(2,$value);//����id
                if(! $stmt -> execute()){
                    $conn -> rollBack();
                    return false;
                }
            }
            $conn -> commit;
            return true;
        }catch(PDOException $e){
           // echo "������Ϣ��".$e->getMessage();//������
            return false;//sql������
        }
    }
    /**
     * ��������task����id���������Ϣ
     * @param int $nid ����id 
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
            $stmt -> bindParam(1,$nid,PDO::PARAM_INT);//����id
            $stmt -> execute();
     
            return $stmt->fetch(PDO::FETCH_ASSOC);//ʧ�ܷ���false
        }catch(PDOException $e){
            // echo "������Ϣ��".$e->getMessage();//������
            return false;//sql������           
        }
    }
    /**
     * ����û����յ�������
     * id =>����id
     * title => �������
     * time => ����ʱ��
     * name => ���������������
     * text => ����
     * read => �Ƿ��Ѷ�---0��δ��1���Ѷ�
     * 
     * @param int $limitL 
     * @param int $limitR ��õ�limitL+1����limitR������
     * @return array() ������ϸ��Ϣ 
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
            $stmt ->bindParam(1,$this->getId());//�û�id
            $stmt ->bindParam(2,$limitL);//��߽�
            $stmt ->bindParam(3,$limitR);//�ұ߽�
            if(! $stmt -> execute() ){//��ѯʧ�ܷ���false
                return false;
            }
            $tasks = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $tasks[] = $row;
            }
            return $tasks;//û��ѯ����Ϣ�򷵻ص��ǿ�����
            
        }catch(PDOException $e){
            // echo "������Ϣ��".$e->getMessage();//������
            return false;//sql������  
        }


    }
     /**
     * �����������������յ�������
     * 
     * 
     * @param string $title ��������
     * @param int $limitL 
     * @param int $limitR ��õ�limitL+1����limitR������
     * @return array() ������ϸ��Ϣ
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
            $stmt ->bindParam(1,$this->getId());//����id
            $stmt ->bindParam(2,$title);//��������
            $stmt ->bindParam(3,$limitL,PDO::PARAM_INT);//��߽�
            $stmt ->bindParam(4,$limitR,PDO::PARAM_INT);//�ұ߽�
            if(! $stmt -> execute() ){//��ѯʧ�ܷ���false
                return false;
            }
            $tasks = array();
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $tasks[] = $row;
            }
            return $tasks;//û��ѯ����Ϣ�򷵻ص��ǿ�����
            
        }catch(PDOException $e){
           // echo "������Ϣ��".$e->getMessage();
            return false;
        }

    }
          

     /**
     * ���У������id
     * @return int У����id
     */
    public function getSauId(){
        return $this->sauId;
    }


    /**
     * ��ȡ�û���
     * @return string �û���
     */
    public function getUserName()
    {
        return isset($this->userName) ? $this->userName : "";
    }



    /**
     * ��ȡ�û�id(Ĭ��0)
     * @return int
     */
    public function getId()
    {
        return isset($this->id) ? $this->id : 0;
    }


    /**��ȡ�û���֯��ʶ
     * @return int
     */
    public function getClubId()
    {
        return isset($this->clubId) ? $this->clubId : 0;
    }
}