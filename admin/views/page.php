<?php defined('EMLOG_ROOT') || exit('access denied!'); ?>
<?php if (isset($_GET['active_del'])): ?>
    <div class="alert alert-success"><?= lang('page_deleted_ok') ?></div><?php endif ?>
<?php if (isset($_GET['active_hide_n'])): ?>
    <div class="alert alert-success"><?= lang('page_published_ok') ?></div><?php endif ?>
<?php if (isset($_GET['active_hide_y'])): ?>
    <div class="alert alert-success"><?= lang('page_drafted_ok') ?></div><?php endif ?>
<?php if (isset($_GET['active_pubpage'])): ?>
    <div class="alert alert-success"><?= lang('page_saved_ok') ?></div><?php endif ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800"><?= lang('page_management') ?></h1>
    <a href="page.php?action=new" class="btn btn-sm btn-success shadow-sm mt-4"><i class="icofont-plus"></i>
        <?= lang('add_page') ?></a>
</div>
<form action="page.php?action=operate_page" method="post" name="form_page" id="form_page">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover dataTable no-footer">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"/></th>
                        <th><?= lang('title') ?></th>
                        <th><?= lang('comments') ?></th>
                        <th><?= lang('views') ?></th>
                        <th><?= lang('alias') ?></th>
                        <th><?= lang('template') ?></th>
                        <th><?= lang('time') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pages as $key => $value):
                        $isHide = '';
                        if ($value['hide'] == 'y') {
                            $isHide = '<span class="text-danger ml-2"> - ' . lang('draft') . '</span>';
                        }
                        ?>
                        <tr>
                            <td style="width: 19px;">
                                <input type="checkbox" name="page[]" value="<?= $value['gid'] ?>" class="ids"/></td>
                            <td>
                                <a href="page.php?action=mod&id=<?= $value['gid'] ?>"><?= $value['title'] ?></a>
                                <?= $isHide ?>
                                <?php if ($value['gid'] == Option::get('home_page_id')): ?>
                                    <br>
                                    <span class="text-secondary">
                                        <span class="badge small badge-danger"><?= lang('home') ?></span> <?= lang('as_home') ?><a href="<?= BLOG_URL ?>posts" target="_blank"><?= BLOG_URL ?>posts</a>
                                    </span>
                                <?php endif; ?>
<!--vot:Link Char-->            <?php if ($value['link']): ?><span class="small">&#x1F517;</span><?php endif ?>
                            </td>
                            <td>
                                <a href="comment.php?gid=<?= $value['gid'] ?>" class="badge badge-primary mx-2 px-3"><?= $value['comnum'] ?></a>
                            </td>
                            <td>
                                <a href="<?= Url::log($value['gid']) ?>" class="badge badge-success mx-2 px-3" target="_blank"><?= $value['views'] ?></a>
                            </td>
                            <td><?= $value['alias'] ?></td>
                            <td><?= $value['template'] ?></td>
                            <td class="small"><?= $value['date'] ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="list_footer">
                <div class="btn-group">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">操作</button>
                    <div class="dropdown-menu">
                        <a href="javascript:pageact('hide');" class="dropdown-item"><?= lang('make_draft') ?></a>
                        <a href="javascript:pageact('pub');" class="dropdown-item"><?= lang('publish') ?></a>
                        <a href="javascript:pageact('del');" class="dropdown-item text-danger"><?= lang('delete') ?></a>
                    </div>
                </div>
                <input name="token" id="token" value="<?= LoginAuth::genToken() ?>" type="hidden"/>
                <input name="operate" id="operate" value="" type="hidden"/>
            </div>
            <div class="page"><?= $pageurl ?></div>
            <div class="text-center small">(<?= lang('have') ?> <?= $pageNum ?> <?= lang('_pages') ?>)</div>
        </div>
    </div>
</form>
<script>
    $(function () {
        setTimeout(hideActived, 3600);
        $("#menu_category_view").addClass('active');
        $("#menu_view").addClass('show');
        $("#menu_page").addClass('active');
    });

    function pageact(act) {
        if (getChecked('ids') === false) {
            infoAlert(lang('select_page_to_operate'));
            return;
        }
        if (act === 'del') {
            delAlert2(lang('sure_delete_selected_pages'), lang('delete_not_recover'), function () {
                $("#operate").val(act);
                $("#form_page").submit();
            })
            return;
        }
        $("#operate").val(act);
        $("#form_page").submit();
    }
</script>
