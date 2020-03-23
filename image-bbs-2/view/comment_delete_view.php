<?php 
session_start();
$genders = [
    'male' => '男性',
    'female' => '女性',
    'other' => 'その他',
];

$id = '_'.$_SESSION['comment_id'];
$name = $_SESSION[$id]['name'];
$gender = $_SESSION[$id]['gender'];
$image = $_SESSION[$id]['image']; 
$comment = $_SESSION[$id]['comment'];
$color = $_SESSION[$id]['color'];
$category =$_SESSION[$id]['category'];
$created_at =$_SESSION[$id]['created_at'];

?>
<!DOCTYPE html>

<html>
<head>
    <title>ひとこと掲示板</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a class="title" href="../index.php">ひとこと掲示板</a>
        </div>
    </header>

    <div class="body-container">
        <ul class="delete-form">
            <li>
                <p>以下のひとことを削除します</p>
            </li>
            <li>
                <p id="user_info"><?php echo htmlspecialchars($name.' '.$genders[$gender], ENT_QUOTES, 'UTF-8'); ?><br/></p>
            </li>
            <li>
                <img id="comment_image" src="<?php echo '../'.$image ?>"><br/>
            </li>
            <li>
                <font color="<?php echo $color ?>">
                    <div id="comment"><?php echo nl2br(htmlspecialchars($comment, ENT_QUOTES, 'UTF-8')); ?><br/></div>
                </font>
            </li>
            <li>
                <p id="datetime"><?php echo htmlspecialchars($created_at, ENT_QUOTES, 'UTF-8'); ?></p><br/>
            </li>
            <li>
                <form class="submit" onsubmit="return confirm_delete()" action="../comment_delete.php" method="post" enctype="multipart/form-data">
                    <input class="delete_button" type="submit" value="削除">
                    <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                </form>
            </li>
        </ul>
    </div>
<script>
function confirm_delete() {
    var select = confirm("削除しますか?");
    return select;
}
</script>
<?php $_SESSION['error'] = ''; ?>
</body>
</html>

