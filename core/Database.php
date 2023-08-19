<?php
final class Database{
    use errors;
    private static $instance = null;
    private $host=DB_HOST;
    private $user=DB_USER;
    private $password=DB_PASWORD;
    private $dbname=DB_NAME;
    private $pdo = null;

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
    public function create($object){

    }
    public function read($object){
        
    }  
    public function search(){
       try {
        $sql = 'SELECT * FROM posts WHERE author = ? && is_published = ? LIMIT ?';
        $stmt = $this->pdo->prepare($sql);
        if($stmt){
            $stmt->execute(['ramin','1',10]);
            $res = $stmt->fetchAll();
            $res = json_encode($res,true);
            Render('index',$res);
            // foreach($posts as $post){
            //     print $post->title."<br>";
            // }
        }
       } catch (Exception $e) {
            $this->error($e);
        }

    }
    public function update($object){
        
    }
    public function delete($object){
        //DELETE DATA
        $id = 5;
        $sql = "DELETE FROM posts WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        if($stmt)
            print "POST DELETED";
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