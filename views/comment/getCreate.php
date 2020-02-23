<?php $this->setLayoutVar('title', 'home') ?>
<h2>HOME</h2>

<form class="submit" name="create" action="./confirm" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
    <input type="hidden" name="action" value="create">
    <ul class="input">
        <li>
            <label id="comment">
                <span class="save-item">
                    ひとこと
                </span>
                <span class="save-rule">
                    (200文字以内)
                </span>
            </label><br/>
            <textarea id="comment" name="comment"><?php if (!empty($comment)) echo $comment; ?></textarea><br/></textarea><br/>
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
                    <input id="<?php echo $color_en ?>" type="radio" name="color" value="<?php echo $color_en ?>" <?php if (!empty($color) && $color_en === $color) echo "checked"; ?>><?php echo $color_jp ?>
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
            <?php foreach ($categories as $category_name): ?>
            <label>
                <input type="checkbox" name="category[]" value="<?php echo array_search($category_name, $categories); ?>" <?php if (!empty($category) && in_array(array_search($category_name, $categories), $category)) echo "checked"; ?>><?php echo $category_name; ?>
            </label>
            <?php endforeach; ?>
        </li>
        <li>
            <?php if (!empty($errors) && is_array($errors)): ?>
                <?php foreach ($errors as $error_key => $error_value): ?>
                    <div class='error'>
                        <?php echo htmlspecialchars($error_value); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </li>
        <li>
            <input id="submit_button" type="submit" name="submit" value="つぶやく">
        </li>
    </ul>
</form>
