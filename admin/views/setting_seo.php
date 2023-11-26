<?php defined('EMLOG_ROOT') || exit('access denied!'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= lang('settings') ?></h1>
</div>
<div class="panel-heading">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="./setting.php"><?= lang('basic_settings') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./setting.php?action=user"><?= lang('user_settings') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./setting.php?action=mail"><?= lang('email_notify') ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="./setting.php?action=seo"><?= lang('seo_settings') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./setting.php?action=api"><?= lang('api_interface') ?></a></li>
        <li class="nav-item"><a class="nav-link" href="./blogger.php"><?= lang('personal_settings') ?></a></li>
    </ul>
</div>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <form action="setting.php?action=seo_save" method="post" name="seo_setting_form" id="seo_setting_form">
            <h4><?= lang('post_url') ?></h4>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="permalink" value="0" <?= $ex0 ?>>
                <label class="form-check-label"><?= lang('default_format') ?>: <span class="permalink_url"><?= BLOG_URL ?>?post=1</span></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="permalink" value="1" <?= $ex1 ?>>
                <label class="form-check-label"><?= lang('file_format') ?>: <span class="permalink_url"><?= BLOG_URL ?>post-1.html</span></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="permalink" value="2" <?= $ex2 ?>>
                <label class="form-check-label"><?= lang('directory_format') ?>: <span class="permalink_url"><?= BLOG_URL ?>post/1</span></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="permalink" value="3" <?= $ex3 ?>>
                <label class="form-check-label"><?= lang('category_format') ?>: <span class="permalink_url"><?= BLOG_URL ?>category/1.html</span></label>
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" value="y" name="isalias" id="isalias" <?= $isalias ?> />
                <label><?= lang('post_alias_enable') ?>: <span class="permalink_url"><?= BLOG_URL ?>abc</span></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="y" name="isalias_html" id="isalias_html" <?= $isalias_html ?> />
                <label><?= lang('enable_html_suffix') ?>: <span class="permalink_url"><?= BLOG_URL ?>abc.html</span></label>
            </div>

            <div class="alert alert-warning">
                <?= lang('post_url_rewriting') ?><br>
            </div>

            <div class="alert alert-primary">
                <p>
                <?= lang('nginx_rewrite') ?>:<br><br>
                    location / {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;index index.php index.html;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;if (!-e $request_filename){<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;rewrite ^/(.*)$ /index.php last;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                    }
                </p>
                <hr>
                <p><?= lang('other_config') ?><a href="https://www.emlog.net/docs/#/faq" target="_blank"><?= lang('common_problems') ?></a></p>
            </div>

            <h4 class="mt-4"><?= lang('meta_settings') ?>:</h4>
            <div class="form-group">
                <label><?= lang('meta_title') ?></label>
                <input class="form-control" value="<?= $site_title ?>" name="site_title">
            </div>
            <div class="form-group">
                <label><?= lang('meta_keywords') ?></label>
                <input class="form-control" value="<?= $site_key ?>" name="site_key">
            </div>
            <div class="form-group">
                <label><?= lang('meta_description') ?></label>
                <textarea name="site_description" class="form-control"><?= $site_description ?></textarea>
            </div>
            <div class="form-group">
                <label><?= lang('meta_title_scheme') ?></label>
                <select name="log_title_style" class="form-control">
                    <option value="0" <?= $opt0 ?>><?= lang('post_title') ?></option>
                    <option value="1" <?= $opt1 ?>><?= lang('post_title_site_title') ?></option>
                    <option value="2" <?= $opt2 ?>><?= lang('post_title_site_meta_title') ?></option>
                </select>
            </div>

            <input name="token" id="token" value="<?= LoginAuth::genToken() ?>" type="hidden"/>
            <input type="submit" value="<?= lang('save_settings') ?>" class="btn btn-sm btn-success"/>
        </form>
    </div>
</div>
<script>
    $(function () {
        setTimeout(hideActived, 3600);
        $("#menu_category_sys").addClass('active');
        $("#menu_sys").addClass('show');
        $("#menu_setting").addClass('active');

        // submit Form
        $("#seo_setting_form").submit(function (event) {
            event.preventDefault();
            submitForm("#seo_setting_form");
        });
    });
</script>