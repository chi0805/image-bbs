<?php
require_once './core/DbManager.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $db_manager = new DbManager();
    
    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
    } else {
        $sort = 'DESC';
    }

    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
    } else {
        $page = 1;
    }

    $start = $page * 10 - 10;

    $select_sql = "SELECT id,name,comment,gender,color,category,image,password,created_at FROM `bbs` WHERE is_deleted = false ORDER BY `created_at` $sort 
    ";
 
    $results = $db_manager->fetchAll($select_sql);
    $gender = [
        'male' => '男性',
        'female' => '女性',
        'other' => 'その他',
    ];
} else {
    http_response_code(404);
    include_once('404.php');
}

include './view/comment_list_view.php';


