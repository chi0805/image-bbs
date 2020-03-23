<?php 
session_start();
require_once './core/DbManager.php';

$_SESSION['comment_id'] = $_POST['id'];
if (isset($_POST['update_password'])) {
    $input_pass_hash = sha1($_POST['update_password']);
    if ($input_pass_hash === $_SESSION['_'.$_SESSION['comment_id']]['password']) {
        header('Location: http://localhost:20000/image-bbs-2/view/comment_update_view.php');
    } else {
        $_SESSION['error'] = 'パスワードが間違っています';
        header('Location: http://localhost:20000/image-bbs-2/comment_list.php');
    }
}

if(isset($_POST['delete_password'])) {
    $input_pass_hash = sha1($_POST['delete_password']);
    if ($input_pass_hash === $_SESSION['_'.$_SESSION['comment_id']]['password']) {
        header('Location: http://localhost:20000/image-bbs-2/view/comment_delete_view.php');
    } else {
        $_SESSION['error'] = 'パスワードが間違っています';
        header('Location: http://localhost:20000/image-bbs-2/comment_list.php');
    }
}

