<?php if(!defined('EMLOG_ROOT')) {exit('error!');}?>
<div class="containertitle2">
<a class="navi3" href="./template.php">当前模板</a>
<a class="navi4" href="./template.php?action=install">安装模板</a>
<?php if(isset($_GET['activated'])):?><span class="actived">模板更换成功</span><?php endif;?>
</div>
<?php if(!$nonceTplData): ?>
<div class="error_msg">当前使用的模板(<?php echo $nonce_templet; ?>)已被删除或损坏，请选择其他模板。</div>
<?php else:?>
<table class="table table-striped table-bordered table-hover dataTable no-footer">
    <tr>
      <td width="42%">
	  <img src="<?php echo TPLS_URL.$nonce_templet; ?>/preview.jpg" width="240" height="180"  border="1" />	  </td>
	  <td width="58%">
	  <?php echo $tplName; ?> <em><?php echo $tplVer; ?></em><br>
	  <?php echo $tplAuthor; ?><br>
	  <?php echo $tplDes; ?>
	  <?php if ('default' == $nonce_templet): ?>
	  <div class="custom_top_button"><a href="./template.php?action=custom-top">自定义顶部图片</a></div>
	  <?php endif; ?>
	  </td>
    </tr>
</table>
<?php endif;?>
<div class="containertitle2">
<span class="navi3">模板库 (<?php echo $tplnums; ?>)</span>
<a name="tpllib"></a>
<?php if(isset($_GET['activate_install'])):?><span class="actived">模板上传成功</span><?php endif;?>
<?php if(isset($_GET['activate_del'])):?><span class="actived">删除模板成功</span><?php endif;?>
<?php if(isset($_GET['error_a'])):?><span class="error">删除失败，请检查模板文件权限</span><?php endif;?>
</div>
<table class="table table-striped table-bordered table-hover dataTable no-footer">
<?php 
$i = 0;
foreach($tpls as $key=>$value):
if($i % 3 == 0){echo "<tr>";}
$i++;
?>
      <td align="center" width="300">
	  <a href="template.php?action=usetpl&tpl=<?php echo $value['tplfile']; ?>&side=<?php echo $value['sidebar']; ?>&token=<?php echo LoginAuth::genToken(); ?>">
	  <img alt="点击使用该模板" src="<?php echo TPLS_URL.$value['tplfile']; ?>/preview.jpg" width="180" height="150" border="0" />
	  </a><br />
      <?php echo $value['tplname']; ?>
      <span> | <a href="javascript: em_confirm('<?php echo $value['tplfile']; ?>', 'tpl', '<?php echo LoginAuth::genToken(); ?>');" class="care">删除</a></span>
      </td>
<?php 
if($i > 0 && $i % 3 == 0){echo "</tr>";}
endforeach; 
?>
</table>
<script>
setTimeout(hideActived,2600);
$("#menu_tpl").addClass('sidebarsubmenu1');
</script>
