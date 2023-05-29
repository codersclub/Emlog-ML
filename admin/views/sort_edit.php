<?php if (!defined('EMLOG_ROOT')) {
    exit('error!');
} ?>
<?php if (isset($_GET['error_a'])): ?>
    <div class="alert alert-danger"><?= lang('category_name_empty') ?></div><?php endif; ?>
<?php if (isset($_GET['error_c'])): ?>
    <div class="alert alert-danger"><?= lang('alias_format_invalid') ?></div><?php endif; ?>
<?php if (isset($_GET['error_d'])): ?>
    <div class="alert alert-danger"><?= lang('alias_unique') ?></div><?php endif; ?>
<?php if (isset($_GET['error_e'])): ?>
    <div class="alert alert-danger"><?= lang('alias_no_keywords') ?></div><?php endif; ?>
<h1 class="h3 mb-2 text-gray-800"><?= lang('category_edit') ?></h1>
<form action="sort.php?action=update" method="post">
    <div class="form-group">
        <label for="sortname"><?= lang('category_name') ?></label>
        <input class="form-control" value="<?= $sortname ?>" name="sortname" id="sortname" required>
    </div>
    <div class="form-group">
        <label for="alias"><?= lang('alias') ?></label>
        <input class="form-control" value="<?= $alias ?>" name="alias" id="alias">
        <small class="form-text text-muted"><?= lang('alias_prompt') ?></small>
    </div>
    <div class="form-group">
        <label for="description"><?= lang('category_description') ?></label>
        <textarea name="description" type="text" class="form-control"><?= $description ?></textarea>
    </div>
    <div class="form-group">
        <label><?= lang('category_parent') ?></label>
        <select name="pid" id="pid" class="form-control">
            <option value="0" <?php if ($pid == 0): ?> selected="selected"<?php endif ?>><?= lang('no') ?></option>
            <?php
            foreach ($sorts as $key => $value):
                if ($key == $sid || $value['pid'] != 0) continue;
                ?>
                <option value="<?= $key ?>"<?php if ($pid == $key): ?> selected="selected"<?php endif ?>><?= $value['sortname'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="form-group">
        <label for="template"><?= lang('category_template') ?></label>
        <?php if ($customTemplates):
            $sortListHtml = '<option value=""><?= lang('default') ?></option>';
            foreach ($customTemplates as $v) {
                $select = $v['filename'] == $template ? 'selected="selected"' : '';
                $sortListHtml .= '<option value="' . str_replace('.php', '', $v['filename']) . '" ' . $select . '>' . ($v['comment']) . '</option>';
            }
            ?>
            <select id="template" name="template" class="form-control"><?= $sortListHtml; ?></select>
            <small class="form-text text-muted"><?= lang('category_template_intro') ?></small>
        <?php else: ?>
            <input class="form-control" id="template" name="template" value="<?= $template ?>">
            <small class="form-text text-muted"><?= lang('custom_template_intro') ?></small>
        <?php endif; ?>
    </div>
    <input type="hidden" value="<?= $sid ?>" name="sid"/>
    <input type="submit" value="<?= lang('save') ?>" class="btn btn-sm btn-success" id="save"/>
    <input type="button" value="<?= lang('cancel') ?>" class="btn btn-sm btn-secondary" onclick="javascript: window.history.back();"/>
    <span id="alias_msg_hook"></span>
</form>

<script>
    $(function () {
        setTimeout(hideActived, 3600);
        $("#menu_category_content").addClass('active');
        $("#menu_content").addClass('show');
        $("#menu_sort").addClass('active');

        $("#alias").keyup(function () {
            checksortalias();
        });
    });

    function issortalias(a) {
        var reg1 = /^[\w-]*$/;
        var reg2 = /^[\d]+$/;
        if (!reg1.test(a)) {
            return 1;
        } else if (reg2.test(a)) {
            return 2;
        } else if (a == 'post' || a == 'record' || a == 'sort' || a == 'tag' || a == 'author' || a == 'page') {
            return 3;
        } else {
            return 0;
        }
    }

    function checksortalias() {
        var a = $.trim($("#alias").val());
        if (1 == issortalias(a)) {
            $("#save").attr("disabled", "disabled");
            $("#alias_msg_hook").html('<span id="input_error"><?=lang('alias_invalid_characters')?></span>');
        } else if (2 == issortalias(a)) {
            $("#save").attr("disabled", "disabled");
            $("#alias_msg_hook").html('<span id="input_error"><?=lang('alias_only_digits')?></span>');
        } else if (3 == issortalias(a)) {
            $("#save").attr("disabled", "disabled");
            $("#alias_msg_hook").html('<span id="input_error"><?=lang('alias_system_link')?></span>');
        } else {
            $("#alias_msg_hook").html('');
            $("#msg").html('');
            $("#save").attr("disabled", false);
        }
    }
</script>