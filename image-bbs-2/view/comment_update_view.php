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

$id = '_'.$_SESSION['comment_id'];
$name = $_SESSION[$id]['name'];
$gender = $_SESSION[$id]['gender'];
$image = $_SESSION[$id]['image']; 
$comment = $_SESSION[$id]['comment'];
$color = $_SESSION[$id]['color'];
$category =explode(',', $_SESSION[$id]['category']); //配列
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
        <ul class="update-form">
            <li>
                <p>以下のひとことを編集します</p>
            </li>
                <form class="update_comment_section" action="../comment_confirm.php" method="post" enctype="multipart/form-data">
                <li>
                    <label for="name">名前</label><br/>
                    <input id="name" type="text" name="name" value="<?php echo $name; ?>"><br/>
                </li>
                <li>
                    <label>性別</label><br/>
                        <label for="male">
                            <input id="male" type="radio" name="gender" value="male" <?php if ($gender =="male") echo 'checked'?>>男性
                        </label>
                        <label for="female">
                            <input id="female" type="radio" name="gender" value="female" <?php if ($gender =="female") echo 'checked'?>>女性
                        </label>
                        <label for="other">
                            <input id="other" type="radio" name="gender" value="other" <?php if ($gender =="other") echo 'checked'?>>その他
                        </label><br/>
                </li>
                <li>
                    <img id="comment_image" src="<?php echo '../'.$image ?>"><br/>
                </li>
                <li>
                    <label id="comment">ひとこと<br/>
                        <textarea id="comment" name="comment"><?php echo $comment; ?></textarea>
                    </label><br/>
                </li>
                <li>
                    <label id="color">色<br/>
                        <?php foreach($colors as $color_en => $color_jp): ?>
                            <label for="<?php echo $color_en ?>">
                                <input id="<?php echo $color_en ?>" type="radio" name="color" value="<?php echo $color_en ?>" <?php if ($color = $color_en) echo 'checked'?>>
                                <?php echo $color_jp ?>
                            </label>
                        <?php endforeach; ?>
                    </label><br/>
                </li>
                <li>
                    <label for="category">
                        <span class="save-item">
                            カテゴリ
                        </span>
                        <span class="save-rule">
                            (3つまで選択)
                        </span>
                    </label><br/>
                    <?php foreach ($categories as $num => $category_name): ?>
                    <label>
                        <input type="checkbox" name="category[]" value="<?php echo $num; ?>"<?php if (in_array($num, $category)) echo 'checked'?>><?php echo $category_name; ?>
                    </label>
                    <?php endforeach; ?>
                </li>
                <li class="error">
                    <?php if (!empty($_SESSION['errors'])): ?>
                        <?php foreach ($_SESSION['errors'] as $error_key => $error_value): ?>
                            <div class='error'>
                                <?php echo htmlspecialchars($error_value); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </li>
                <input class="update_button" type="submit" value="編集">
                <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
            </form>
        </ul>
    </div>
<?php $_SESSION['error'] = ''; ?>
</body>
</html>
