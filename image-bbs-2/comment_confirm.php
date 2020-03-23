<?php 
session_start();

$errors = [];

$_SESSION['name'] = $_POST['name'];
$_SESSION['gender'] = $_POST['gender'];
$_SESSION['comment'] = $_POST['comment'];
$_SESSION['color'] = $_POST['color'];
$_SESSION['category'] = $_POST['category'];
$_SESSION['password'] = $_POST['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //名前のエラー処理
    if (!isset($_POST['name']) || !mb_strlen($_POST['name'])) {
        $errors['name'] = '名前を入力してください';    
    } elseif (mb_strlen($_POST['name']) > 40) {
        $errors['name'] = '名前は40文字以内で入力してください';
    }

    //性別のエラー処理
    if (!isset($_POST['gender'])) {
        $errors['gender'] = '性別を選択してください';
    }

    //コメント内容のエラー処理
    if (!isset($_POST['comment']) || !mb_strlen($_POST['comment'])) {
        $errors['comment'] = 'コメントを入力してください';
    } elseif (mb_strlen($_POST['comment']) > 200) {
        $errors['comment'] = 'コメントは200文字以内で入力してください';
    }

    //色のエラー処理
    if (!isset($_POST['color'])) {
        $errors['color'] = '色を選択してください';
    }

    //カテゴリのエラー処理、問題なければDB保存用の形式にする
    if (empty($_POST['category'])) {
        $errors['category'] = 'カテゴリを選択してください';
    } elseif (count($_POST['category']) > 3) {
        $errors['category'] = 'カテゴリは3つまで選択できます';
    } else {
        $_SESSION['category'] = $_POST['category'];
    }

    //コメント新規作成時処理
    if (isset($_FILES['image']) && isset($_POST['password'])) {
        //画像のエラー処理と保存処理
        $file_name = uniqid() . '-' . $_FILES['image']['name'];
        $file_path = './images/' . $file_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $file_path);

        $_SESSION['image'] = $file_path;
        $_SESSION['password'] = $_POST['password'];

        switch($_FILES['image']['error']) {
            case 0:
                $ext = getimagesize($file_path)['mime'];
                $ext = str_replace('image/', '', $ext);
                if (!in_array($ext, ['png', 'PNG', 'jpg', 'JPG', 'gif'])) {
                    $errors['image'] = '画像の形式が間違っています';
                }
                break;
            case 1:
            case 2:
                $errors['image'] = '画像サイズが大きすぎます';
                break;
            case 4:
                $errors['image'] = '画像を選択してください';
                break;
        }
    
        if (!mb_strlen($_POST['password'])) {
            $errors['password'] = "パスワードを入力してください";
        } elseif (mb_strlen($_POST['password']) < 8 || mb_strlen($_POST['password']) > 20){
            $errors['password'] = "パスワードは8〜20文字以下にしてください";
        }

        if (empty($errors)) {
            $action = 'create';
            include './view/comment_confirm_view.php';
        } else {
            $_SESSION['errors'] = $errors;
            header('Location: ./comment_create.php');
        }

    //コメント編集時処理
    } else {
        if (empty($errors)) {
            $_SESSION['image'] = $_SESSION['_'.$_SESSION['comment_id']]['image'];
            $action = 'update';
            include './view/comment_confirm_view.php';
        } else {
            $_SESSION['errors'] = $errors;
            header('Location: ./view/comment_update_view.php');
        }
    }
}
