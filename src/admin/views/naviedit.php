<?php if(!defined('EMLOG_ROOT')) {exit('error!');}?>
<div class=line></div>
<section class="content-header">
    <h1>修改导航</h1>
</section>
<section class="content">
<form action="navbar.php?action=update" method="post">
<div class="form-group form-inline">
    <li>
        <input size="20" class="form-control" value="<?php echo $naviname; ?>" name="naviname" /> <label>导航名称</label>
    </li>
    <li>
        <input size="50" class="form-control" value="<?php echo $url; ?>" name="url" <?php echo $conf_isdefault; ?> /> <label>导航地址</label>
    </li>
    <li class="checkbox">
        <label><input type="checkbox" value="y" name="newtab" <?php echo $conf_newtab; ?> /> 在新窗口打开</label>
    </li>
    <?php if ($type == Navi_Model::navitype_custom && $pid != 0): ?>
    <li>
            <select name="pid" id="pid" class="form-control">
<!--vot-->    <option value="0"><?=lang('no')?></option>
                <?php
                    foreach($navis as $key=>$value):
                        if($value['type'] != Navi_Model::navitype_custom || $value['pid'] != 0) {
                            continue;
                        }
                        $flg = $value['id'] == $pid ? 'selected' : '';
                ?>
                <option value="<?php echo $value['id']; ?>" <?php echo $flg;?>><?php echo $value['naviname']; ?></option>
                <?php endforeach; ?>
            </select>
<!--vot-->   <?=lang('nav_parent')?>
    </li>
    <?php endif; ?>
    <li>
    <input type="hidden" value="<?php echo $naviId; ?>" name="navid" />
    <input type="hidden" value="<?php echo $isdefault; ?>" name="isdefault" />
    <input type="submit" value="<?=lang('save')?>" class="btn btn-primary" />
    <input type="button" value="?=lang('cancel')?>" class="btn btn-default" onclick="javascript: window.history.back();" />
    </li>
</div>
</form>
</section>
<script>
$("#menu_navi").addClass('active').parent().parent().addClass('active');
</script>
