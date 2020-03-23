<?php 
session_start();
require_once './core/DbManager.php';

$now = new DateTime();
$db_manager = new DbManager();

$delete_sql = "UPDATE bbs 
SET is_deleted = true, deleted_at = :deleted_at 
WHERE id = :id
";

$params = [ 
    ':deleted_at' => $now->format('Y-m-d H:i:s'),
    ':id' => $_SESSION['comment_id'],
];
$db_manager->execute($delete_sql, $params);

$_SESSION = [];
header('Location: http://localhost:20000/image-bbs-2/comment_list.php');


