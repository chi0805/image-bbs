<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transfoemational//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-eqiv="Content-Type" contenr="text/html; charset=utf-8">
    <title><?php if (isset($title)): echo $this->escape($title) . ' - '; endif; ?>Image BBS</title>
</head>
<body> 
    <div id="header">
        <h1><a href="<?php echo $base_url; ?>/">ひとこと掲示板</a></h1>
    </div>

    <div id="main">
        <?php echo $_content; ?>
    </div>
</body>
</html>
