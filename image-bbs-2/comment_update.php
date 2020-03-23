<?php 
session_start();
require_once './core/DbManager.php';

$now = new DateTime(); 
$db_manager = new DbManager();

$update_sql = "UPDATE bbs 
SET name = :name, gender = :gender, comment = :comment, color = :color, category = :category, updated_at = :updated_at 
WHERE id = :id
";
$params = [ 
    ':name'       => $_SESSION['name'],
    ':gender'     => $_SESSION['gender'],
    'comment'     => $_SESSION['comment'],
    ':color'      => $_SESSION['color'],
    ':category'   => implode(',', $_SESSION['category']),
    ':updated_at' => $now->format('Y.m.d. H:i:s'), 
    ':id'         => $_SESSION['comment_id'],
];
$db_manager->execute($update_sql, $params);

$_SESSION = [];
header('Location: http://localhost:20000/image-bbs-2/comment_list.php');
