<?php 

$colors = [
    'red'    => '赤',
    'blue'  => '青',
    'yellow' => '黄色',
    'green'  => '緑',
    'pink'   => 'ピンク',
    'black'  => '黒',
];

$categories = ['料理','動物','スポーツ','ゲーム','その他',];

$genders = [
    'male' => '男性',
    'female' => '女性',
    'other' => 'その他',
];

?>
<?php $this->setLayoutVar('title', 'home') ?>

<p>以下のひとことを編集します</p>
<form class="submit" name="edit" action="../confirm" method="post" enctype="multipart/form-data">
    <ul class="input">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="comment_id" value="<?php echo $this->escape($comment_id); ?>">
        <input type="hidden" name="image" value="<?php echo $this->escape($image); ?>">
        <li>
            <img id="comment_image" src="<?php echo '../../' . $image; ?>"><br/>
        </li>
        <li>
            <label id="comment">
                <span class="edit-item">
                    ひとこと
                </span>
                <span class="save-rule">
                    (200文字以内)
                </span>
            </label><br/>
            <textarea id="comment" name="comment"><?php echo $comment; ?></textarea><br/>
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
                    <input id="<?php echo $color_en ?>" type="radio" name="color" value="<?php echo $color_en ?>" <?php if ($color === $color_en) echo "checked" ?>><?php echo $color_jp ?>
                </label>
            <?php endforeach; ?><br/>
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
            <?php foreach ($categories as $category_name): ?>
            <label>
                <input type="checkbox" name="category[]" value="<?php echo array_search($category_name, $categories); ?>" <?php if (in_array(array_search($category_name, $categories), $category)) echo "checked"; ?>><?php echo $category_name; ?>
            </label>
            <?php endforeach; ?>
        </li>
        <li>
            <?php if (isset($errors) && is_countable($errors)): ?>
                <?php foreach ($errors as $error_key => $error_value): ?>
                    <div class='error'>
                        <?php echo htmlspecialchars($error_value); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </li>
        <li>    
            <input class="submit_button" type="submit" value="つぶやく">
        </li>
    </ul>
</form>
