<?php if (!defined('EMLOG_ROOT')) {
    exit('error!');
} ?>
<div class="container-fluid">
    <?php if (isset($_GET['activated'])): ?><div class="alert alert-success">模板更换成功</div><?php endif; ?>
    <?php if (isset($_GET['activate_install'])): ?><div class="alert alert-success">模板上传成功</div><?php endif; ?>
    <?php if (isset($_GET['activate_del'])): ?><div class="alert alert-success">删除模板成功</div><?php endif; ?>
    <?php if (isset($_GET['error_a'])): ?><div class="alert alert-danger">删除失败，请检查模板文件权限</div><?php endif; ?>
    <?php if (!$nonceTplData): ?><div class="alert alert-danger">当前使用的模板(<?php echo $nonce_templet; ?>)已被删除或损坏，请选择其他模板。</div><?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">模板管理</h1>
        <a href="./template.php?action=install" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="far fa-edit"></i> 安装新的模板</a>
    </div>
    <div class="card-columns">
        <?php foreach ($tpls as $key => $value): ?>
            <div class="card">
                <img class="card-img-top" src="<?php echo TPLS_URL . $value['tplfile']; ?>/preview.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $value['tplname']; ?></h5>
                    <a href="template.php?action=usetpl&tpl=<?php echo $value['tplfile']; ?>&side=<?php echo $value['sidebar']; ?>&token=<?php echo LoginAuth::genToken(); ?>"
                       class="btn btn-primary">使用该模板</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    setTimeout(hideActived, 2600);
    $("#menu_category_view").addClass('active');
    $("#menu_view").addClass('show');
    $("#menu_tpl").addClass('active');
</script>
