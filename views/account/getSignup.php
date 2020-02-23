<?php $this->setLayoutVar('title', 'sign-up') ?>

<h2>sign up</h2>
<div class="signup-form">
    <form action="./signup/post" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
        <ul>
            <li><label for="name">
                <span class="save-item">
                    名前
                </span>
                <span class="save-rule">
                    (40文字以内)
                </span><br/>
                <input id="user_name" type="text" name="user_name" value="<?php echo $this->escape($user_name); ?>"><br/>
            </label></li>
            <li><label for="password">
                <span class="save-item">
                    パスワード
                </span><br/>
                <span class="save-rule">
                    (半角英大文字・小文字・数字・記号を含む8〜20文字)
                </span><br>
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
                <input id="submit_button" type="submit" name="submit" value="登録">
            </li>
        </ul>
    </form>
</div>


