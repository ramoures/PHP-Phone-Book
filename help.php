<?php
$host='localhost';
$user='root';
$password='';
$dbname='pdoposts';
//set DSN
$dsn = "mysql:host=".$host.";dbname=".$dbname;

//create PDO instance
$pdo = new PDO($dsn, $user, $password);
$pdo->exec("set names utf8mb4");
//Set default fot fetch
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
//diabled PDO emulate:
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

//PDO Query
// $stmt = $pdo->query('SELECT * FROM posts');
// // while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
// //     print $row['title'] . '<br>';
// // }
// // while($row = $stmt->fetch(PDO::FETCH_OBJ)){
// //     print $row->title . '<br>';
// // } 
// while($row = $stmt->fetch()){
//     print $row->title . '<br>';
// }

// PREPARED STATEMENT (prepared and execute);
//unsafe
// $sql = "SELECT * FROM posts WHERE = $ahthur";

//User input
$author = 'ramin';
$is_pubished = 1;
$id=1;
$limit=2;
    
//FETCH MULTIPLE POSTS

//Positional Params:
// $sql = 'SELECT * FROM posts WHERE author = ? && is_published = ? LIMIT ?';
// $stmt = $pdo->prepare($sql);
// $stmt->execute([$author,$is_pubished,$limit]);
// $posts = $stmt->fetchAll();
// foreach($posts as $post){
//     print $post->title."<br>";
// }
// Named Params:
// $sql = "SELECT * FROM posts WHERE author = :author && is_published = :is_published";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['author'=>$author,'is_published'=>$is_pubished]);
// $posts = $stmt->fetchAll();
// foreach($posts as $post){
//     print $post->title."<br>";
// }


//FETCH SINGLE POST

// $sql = "SELECT * FROM posts WHERE id = :id";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['id'=>$id]);
// $post = $stmt->fetch();
// print "<h1>".$post->title."</h1>";

//GET ROW COUNT
// $sql = "SELECT * FROM posts WHERE author = ?";
// $stmt = $pdo->prepare($sql);
// $stmt->execute([$author]);
// $postCount = $stmt->rowCount();
// print $postCount;

//INSERT DATA
// $title = 'POST 5';
// $body = 'this is POST 5';
// $author = 'amir';

// $sql = "INSERT INTO posts(title, body, author) VALUES(:title, :body, :author)";
// $stmt = $pdo->prepare($sql);
// $stmt = $stmt->execute(['title'=>$title,'body'=>$body,'author'=>$author]);
// if($stmt)
//     print "POST Added";

//UPDATE DATA
// $id = 5;
// $body = 'this is UPDATED post';

// $sql = "UPDATE posts SET body=:body WHERE id=:id";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['body'=>$body,'id'=>$id]);
// if($stmt)
//     print "POST UPDATED";

//DELETE DATA
// $id = 5;

// $sql = "DELETE FROM posts WHERE id=:id";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['id'=>$id]);
// if($stmt)
//     print "POST DELETED";


//SEARCH DATA
// $search = '%test%';

// $sql = "SELECT * FROM posts WHERE title LIKE :search OR body LIKE :search";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['search'=>$search]);
// $posts = $stmt->fetchAll();
// print_r(json_encode($posts,true));
// // foreach($posts as $post){
// //     print $post->title;
// // }

$sql = 'SELECT * FROM posts WHERE author = ? && is_published = ? LIMIT ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$author,$is_pubished,$limit]);
$posts = $stmt->fetchAll();
foreach($posts as $post){
    print $post->title."<br>";
}
?>