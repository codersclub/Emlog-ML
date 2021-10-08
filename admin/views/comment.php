<?php if (!defined('EMLOG_ROOT')) {
	exit('error!');
} ?>

<?php if (isset($_GET['active_del'])): ?>
<!--vot--><div class="alert alert-success"><?=lang('comment_delete_ok')?></div><?php endif; ?>
<?php if (isset($_GET['active_show'])): ?>
<!--vot--><div class="alert alert-success"><?=lang('comment_audit_ok')?></div><?php endif; ?>
<?php if (isset($_GET['active_hide'])): ?>
<!--vot--><div class="alert alert-success"><?=lang('comment_hide_ok')?></div><?php endif; ?>
<?php if (isset($_GET['active_edit'])): ?>
<!--vot--><div class="alert alert-success"><?=lang('comment_edit_ok')?></div><?php endif; ?>
<?php if (isset($_GET['active_rep'])): ?>
<!--vot--><div class="alert alert-success"><?=lang('comment_reply_ok')?></div><?php endif; ?>
<?php if (isset($_GET['error_a'])): ?>
<!--vot--><div class="alert alert-danger"><?=lang('comment_choose_operation')?></div><?php endif; ?>
<?php if (isset($_GET['error_b'])): ?>
<!--vot--><div class="alert alert-danger"><?=lang('select_action_to_perform')?></div><?php endif; ?>
<?php if (isset($_GET['error_c'])): ?>
<!--vot--><div class="alert alert-danger"><?=lang('reply_is_empty')?></div><?php endif; ?>
<?php if (isset($_GET['error_d'])): ?>
<!--vot--><div class="alert alert-danger"><?=lang('comment_too_long')?></div><?php endif; ?>
<?php if (isset($_GET['error_e'])): ?>
<!--vot--><div class="alert alert-danger"><?=lang('comment_is_empty')?></div><?php endif; ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
<!--vot--><h1 class="h3 mb-0 text-gray-800"><?= lang('comment_management') ?></h1>
</div>
<?php if ($hideCommNum > 0) : ?>
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link <?php if ($hide == '') {
					echo 'active';
/*vot*/         } ?>" href="./comment.php?<?php echo $addUrl_1 ?>"><?=lang('all')?></a></li>
            <li class="nav-item"><a class="nav-link <?php if ($hide == 'y') {
					echo 'active';
/*vot*/         } ?>" href="./comment.php?hide=y&<?php echo $addUrl_1 ?>"><?=lang('pending')?><?php
					$hidecmnum = ROLE == ROLE_ADMIN ? $sta_cache['hidecomnum'] : $sta_cache[UID]['hidecommentnum'];
					if ($hidecmnum > 0) echo '(' . $hidecmnum . ')';
					?></a>
            </li>
        </ul>
    </div>
<?php endif; ?>
<form action="comment.php?action=admin_all_coms" method="post" name="form_com" id="form_com">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"/></th>
<!--vot-->              <th><?= lang('content') ?></th>
<!--vot-->              <th><?= lang('comment_author') ?></th>
<!--vot-->              <th><?= lang('time') ?></th>
<!--vot-->              <th><?= lang('belongs_to_article') ?></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ($comment as $key => $value):
/*vot*/                 $ishide = $value['hide'] == 'y' ? '<span class="text-danger">[' . lang('pending') . ']</span>' : '';
						$mail = $value['mail'] ? "({$value['mail']})" : '';
						$ip = $value['ip'];
						$gid = $value['gid'];
						$cid = $value['cid'];
/*vot*/					$ip_info = $ip ? '<br />' . lang('from_ip') . ': ' . $ip : '';
						$comment = $value['comment'];
						$poster = $value['poster'] ?: '';
						$title = subString($value['title'], 0, 42);
						$hide = $value['hide'];
						$date = $value['date'];
						doAction('adm_comment_display');
						?>
                        <tr>
                            <td width="19"><input type="checkbox" value="<?php echo $cid; ?>" name="com[]" class="ids"/></td>
                            <td width="350">
                                <a href="#" data-toggle="modal" data-target="#replyModal"
                                   data-cid="<?php echo $cid; ?>"
                                   data-comment="<?php echo $comment; ?>"
                                   data-hide="<?php echo $value['hide']; ?>"
                                   data-gid="<?php echo $gid; ?> ">
									<?php echo $comment; ?>
                                </a>
								<?php echo $ishide; ?>
                            </td>
                            <td class="small">
<!--vot-->                      <?php echo $poster; ?> <?php echo $mail; ?> <?php echo $ip_info; ?>
								<?php if (ROLE == ROLE_ADMIN): ?>
<!--vot-->                          <a href="javascript: em_confirm('<?php echo $ip; ?>', 'commentbyip', '<?php echo LoginAuth::genToken(); ?>');"
                                       class="badge badge-pill badge-warning"><?=lang('del_from_ip')?></a>
								<?php endif; ?>
                            </td>
                            <td class="small"><?php echo $date; ?></td>
                            <td class="small"><a href="<?php echo Url::log($gid); ?>"><?php echo $title; ?></a></td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="list_footer">
                <div class="btn-group btn-group-sm" role="group">
<!--vot-->        <a href="javascript:commentact('del');" class="btn btn-sm btn-danger"><?=lang('delete')?></a>
<!--vot-->        <a href="javascript:commentact('hide');" class="btn btn-sm btn-success"><?=lang('hide')?></a>
<!--vot-->        <a href="javascript:commentact('pub');" class="btn btn-sm btn-success"><?=lang('approve')?></a>
                </div>
                <input name="operate" id="operate" value="" type="hidden"/>
            </div>
<!--vot-->  <div class="page"><?php echo $pageurl; ?> (<?=lang('have')?> <?php echo $cmnum; ?> <?=lang('_comments')?>)</div>
        </div>
    </div>
</form>
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
<!--vot-->      <h5 class="modal-title" id="replyModalLabel"><?=lang('comment_reply')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="comment.php?action=doreply" method="post">
                <div class="modal-body">
                    <p></p>
                    <div class="form-group">
                        <input type="hidden" value="" name="cid" id="cid"/>
                        <input type="hidden" value="" name="gid" id="gid"/>
                        <input type="hidden" value="" name="hide" id="hide"/>
                        <textarea class="form-control" id="reply" name="reply" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
<!--vot-->          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><?=lang('cancel')?></button>
<!--vot-->          <button type="submit" class="btn btn-sm btn-success"><?=lang('reply')?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#menu_cm").addClass('active');
    setTimeout(hideActived, 2600);

    function commentact(act) {
        if (getChecked('ids') == false) {
/*vot*/     alert('<?=lang('comment_operation_select')?>');
            return;
        }
/*vot*/ if (act == 'del' && !confirm('<?=lang('comment_selected_delete_sure')?>')) {
            return;
        }
        $("#operate").val(act);
        $("#form_com").submit();
    }

    $('#replyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var comment = button.data('comment')
        var cid = button.data('cid')
        var gid = button.data('gid')
        var hide = button.data('hide')
        var modal = $(this)
        modal.find('.modal-body p').html(removeHTMLTag(comment))
        modal.find('.modal-body #cid').val(cid)
        modal.find('.modal-body #gid').val(gid)
        modal.find('.modal-body #hide').val(hide)
    })
</script>
