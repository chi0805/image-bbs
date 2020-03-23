<?php 
session_start();

$colors = [
    'red'    => '赤',
    'blue'  => '青',
    'yellow' => '黄色',
    'green'  => '緑',
    'pink'   => 'ピンク',
    'black'  => '黒',
];

$categories = [
    '1' => '料理',
    '2' => '動物',
    '3' => 'スポーツ',
    '4' => 'ゲーム',
    '5' => 'その他',
];

$genders = [
    'male' => '男性',
    'female' => '女性',
    'other' => 'その他',
];

?>
<!DOCTYPE html>

<html>
<head>
    <title>ひとこと掲示板</title>
    <link rel="stylesheet" href="./css/style.css" type="text/css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a class="title" href="./index.php">ひとこと掲示板</a>
        </div>
    </header>

    <div class="body-container">
        <ul class="update-form">
            <li>
                <p>以下のひとことを投稿します</p>
            </li>
            <form class="submit_comment_section" action="<?php echo $action == 'create' ? "./comment_save.php" : "./comment_update.php" ?>" method="post" enctype="multipart/form-data">
                <li>
                    <label for="name">
                        名前:<?php if (isset($_POST['name'])): ?>
                        <?php echo $_POST['name']; ?>
                        <?php else: ?>
                        <?php echo $_SESSION['name']; ?>
                        <?php endif; ?>
                    </label><br/>
                </li>
                <li>
                    <label>
                        性別:<?php echo $genders[$_SESSION['gender']]; ?>
                    </label><br/>
                </li>
                <li>
                    <img id="comment_image" src="<?php echo $_SESSION['image']; ?>"><br/>
                </li>
                <li>
                    <label id="comment">ひとこと:<br/>
                        <font color="<?php echo $_SESSION['color']; ?>"> 
                            <p><?php echo $_SESSION['comment']; ?></p>
                        </font>
                    </label><br/>
                </li>
                <li>
                    <label for="category">
                        カテゴリ:<br/>
                        <?php foreach ($_POST['category'] as $num_category): ?>
                        <?php echo $categories[$num_category]; ?>
                        <?php endforeach ?> 
                    </label><br/>
                    <input class="submit_button" type="submit" value="つぶやく">
                    <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                </li>
            </form>
        </ul>
    </div>
<?php $_SESSION['errors'] = []; ?>
</body>
</html>
