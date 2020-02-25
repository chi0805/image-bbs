
<?php $this->setLayoutVar('title', 'home') ?>

<h2>HOME</h2>

<a href="/account/signin">SIGN IN</a>
<a href="/account/signup">SIGN UP</a>


<ul class="sort">
    <li class="sort-rule"><a href="./home?sort=DESC&page=<?php echo $page; ?>">新しい順</a></li>
    <li class="sort-rule"><a href="./home?sort=ASC&page=<?php echo $page; ?>">古い順</a></li>
</ul>
<div class="category-select">
    <p>カテゴリー</p>
    <form class="category-select" method="get" action="./home">
    <?php foreach ($categories as $num => $category): ?>
    <label>
        <input type="checkbox" name="select_categories[]" value="<?php echo $num; ?>" <?php if (in_array($num, $select_categories)) echo 'checked'; ?> ><?php echo $category; ?>
    </label>
    <?php endforeach; ?>
    <input type="submit" value="選択">
    </form>
</div>
<ul class="comment_list">
    <?php $i = 0; ?>
    <?php while($i < 10 && isset($select_results[10 * ($page - 1) + $i])): ?>
    <?php $result = $select_results[10 * ($page - 1) + $i]; ?>
    <li class="comment">
        <div class="comment-section">
            <p class="name"><?php echo $this->escape($result['user_name']); ?></p>
            <p class="category"><?php echo $this->escape($result['category']); ?></p>
            <p class="image"><img src="<?php echo $result['image']; ?>"></p>
            <font color="<?php echo $result['color'] ?>">
                <p class="comment"><?php echo nl2br($this->escape($result['comment'])); ?></p>
            </font>
            <p class="datetime"><?php echo $result['created_at']; ?></p>
        </div>
    </li>
    <?php $i++; ?>
    <?php endwhile; ?>
</ul>
<div class="page-navi">
    <a href="./home?sort=<?php echo $sort; ?>&page=1&<?php foreach($select_categories as $select_category) echo "&category%5B%5D=".$select_category; ?>"
        class=<?php if ($page === 1) echo 'no-link'; ?>>最初のページ</a>
    <a href="./home?sort=<?php echo $sort; ?>&page=<?php echo $page - 1; ?>"
        class="<?php if ($page === 1) echo 'no-link'; ?>">前のページ</a>
    <a href="./home?sort=<?php echo $sort; ?>&page=<?php echo $page + 1; ?>"
        class="<?php if ($page == $last_page) echo 'no-link'; ?>">次のページ</a>
    <a href="./home?sort=<?php echo $sort; ?>&page=<?php echo $last_page; ?>"
        class="<?php if ($page == $last_page) echo 'no-link'; ?>">最後のページ</a>
</div>
