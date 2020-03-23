<?php 
session_start();
$categories = [
    '1' => '料理',
    '2' => '動物',
    '3' => 'スポーツ',
    '4' => 'ゲーム',
    '5' => 'その他',
];

if (!isset($_GET['category'])) {
    $_GET['category'] = array_keys($categories);
}
$_SESSION['select_categories'] = $_GET['category'];

//カテゴリの絞り込み
foreach ($results as $result){
    //コメントのカテゴリ（数字表記)を配列にする
    $category_results = explode(',', $result['category']);
    //コメントのカテゴリに絞り込みで選択したカテゴリが存在する場合、表示する
    if (array_intersect($category_results, $_GET['category'])) {
        $select_results[] = $result;
    }
}

if (!empty($select_results)) {
    $last_page = (int)ceil(count($select_results)/10);
} else {
    $last_page = 1;
}

if (!isset($_GET['page'])) {
    $_GET['page'] = 1;
}

?>

<!DOCTYPE html>

<html>
<head>
    <title>ひとこと掲示板</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/reset.css" type="text/css">
</script>

</head>
<body>
    <header class="header">
        <div class="header-container">
            <a class="title" href="./index.php">ひとこと掲示板</a>
            <a class="tweet-button" href="./comment_create.php">つぶやく</a>
        </div>
    </header>

    <div class="body-container">
        <ul class="sort">
            <li id="sort_title">
            並び替え</li><li id="sort_rule">
            <a href="./comment_list.php?sort=DESC<?php foreach($_SESSION['select_categories'] as $select_category) echo "&category%5B%5D=".$select_category; ?>">新しい順</a></li><li id="sort_rule">
            <a href="./comment_list.php?sort=ASC<?php foreach($_SESSION['select_categories'] as $select_category) echo "&category%5B%5D=".$select_category; ?>">古い順</a></li>
        </ul><br/>
        <div class="category-select">
            <p>カテゴリー</p>
            <form class="category-select" method="get" action="./comment_list.php">
                    <?php foreach ($categories as $num => $category): ?>
                    <label>
                        <input type="checkbox" name="category[]" value="<?php echo $num; ?>" <?php if (in_array($num, $_GET['category'])) echo 'checked'; ?> ><?php echo $category; ?>
                    </label>
                    <?php endforeach; ?>
                    <input type="submit" value="選択">
            </form>
        </div>
        <ul class="comment_list">
            <?php if (!empty($select_results)): ?> 
                <?php $i = 0; ?>
                <?php while($i < 10 && isset($select_results[10 * ($_GET['page'] - 1) + $i])): ?>
                    <?php 
                    $result = $select_results[10 * ($_GET['page'] - 1) + $i];
                    $week = array( "日", "月", "火", "水", "木", "金", "土" );
                    $created_at = date('Y年m月d日(', strtotime($result['created_at'])).$week[date('w', strtotime($result['created_at']))].date(')H時i分', strtotime($result['created_at']));
                    ?>
                    <li class="comment" id="<?php if (isset($_SESSION['comment_id']) && isset($_SESSION['error']) && $_SESSION['comment_id'] == $result['id']) echo 'error_comment'; ?>">
                        <div class="comment_section">
                            <p class="user_info"><?php echo htmlspecialchars($result['name'].' '.$gender[$result['gender']], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="category">
                                カテゴリ：<?php foreach (explode(',', $result['category']) as $category_result):?>
                                <?php echo htmlspecialchars($categories[$category_result], ENT_QUOTES, 'UTF-8'); ?>
                                <?php endforeach; ?>
                            </p>
                            <p><img class="comment_image" src="<?php echo $result['image'] ?>"></p>
                            <font color="<?php echo $result['color'] ?>">
                                <p class="comment_body"><?php echo nl2br(htmlspecialchars($result['comment'], ENT_QUOTES, 'UTF-8')); ?></p>
                            </font>
                            <p class="datetime"><?php echo htmlspecialchars($created_at, ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="edit">
                                <form class="input_password" method="POST" action="./comment_pass_check.php">
                                    <details class="update">
                                        <summary>編集</summary>
                                        <p class=password>パスワード</p>
                                        <input id="password" type="password" name="update_password" value="<?php echo $_POST['password'] ?>"/>
                                        <input class="button" type="submit" name="submit" value="編集" />
                                        <input type="hidden" name="id" value=<?php echo $result['id']; ?> />
                                    </details>
                                </form>
                                <form class="input_password" method="POST" action="./comment_pass_check.php">
                                    <details class="delete">
                                        <summary>削除</summary>
                                        <p class=password>パスワード</p>
                                        <input id="password" type="password" name="delete_password" value="<?php echo $_POST['password'] ?>"/><br/>
                                        <input class="button" type="submit" name="submit" value="削除" />
                                        <input type="hidden" name="id" value=<?php echo $result['id']; ?> />
                                    </details><br/>
                                </form>
                            </div>
                            <div>
                            <?php if (isset($_SESSION['comment_id']) && isset($_SESSION['error'])): ?>
                                <?php if ($_SESSION['comment_id'] == $result['id']): ?> 
                                    <p class="password_error"><?php echo $_SESSION['error']; ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    
                    <?php    $_SESSION['_' . $result['id']] = [
                            'name'   => $result['name'],
                            'gender' => $result['gender'],
                            'image'  => $result['image'],
                            'comment' => $result['comment'],
                            'color' => $result['color'],
                            'category' => $result['category'],
                            'created_at' => $created_at,
                            'password' => $result['password'],
                        ];
                    ?>
                    <?php $i++; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>該当するひとことがありません</p>
            <?php endif; ?>
        </ul>
        <div class="page-navi">
            <a href="./comment_list.php?sort=<?php echo $sort; ?>&page=1&<?php foreach($_SESSION['select_categories'] as $select_category) echo "&category%5B%5D=".$select_category; ?>"
                class=<?php if ($_GET['page'] == 1) echo 'no-link'; ?>>最初のページ</a>
            <a href="./comment_list.php?sort=<?php echo $sort; ?>&page=<?php echo $page - 1; ?><?php foreach($_SESSION['select_categories'] as $select_category) echo "&category%5B%5D=".$select_category; ?>"
                class="<?php if ($_GET['page'] == 1) echo 'no-link'; ?>">前のページ</a>
            <a href="./comment_list.php?sort=<?php echo $sort; ?>&page=<?php echo $page + 1; ?><?php foreach($_SESSION['select_categories'] as $select_category) echo "&category%5B%5D=".$select_category; ?>"
                class="<?php if ($_GET['page'] == $last_page) echo 'no-link'; ?>">次のページ</a>
            <a href="./comment_list.php?sort=<?php echo $sort; ?>&page=<?php echo $last_page; ?>"<?php foreach($_SESSION['select_categories'] as $select_category) echo "&category%5B%5D=".$select_category; ?>
                class="<?php if ($_GET['page'] == $last_page) echo 'no-link'; ?>">最後のページ</a>
        </div>
    </div>

<script>
document.getElementById("error_comment").scrollIntoView(true);
</script>
</body>
</html>

