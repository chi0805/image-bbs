<?php $this->setLayoutVar('title', 'sign-in') ?>

<h2>LOGIN</h2>
<div class="signin-form">
    <form action="./signin/post" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        <ul>
            <li><label for="name">
                <span class="save-item">
                    名前
                </span><br/>
                <input id="user_name" type="text" name="user_name" value="<?php ?>"><br/>
            </label></li>
            <li><label for="password">
                <span class="save-item">
                    パスワード
                </span><br/>
                <input id="name" type="password" name="password" value="<?php ?>"><br/>
            </label></li>
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
                <input id="submit_button" type="submit" name="submit" value="LOGIN">
            </li>
        </ul>
    </form>
</div>
