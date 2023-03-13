<?php if (!defined('EMLOG_ROOT')) {
	exit('error!');
} ?>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">商店暂不可用，可能是网络问题</div><?php endif ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">应用商店 - <?= $sub_title ?></h1>
</div>
<div class="row mb-4 ml-1 justify-content-between">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link active" href="./store.php"><i class="icofont-paint"></i> 模板主题</a></li>
        <li class="nav-item"><a class="nav-link" href="./store.php?action=plu">扩展插件</a></li>
        <li class="nav-item"><a class="nav-link" href="./store.php?action=svip">铁杆SVIP专属</a></li>
        <li class="nav-item"><a class="nav-link" href="./store.php?action=mine">我的已购</a></li>
    </ul>
    <form action="./store.php" method="get">
        <div class="form-inline search-inputs-nowrap">
            <input type="text" name="keyword" value="<?= $keyword ?>" class="form-control m-1 small" placeholder="搜索模板...">
            <div class="input-group-append">
                <button class="btn btn-sm btn-success" type="submit">
                    <i class="icofont-search-2"></i>
                </button>
            </div>
        </div>
    </form>
</div>
<div class="row mb-3 ml-1">
    <a href="./store.php" class="badge badge-success m-1 p-2">全部</a>
    <a href="./store.php?tag=free" class="badge badge-success m-1 ml-2 p-2">仅看免费</a>
    <a href="./store.php?tag=paid" class="badge badge-warning m-1 ml-2 p-2">仅看付费</a>
</div>
<div class="mb-3">
	<?php if (!empty($templates)): ?>
        <div class="d-flex flex-wrap justify-content-center app-list">
            <?php foreach ($templates as $k => $v):
                $icon = $v['icon'] ?: "./views/images/theme.png";
                ?>
                <div class="card mb-4 mr-4 shadow-sm">
                    <a class="card-img-top-link d-flex border-bottom-light overflow-hidden" href="<?= $v['buy_url'] ?>" target="_blank">
                        <img class="bd-placeholder-img card-img-top" alt="cover" src="<?= $icon ?>">
                    </a>
                    <div class="card-body">
                        <p class="card-text font-weight-bold overflow-hidden text-nowrap name">
                            <?php if ($v['top'] === 1): ?>
                                <span class="badge badge-success p-1 mr-1">今日推荐</span>
                            <?php endif; ?>
                            <a class="text-secondary" href="<?= $v['buy_url'] ?>" target="_blank"><?= $v['name'] ?></a>
                        </p>

                        <div class="card-text d-flex justify-content-between">
                            <div class="price mb-4">
                                <?= $v['price'] > 0 ? '<span class="text-danger">¥ ' . $v['price'] . '元</span>' : '<span class="text-success">免费</span>' ?><br>
                            </div>
                            <div class="installMsg"></div>
                            <?php if ($v['price'] > 0): ?>
                                <a href="<?= $v['buy_url'] ?>" class="btn btn-sm btn-warning btn-sm" target="_blank">去购买</a>
                            <?php else: ?>
                                <a href="#" class="btn btn-success btn-sm installBtn" data-url="<?= urlencode($v['download_url']) ?>" data-type="tpl">免费安装</a>
                            <?php endif ?>
                        </div>
                        <p class="card-text text-muted small">
                            开发者：&nbsp;&nbsp;&nbsp;&nbsp;<?= $v['author'] ?> <a href="./store.php?author_id=<?= $v['author_id'] ?>">仅看Ta的作品</a><br>
                            版本号：&nbsp;&nbsp;&nbsp;&nbsp;<?= $v['ver'] ?><br>
                            更新时间：<?= $v['update_time'] ?><br>
                        </p>
                        <div class="small"><?= $v['info'] ?></div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="page my-5"><?= $pageurl ?> (有<?= $count ?>个模板)</div>
	<?php else: ?>
        <div class="alert alert-info">暂未找到结果，应用商店进货中，敬请期待：）</div>
	<?php endif ?>
</div>
<script>
    $("#menu_store").addClass('active');
    setTimeout(hideActived, 3600);
</script>
