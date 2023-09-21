<?php
/**
 * Custom 404 page
 */
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
?>
<!doctype html>
<html lang="<?= LANG ?>" dir="<?= LANG_DIR ?>">
<head>
    <meta charset="utf-8">
    <title><?=lang('404_error')?></title>
    <style>
        body {
            background-color: #F7F7F7;
            font-family: helvetica neue, Helvetica, Arial, sans-serif
            font-size: 12px;
            line-height: 150%
        }

        .main {
            background-color: #FFFFFF;
            font-size: 12px;
            color: #666666;
            width: 650px;
            margin: 60px auto 0px;
            padding: 30px 10px;
            box-shadow: 0 2px 8px 0 rgba(0, 0, 0, .02);
            border-radius: 10px;
            transition: box-shadow 0.4s
        }

        .main p {
            text-align: center;
            font-weight: 600;
            font-size: 2rem
        }

        .main p a {
            border: 1px solid #ccc !important;
            padding: 8px;
            border-radius: 10px !important;
            color: #929292;
            font-size: initial;
            text-decoration: none
        }
    </style>
</head>
<body>
<div class="main">
    <p><?= lang('404_description') ?></p>
    <p><a href="<?= BLOG_URL ?>"><?= lang('click_return') ?></a></p>
</div>
</body>
</html>
