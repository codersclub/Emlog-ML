<?php if(!defined('EMLOG_ROOT')) {exit('error!');} ?>
<!--vot--><div class=containertitle><b><?=lang('data_backup')?></b>
<!--vot--><?php if(isset($_GET['active_del'])):?><span class="actived"><?=lang('backup_delete_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active_backup'])):?><span class="actived"><?=lang('backup_create_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active_import'])):?><span class="actived"><?=lang('backup_import_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_a'])):?><span class="error"><?=lang('backup_file_select')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_b'])):?><span class="error"><?=lang('backup_file_invalid')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_c'])):?><span class="error"><?=lang('backup_import_zip_unsupported')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_d'])):?><span class="error"><?=lang('backup_upload_failed')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_e'])):?><span class="error"><?=lang('backup_file_wrong')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_f'])):?><span class="error"><?=lang('backup_export_zip_unsupported')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active_mc'])):?><span class="actived"><?=lang('cache_update_ok')?></span><?php endif;?>
</div>
<div class=line></div>
<form  method="post" action="data.php?action=dell_all_bak" name="form_bak" id="form_bak">
<table width="100%" id="adm_bakdata_list" class="item_list">
  <thead>
    <tr>
<!--vot--><th width="683" colspan="2"><b><?=lang('backup_file')?></b></th>
<!--vot--><th width="226"><b><?=lang('backup_time')?></b></th>
<!--vot--><th width="149"><b><?=lang('file_size')?></b></th>
      <th width="87"></th>
    </tr>
  </thead>
  <tbody>
    <?php
        if($bakfiles):
        foreach($bakfiles  as $value):
        $modtime = smartDate(filemtime($value),'Y-m-d H:i:s');
        $size =  changeFileSize(filesize($value));
        $bakname = substr(strrchr($value,'/'),1);
    ?>
    <tr>
      <td width="22"><input type="checkbox" value="<?php echo $value; ?>" name="bak[]" class="ids" /></td>
      <td width="661"><a href="../content/backup/<?php echo $bakname; ?>"><?php echo $bakname; ?></a></td>
      <td><?php echo $modtime; ?></td>
      <td><?php echo $size; ?></td>
<!--vot--><td><a href="javascript: em_confirm('<?php echo $value; ?>', 'backup', '<?php echo LoginAuth::genToken(); ?>');"><?=lang('import')?></a></td>
    </tr>
    <?php endforeach;else:?>
<!--vot--><tr><td class="tdcenter" colspan="5"><?=lang('backup_no')?></td></tr>
    <?php endif;?>
    </tbody>
</table>
<div class="list_footer">
<!--vot--><a href="javascript:void(0);" id="select_all"><?=lang('select_all')?></a> <?=lang('selected_items')?>: <a href="javascript:bakact('del');" class="care"><?=lang('delete')?></a></div>
</form>

<div style="margin:50px 0px 20px 0px;">
<!--vot--><a href="javascript:$('#import').hide();$('#cache').hide();displayToggle('backup', 0);" style="margin-right: 16px;"><?=lang('db_backup')?>+</a> 
<!--vot--><a href="javascript:$('#backup').hide();$('#cache').hide();displayToggle('import', 0);" style="margin-right: 16px;"><?=lang('backup_import_local')?>+</a> 
<!--vot--><a href="javascript:$('#backup').hide();$('#import').hide();displayToggle('cache', 0);" style="margin-right: 16px;"><?=lang('cache_update')?>+</a>
</div>

<form action="data.php?action=bakstart" method="post">
<div id="backup">
<!--vot--><p><?=lang('backup_choose_table')?>:<br />
        <select multiple="multiple" size="12" name="table_box[]">
        <?php foreach($tables  as $value): ?>
        <option value="<?php echo DB_PREFIX; ?><?php echo $value; ?>" selected="selected"><?php echo DB_PREFIX; ?><?php echo $value; ?></option>
        <?php endforeach; ?>
        </select>
    </p>
<!--vot--><p><?=lang('backup_export_to')?>:
        <select name="bakplace" id="bakplace">
<!--vot-->    <option value="local" selected="selected"><?=lang('backup_local')?></option>
<!--vot-->    <option value="server"><?=lang('backup_server')?></option>
        </select>
    </p>
<!--vot--><p id="local_bakzip"><?=lang('compress_zip')?>: <input type="checkbox" style="vertical-align:middle;" value="y" name="zipbak" id="zipbak"></p>
    <p>
        <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
<!--vot--><input type="submit" value="<?=lang('backup_start')?>" class="button" />
    </p>
</div>
</form>

<form action="data.php?action=import" enctype="multipart/form-data" method="post">
<div id="import">
<!--vot--><p class="des"><?=lang('backup_version_tip')?><?php echo DB_PREFIX; ?></p>
    <p>
        <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
<!--vot--><input type="file" name="sqlfile" /> <input type="submit" value="<?=lang('import')?>" class="button" />
    </p>
</div>
</form>

<div id="cache">
<!--vot--><p class="des"><?=lang('cache_update_info')?></p>
<!--vot--><p><input type="button" onclick="window.location='data.php?action=Cache';" value="<?=lang('cache_update')?>" class="button" /></p>
</div>

<script>
setTimeout(hideActived,2600);
$(document).ready(function(){
    $("#select_all").toggle(function () {$(".ids").attr("checked", "checked");},function () {$(".ids").removeAttr("checked");});
    $("#adm_bakdata_list tbody tr:odd").addClass("tralt_b");
    $("#adm_bakdata_list tbody tr")
        .mouseover(function(){$(this).addClass("trover")})
        .mouseout(function(){$(this).removeClass("trover")});
    $("#bakplace").change(function(){$("#server_bakfname").toggle();$("#local_bakzip").toggle();});
});
function bakact(act){
    if (getChecked('ids') == false) {
/*vot*/ alert('<?=lang('backup_file_select')?>');
        return;
    }
/*vot*/ if(act == 'del' && !confirm('<?=lang('backup_delete_sure')?>')){return;}
    $("#operate").val(act);
    $("#form_bak").submit();
}
$("#menu_data").addClass('sidebarsubmenu1');
</script>