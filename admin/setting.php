<?php
/**
 * setting
 * @package EMLOG
 * @link https://emlog.io
 */

/**
 * @var string $action
 * @var object $CACHE
 */

require_once 'globals.php';

if (empty($action)) {
    $options_cache = $CACHE->readCache('options');
    extract($options_cache);

    $conf_comment_code = $comment_code == 'y' ? 'checked="checked"' : '';
    $conf_comment_needchinese = $comment_needchinese == 'y' ? 'checked="checked"' : '';
    $conf_iscomment = $iscomment == 'y' ? 'checked="checked"' : '';
    $conf_login_comment = $login_comment == 'y' ? 'checked="checked"' : '';
    $conf_ischkcomment = $ischkcomment == 'y' ? 'checked="checked"' : '';
    $conf_isthumbnail = $isthumbnail == 'y' ? 'checked="checked"' : '';
    $conf_isgravatar = $isgravatar == 'y' ? 'checked="checked"' : '';
    $conf_comment_paging = $comment_paging == 'y' ? 'checked="checked"' : '';
    $conf_detect_url = $detect_url == 'y' ? 'checked="checked"' : '';
    $conf_accept_app_recs = $accept_app_recs == 'y' ? 'checked="checked"' : '';

    $ex1 = $ex2 = $ex3 = $ex4 = '';
    if ($rss_output_fulltext == 'y') {
        $ex1 = 'selected="selected"';
    } else {
        $ex2 = 'selected="selected"';
    }
    if ($comment_order == 'newer') {
        $ex3 = 'selected="selected"';
    } else {
        $ex4 = 'selected="selected"';
    }

    include EMLOG_ROOT . '/lang/' . LANG . '/lang_tz.php'; // Load Time Zone List
    include View::getAdmView('header');
    require_once(View::getAdmView('setting'));
    include View::getAdmView('footer');
    View::output();
}

if ($action == 'save') {
    LoginAuth::checkToken();
    $getData = [
        'blogname'            => isset($_POST['blogname']) ? addslashes($_POST['blogname']) : '',
        'blogurl'             => isset($_POST['blogurl']) ? addslashes($_POST['blogurl']) : '',
        'bloginfo'            => isset($_POST['bloginfo']) ? addslashes($_POST['bloginfo']) : '',
        'icp'                 => isset($_POST['icp']) ? addslashes($_POST['icp']) : '',
        'footer_info'         => isset($_POST['footer_info']) ? addslashes($_POST['footer_info']) : '',
        'index_lognum'        => isset($_POST['index_lognum']) ? (int)$_POST['index_lognum'] : '',
        'timezone'            => isset($_POST['timezone']) ? addslashes($_POST['timezone']) : '',
        'comment_code'        => isset($_POST['comment_code']) ? addslashes($_POST['comment_code']) : 'n',
        'comment_needchinese' => isset($_POST['comment_needchinese']) ? addslashes($_POST['comment_needchinese']) : 'n',
        'comment_interval'    => isset($_POST['comment_interval']) ? (int)$_POST['comment_interval'] : 15,
        'iscomment'           => isset($_POST['iscomment']) ? addslashes($_POST['iscomment']) : 'n',
        'login_comment'       => Input::postStrVar('login_comment', 'n'),
        'ischkcomment'        => isset($_POST['ischkcomment']) ? addslashes($_POST['ischkcomment']) : 'n',
        'isthumbnail'         => isset($_POST['isthumbnail']) ? addslashes($_POST['isthumbnail']) : 'n',
        'rss_output_num'      => isset($_POST['rss_output_num']) ? (int)$_POST['rss_output_num'] : 10,
        'rss_output_fulltext' => isset($_POST['rss_output_fulltext']) ? addslashes($_POST['rss_output_fulltext']) : 'y',
        'isgravatar'          => isset($_POST['isgravatar']) ? addslashes($_POST['isgravatar']) : 'n',
        'comment_paging'      => isset($_POST['comment_paging']) ? addslashes($_POST['comment_paging']) : 'n',
        'comment_pnum'        => isset($_POST['comment_pnum']) ? (int)$_POST['comment_pnum'] : '',
        'comment_order'       => isset($_POST['comment_order']) ? addslashes($_POST['comment_order']) : 'newer',
        'att_maxsize'         => isset($_POST['att_maxsize']) ? (int)$_POST['att_maxsize'] : 20480,
        'att_type'            => isset($_POST['att_type']) ? str_replace('php', 'x', strtolower(addslashes($_POST['att_type']))) : '',
        'att_imgmaxw'         => isset($_POST['att_imgmaxw']) ? (int)$_POST['att_imgmaxw'] : 420,
        'att_imgmaxh'         => isset($_POST['att_imgmaxh']) ? (int)$_POST['att_imgmaxh'] : 460,
        'detect_url'          => isset($_POST['detect_url']) ? addslashes($_POST['detect_url']) : 'n', // Automatically detect site URL
        'admin_perpage_num'   => Input::postIntVar('admin_perpage_num'),
        'accept_app_recs'     => isset($_POST['accept_app_recs']) ? addslashes($_POST['accept_app_recs']) : 'n',
    ];

    if ($getData['login_code'] == 'y' && !function_exists("imagecreate") && !function_exists('imagepng')) {
        emMsg(lang('verification_code_not_supported'), "setting.php");
    }
    if ($getData['comment_code'] == 'y' && !function_exists("imagecreate") && !function_exists('imagepng')) {
        emMsg(lang('verification_code_comment_not_supported'), "setting.php");
    }
    if ($getData['blogurl'] && substr($getData['blogurl'], -1) != '/') {
        $getData['blogurl'] .= '/';
    }
    if ($getData['blogurl'] && strncasecmp($getData['blogurl'], 'http', 4)) {
        $getData['blogurl'] = 'http://' . $getData['blogurl'];
    }

    foreach ($getData as $key => $val) {
        Option::updateOption($key, $val);
    }
    $CACHE->updateCache(array('tags', 'options', 'comment', 'record'));
    emDirect("./setting.php?activated=1");
}

if ($action == 'seo') {
    $options_cache = $CACHE->readCache('options');
    extract($options_cache);

    $ex0 = $ex1 = $ex2 = $ex3 = '';
    $t = 'ex' . $isurlrewrite;
    $$t = 'checked="checked"';

    $opt0 = $opt1 = $opt2 = '';
    $t = 'opt' . $log_title_style;
    $$t = 'selected="selected"';

    $isalias = $isalias == 'y' ? 'checked="checked"' : '';
    $isalias_html = $isalias_html == 'y' ? 'checked="checked"' : '';

    include View::getAdmView('header');
    require_once(View::getAdmView('setting_seo'));
    include View::getAdmView('footer');
    View::output();
}

if ($action == 'seo_save') {
    LoginAuth::checkToken();
    $permalink = isset($_POST['permalink']) ? addslashes($_POST['permalink']) : '0';
    $isalias = isset($_POST['isalias']) ? addslashes($_POST['isalias']) : 'n';
    $isalias_html = isset($_POST['isalias_html']) ? addslashes($_POST['isalias_html']) : 'n';

    $getData = array(
        'site_title'       => isset($_POST['site_title']) ? addslashes($_POST['site_title']) : '',
        'site_description' => isset($_POST['site_description']) ? addslashes($_POST['site_description']) : '',
        'site_key'         => isset($_POST['site_key']) ? addslashes($_POST['site_key']) : '',
        'isurlrewrite'     => isset($_POST['permalink']) ? addslashes($_POST['permalink']) : '0',
        'isalias'          => isset($_POST['isalias']) ? addslashes($_POST['isalias']) : 'n',
        'isalias_html'     => isset($_POST['isalias_html']) ? addslashes($_POST['isalias_html']) : 'n',
        'log_title_style'  => isset($_POST['log_title_style']) ? addslashes($_POST['log_title_style']) : '0',
    );

    if ($permalink != '0' || $isalias == 'y') {
        $t = parse_url(BLOG_URL);
        $rw_rule = '<IfModule mod_rewrite.c>
                       RewriteEngine on
                       RewriteCond %{REQUEST_FILENAME} !-f
                       RewriteCond %{REQUEST_FILENAME} !-d
                       RewriteBase ' . $t['path'] . '
                       RewriteRule . ' . $t['path'] . 'index.php [L]
                    </IfModule>';
        if (!file_put_contents(EMLOG_ROOT . '/.htaccess', $rw_rule)) {
            header('Location: ./setting.php?action=seo&error=1');
            exit;
        }
    }

    foreach ($getData as $key => $val) {
        Option::updateOption($key, $val);
    }
    $CACHE->updateCache(array('options', 'navi'));
    header('Location: ./setting.php?action=seo&activated=1');
}

if ($action == 'mail') {
    $options_cache = $CACHE->readCache('options');
    $smtp_mail = isset($options_cache['smtp_mail']) ? $options_cache['smtp_mail'] : '';
    $smtp_pw = isset($options_cache['smtp_pw']) ? $options_cache['smtp_pw'] : '';
    $smtp_from_name = isset($options_cache['smtp_from_name']) ? $options_cache['smtp_from_name'] : '';
    $smtp_server = isset($options_cache['smtp_server']) ? $options_cache['smtp_server'] : '';
    $smtp_port = isset($options_cache['smtp_port']) ? $options_cache['smtp_port'] : '';
    $mail_notice_comment = isset($options_cache['mail_notice_comment']) ? $options_cache['mail_notice_comment'] : '';
    $mail_notice_post = isset($options_cache['mail_notice_post']) ? $options_cache['mail_notice_post'] : '';
    $mail_template = isset($options_cache['mail_template']) ? $options_cache['mail_template'] : '';

    $conf_mail_notice_comment = $mail_notice_comment == 'y' ? 'checked="checked"' : '';
    $conf_mail_notice_post = $mail_notice_post == 'y' ? 'checked="checked"' : '';

    include View::getAdmView('header');
    require_once(View::getAdmView('setting_mail'));
    include View::getAdmView('footer');
    View::output();

}

if ($action == 'mail_save') {
    LoginAuth::checkToken();
    $data = [
        'smtp_mail'           => Input::postStrVar('smtp_mail'),
        'smtp_pw'             => Input::postStrVar('smtp_pw'),
        'smtp_from_name'      => Input::postStrVar('smtp_from_name'),
        'smtp_server'         => Input::postStrVar('smtp_server'),
        'smtp_port'           => Input::postStrVar('smtp_port'),
        'mail_notice_comment' => Input::postStrVar('mail_notice_comment', 'n'),
        'mail_notice_post'    => Input::postStrVar('mail_notice_post', 'n'),
        'mail_template'       => isset($_POST['mail_template']) ? addslashes(base64_decode($_POST['mail_template'])) : '',
    ];

    foreach ($data as $key => $val) {
        Option::updateOption($key, $val);
    }

    $CACHE->updateCache(array('options'));
    header('Location: ./setting.php?action=mail&activated=1');
}

if ($action == 'mail_test') {
    $data = [
        'smtp_mail'      => isset($_POST['smtp_mail']) ? addslashes($_POST['smtp_mail']) : '',
        'smtp_pw'        => isset($_POST['smtp_pw']) ? addslashes($_POST['smtp_pw']) : '',
        'smtp_from_name' => isset($_POST['smtp_from_name']) ? addslashes($_POST['smtp_from_name']) : '',
        'smtp_server'    => isset($_POST['smtp_server']) ? addslashes($_POST['smtp_server']) : '',
        'smtp_port'      => isset($_POST['smtp_port']) ? (int)$_POST['smtp_port'] : '',
        'testTo'         => isset($_POST['testTo']) ? $_POST['testTo'] : '',
    ];

    if (!checkMail($data['testTo'])) {
        exit("<small class='text-info'>" . lang('email_enter_please') . "</small>");
    }

    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = $data['smtp_port'] == '587' ? 'STARTTLS' : 'ssl';
    $mail->Port = $data['smtp_port'];
    $mail->Host = $data['smtp_server'];
    $mail->Username = $data['smtp_mail'];
    $mail->Password = $data['smtp_pw'];
    $mail->From = $data['smtp_mail'];
    $mail->FromName = $data['smtp_from_name'];
    $mail->AddAddress($data['testTo']);
    $mail->Subject = lang('test_mail_subj');
    $mail->isHTML();
    $mail->Body = Notice::getMailTemplate(lang('test_mail_body'));

    try {
        return $mail->Send();
    } catch (Exception $exc) {
        exit("<small class='text-danger'>" . lang('test_mail_failed') . "</small>");
    }
}

if ($action == 'user') {
    $options_cache = $CACHE->readCache('options');
    $is_signup = isset($options_cache['is_signup']) ? $options_cache['is_signup'] : '';
    $login_code = isset($options_cache['login_code']) ? $options_cache['login_code'] : '';
    $ischkarticle = isset($options_cache['ischkarticle']) ? $options_cache['ischkarticle'] : '';
    $article_uneditable = isset($options_cache['article_uneditable']) ? $options_cache['article_uneditable'] : '';
    $posts_per_day = isset($options_cache['posts_per_day']) ? $options_cache['posts_per_day'] : '';
//vot $posts_name = isset($options_cache['posts_name']) ? $options_cache['posts_name'] : '';
    $email_code = isset($options_cache['email_code']) ? $options_cache['email_code'] : '';

    $conf_is_signup = $is_signup == 'y' ? 'checked="checked"' : '';
    $conf_login_code = $login_code == 'y' ? 'checked="checked"' : '';
    $conf_email_code = $email_code == 'y' ? 'checked="checked"' : '';
    $conf_ischkarticle = $ischkarticle == 'y' ? 'checked="checked"' : '';
    $conf_article_uneditable = $article_uneditable == 'y' ? 'checked="checked"' : '';
    $conf_posts_per_day = $posts_per_day;
//vot    $conf_posts_name = $posts_name;

    include View::getAdmView('header');
    require_once(View::getAdmView('setting_user'));
    include View::getAdmView('footer');
    View::output();
}

if ($action == 'user_save') {
    LoginAuth::checkToken();
    $data = [
        'is_signup'          => Input::postStrVar('is_signup', 'n'),
        'login_code'         => Input::postStrVar('login_code', 'n'),
        'email_code'         => Input::postStrVar('email_code', 'n'),
        'ischkarticle'       => Input::postStrVar('ischkarticle', 'n'),
        'article_uneditable' => Input::postStrVar('article_uneditable', 'n'),
        'posts_per_day'      => Input::postIntVar('posts_per_day', 0),
//vot        'posts_name'         => Input::postStrVar('posts_name'),
    ];
    foreach ($data as $key => $val) {
        Option::updateOption($key, $val);
    }
    $CACHE->updateCache('options');
    header('Location: ./setting.php?action=user&activated=1');
}

if ($action == 'api') {
    $apikey = Option::get('apikey');
    $is_openapi = Option::get('is_openapi');
    $conf_is_openapi = $is_openapi == 'y' ? 'checked="checked"' : '';

    include View::getAdmView('header');
    require_once(View::getAdmView('setting_api'));
    include View::getAdmView('footer');
    View::output();

}

if ($action == 'api_save') {
    LoginAuth::checkToken();

    $is_openapi = isset($_POST['is_openapi']) ? addslashes($_POST['is_openapi']) : 'n';
    Option::updateOption('is_openapi', $is_openapi);
    $CACHE->updateCache('options');
    header('Location: ./setting.php?action=api&ok=1');
}

if ($action == 'api_reset') {
    LoginAuth::checkToken();

    $apikey = md5(getRandStr(32));

    Option::updateOption('apikey', $apikey);
    $CACHE->updateCache('options');
    header('Location: ./setting.php?action=api&ok_reset=1');
}
