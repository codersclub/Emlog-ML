<?php if (!defined('EMLOG_ROOT')) {
	exit('error!');
} ?>
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <div class="mb-0 text-gray-800">
        <span class="h3"><?= lang('welcome') ?>, <a class="small" href="./blogger.php"><?= $user_cache[UID]['name'] ?></a></span>
        <span class="badge badge-primary ml-2"><?= $role_name ?></span>
    </div>
    <a href="./article.php?action=write" class="btn btn-sm btn-success shadow-sm mt-4"><i class="icofont-pencil-alt-5"></i> <?= lang('article_add') ?></a>
</div>
<div class="row">
    <div class="mb-3 col-lg-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?= lang('articles') ?></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="./article.php?checked=n"><?= $article_amount ?></a></div>
                    </div>
                    <div class="col-auto">
                        <i class="icofont-pencil-alt-5 fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3 col-lg-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            <?= lang('comments_received') ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="./comment.php?hide=y"><?= $comment_amount ?></a></div>
                    </div>
                    <div class="col-auto">
                        <i class="icofont-comment fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3 col-lg-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            <?= lang('twitters') ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="./user.php"><?= $note_amount ?></a></div>
                    </div>
                    <div class="col-auto">
                        <i class="icofont-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <h6 class="card-header"><?= lang('last_articles') ?></h6>
            <div class="card-body" id="admindex_msg">
                <ul class="list-group list-group-flush">
					<?php foreach ($logs as $v) : ?>
                        <li class="msg_type_0"><a href="<?= Url::log($v['gid']) ?>" target="_blank"><?= $v['title'] ?></a></li>
					<?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <h6 class="card-header"><?= lang('last_comments') ?></h6>
            <div class="card-body" id="admindex_msg">
                <ul class="list-group list-group-flush">
					<?php foreach ($comments as $v) : ?>
                        <li class="msg_type_0"><a href="<?= Url::log($v['gid']) ?>" target="_blank"><?= $v['comment'] ?></a></li>
					<?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
