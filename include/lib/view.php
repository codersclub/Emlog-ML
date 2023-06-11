<?php
/**
 * View control
 * @package EMLOG
 * @link https://emlog.io
 */

class View {
    public static function getView($template, $ext = '.php') {
        if (!is_dir(TEMPLATE_PATH)) {
            emMsg(lang('template_not_found'), BLOG_URL . 'admin/template.php');
        }
        return TEMPLATE_PATH . $template . $ext;
    }

    public static function getAdmView($template, $ext = '.php') {
        if (!is_dir(ADMIN_TEMPLATE_PATH)) {
            emMsg(lang('template_corrupted'), BLOG_URL);
        }
        return ADMIN_TEMPLATE_PATH . $template . $ext;
    }

    public static function isTplExist($template, $ext = '.php') {
        if (file_exists(TEMPLATE_PATH . $template . $ext)) {
            return true;
        }
        return false;
    }

    public static function output() {
        $content = ob_get_clean();
        ob_start();
        echo $content;
        ob_end_flush();
        exit;
    }

}
