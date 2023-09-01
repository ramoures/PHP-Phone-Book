<?php
/**
 * (MYSQL - PDO - PREPARED STATEMENT) C.R.U.D
 * @Author: ramin moradi . github.com/ramoures
 * @Version: 1.0
 * @Email: ramoures@gmail.com
 * @License: MIT
*/
final class Database{
    use errors;
    private static $instance;
    private $host=DB_HOST;
    private $user=DB_USER;
    private $password=DB_PASWORD;
    private $dbname=DB_NAME;
    private $pdo;
    public static $n;
    private function __construct(){
        try {
            $dsn = "mysql:host=".$this->host.";dbname=".$this->dbname;
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            if($this->pdo){
                $this->pdo->exec("set names utf8mb4");
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }
        } catch (Exception $e) {
            $this->error($e);
        }
    }
    static function getInstance(){
        try {
            if(self::$instance==null)
                self::$instance = new Database();
            return self::$instance;
        } catch (\Throwable $th) {
            self::error($th);
        }
    }
    public function create($object){
       try {
            if(!isset($object) || !isset($object['tableName']))
                return $this->error('"tableName" is not defined.');
            $columns = array_map(function ($column){return ":".$column; }, array_keys($object['data']));
            $columnsStr = implode(", ",$columns);
            $rows = implode(",",array_keys($object['data']));
            $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)",TABLE_PREFIX.$object['tableName'],$rows,$columnsStr);
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
            if(!isset($object) || !isset($object['tableName']))
                return $this->error('"tableName" is not defined.');
            $andOr = " AND ";
            $issetCheck = $object['issetCheck']??false;
            $count = $object['count']??false;
            $selector = isset($object['selector'])?implode(', ',$object['selector']):'*';
            $search = isset($object['search']) && $object['search']?true:false;
            if($search)
                $andOr = " OR ";
            $sql = sprintf("SELECT $selector FROM %s",TABLE_PREFIX.$object['tableName']);
            if(isset($object['where'])){
                if($search)
                    $columns = array_map(function ($column){return $column." LIKE ?"; }, array_keys($object['where']));
                else
                    $columns = array_map(function ($column){return $column."=?"; }, array_keys($object['where']));
                $columnsStr = implode($andOr,$columns);
                $where = array_values($object['where']);
                $sql .= sprintf(" WHERE (%s)",$columnsStr);
            }
            if(isset($object['whereNot'])){
                if(isset($object['where'])) 
                    $sql .= " AND ";
                $columnsWn = array_map(function ($column){return $column."=?"; }, array_keys($object['whereNot']));
                $columnsStrWn = implode($andOr,$columnsWn);
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
            if(!isset($object) || !isset($object['tableName']))
                return $this->error('"tableName" is not defined.');
            $columns = array_map(function ($column){return $column."=:".$column; }, array_keys($object['data']));
            $columnsStr = implode(" , ",$columns);
            $whereColumns = array_map(function ($column){return $column."=:".$column; }, array_keys($object['where']));
            $whereStr = implode(" AND ",$whereColumns);
            $exe = array_merge($object['data'],$object['where']);
            $sql = sprintf("UPDATE %s SET %s WHERE %s",TABLE_PREFIX.$object['tableName'],$columnsStr,$whereStr);
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
        if(!isset($object) || !isset($object['tableName']))
                return $this->error('"tableName" is not defined.');
        $sql = sprintf("DELETE FROM %s WHERE id = ?",TABLE_PREFIX.$object['tableName']);
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
    public function __destruct(){
        $this->pdo = null;
    }
}
?>