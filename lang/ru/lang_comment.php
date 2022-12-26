<?php
//File: LANG_COMMENT

$lang = array(

//---------------------------
//admin/comment.php
'comment_not_exist'		=> 'Указанный комментарий не существует!',//'不存在该评论！',

//---------------------------
//admin/views/comment.php
'comment_management'		=> 'Управление комментариями',//'评论管理',
'comment_delete_ok'		=> 'Комментарий успешно удалён',//'删除评论成功',
'comment_audit_ok'		=> 'Комментарий успешно верифицирован',//'审核评论成功',
'comment_hide_ok'		=> 'Комментарий успешно скрыт',//'隐藏评论成功',
'comment_edit_ok'		=> 'Комментарий успешно изменён',//'修改评论成功',
'comment_reply_ok'		=> 'Комментарий успешно отвечен',//'回复评论成功',
'comment_choose_operation'	=> 'Пожалуйста, выберите действие для комментариев',//'请选择要执行操作的评论',
// 'select_action_to_perform'	=> 'Пожалуйста, выберите действие',//'请选择要执行的操作',
'reply_is_empty'		=> 'Ответ не должен быть пустым',//'回复内容不能为空',
'comment_too_long'		=> 'Комментарий слишком длинный',//'内容过长',
'comment_is_empty'		=> 'Комментарий не должен быть пустым',//'评论内容不能为空',
// 'all'			=> 'Все',//'全部',
// 'pending'			=> 'Ожидает',//'待审',
'audited'			=> 'Проверено',//'已审',
'content'			=> 'Контент',//'内容',
'commentator'			=> 'Комментатор',//'评论者',
'belongs_to_post'		=> 'Относится к статье',//'所属文章',
'from'				=> 'From',//'来自',
'delete'			=> 'Удалить',//'删除',
'approve'			=> 'Одобрить',//'审核',
'hide'				=> 'Скрыть',//'隐藏',
'reply'				=> 'Ответить',//'回复',
'edit'				=> 'Изменить',//'编辑',
'delete_comments_from_ip'	=> 'Удалить все комментарии с данного IP',//'删除来自该IP的所有评论',
'show_post'			=> 'Показать статью',//'查看该文章',
'no_comments_yet'		=> 'Комментариев пока ещё нет',//'还没有收到评论',
// 'select_all'			=> 'Выбрать все',//'全选',
// 'selected_items'		=> 'Выбранные объекты',//'选中项',
// 'have'			=> 'Имеется ',//'有',
'_comments'			=> ' комментариев',//'条评论',
'comment_operation_select'	=> 'Пожалуйста, выберите действие для комментариев',//'请选择要操作的评论',
'comment_selected_delete_sure'	=> 'Вы уверены, что хотите удалить выбранные комментарии?',//'确定要删除所选评论吗？',

//---------------------------
//admin/views/comment_edit.php
'comment_edit'		=> 'Редактировать комментарий',//'编辑评论',
'email'			=> 'E-mail',//'电子邮件',
'home_page'		=> 'Домашняя страница',//'主页',
'comment_content'	=> 'Текст комментария',//'评论内容',
'save'			=> 'Сохранить',//'保 存',
'cancel'		=> 'Отменить',//'取 消',

//---------------------------
//admin/views/comment_reply.php
'comment_reply'		=> 'Ответить на комментарий',//'回复评论',
// 'commentator'	=> 'Commentator',//'评论人',
// 'time'		=> 'Date',//'时间',
// 'content'		=> 'Content',//'内容',
// 'reply'		=> 'Reply',//'回复',
'reply_and_publish'	=> 'Ответить и опубликовать',//'回复并审核',
// 'cancel'		=> 'Cancel',//'取 消',

//---------------------------
//include/controller/comment_controller.php
'comment_error_comment_disabled'	=> 'Ошибка: Комментирование данной статьи запрещено.',//'评论失败：该文章已关闭评论',
'comment_error_content_exists'		=> 'Ошибка: Аналогичный текст уже существует.',//'评论失败：已存在相同内容评论',
'comment_error_flood_control'		=> 'Ошибка: Неоходима небольшая пауза передотправкой следующего комментария.',//'评论失败：您写评论的速度太快了，请稍后再试',
'comment_error_name_enter'		=> 'Ошибка: Пожалуйста, укажите своё имя.',//'评论失败：请填写姓名',
'comment_error_name_invalid'		=> 'Ошибка: Имя не соотвтствует требованиям.',//'评论失败：姓名不符合规范',
'comment_error_email_invalid'		=> 'Ошибка: E-mail не соответствует требованиям.',//'评论失败：邮件地址不符合规范',
'comment_error_other_user'		=> 'Ошибка: Данные пользователя не должны совпадать с данными администратора или других пользователей.',//'评论失败：禁止使用管理员昵称或邮箱评论',
'comment_error_url_invalid'		=> 'Ошибка: Недопустимый URL домашней страницы.',//'评论失败：主页地址不符合规范',
'comment_error_empty'			=> 'Ошибка: Пожалуйста, введите какой-нибудь текст.',//'评论失败：请填写评论内容',
'comment_error_content_invalid'		=> 'Ошибка: Текст не соответствует требованиям.',//'评论失败：内容不符合规范',
'comment_error_national_chars'		=> 'Ошибка: Текст должен содержать китайские иероглифы.',//'评论失败：评论内容需包含中文',
'comment_error_captcha_invalid'		=> 'Ошибка: Неверный проверочный код.',//'评论失败：验证码错误',

//---------------------------
//include/model/comment_model.php
'comment_wait_approve'		=> 'Спасибо за Ваш комментарий. Нужно подождать, пока он будет одобрен',//'评论发表成功，请等待管理员审核',
// 'no_permission'		=> 'Недостаточно привилегий!',//'权限不足！',

);

