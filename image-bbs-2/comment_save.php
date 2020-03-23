<?php 
session_start();
require_once './core/DbManager.php';

//エラーがない場合、コメントを保存してコメント一覧ページへリダイレクト
$now = new DateTime();
$db_manager = new DbManager;
$save_sql = "INSERT INTO bbs (`name`, `gender`, `comment`, `created_at`, `color`, `category`, `password`, `image`) VALUES (  
    :name, :gender, :comment, :created_at, :color, :category, :password, :image) 
";
$params = [
    ':name'       => $_SESSION['name'],
    ':gender'     => $_SESSION['gender'],
    ':comment'    => $_SESSION['comment'],
    ':created_at' => $now->format('Y-m-d H:i:s'),
    ':category'   => implode(',', $_SESSION['category']),
    ':color'      => $_SESSION['color'],
    ':password'   => sha1($_SESSION['password']),
    ':image'      => $_SESSION['image'],
];
$db_manager->execute($save_sql, $params);
$_SESSION=[];
header('Location: http://localhost:20000/image-bbs-2/comment_list.php');

