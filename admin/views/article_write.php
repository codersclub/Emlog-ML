<?php if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
?>
<div id="msg" class="fixed-top alert" style="display: none"></div>
<h1 class="h3 mb-4 text-gray-800"><?= $containertitle ?> <span id="save_info"></span></h1>
<form action="article_save.php" method="post" enctype="multipart/form-data" id="addlog" name="addlog">
    <div class="row">
        <div class="col-xl-12">
            <div id="post" class="form-group">
                <div>
                    <input type="text" name="title" id="title" value="<?= $title ?>" class="form-control" placeholder="文章标题" autofocus required/>
                </div>
                <div id="post_bar" class="small my-3">
                    <a href="#mediaModal" data-toggle="modal" data-target="#mediaModal"><i class="icofont-plus"></i>上传插入图片</a>
                    <?php doAction('adm_writelog_head') ?>
                </div>
                <div id="logcontent"><textarea><?= $content ?></textarea></div>
            </div>
            <div class="form-group">
                <label>文章摘要：</label>
                <div id="logexcerpt"><textarea><?= $excerpt ?></textarea></div>
            </div>
            <div class="show_advset" id="displayToggle" onclick="displayToggle('advset');">更多选项<i class="icofont-simple-right"></i></div>
            <div id="advset" class="shadow-sm p-3 mb-2 bg-white rounded">
                <div class="form-group">
                    <label>文章封面：</label>
                    <input name="cover" id="cover" class="form-control" placeholder="封面图地址URL，手动填写或点击下方图片区域上传" value="<?= $cover ?>"/>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="upload_img">
                                <img src="<?= $cover ?: './views/images/cover.svg' ?>" width="200" id="cover_image" class="rounded" alt="封面图片"/>
                                <input type="file" name="upload_img" class="image" id="upload_img" style="display:none"/>
                                <button type="button" id="cover_rm" class="btn-sm btn btn-link" <?php if (!$cover): ?>style="display:none"<?php endif ?>>x</button>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>分类：</label>
                    <select name="sort" id="sort" class="form-control">
                        <option value="-1">选择分类...</option>
                        <?php
                        foreach ($sorts as $key => $value):
                            if ($value['pid'] != 0) {
                                continue;
                            }
                            $flg = $value['sid'] == $sortid ? 'selected' : '';
                            ?>
                            <option value="<?= $value['sid'] ?>" <?= $flg ?>><?= $value['sortname'] ?></option>
                            <?php
                            $children = $value['children'];
                            foreach ($children as $key):
                                $value = $sorts[$key];
                                $flg = $value['sid'] == $sortid ? 'selected' : '';
                                ?>
                                <option value="<?= $value['sid'] ?>" <?= $flg ?>>&nbsp; &nbsp; &nbsp; <?= $value['sortname'] ?></option>
                            <?php
                            endforeach;
                        endforeach;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>标签：<small class="text-muted">(也用于文章页关键词， 多个用英文逗号分隔)</small></label>
                    <input name="tag" id="tag" class="form-control" value="<?= $tagStr ?>"/>
                    <?php if ($tags): ?>
                        <span class="small"><a href="javascript:doToggle('tags', 1);">近期使用的+</a></span>
                        <div id="tags" style="display: none">
                            <?php
                            foreach ($tags as $val) {
                                echo " <a class=\"badge badge-primary\" href=\"javascript: insertTag('{$val['tagname']}','tag');\">{$val['tagname']}</a> ";
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>发布时间：<small class="text-muted">（当设置未来的时间点时，文章将在该时间点后定时发布）</small></label>
                    <input type="text" maxlength="200" name="postdate" id="postdate" value="<?= $postDate ?>" class="form-control"/>
                </div>
                <div class="form-group">
                    <label>链接别名：<small class="text-muted">（用于seo设置 <a href="./setting.php?action=seo">&rarr;</a>）</small></label>
                    <input name="alias" id="alias" class="form-control" value="<?= $alias ?>"/>
                </div>
                <div class="form-group">
                    <label>跳转链接：<small class="text-muted">（填写后不展示文章内容直接跳转该地址）</small></label>
                    <input name="link" id="link" type="url" class="form-control" value="<?= $link ?>" placeholder="https://"/>
                </div>
                <div class="form-group">
                    <label>访问密码：</label>
                    <input type="text" name="password" id="password" class="form-control" value="<?= $password ?>"/>
                </div>
                <div class="form-group">
                    <input type="checkbox" value="y" name="allow_remark" id="allow_remark" <?= $is_allow_remark ?> />
                    <label for="allow_remark">允许评论</label>
                </div>
            </div>
            <div id="post_button">
                <input type="hidden" name="ishide" id="ishide" value="<?= $hide ?>"/>
                <input type="hidden" name="as_logid" id="as_logid" value="<?= $logid ?>"/>
                <input type="hidden" name="gid" id="gid" value="<?= $logid ?>"/>
                <input type="hidden" name="author" id="author" value="<?= $author ?>"/>
                <?php if ($logid < 0): ?>
                    <input type="submit" name="pubPost" id="pubPost" value="发布文章" onclick="return checkform();" class="btn btn-sm btn-success"/>
                    <input type="button" name="savedf" id="savedf" value="保存草稿" onclick="autosave(2);" class="btn btn-sm btn-primary"/>
                <?php else: ?>
                    <input type="submit" value="保存并返回" onclick="return checkform();" class="btn btn-sm btn-success"/>
                    <input type="button" name="savedf" id="savedf" value="保存" onclick="autosave(2);" class="btn btn-sm btn-primary"/>
                    <?php if ($isdraft) : ?>
                        <input type="submit" name="pubPost" id="pubPost" value="发布" onclick="return checkform();" class="btn btn-sm btn-success"/>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</form>

<div class="modal" id="mediaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">图文资源</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <div><a href="#" id="mediaAdd" class="btn btn-sm btn-success shadow-sm mb-3">上传图片/文件</a></div>
                    <div>
                        <?php if (User::haveEditPermission() && $mediaSorts): ?>
                            <select class="form-control" id="media-sort-select">
                                <option value="">选择资源分类…</option>
                                <?php foreach ($mediaSorts as $v): ?>
                                    <option value="<?= $v['id'] ?>"><?= $v['sortname'] ?></option>
                                <?php endforeach ?>
                            </select>
                        <?php endif ?>
                    </div>
                </div>
                <form action="media.php?action=operate_media" method="post" name="form_media" id="form_media">
                    <div class="row" id="image-list"></div>
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-sm mt-2" id="load-more">加载更多…</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="dropzone-previews" style="display: none;"></div>
<script src="./views/js/dropzone.min.js?t=<?= Option::EMLOG_VERSION_TIMESTAMP ?>"></script>
<script src="./views/js/media-lib.js?t=<?= Option::EMLOG_VERSION_TIMESTAMP ?>"></script>
<!-- 封面图裁剪 -->
<div class="modal fade" id="modal" tabindex="-2" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">裁剪并上传</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-11">
                            <img src="" id="sample_image"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>按住 Shift 等比例调整裁剪区域</div>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" id="crop" class="btn btn-sm btn-success">保存</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./editor.md/editormd.js?t=<?= Option::EMLOG_VERSION_TIMESTAMP ?>"></script>
<script>
    $("#alias").keyup(function () {
        checkalias();
    });
    setTimeout("autosave(1)", 60000);
    $("#menu_category_content").addClass('active');
    $("#menu_content").addClass('show');
    $("#menu_write").addClass('active');

    // 编辑器
    var Editor, Editor_summary;
    $(function () {
        Editor = editormd("logcontent", {
            width: "100%",
            height: 640,
            toolbarIcons: function () {
                return ["undo", "redo", "|", "bold", "del", "italic", "quote", "|", "h1", "h2", "h3", "|", "list-ul", "list-ol", "hr", "|",
                    "link", "image", "video", "preformatted-text", "code-block", "table", "|", "search", "watch", "help", "fullscreen"]
            },
            path: "editor.md/lib/",
            tex: false,
            watch: false,
            htmlDecode: true,
            flowChart: false,
            autoFocus: false,
            sequenceDiagram: false,
            imageUpload: true,
            imageFormats: ["jpg", "jpeg", "gif", "png"],
            imageUploadURL: "media.php?action=upload&editor=1",
            videoUpload: false, //开启视频上传
            syncScrolling: "single",
            onload: function () {
                hooks.doAction("loaded", this);
                //在大屏模式下，编辑器默认显示预览
                if ($(window).width() > 767) {
                    this.watch();
                }
            }
        });
        Editor_summary = editormd("logexcerpt", {
            width: "100%",
            height: 300,
            toolbarIcons: function () {
                return ["undo", "redo", "|", "bold", "del", "italic", "quote", "|", "h1", "h2", "h3", "|", "list-ul", "list-ol", "hr", "|", "link", "image", "|", "watch"]
            },
            path: "editor.md/lib/",
            tex: false,
            watch: false,
            htmlDecode: true,
            flowChart: false,
            autoFocus: false,
            sequenceDiagram: false,
            placeholder: "如果留空，则使用文章内容作为摘要...",
            onload: function () {
                hooks.doAction("sum_loaded", this);
            }
        });
        Editor.setToolbarAutoFixed(false);
        Editor_summary.setToolbarAutoFixed(false);
    });

    // 封面图
    $(function () {
        var $modal = $('#modal');
        var image = document.getElementById('sample_image');
        var cropper;
        $('#upload_img').change(function (event) {
            var files = event.target.files;
            var done = function (url) {
                image.src = url;
                $modal.modal('show');
            };
            if (files && files.length > 0) {
                reader = new FileReader();
                reader.onload = function (event) {
                    done(reader.result);
                };
                reader.readAsDataURL(files[0]);
            }
        });
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: NaN,
                viewMode: 1
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });
        $('#crop').click(function () {
            canvas = cropper.getCroppedCanvas({
                width: 650,
                height: 366
            });
            canvas.toBlob(function (blob) {
                var formData = new FormData();
                formData.append('image', blob, 'cover.jpg');
                $.ajax('./article.php?action=upload_cover', {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $modal.modal('hide');
                        if (data != "error") {
                            $('#cover_image').attr('src', data);
                            $('#cover').val(data);
                            $('#cover_rm').show();
                        }
                    }
                });
            });
        });

        $('#cover_rm').click(function () {
            $('#cover_image').attr('src', "./views/images/cover.svg");
            $('#cover').val("");
            $('#cover_rm').hide();
        });
    });

    $('#cover').blur(function () {
            c = $('#cover').val();
            if (!c) {
                $('#cover_image').attr('src', "./views/images/cover.svg");
                $('#cover_rm').hide();
                return
            }
            $('#cover_image').attr('src', c);
            $('#cover_rm').show();
        }
    );

    // 离开页面时，如果文章内容已做修改，则询问用户是否离开
    var articleTextRecord;
    hooks.addAction("loaded", function () {
        articleTextRecord = $("textarea[name=logcontent]").text();
    });
    window.onbeforeunload = function (e) {
        if ($("textarea[name=logcontent]").text() == articleTextRecord) return
        e = e || window.event;
        if (e) e.returnValue = '离开页面提示';
        return '离开页面提示';
    }

    // 如果文章内容已做修改，则使网页标题修改为‘已修改’
    var titleText = $('title').text()
    hooks.addAction("loaded", function (obj) {
        obj.config({
            onchange: function () {
                if ($("textarea[name=logcontent]").text() == articleTextRecord) return
                $('title').text('[已修改] ' + titleText);
            }
        });
    });

    // 文章编辑界面全局快捷键 Ctrl（Cmd）+ S 保存内容
    document.addEventListener('keydown', function (e) {
        if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
            e.preventDefault();
            autosave(2);
        }
    });

    // Use cookie to decide whether to collapse [More Options]
    if (Cookies.get('em_advset') === "right") {
        $("#advset").toggle();
        icon_mod = "right";
        $(".icofont-simple-down").attr("class", "icofont-simple-right")
    } else {
        $(".icofont-simple-right").attr("class", "icofont-simple-down")
    }
</script>
