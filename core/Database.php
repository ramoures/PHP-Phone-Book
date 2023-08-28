<?php
final class Database{
    use errors;
    private static $instance = null;
    private $host=DB_HOST;
    private $user=DB_USER;
    private $password=DB_PASWORD;
    private $dbname=DB_NAME;
    private $pdo = null;
    public static $n;
    private function __construct(){
        try {
            //set DSN
            $dsn = "mysql:host=".$this->host.";dbname=".$this->dbname;
            //create PDO instance
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            if($this->pdo){
                $this->pdo->exec("set names utf8mb4");
                //set default for Fetch
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                //diabled PDO emulate:
                $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }
           
        } catch (Exception $e) {
            $this->error($e);
        }
       
    }
    public function create($object){ //Insert Data
       try {
            $columns = array_map(function ($column){return ":".$column; }, array_keys($object['data']));
            $columnsStr = implode(", ",$columns);
            if(isset($object['data']['phone_numbers']))
                $object['data']['phone_numbers'] =  implode("+",$object['data']['phone_numbers']);;
            $rows = implode(",",array_keys($object['data']));
            $sql = "INSERT INTO ".$object['tableName']."($rows) VALUES($columnsStr)";
            $stmt = $this->pdo->prepare($sql);
            $stmt = $stmt->execute($object['data']);
            if($stmt)
                return $this->pdo->lastInsertId();
            return false;
       } catch (\Throwable $th) {
            return false;
       }
    }
    
    public function read($object){
        try {
            $issetCheck = $object['issetCheck']??false;
            $sql = sprintf("SELECT * FROM %s",$object['tableName']);

            if(isset($object['where'])){
                $columns = array_map(function ($column){return $column."=?"; }, array_keys($object['where']));
                $columnsStr = implode(" AND ",$columns);
                $where = array_values($object['where']);
                $sql .= sprintf(" WHERE (%s)",$columnsStr);
            }
            if(isset($object['whereNot'])){
                if(isset($object['where'])) 
                    $sql .= " AND ";
                $columnsWn = array_map(function ($column){return $column."=?"; }, array_keys($object['whereNot']));
                $columnsStrWn = implode(" AND ",$columnsWn);
                $whereNot = array_values($object['whereNot']);
                $sql .= sprintf(" NOT (%s)",$columnsStrWn);
                $where = array_merge($where,$whereNot);
            }
            if(isset($object['orderBy']))
                $sql .= sprintf(" ORDER BY %s",$object['orderBy']);
            if(isset($object['asc'])){
                $ascDesc = $object['asc']?'DESC':'ASC';
                $sql .= sprintf(" %s",$ascDesc);
            }
            if(isset($object['limit']))
                $sql .= sprintf(" LIMIT %s",$object['limit']);
            if(isset($object['offset']))
                $sql .= sprintf(" OFFSET %s",$object['offset']);
            if(isset($object['where']) || isset($object['whereNot'])){
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($where);
            }
            else
                $stmt = $this->pdo->query($sql);
            if($stmt){
                $res = $stmt->fetchAll();
                if(count($res)>0){
                    if($issetCheck)
                        return true;
                    else
                        return json_decode(json_encode($res),true);
                }
                else
                 return false;
            }
            else
                return false;
        } catch (\Throwable $th) {
            return false;
        }
       
    }
    public function getRow($tableName,$where){
        try {
            $sql = "SELECT id FROM $tableName WHERE username = ? AND password = ?";
            $stmt = $this->pdo->prepare($sql);
            if($stmt){
                $stmt->execute($where);
                $res = $stmt->fetchAll();
                if(count($res)>0){
                    return json_decode(json_encode($res),true);
                }
                else
                 return false;
              
            }
            else
                return false;
        } catch (Exception $e) {
            return false;
        }
    }
    public function search($object){
        try {
            $issetCheck = $object['issetCheck']??false;
            $count = $object['count']??false;
            $sql = sprintf("SELECT * FROM %s",$object['tableName']);

            if(isset($object['where'])){
                $columns = array_map(function ($column){return $column." LIKE ?"; }, array_keys($object['where']));
                $columnsStr = implode(" OR ",$columns);
                $where = array_values($object['where']);
                $sql .= sprintf(" WHERE (%s)",$columnsStr);
            }
            if(isset($object['whereNot'])){
                if(isset($object['where'])) 
                    $sql .= " AND ";
                $columnsWn = array_map(function ($column){return $column."=?"; }, array_keys($object['whereNot']));
                $columnsStrWn = implode(" OR ",$columnsWn);
                $whereNot = array_values($object['whereNot']);
                $sql .= sprintf(" NOT (%s)",$columnsStrWn);
                $where = array_merge($where,$whereNot);
            }
            if(isset($object['orderBy']))
                $sql .= sprintf(" ORDER BY %s",$object['orderBy']);
            if(isset($object['asc'])){
                $ascDesc = $object['asc']?'DESC':'ASC';
                $sql .= sprintf(" %s",$ascDesc);
            }
            if(isset($object['limit']))
                $sql .= sprintf(" LIMIT %s",$object['limit']);
            if(isset($object['offset']))
                $sql .= sprintf(" OFFSET %s",$object['offset']);
            if(isset($object['where']) || isset($object['whereNot'])){
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($where);
            }
            else
                $stmt = $this->pdo->query($sql);
            if($stmt){
                $res = $stmt->fetchAll();
                if(count($res)>0){
                    if($issetCheck)
                        return true;
                    else
                    if($count)
                        return count($res);
                    else
                        return json_decode(json_encode($res),true);
                }
                else
                 return false;
            }
            else
                return false;
        } catch (\Throwable $th) {
            return false;
        }
       
     }

    public function update($object){
        try {
            // $sql = "UPDATE posts SET body=:body WHERE id=:id";

            $columns = array_map(function ($column){return $column."=:".$column; }, array_keys($object['data']));
            $columnsStr = implode(" , ",$columns);
            if(isset($object['data']['phone_numbers']))
                $object['data']['phone_numbers'] =  implode("+",$object['data']['phone_numbers']);;

            $whereColumns = array_map(function ($column){return $column."=:".$column; }, array_keys($object['where']));
            $whereStr = implode(" AND ",$whereColumns);

            $exe = array_merge($object['data'],$object['where']);

            $sql = sprintf("UPDATE %s SET %s WHERE %s",$object['tableName'],$columnsStr,$whereStr);
            
            $stmt = $this->pdo->prepare($sql);
            $stmt = $stmt->execute($exe);
            if($stmt)
                return true;
            return false;
       } catch (\Throwable $th) {
            return false;
       }
    }
    public function delete($object){
     try {
        $sql = "DELETE FROM ".$object['tableName']." WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        if($stmt){
            $stmt->execute([$object['id']]);
            return true;
        }
        else return false;
     } catch (\Throwable $th) {
        return false;
     }
      
    }
    static function getInstance()
    {
        if(self::$instance==null)
            self::$instance = new Database();
        return self::$instance;
    }
    public function __destruct(){
        $this->pdo = null;
    }
}
?>