<?php if (!defined('EMLOG_ROOT')) {exit('error!');}?>
<script>setTimeout(hideActived, 2600);</script>
<div class="containertitle">
<!--vot--><?php if(isset($_GET['active_taxis'])):?><span class="alert alert-success"><?=lang('category_update_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active_del'])):?><span class="alert alert-success"><?=lang('category_deleted_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active_edit'])):?><span class="alert alert-success"><?=lang('category_modify_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active_add'])):?><span class="alert alert-success"><?=lang('category_add_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_a'])):?><span class="alert alert-danger"><?=lang('category_name_empty')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_b'])):?><span class="alert alert-danger"><?=lang('category_no_order')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_c'])):?><span class="alert alert-danger"><?=lang('alias_format_invalid')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_d'])):?><span class="alert alert-danger"><?=lang('alias_unique')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_e'])):?><span class="alert alert-danger"><?=lang('alias_no_keywords')?></span><?php endif;?>
</div>
    <!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><?=lang('category_management')?></h1>
    <form  method="post" action="sort.php?action=taxis">
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=lang('category_management')?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
                            <th><?=lang('id')?></th>
                            <th><?=lang('name')?></th>
                            <th><?=lang('description')?></th>
                            <th><?=lang('alias')?></th>
                            <th><?=lang('template')?></th>
                            <th><?=lang('views')?></th>
                            <th><?=lang('posts')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($sorts):
                        foreach ($sorts as $key => $value):
                            if ($value['pid'] != 0) {
                                continue;
                            }
                        ?>
                        <tr>
                            <td>
                            <input type="hidden" value="<?= $value['sid'] ?>" class="sort_id">
                            <input class="form-control em-small" name="sort[<?= $value['sid'] ?>]" value="<?= $value['taxis'] ?>">
                            </td>
                            <td class="sortname">
                            <a href="sort.php?action=mod_sort&sid=<?= $value['sid'] ?>"><?= $value['sortname'] ?></a>
                            </td>
                        <td><?= $value['description'] ?></td>
                        <td class="alias"><?= $value['alias'] ?></td>
                        <td class="alias"><?= $value['template'] ?></td>
                            <td class="tdcenter">
                                <a href="<?php echo Url::sort($value['sid']); ?>" target="_blank"><img src="./templates/<?php echo ADMIN_TEMPLATE; ?>/images/vlog.gif" align="absbottom" border="0" /></a>
                            </td>
                        <td class="tdcenter"><a href="./admin_log.php?sid=<?= $value['sid'] ?>"><?= $value['lognum'] ?></a></td>
                            <td>
<!--vot-->                  <a href="sort.php?action=mod_sort&sid=<?= $value['sid'] ?>"><?=lang('edit')?></a>
<!--vot-->                  <a href="javascript: em_confirm(<?= $value['sid'] ?>, 'sort', '<?= LoginAuth::genToken() ?>');" class="care"><?=lang('delete')?></a>
                            </td>
                        </tr>
                        <?php
                        $children = $value['children'];
                        foreach ($children as $key):
                            $value = $sorts[$key];
                            ?>
                            <tr>
                                <td>
                                <input type="hidden" value="<?= $value['sid'] ?>" class="sort_id">
                                <input class="form-control em-small" name="sort[<?= $value['sid'] ?>]" value="<?= $value['taxis'] ?>">
                                </td>
                            <td class="sortname">---- <a href="sort.php?action=mod_sort&sid=<?= $value['sid'] ?>"><?= $value['sortname'] ?></a></td>
                            <td><?= $value['description'] ?></td>
                            <td class="alias"><?= $value['alias'] ?></td>
                            <td class="alias"><?= $value['template'] ?></td>
                                <td class="tdcenter">
                                    <a href="<?php echo Url::sort($value['sid']); ?>" target="_blank"><img src="./templates/<?php echo ADMIN_TEMPLATE; ?>/images/vlog.gif" align="absbottom" border="0" /></a>
                                </td>
                            <td class="tdcenter"><a href="./admin_log.php?sid=<?= $value['sid'] ?>"><?= $value['lognum'] ?></a></td>
                                <td>
<!--vot-->                      <a href="sort.php?action=mod_sort&sid=<?= $value['sid'] ?>"><?=lang('edit')?></a>
<!--vot-->                      <a href="javascript: em_confirm(<?= $value['sid'] ?>, 'sort', '<?= LoginAuth::genToken() ?>');" class="care"><?=lang('delete')?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach;
                else: ?>
<!--vot-->      <tr><td class="tdcenter" colspan="8"><?=lang('categories_no')?></td></tr>
                <?php endif; ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="list_footer">
<!--vot--><input type="submit" value="<?=lang('order_change')?>" class="btn btn-primary"> 
<!--vot--><a href="javascript:displayToggle('sort_new', 2);" class="btn btn-success"><?=lang('category_add')?>+</a>
        </div>
    </form>
    <form action="sort.php?action=add" method="post" id="sort_new">
        <div class="form-group row">
<!--vot-->  <label><?=lang('id')?></label>
            <input maxlength="4" style="width:50px;" name="taxis" class="form-control" />
        </div>
        <div class="form-group">
            <input style="width:243px;" class="form-control" name="sortname" id="sortname" required="required" />
<!--vot-->  <label><?=lang('name')?></label>
        </div>
        <div class="form-group">
            <input style="width:243px;" class="form-control" name="alias" id="alias">
<!--vot-->  <label><?=lang('alias_info')?></label>
        </div>
        <div class="form-group">
            <select name="pid" id="pid" class="form-control" style="width:243px;">
<!--vot-->      <option value="0"><?=lang('no')?></option>
                <?php
                foreach ($sorts as $key => $value):
                    if ($value['pid'] != 0) {
                        continue;
                    }
                    ?>
                    <option value="<?= $key ?>"><?= $value['sortname'] ?></option>
                <?php endforeach; ?>
            </select>
<!--vot-->  <label><?=lang('category_parent')?></label>
        </div>
        <div class="form-group">
            <input style="width:243px;" class="form-control" name="template" id="template" value="log_list">
<!--vot-->  <label><?=lang('template')?> <?=lang('template_info')?></label>
        </div>
        <div class="form-group">
            <textarea name="description" type="text" style="width:360px;height:80px;overflow:auto;" class="form-control" placeholder="分类描述"></textarea>
        </div>
        <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
        <input type="submit" id="addsort" value="添加新分类" class="btn btn-primary"/><span id="alias_msg_hook"></span>
    </form>
</div>
<!-- /.container-fluid -->
<script>
    $("#sort_new").css('display', $.cookie('em_sort_new') ? $.cookie('em_sort_new') : 'none');
    $("#alias").keyup(function() {
        checksortalias();
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
            $("#addsort").attr("disabled", "disabled");
<!--vot-->  $("#alias_msg_hook").html('<span id="input_error"><?=lang('alias_invalid_characters')?></span>');
        } else if (2 == issortalias(a)) {
            $("#addsort").attr("disabled", "disabled");
<!--vot-->  $("#alias_msg_hook").html('<span id="input_error"><?=lang('alias_only_digits')?></span>');
        } else if (3 == issortalias(a)) {
            $("#addsort").attr("disabled", "disabled");
<!--vot-->  $("#alias_msg_hook").html('<span id="input_error"><?=lang('alias_system_link')?></span>');
        } else {
            $("#alias_msg_hook").html('');
            $("#msg").html('');
            $("#addsort").attr("disabled", false);
        }
    }
    $(document).ready(function() {
        $("#adm_sort_list tbody tr:odd").addClass("tralt_b");
        $("#adm_sort_list tbody tr")
                .mouseover(function() {
                    $(this).addClass("trover")
                })
                .mouseout(function() {
                    $(this).removeClass("trover")
                });
        $("#menu_sort").addClass('active');
    });
</script>
