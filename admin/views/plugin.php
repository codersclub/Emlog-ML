<?php if (!defined('EMLOG_ROOT')) {
    exit('error!');
} ?>
<?php if (isset($_GET['activate_install'])): ?>
    <div class="alert alert-success"><?= lang('plugin_upload_ok') ?></div><?php endif ?>
<?php if (isset($_GET['activate_upgrade'])): ?>
    <div class="alert alert-success"><?=lang('plugin_update_ok')?></div><?php endif ?>
<?php if (isset($_GET['active'])): ?>
    <div class="alert alert-success"><?= lang('plugin_active_ok') ?></div><?php endif ?>
<?php if (isset($_GET['activate_del'])): ?>
    <div class="alert alert-success"><?= lang('deleted_ok') ?></div><?php endif ?>
<?php if (isset($_GET['active_error'])): ?>
    <div class="alert alert-danger"><?= lang('plugin_active_failed') ?></div><?php endif ?>
<?php if (isset($_GET['inactive'])): ?>
    <div class="alert alert-success"><?= lang('plugin_disable_ok') ?></div><?php endif ?>
<?php if (isset($_GET['error_a'])): ?>
    <div class="alert alert-danger"><?= lang('plugin_delete_failed') ?></div><?php endif ?>
<?php if (isset($_GET['error_b'])): ?>
    <div class="alert alert-danger"><?= lang('plugin_not_writable') ?></div><?php endif ?>
<?php if (isset($_GET['error_c'])): ?>
    <div class="alert alert-danger"><?= lang('plugin_zip_nonsupport') ?></div><?php endif ?>
<?php if (isset($_GET['error_d'])): ?>
    <div class="alert alert-danger"><?= lang('plugin_zip_select') ?></div><?php endif ?>
<?php if (isset($_GET['error_e'])): ?>
    <div class="alert alert-danger"><?= lang('plugin_wrong_format') ?></div><?php endif ?>
<?php if (isset($_GET['error_f'])): ?>
    <div class="alert alert-danger"><?= lang('plugin_zipped_only') ?></div><?php endif ?>
<?php if (isset($_GET['error_g'])): ?>
<!--vot-->    <div class="alert alert-danger"><?=lang('php_size_limit')?></div><?php endif ?>
<?php if (isset($_GET['error_h'])): ?>
    <div class="alert alert-danger"><?=lang('plugin_update_fail')?></div><?php endif ?>
<?php if (isset($_GET['error_i'])): ?>
    <div class="alert alert-danger"><?=lang('emlog_unregistered')?></div><?php endif ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= lang('plugin_manage') ?></h1>
    <a href="#" class="btn btn-sm btn-success shadow-sm mt-4" data-toggle="modal" data-target="#addModal"><i class="icofont-plus"></i> <?= lang('plugin_new_install') ?></a>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr>
                    <th><?= lang('plugin_name') ?></th>
                    <th><?= lang('plugin_status') ?></th>
                    <th><?= lang('description') ?></th>
                    <th><?= lang('version') ?></th>
                    <th><?= lang('operation') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($plugins):
                    $i = 0;
                    foreach ($plugins as $key => $val):
                        $plug_state = 'inactive';
                        $plug_action = 'active';
                        $plug_state_des = lang('plugin_active_click');
                        if ($val['active']) {
                            $plug_state = 'active';
                            $plug_action = 'inactive';
                            $plug_state_des = lang('plugin_disable_click');
                        }
                        $i++;
                        if (TRUE === $val['Setting']) {
                            $val['Name'] = "<a href=\"./plugin.php?plugin={$val['Plugin']}\" title=\"" . lang('plugin_settings_click') . "\">{$val['Name']}</a>";
                        }
                        ?>
                        <tr data-plugin-alias="<?= $val['Plugin'] ?>" data-plugin-version="<?= $val['Version'] ?>">
                            <td><?= $val['Name'] ?></td>
                            <td id="plugin_<?= $i ?>">
                                <a href="./plugin.php?action=<?= $plug_action ?>&plugin=<?= $key ?>&token=<?= LoginAuth::genToken() ?>"><img
                                        src="./views/images/plugin_<?= $plug_state ?>.gif" title="<?= $plug_state_des ?>"></a>
                            </td>
                            <td>
                                <?= $val['Description'] ?>
                                <?php if ($val['Url'] != ''): ?><a href="<?= $val['Url'] ?>" target="_blank"><?= lang('more_info') ?></a><?php endif ?>
                                <div class="small mt-3">
                                    <?php if ($val['ForEmlog'] != ''): ?><?= lang('ok_for_emlog') ?>: <?= $val['ForEmlog'] ?>&nbsp | &nbsp<?php endif ?>
                                    <?php if ($val['Author'] != ''): ?>
<!--vot-->                                    <?= lang('author') ?>:
                                        <?php if ($val['AuthorUrl'] != ''): ?>
                                            <a href="<?= $val['AuthorUrl'] ?>" target="_blank"><?= $val['Author'] ?></a>
                                        <?php else: ?>
                                            <?= $val['Author'] ?>
                                        <?php endif ?>
                                    <?php endif ?>
                                </div>
                            </td>
                            <td><?= $val['Version'] ?></td>
                            <td>
                                <a href="javascript: em_confirm('<?= $key ?>', 'plu', '<?= LoginAuth::genToken() ?>');" class="btn btn-sm btn-danger"><?= lang('delete') ?></a>
                                <span class="update-btn"></span>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5"><?= lang('plugin_no_installed') ?></td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>
            <div class="my-3" id="upMsg"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= lang('plugin_install') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="./plugin.php?action=upload_zip" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="plugin_new" class="form-group">
                        <li><?= lang('upload_install_info') ?></li>
                        <li><input name="pluzip" type="file"/></li>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><?= lang('cancel') ?></button>
                    <button type="submit" class="btn btn-sm btn-success"><?= lang('upload') ?>
                    <input name="token" id="token" value="<?= LoginAuth::genToken() ?>" type="hidden"/>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    setTimeout(hideActived, 3600);
    $("#menu_category_ext").addClass('active');
    $("#menu_ext").addClass('show');
    $("#menu_plug").addClass('active');

    // check for upgrade
    $(function () {
        var pluginList = [];
        $('table tbody tr').each(function () {
            var $tr = $(this);
            var pluginAlias = $tr.data('plugin-alias');
            var currentVersion = $tr.data('plugin-version');
            pluginList.push({
                name: pluginAlias,
                version: currentVersion
            });
        });
        $.ajax({
            url: './plugin.php?action=check_update',
            type: 'POST',
            data: {
                plugins: pluginList
            },
            success: function (response) {
                if (response.code === 0) {
                    var pluginsToUpdate = response.data;
                    $.each(pluginsToUpdate, function (index, item) {
                        var $tr = $('table tbody tr[data-plugin-alias="' + item.name + '"]');
                        var $updateBtn = $tr.find('.update-btn');
/*vot*/                 $updateBtn.append($('<a>').addClass('btn btn-success btn-sm').text(lang('update')).attr("href", "./plugin.php?action=upgrade&alias=" + item.name));
                    });
                } else {
                    $('#upMsg').html(lang('plugin_update_check_fail') + response.code).addClass('alert alert-warning');
                }
            },
            error: function (xhr) {
                var responseText = xhr.responseText;
                var responseObject = JSON.parse(responseText);
                var msgValue = responseObject.msg;
/*vot*/         $('#upMsg').html(lang('plugin_update_check_exception') + msgValue).addClass('alert alert-warning');
            }
        });
    });
</script>
