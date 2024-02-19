<?php defined('EMLOG_ROOT') || exit('access denied!'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800"><?= lang('settings') ?></h1>
</div>
<div class="panel-heading">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="./setting.php"><?= lang('basic_settings') ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="./setting.php?action=user"><?= lang('user_settings') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./setting.php?action=mail"><?= lang('email_notify') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./setting.php?action=seo"><?= lang('seo_settings') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./setting.php?action=api"><?= lang('api_interface') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./blogger.php"><?= lang('personal_settings') ?></a></li>
    </ul>
</div>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <form action="setting.php?action=user_save" method="post" name="user_setting_form" id="user_setting_form">
            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="y" name="is_signup" id="is_signup" <?= $conf_is_signup ?> />
                <label class="form-check-label"><?= lang('registration_open') ?></label>
            </div>
            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="y" name="login_code" id="login_code" <?= $conf_login_code ?> >
                <label class="form-check-label"><?= lang('registration_captcha') ?></label>
            </div>
            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="y" name="email_code" id="email_code" <?= $conf_email_code ?> >
<!--vot-->                <label class="form-check-label"><?=lang('enable_email_code')?></label>
            </div>
            <hr>
            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="y" name="ischkarticle" id="ischkarticle" <?= $conf_ischkarticle ?> />
                <label class="form-check-label"><?= lang('writer_need_approve') ?></label>
            </div>
            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="y" name="article_uneditable" id="article_uneditable" <?= $conf_article_uneditable ?> />
                <label><?= lang('not_editable') ?></label>
            </div>
            <div class="form-group form-inline">
                <label><?=lang('limit_daily_posts')?>:</label>
                <input class="form-control mx-sm-3" style="width:60px;" value="<?= $conf_posts_per_day ?>" type="number" min="0" name="posts_per_day"/> <?= lang('if_0_upload_disabled') ?>
            </div>
<!--vot NOT COMPATIBLE WITH MULTILINGUAL!
            <div class="form-group form-inline">
                <label><?= lang('article_alias_prompt') ?></label>
                <input class="form-control mx-sm-3" style="width:80px;" value="<?= $conf_posts_name ?>" name="posts_name"/> <?= lang('article_alias_prompt') ?>
            </div>
-->
            <div class="form-group">
                <input name="token" id="token" value="<?= LoginAuth::genToken() ?>" type="hidden"/>
                <input type="submit" value="<?= lang('save_settings') ?>" class="btn btn-sm btn-success"/>
            </div>
        </form>
        <div class="alert alert-warning">
            <?= lang('groups_about') ?>
        </div>
    </div>
</div>
<script>
    $(function () {
        $("#menu_category_sys").addClass('active');
        $("#menu_sys").addClass('show');
        $("#menu_setting").addClass('active');
        setTimeout(hideActived, 3600);

        // submit Form
        $("#user_setting_form").submit(function (event) {
            event.preventDefault();
            submitForm("#user_setting_form");
        });
    });
</script>
