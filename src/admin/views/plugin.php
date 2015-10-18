<?php if(!defined('EMLOG_ROOT')) {exit('error!');}?>
<!--vot--><div class=containertitle><b><?=lang('plugin_manage')?></b><div id="msg"></div>
<!--vot--><?php if(isset($_GET['activate_install'])):?><span class="alert alert-success"><?=lang('plugin_upload_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active'])):?><span class="alert alert-success"><?=lang('plugin_active_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['activate_del'])):?><span class="alert alert-success"><?=lang('deleted_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['active_error'])):?><span class="alert alert-danger"><?=lang('plugin_active_failed')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['inactive'])):?><span class="alert alert-success"><?=lang('plugin_disable_ok')?></span><?php endif;?>
<!--vot--><?php if(isset($_GET['error_a'])):?><span class="alert alert-danger"><?=lang('plugin_delete_failed')?></span><?php endif;?>
<?php if(isset($_GET['error_b'])):?><span class="alert alert-danger"><?=lang('plugin_upload_failed_nonwritable')?></span><?php endif;?>
<?php if(isset($_GET['error_c'])):?><span class="alert alert-danger"><?=lang('plugin_zip_nonsupport')?></span><?php endif;?>
<?php if(isset($_GET['error_d'])):?><span class="alert alert-danger"><?=lang('plugin_zip_select')?></span><?php endif;?>
<?php if(isset($_GET['error_e'])):?><span class="alert alert-danger"><?=lang('plugin_install_failed_wrong_format')?></span><?php endif;?>
<?php if(isset($_GET['error_f'])):?><span class="alert alert-danger"><?=lang('plugin_zipped_only')?></span><?php endif;?>
</div>
<div class=line></div>
  <table class="table table-striped table-bordered table-hover dataTable no-footer">
  <thead>
      <tr>
        <th width="200"></th>
<!--vot--><th width="40" class="tdcenter"><b><?=lang('status')?></b></th>
<!--vot--><th width="60" class="tdcenter"><b><?=lang('version')?></b></th>
<!--vot--><th width="450" class="tdcenter"><b><?=lang('description')?></b></th>
        <th width="60" class="tdcenter"></th>
      </tr>
  </thead>
  <tbody>
    <?php 
    if($plugins):
    $i = 0;
    foreach($plugins as $key=>$val):
        $plug_state = 'inactive';
        $plug_action = 'active';
/*vot*/        $plug_state_des = lang('plugin_active_ok');
        if (in_array($key, $active_plugins))
        {
            $plug_state = 'active';
            $plug_action = 'inactive';
/*vot*/            $plug_state_des = lang('plugin_disable_ok');
        }
        $i++;
        if (TRUE === $val['Setting']) {
/*vot*/        $val['Name'] = "<a href=\"./plugin.php?plugin={$val['Plugin']}\" title=\"".lang('plugin_settings_click')."\">{$val['Name']} <img src=\"./views/images/set.png\" border=\"0\" /></a>";
        }
    ?>	
      <tr>
        <td class="tdcenter"><?php echo $val['Name']; ?></td>
        <td class="tdcenter" id="plugin_<?php echo $i;?>">
        <a href="./plugin.php?action=<?php echo $plug_action;?>&plugin=<?php echo $key;?>&token=<?php echo LoginAuth::genToken(); ?>"><img src="./views/images/plugin_<?php echo $plug_state; ?>.gif" title="<?php echo $plug_state_des; ?>" align="absmiddle" border="0"></a>
        </td>
        <td class="tdcenter"><?php echo $val['Version']; ?></td>
        <td>
        <?php echo $val['Description']; ?>
<!--vot-->    <?php if ($val['Url'] != ''):?><a href="<?php echo $val['Url'];?>" target="_blank"><?=lang('more_info')?></a><?php endif;?>
        <div style="margin-top:5px;">
<!--vot-->        <?php if ($val['ForEmlog'] != ''):?><?=lang('ok_for_emlog')?>: <?php echo $val['ForEmlog'];?>&nbsp | &nbsp<?php endif;?>
        <?php if ($val['Author'] != ''):?>
<!--vot--> <?=lang('user')?>: <?php if ($val['AuthorUrl'] != ''):?>
            <a href="<?php echo $val['AuthorUrl'];?>" target="_blank"><?php echo $val['Author'];?></a>
            <?php else:?>
            <?php echo $val['Author'];?>
            <?php endif;?>
        <?php endif;?>
        </div>
        </td>
        <td class="tdcenter">
<!--vot-->  <a href="javascript: em_confirm('<?php echo $key; ?>', 'plu', '<?php echo LoginAuth::genToken(); ?>');" class="care"><?=lang('delete')?></a>
        </td>
      </tr>
    <?php endforeach;else: ?>
      <tr>
<!--vot-->  <td class="tdcenter" colspan="5"><?=lang('plugin_no_installed')?></td>
      </tr>
    <?php endif;?>
    </tbody>
  </table>
<!--vot--><div><a href="javascript:displayToggle('plugin_new', 2);" class="btn btn-success"><?=lang('plugin_install')?>+</a></div>
<form action="./plugin.php?action=upload_zip" method="post" enctype="multipart/form-data" >
    <div id="plugin_new" class="form-group" style="margin:50px 0px;">
<!--vot--><li><?=lang('upload_install_info')?></li>
        <li>
            <input name="pluzip" type="file" />
        </li>
        <li>
<!--vot-->  <input type="submit" value="<?=lang('upload_install')?>" class="btn btn-primary" />
            <input name="token" id="token" value="<?php echo LoginAuth::genToken(); ?>" type="hidden" />
        </li>
<!--vot--><li style="margin:10px 0px;"><?=lang('plugin_get_more')?>:<a href="store.php"><?=lang('app_center')?></a></li>
    </div>
</form>
<script>
$("#plugin_new").css('display', $.cookie('em_plugin_new') ? $.cookie('em_plugin_new') : 'none');
$("#adm_plugin_list tbody tr:odd").addClass("tralt_b");
$("#adm_plugin_list tbody tr")
    .mouseover(function(){$(this).addClass("trover")})
    .mouseout(function(){$(this).removeClass("trover")})
setTimeout(hideActived,2600);
$("#menu_category_sys").addClass('active');
$("#menu_sys").addClass('in');
$("#menu_plug").addClass('active');
</script>
