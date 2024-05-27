<?php
/**
 * 首页模板
 */
defined('EMLOG_ROOT') || exit('access denied!');
?>
    <main class="container blog-container">
        <div class="row">
            <div class="column-big">
                <?php doAction('index_loglist_top');
                if (!empty($logs)):
                    foreach ($logs as $value):
                        ?>
                        <div class="shadow-theme bottom-5">
                            <?php if (!empty($value['log_cover'])) : ?>
                                <div class="loglist-cover">
                                    <img src="<?= $value['log_cover'] ?>" alt="article cover" class="rea-width" data-action="zoom">
                                </div>
                            <?php endif ?>
                            <div class="card-padding loglist-body">
                                <h3 class="card-title">
                                    <a href="<?= $value['log_url'] ?>" class="loglist-title"><?= $value['log_title'] ?></a>
                                    <?php topflg($value['top'], $value['sortop'], isset($sortid) ? $sortid : '') ?>
                                    <?php bloglist_sort($value['sortid']) ?>
                                </h3>
                                <div class="loglist-content markdown"><?php echo subContent($value['log_description'], 180, 1); ?></div>
                                <div class="loglist-tag"><?php blog_tag($value['logid']) ?></div>
                            </div>
                            <div class="row info-row">
                                <div class="log-info">
                                    <?php blog_author($value['author']) ?>&nbsp;发布于&nbsp;
                                    <?= date('Y-n-j H:i', $value['date']) ?>&nbsp;
                                </div>
                                <div class="log-count">
                                    <a href="<?= $value['log_url'] ?>"><?= $value['views'] ?>&nbsp;阅读</a>
                                    <a href="<?= $value['log_url'] ?>#comment"><?= $value['comnum'] ?>&nbsp;评论</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else:
                    ?>
                    <p>抱歉，暂时还没有内容。</p>
                <?php endif ?>
                <div class="pagination bottom-5">
                    <?= $page_url ?>
                </div>
            </div>
            <?php include View::getView('side') ?>
        </div>
    </main>

<?php include View::getView('footer') ?>