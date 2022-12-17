function getChecked(node) {
    let re = false;
    $('input.' + node).each(function (i) {
        if (this.checked) {
            re = true;
        }
    });
    return re;
}

function timestamp() {
    return new Date().getTime();
}

function em_confirm(id, property, token) {
    let url, msg;
    let text = '删除后可能无法恢复'
    switch (property) {
        case 'article':
            url = 'article.php?action=del&gid=' + id;
            msg = lang('article_del_sure');
            break;
        case 'draft':
            url = 'article.php?action=del&draft=1&gid=' + id;
            msg = lang('draft_del_sure');
            break;
        case 'tw':
            url = 'twitter.php?action=del&id=' + id;
            msg = lang('twitter_del_sure');
            break;
        case 'comment':
            url = 'comment.php?action=del&id=' + id;
            msg = lang('comment_del_sure');
            break;
        case 'commentbyip':
            url = 'comment.php?action=delbyip&ip=' + id;
            msg = lang('comment_ip_del_sure');
            break;
        case 'link':
            url = 'link.php?action=dellink&linkid=' + id;
            msg = lang('link_del_sure');
            break;
        case 'navi':
            url = 'navbar.php?action=del&id=' + id;
            msg = lang('navi_del_sure');
            break;
        case 'media':
            url = 'media.php?action=delete&aid=' + id;
            msg = lang('attach_del_sure');
            break;
        case 'avatar':
            url = 'blogger.php?action=delicon';
            msg = lang('avatar_del_sure');
            break;
        case 'sort':
            url = 'sort.php?action=del&sid=' + id;
            msg = lang('category_del_sure');
            break;
        case 'del_user':
            url = 'user.php?action=del&uid=' + id;
            msg = lang('user_del_sure');
            break;
        case 'forbid_user':
            url = 'user.php?action=forbid&uid=' + id;
            msg = lang('user_disable_sure');
            text = '';
            break;
        case 'tpl':
            url = 'template.php?action=del&tpl=' + id;
            msg = lang('template_del_sure');
            break;
        case 'reset_widget':
            url = 'widgets.php?action=reset';
            msg = lang('plugin_reset_sure');
            text = '';
            break;
        case 'plu':
            url = 'plugin.php?action=del&plugin=' + id;
            msg = lang('plugin_del_sure');
            break;
        case 'media_sort':
            url = 'media.php?action=del_media_sort&id=' + id;
            msg = lang('media_category_del_sure');
            text = '不会删除分类下资源文件';
            break;
    }
    swal({
        title: msg,
        text: text,
        icon: "warning",
        buttons: ["取消", "确定"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            window.location = url + '&token=' + token;
        }
    });
}

function focusEle(id) {
    try {
        document.getElementById(id).focus();
    } catch (e) {
    }
}

function hideActived() {
    $(".alert-success").slideUp(300);
    $(".alert-danger").slideUp(300);
}

// Click action of [More Options]
let icon_mod = "down";

function displayToggle(id) {
    $("#" + id).toggle();
    if (icon_mod === "down") {
        icon_mod = "right";
        $(".icofont-simple-down").attr("class", "icofont-simple-right")
    } else {
        icon_mod = "down";
        $(".icofont-simple-right").attr("class", "icofont-simple-down")
    }

    Cookies.set('em_' + id, icon_mod, {expires: 365})
}

function doToggle(id) {
    $("#" + id).toggle();
}

function insertTag(tag, boxId) {
    var targetinput = $("#" + boxId).val();
    if (targetinput == '') {
        targetinput += tag;
    } else {
        var n = ',' + tag;
        targetinput += n;
    }
    $("#" + boxId).val(targetinput);
    if (boxId == "tag") $("#tag_label").hide();
}

function isalias(a) {
    var reg1 = /^[\u4e00-\u9fa5\w-]*$/;
    var reg2 = /^[\d]+$/;
    var reg3 = /^post(-\d+)?$/;
    if (!reg1.test(a)) {
        return 1;
    } else if (reg2.test(a)) {
        return 2;
    } else if (reg3.test(a)) {
        return 3;
    } else if (a == 't' || a == 'm' || a == 'admin') {
        return 4;
    } else {
        return 0;
    }
}

function checkform() {
    var a = $.trim($("#alias").val());
    var t = $.trim($("#title").val());

/*vot*/ if (typeof articleTextRecord !== "undefined") {  // When submitting, reset the original text record value to prevent the leaving prompt from appearing
        articleTextRecord = $("textarea[name=logcontent]").text();
    } else {
        pageText = $("textarea").text();
    }
    if (0 == isalias(a)) {
        return true;
    } else {
        alert(lang('alias_link_error'));
        $("#alias").focus();
        return false;
    }
}

function checkalias() {
    var a = $.trim($("#alias").val());
    if (1 == isalias(a)) {
        $("#alias_msg_hook").html('<span id="input_error">' + lang('alias_invalid_chars') + '</span>');
    } else if (2 == isalias(a)) {
        $("#alias_msg_hook").html('<span id="input_error">' + lang('alias_digital') + '</span>');
    } else if (3 == isalias(a)) {
        $("#alias_msg_hook").html('<span id="input_error">' + lang('alias_format_must_be') + '</span>');
    } else if (4 == isalias(a)) {
        $("#alias_msg_hook").html('<span id="input_error">' + lang('alias_system_conflict') + '</span>');
    } else {
        $("#alias_msg_hook").html('');
        $("#msg").html('');
    }
}

function insert_media_img(fileurl, imgsrc) {
    Editor.insertValue('[![](' + imgsrc + ')](' + fileurl + ')\n\n');
}

function insert_media_video(fileurl) {
    Editor.insertValue('<video class=\"video-js\" controls preload=\"auto\" width=\"100%\" data-setup=\'{"aspectRatio":"16:9"}\'> <source src="' + fileurl + '" type=\'video/mp4\' > </video>');
}

function insert_media(fileurl, filename) {
    Editor.insertValue('[' + filename + '](' + fileurl + ')\n\n');
}

function insert_cover(imgsrc) {
    $('#cover_image').attr('src', imgsrc);
    $('#cover').val(imgsrc);
    $('#cover_rm').show();
}

// act 1：auto save 2：save
function autosave(act) {
    var nodeid = "as_logid";
    var timeout = 30000;
    var url = "article_save.php?action=autosave";
    var title = $.trim($("#title").val());
    var cover = $.trim($("#cover").val());
    var alias = $.trim($("#alias").val());
    var sort = $.trim($("#sort").val());
    var postdate = $.trim($("#postdate").val());
    var date = $.trim($("#date").val());
    var logid = $("#as_logid").val();
    var author = $("#author").val();
    var content = Editor.getMarkdown();
    var excerpt = Editor_summary.getMarkdown();
    var tag = $.trim($("#tag").val());
    var top = $("#top").is(":checked") ? 'y' : 'n';
    var sortop = $("#sortop").is(":checked") ? 'y' : 'n';
    var allow_remark = $("#allow_remark").is(":checked") ? 'y' : 'n';
    var password = $.trim($("#password").val());
    var ishide = $.trim($("#ishide").val());
    var token = $.trim($("#token").val());
    var ishide = ishide == "" ? "y" : ishide;
    var querystr = "logcontent=" + encodeURIComponent(content) + "&logexcerpt=" + encodeURIComponent(excerpt) + "&title=" + encodeURIComponent(title) + "&cover=" + encodeURIComponent(cover) + "&alias=" + encodeURIComponent(alias) + "&author=" + author + "&sort=" + sort + "&postdate=" + postdate + "&date=" + date + "&tag=" + encodeURIComponent(tag) + "&top=" + top + "&sortop=" + sortop + "&allow_remark=" + allow_remark + "&password=" + password + "&token=" + token + "&ishide=" + ishide + "&as_logid=" + logid;

    if (alias != '' && 0 != isalias(alias)) {
        $("#msg").show().html(lang('alias_link_error_not_saved'));
        if (act == 0) {
            setTimeout("autosave(1)", timeout);
        }
        return;
    }
    // Do not automatically save when editing published article
    if (act == 1 && ishide == 'n') {
        return;
    }
    // Do not save automatically when the content is empty
    if (act == 1 && content == "") {
        setTimeout("autosave(1)", timeout);
        return;
    }
    // Manual saving is not allowed when the last successful save time is less than one second
    if ((new Date().getTime() - Cookies.get('em_saveLastTime')) < 1000 && act != 1) {
        alert(lang('too_quick'));
        return;
    }
    var btname = $("#savedf").val();
    $("#savedf").val(lang('saving'));
    $('title').text(lang('saving_in') + titleText);
    $("#savedf").attr("disabled", "disabled");
    $.post(url, querystr, function (data) {
        data = $.trim(data);
        var isresponse = /autosave\_gid\:\d+\_df\:\d*\_/;
        if (isresponse.test(data)) {
            var getvar = data.match(/\_gid\:([\d]+)\_df\:([\d]*)\_/);
            var logid = getvar[1];
            var d = new Date();
            var h = d.getHours();
            var m = d.getMinutes();
            var s = d.getSeconds();
            var tm = (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
            $("#save_info").html(lang('saved_ok_time')+ tm);
            $('title').text(lang('saved_ok') + titleText);
            articleTextRecord = $("textarea[name=logcontent]").text();  // After the save is successful, replace the original text record value with the current text
            Cookies.set('em_saveLastTime', new Date().getTime());  // Put (or update) the save success timestamp into a cookie
            $("#" + nodeid).val(logid);
            $("#savedf").attr("disabled", false).val(btname);
        } else {
            $("#savedf").attr("disabled", false).val(btname);
            $("#msg").html(lang('save_system_error')).addClass("alert-danger");
            $('title').text(lang('save_failed') + titleText);
            alert(lang('save_failed_prompt'))
        }
    });
    if (act == 1) {
        setTimeout("autosave(1)", timeout);
    }
}

// editor.md: Page AutoSave shortcut: Ctrl + S
function pagesave() {
/*vot*/ document.addEventListener('keydown', function (e) {  // Prevents the browser default action from autosave
        if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
            e.preventDefault();
        }
    });

    let url = "page.php?action=save";

    $.post(url, $("#addlog").serialize(), function (data) {
        let titleText = $('title').text();
        $('title').text('[保存成功] ' + titleText);
        setTimeout(function () {
            $('title').text(titleText);
        }, 2000);
    }).fail(function () {
/*vot*/ $('title').text(lang('save_failed') + $('title').text());
/*vot*/ alert(lang('save_failed!'))
    });
}

// toggle plugin
$.fn.toggleClick = function () {
    var functions = arguments;
    return this.click(function () {
        var iteration = $(this).data('iteration') || 0;
        functions[iteration].apply(this, arguments);
        iteration = (iteration + 1) % functions.length;
        $(this).data('iteration', iteration);
    });
};

function removeHTMLTag(str) {
    str = str.replace(/<\/?[^>]*>/g, ''); //去除HTML tag
    str = str.replace(/[ | ]*\n/g, '\n'); //去除行尾空白
    str = str.replace(/ /ig, '');
    return str;
}

// Select all forms
$(function () {
    $('#checkAll').click(function (event) {
        let tr_checkbox = $('table tbody tr').find('input[type=checkbox]');
        tr_checkbox.prop('checked', $(this).prop('checked'));
        event.stopPropagation();
    });
    // Click on the checkbox in each row of the table, and when the number of checkboxes selected in the table = the number of table rows, set the "checkAll" radio box in the header of the table to be selected, otherwise it is unselected
    $('table tbody tr').find('input[type=checkbox]').click(function (event) {
        let tbr = $('table tbody tr');
        $('#checkAll').prop('checked', tbr.find('input[type=checkbox]:checked').length == tbr.length ? true : false);
        event.stopPropagation();
    });
});

// Select all cards
$(function () {
    $('#checkAllCard').click(function (event) {
        let card_checkbox = $('.card-body').find('input[type=checkbox]');
        card_checkbox.prop('checked', $(this).prop('checked'));
        event.stopPropagation();
    });
    $('.card-body').find('input[type=checkbox]').click(function (event) {
        let cards = $('.card-body');
        $('#checkAllCard').prop('checked', cards.find('input[type=checkbox]:checked').length == cards.length ? true : false);
        event.stopPropagation();
    });
});

// editor.md js hook
var queue = new Array();
var hooks = {
    addAction: function (hook, func) {
        if (typeof (queue[hook]) == "undefined" || queue[hook] == null) {
            queue[hook] = new Array();
        }
        if (typeof func == 'function') {
            queue[hook].push(func);
        }
    }, doAction: function (hook, obj) {
        try {
            for (var i = 0; i < queue[hook].length; i++) {
                queue[hook][i](obj);
            }
        } catch (e) {
        }
    }
}

// Paste upload image
function imgPasteExpand(thisEditor) {
/*vot*/    var listenObj = document.querySelector("textarea").parentNode  // Object to listen for
/*vot*/    var postUrl = './media.php?action=upload';  // emlog image upload address
/*vot*/    var emMediaPhpUrl = "./media.php?action=lib";  // The resource library address of emlog, which is used to asynchronously obtain the uploaded image data

    // By dynamically configuring the read-only mode, the original paste action of the editor is prevented and the cursor position is restored
    function preventEditorPaste() {
        let l = thisEditor.getCursor().line;
        let c = thisEditor.getCursor().ch - 3;

        thisEditor.config({readOnly: true,});
        thisEditor.config({readOnly: false,});
        thisEditor.setCursor({line: l, ch: c});
    }

    // The editor replaces the text by the first few digits of the cursor position
    function replaceByNum(text, num) {
        let l = thisEditor.getCursor().line;
        let c = thisEditor.getCursor().ch;

        thisEditor.setSelection({line: l, ch: (c - num)}, {line: l, ch: c});
        thisEditor.replaceSelection(text);
    }

    // Paste event fires
    listenObj.addEventListener("paste", function (e) {
/*vot*/        if ($('.editormd-dialog').css('display') == 'block') return;  // Exit if editor has dialog
        if (!(e.clipboardData && e.clipboardData.items)) return;

/*vot*/        var pasteData = e.clipboardData || window.clipboardData; // Get the entire contents of the clipboard
/*vot*/        pasteAnalyseResult = new Array;  // Used to store the results of traversal analysis

/*vot*/        for (var i = 0; i < pasteData.items.length; i++) {  // Traverse the data in the analysis clipboard
            var item = pasteData.items[i];

            if ((item.kind == "file") && (item.type.match('^image/'))) {
                var imgData = item.getAsFile();
                if (imgData.size === 0) return;
                pasteAnalyseResult['type'] = 'img';
                pasteAnalyseResult['data'] = imgData;
/*vot*/                break;  // When there is a picture in the pasteboard, jump out of the loop
            }
            ;
        }

/*vot*/        if (pasteAnalyseResult['type'] == 'img') {  // If there is a picture in the clipboard, upload the picture
            preventEditorPaste();
            uploadImg(pasteAnalyseResult['data']);
            return;
        }
    }, false);

    // Upload image
    function uploadImg(img) {
        var formData = new FormData();
        var imgName = lang('paste_upload') + new Date().getTime() + "." + img.name.split(".").pop();

        formData.append('file', img, imgName);
        thisEditor.insertValue(lang('uploading'));
        $.ajax({
            url: postUrl, type: 'post', data: formData, processData: false, contentType: false, xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    thisEditor.insertValue("....");
                    xhr.upload.addEventListener('progress', function (e) {  // Show upload progress
                        console.log(lang('progress') + e.loaded + ' / ' + e.total);
                        let percent = Math.floor(e.loaded / e.total * 100);
                        if (percent < 10) {
                            replaceByNum('..' + percent + '%', 4);
                        } else if (percent < 100) {
                            replaceByNum('.' + percent + '%', 4);
                        } else {
                            replaceByNum(percent + '%', 4);
                        }
                    }, false);
                }
                return xhr;
            }, success: function (result) {
                let imgUrl, thumbImgUrl;
                console.log(lang('upload_ok_get_result'));
                $.get(emMediaPhpUrl, function (data) {  // Get the result asynchronously, append to the editor
                    console.log(lang('result_ok'));
                    imgUrl = data.match(/[a-zA-z]+:\/[^\s\"\']*/g)[0];
                    thumbImgUrl = data.match(/[a-zA-z]+:\/[^\s\"\']*/g)[1];
                    replaceByNum(`[![](${thumbImgUrl})](${imgUrl})`, 10);  // The number 10 here corresponds to 'Uploading...100%' which is 10 characters
                })
            }, error: function (result) {
                alert(lang('upload_failed_error'));
                replaceByNum(lang('upload_failed_error'), 6);
            }
        })
    }
}

// Attach the paste upload image function to the js hook located in the article editor and page editor
hooks.addAction("loaded", imgPasteExpand);
hooks.addAction("page_loaded", imgPasteExpand);

// Setting interface, if you set "Automatic address detection", set input to read-only to indicate that this item is invalid
$(document).ready(function () {
    // Check the page after loading
    if ($("#detect_url").prop("checked")) {
        $("[name=blogurl]").attr("readonly", "readonly")
    }

    $("#detect_url").click(function () {
        if ($(this).prop("checked")) {
            $("[name=blogurl]").attr("readonly", "readonly")
        } else {
            $("[name=blogurl]").removeAttr("readonly")
        }
    })
})

function checkupdate() {
    $("#upmsg").html("").addClass("spinner-border text-primary");
    $.get("./upgrade.php?action=check_update", function (result) {
        if (result.code == 1001) {
            $("#upmsg").html("您的emlog pro尚未注册，<a href=\"auth.php\">去注册</a>").removeClass();
        } else if (result.code == 1002) {
            $("#upmsg").html("已经是最新版本").removeClass();
        } else if (result.code == 1003) {
            $("#upmsg").html("更新服务已到期，<a href=\"https://www.emlog.net/\" target=\"_blank\">登录官网续期</a>").removeClass();
        } else if (result.code == 200) {
            $("#upmsg").html("有可用的新版本 " + result.data.version + "，<a href=\"https://www.emlog.net/docs/#/changelog\" target=\"_blank\">查看更新内容</a>，<a id=\"doup\" href=\"javascript:doup('" + result.data.file + "','" + result.data.sql + "');\">现在更新</a>").removeClass();
        } else {
            $("#upmsg").html("检查失败，可能是网络问题").removeClass();
        }
    });
}

function doup(source, upsql) {
    $("#upmsg").html("正在更新中，请耐心等待").addClass("ajaxload");
    $.get('./upgrade.php?action=update&source=' + source + "&upsql=" + upsql, function (data) {
        $("#upmsg").removeClass();
        if (data.match("succ")) {
            $("#upmsg").html('恭喜您！更新成功了，请<a href="./">刷新页面</a>开始体验新版emlog');
        } else if (data.match("error_down")) {
            $("#upmsg").html('下载更新失败，可能是服务器网络问题');
        } else if (data.match("error_zip")) {
            $("#upmsg").html('解压更新失败，可能是你的服务器空间不支持zip模块');
        } else if (data.match("error_dir")) {
            $("#upmsg").html('更新失败，目录不可写');
        } else {
            $("#upmsg").html('更新失败');
        }
    });
}
