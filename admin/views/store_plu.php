<?php if (!defined('EMLOG_ROOT')) {
	exit('error!');
} ?>
<?php if (isset($_GET['active'])): ?>
    <div class="alert alert-success"><?= lang('install_ok') ?></div><?php endif ?>
<?php if (isset($_GET['error_param'])): ?>
    <div class="alert alert-danger"><?= lang('install_failed') ?></div><?php endif ?>
<?php if (isset($_GET['error_down'])): ?>
    <div class="alert alert-danger"><?= lang('install_failed_download') ?></div><?php endif ?>
<?php if (isset($_GET['error_dir'])): ?>
    <div class="alert alert-danger"><?= lang('install_failed_write') ?></div><?php endif ?>
<?php if (isset($_GET['error_zip'])): ?>
    <div class="alert alert-danger"><?= lang('install_failed_zip') ?></div><?php endif ?>
<?php if (isset($_GET['error_source'])): ?>
    <div class="alert alert-danger"><?= lang('install_invalid_ext') ?></div><?php endif ?>

<?php if (isset($_GET['error'])): ?>
    <div class="container-fluid">
        <div class="text-center">
            <p class="lead text-gray-800 mb-5"><?= lang('store_unavailable') ?></p>
            <a href="./">&larr; <?= lang('back_home') ?></a>
        </div>
    </div>
<?php endif ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= lang('app_store') ?> - <?= $sub_title ?></h1>
</div>
<div class="row mb-4 ml-1 justify-content-between">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="./store.php"><?= lang('ext_store_templates') ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="./store.php?action=plu"><i class="icofont-plugin"></i> <?= lang('ext_store_plugins') ?></a></li>
<!--vot-->        <li class="nav-item"><a class="nav-link" href="./store.php?action=mine"><?=lang('my_apps')?></a></li>
    </ul>
    <form action="./store.php" method="get">
        <div class="form-inline search-inputs-nowrap">
            <input type="hidden" name="action" value="plu">
            <input type="text" name="keyword" value="<?= $keyword ?>" class="form-control m-1 small" placeholder="<?= lang('plugin_search') ?>">
            <div class="input-group-append">
                <button class="btn btn-sm btn-success" type="submit">
                    <i class="icofont-search-2"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<div class="row mb-3 ml-1">
    <a href="./store.php?action=plu" class="badge badge-success m-1 p-2 active"><?= lang('all') ?></a>
    <a href="./store.php?action=plu&tag=free" class="badge badge-success m-1 p-2"><?= lang('free_zone') ?></a>
    <a href="./store.php?action=plu&tag=paid" class="badge badge-warning m-1 ml-2 p-2"><?= lang('paid_zone') ?></a>
</div>
<div class="row">
	<?php if (!empty($plugins)): ?>
		<?php foreach ($plugins as $k => $v):
			$icon = $v['icon'] ?: "./views/images/plugin.png";
			?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <a class="p-1" href="<?= $v['buy_url'] ?>" target="_blank">
                        <img class="bd-placeholder-img card-img-top" width="100%" height="225" src="<?= $icon ?>">
                    </a>
                    <div class="card-body">
                        <p class="card-text"><?= $v['name'] ?></p>
                        <p class="card-text text-muted small">
							<?= $v['info'] ?><br><br>
<!--vot-->                  <?= lang('price') ?>: <?= $v['price'] > 0 ? '<span class="text-danger">' . $v['price'] . ' ' . lang('price_unit') . '</span>' : '<span class="text-success">' . lang('free') . '</span>' ?><br>
<!--vot-->		    <?= lang('developer') ?>: <?= $v['author'] ?> <a href="./store.php?action=plu&author_id=<?= $v['author_id'] ?>"><?=lang('this_author_only')?></a><br>
			    <?= lang('version_number') ?>: <?= $v['ver'] ?><br>
			    <?= lang('update_time') ?>: <?= $v['update_time'] ?><br>
                        </p>
                        <p class="card-text text-right">
							<?php if ($v['price'] > 0): ?>
<!--vot-->                                <a href="<?= $v['buy_url'] ?>" class="btn btn-warning btn-sm" target="_blank"><?= lang('go_buy') ?></a>
							<?php else: ?>
                                <a href="./store.php?action=install&source=<?= urlencode($v['download_url']) ?>&type=plugin" class="btn btn-success btn-sm"><?= lang('install_free') ?></a>
							<?php endif ?>
                        </p>
                    </div>
                </div>
            </div>
		<?php endforeach ?>
<!--vot-->        <div class="col-md-12 page my-5"><?= $pageurl ?> (<?=lang('have')?> <?= $count ?><?=lang('_plugins')?>)</div>
	<?php else: ?>
        <div class="col-md-12">
            <div class="alert alert-info"><?= lang('store_no_results') ?></div>
        </div>
	<?php endif ?>
</div>
<script>
    $("#menu_store").addClass('active');
    setTimeout(hideActived, 3600);
</script>
