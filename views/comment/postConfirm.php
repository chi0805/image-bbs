<?php $this->setLayoutVar('title', 'home') ?>

<ul class="update-form">
    <li>
        <p>以下のひとことを投稿します</p>
    </li>
    <form class="submit_comment_section" action="./save/post" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
    <input type="hidden" name="action" value="<?php echo $this->escape($action); ?>">
    <input type="hidden" name="image" value="<?php echo $this->escape($image); ?>">
    <input type="hidden" name="comment_id" value="<?php echo $this->escape($comment_id); ?>">
        <li>
            <img id="comment_image" src="<?php echo $image; ?>"><br/>
        </li>
        <li>
            <label id="comment">ひとこと:<br/>
                <font color="<?php echo $color; ?>"> 
                <textarea id="comment" type="text" name="comment" readonly><?php echo $comment; ?></textarea>
                </font>
            </label><br/>
        </li>
        <li>
            <label id="color" for="">
                <span class="save-item">
                    色
                </span><br/>
                <?php foreach ($colors as $color_en => $color_jp): ?>
                <label for="<?php echo $color_en ?>">
                    <input type="radio" name="color" value="<?php echo $color_en ?>" 
                        <?php if ($color_en === $color): ?>
                        <?php echo "checked"; ?> 
                        <?php else: ?>
                        <?php echo "disabled"; ?>
                        <?php endif; ?>
                    >
                    <?php echo $color_jp ?>
                </label>
                <?php endforeach; ?>
            </label>
        </li>
        <li>
            <label for="category">
                <span class="save-item">
                    カテゴリ
                </span>
            </label><br/>
            <?php foreach ($categories as $category_name): ?>
            <label name="category">
                <input type="checkbox" name="category[]" value="<?php echo array_search($category_name, $categories); ?>"
                    <?php if (in_array(array_search($category_name, $categories), $category)): ?>
                    <?php echo "checked"; ?>
                    <?php else: ?>
                    <?php echo "disabled" ?>
                    <?php endif; ?>
                ><?php echo $category_name; ?>
            </label>
            <?php endforeach; ?><br/>
            <input class="submit_button" type="submit" value="つぶやく">
            <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        </li>
    </form>
</ul>
