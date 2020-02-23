<?php $this->setLayoutVar('title', 'home') ?>

<h2>HOME</h2>

<a href="./comment/create">つぶやく</a>
<form class="delete-button" name="logout" action="../account/signout/post" method="post">
    <a href="javascript:logout.submit()">logout</a>
</form>

<ul class="sort">
    <li class="sort-rule"><a href="./home?sort=DESC">新しい順</a></li>
    <li class="sort-rule"><a href="./home?sort=ASC">古い順</a></li>
</ul>

<ul class="comment_list">
    <?php foreach ($results as $result): ?>
    <li class="comment">
        <div class="comment-section">
            <p class="name"><?php echo $this->escape($user_name); ?></p>
            <p class="category"><?php echo $this->escape($result['category']); ?></p>
            <p class="image"><img src="<?php echo $result['image']; ?>"></p>
            <font color="<?php echo $result['color'] ?>">
                <p class="comment"><?php echo $this->escape($result['comment']); ?></p>
            </font>
            <p class="datetime"><?php echo $result['created_at']; ?></p>
        </div>
        <div class="delete">
        <form class="edit-button" action="./comment/edit/<?php echo $result['id']; ?>" method="get">
                <input type="submit"  value="編集">
                <input type="hidden" name="comment_id" value="<?php echo $result['id']; ?>">
            </form>
            <form class="delete-button" action="./comment/delete/post" method="post">
                <input type="submit" name="delete" value="削除">
                <input type="hidden" name="comment_id" value="<?php echo $result['id']; ?>">
                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
            </form>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
