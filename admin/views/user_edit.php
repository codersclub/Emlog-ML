<?php if (!defined('EMLOG_ROOT')) {
    exit('error!');
} ?>
<?php if (isset($_GET['error_nickname'])): ?>
    <div class="alert alert-danger"><?= lang('nickname_is_empty') ?></div><?php endif ?>
<?php if (isset($_GET['error_email'])): ?>
    <div class="alert alert-danger"><?= lang('email_empty') ?></div><?php endif ?>
<?php if (isset($_GET['error_exist'])): ?>
    <div class="alert alert-danger"><?= lang('user_name_exists') ?></div><?php endif ?>
<?php if (isset($_GET['error_exist_email'])): ?>
    <div class="alert alert-danger"><?= lang('email_is_used') ?></div><?php endif ?>
<?php if (isset($_GET['error_pwd_len'])): ?>
    <div class="alert alert-danger"><?= lang('password_length_short') ?></div><?php endif ?>
<?php if (isset($_GET['error_pwd2'])): ?>
    <div class="alert alert-danger"><?= lang('passwords_not_equal') ?></div><?php endif ?>
<h1 class="h3 mb-4 text-gray-800"><?= lang('user_manage') ?></h1>
<form action="user.php?action=update" method="post">
    <div class="form-group">
        <label for="nickname"><?= lang('nickname') ?></label>
        <input class="form-control" value="<?= $nickname ?>" name="nickname" id="nickname" maxlength="20" required>
    </div>
    <div class="form-group">
        <label for="email"><?= lang('email') ?></label>
        <input type="email" class="form-control" value="<?= $email ?>" name="email" id="email" required>
    </div>
    <div class="form-group">
        <label for="role"><?= lang('user_role') ?></label>
        <select name="role" id="role" class="form-control">
            <option value="writer" <?= $ex1 ?>><?= lang('registered_user') ?></option>
            <option value="editor" <?= $ex2 ?>><?= lang('editor') ?></option>
            <option value="admin" <?= $ex2 ?>><?= lang('admin') ?></option>
        </select>
    </div>
    <div class="form-group">
        <label for="description"><?= lang('personal_description') ?></label>>
        <textarea name="description" type="text" class="form-control"><?= $description ?></textarea>
    </div>
    <div class="form-group">
        <label for="username"><?= lang('user_name') ?></label>
        <input class="form-control" value="<?= $username ?>" name="username" id="username" required>
    </div>
    <div class="form-group">
        <label for="password"><?= lang('password_new') ?></label>
        <input type="password" class="form-control" autocomplete="new-password" name="password" id="password">
    </div>
    <div class="form-group">
        <label for="password2"><?= lang('password_new_repeat') ?></label>
        <input type="password" class="form-control" name="password2" id="password2">
    </div>
    <input name="token" id="token" value="<?= LoginAuth::genToken() ?>" type="hidden"/>
    <input type="hidden" value="<?= $uid ?>" name="uid"/>
    <input type="submit" value="<?= lang('save') ?>" class="btn btn-sm btn-success"/>
    <input type="button" value="<?= lang('cancel') ?>" class="btn btn-sm btn-secondary" onclick="window.location='user.php';"/>
</form>

<script>
    $(function () {
        setTimeout(hideActived, 3600);
        $("#menu_category_sys").addClass('active');
        $("#menu_sys").addClass('show');
        $("#menu_user").addClass('active');

        if ($("#role").val() == 'admin') $("#ischeck").hide();
        $("#role").change(function () {
            $("#ischeck").toggle()
        })
    });
</script>
