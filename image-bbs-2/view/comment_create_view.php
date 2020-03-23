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
?>

<!DOCTYPE html>

<html>
<head>
    <title>ひとこと掲示板</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/reset.css" type="text/css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a class="title" href="./index.php">ひとこと掲示板</a>
        </div>
    </header>
    
    <div class="body-container">
        <form class="submit" name="create" action="./comment_confirm.php" method="post" enctype="multipart/form-data">
            <ul class="input">
                <li>
                    <label for="name">
                        <span class="save-item">
                            名前
                        </span>
                        <span class="save-rule">
                            (40文字以内)
                        </span>
                    </label><br/>
                    <input id="name" type="text" name="name" value="<?php echo $_SESSION['name']; ?>"><br/>
                </li>
                <li>
                    <label>
                        <span class="save-item">
                            性別
                        </span>
                        <span class="save-rule">
                            (必須)
                        </span>
                    </label><br/>
                    <label for="male">
                        <input id="male" type="radio" name="gender" value="male" <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] =="male") echo 'checked'?>>男性
                    </label>
                    <label for="female">
                        <input id="female" type="radio" name="gender" value="female" <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] =="female") echo 'checked'?>>女性
                    </label>
                    <label for="other">
                        <input id="other" type="radio" name="gender" value="other" <?php if (isset($_SESSION['gender']) && $_SESSION['gender'] =="other") echo 'checked'?>>その他
                    </label><br/>
                </li>
                <li>
                    <label id="comment">
                        <span class="save-item">
                            ひとこと
                        </span>
                        <span class="save-rule">
                            (200文字以内)
                        </span>
                    </label><br/>
                    <textarea id="comment" name="comment"><?php echo $_SESSION['comment']; ?></textarea><br/>
                </li>
                <li>
                    <label id="color">
                        <span class="save-item">
                            色
                        </span>
                        <span class="save-rule">
                            (必須)
                        </span>
                    </label><br/>
                    <?php foreach($colors as $color_en => $color_jp): ?>
                        <label for="<?php echo $color_en ?>">
                            <input id="<?php echo $color_en ?>" type="radio" name="color" value="<?php echo $color_en ?>" <?php if (isset($_SESSION['color']) && $_SESSION['color'] == $color_en) echo 'checked'?>><?php echo $color_jp ?>
                        </label>
                    <?php endforeach; ?><br/>
                <li>
                    <label id="image">
                        <span class="save-item">
                            画像
                        </span>
                        <span class="save-rule">
                            (必須)
                        </span>
                    </label><br/>
                    <input class="select_image" type="file" name="image"><br/>
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
                    <?php foreach ($categories as $num => $category): ?>
                    <label>
                        <input type="checkbox" name="category[]" value="<?php echo $num; ?>" <?php if (isset($_SESSION['category']) && in_array($num, $_SESSION['category'])) echo 'checked'; ?>><?php echo $category; ?>
                    </label>
                    <?php endforeach; ?>
                </li>
                <li>
                    <label id="password">
                        <span class="save-item">
                            パスワード
                        </span>
                        <span class="save-rule">
                            (8〜20字以内)
                        </span>
                    </label><br/>
                    <input id="password" type="password" name="password" value="<?php echo $_SESSION['password']?>"><br/>
                </li>
                <li>
                    <?php if (is_countable($_SESSION['errors'])): ?>
                        <?php foreach ($_SESSION['errors'] as $error_key => $error_value): ?>
                            <div class='error'>
                                <?php echo htmlspecialchars($error_value); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php $_SESSION = []; ?>
                </li>
                <li>
                    <input id="submit_button" type="submit" name="submit" value="つぶやく">
                </li>
            </ul>
        </form>
    </div>
</body>
</html>
