var LNG = {
//---------------------------
//admin/views/article_write.php
 'leave_prompt'		: 'Сообщение при покидании страницы',//'离开页面提示',
 'already_edited'	: '[было изменено] ',//'[已修改] ',

//---------------------------
//admin/views/js/common.js
 'twitter_del_sure'	: 'Вы уверены, что хотите удалить данный твит?',//'确定要删除该笔记吗？',
 'comment_del_sure'	: 'Вы уверены, что хотите удалить данный комментарий?',//'确定要删除该评论吗？',
 'comment_ip_del_sure'	: 'Вы уверены, что хотите удалить все комментарии с данного IP?',//'确定要删除来自该IP的所有评论吗？',
 'link_del_sure'	: 'Вы уверены, что хотите удалить данную ссылку?',//'确定要删除该链接吗？',
 'navi_del_sure'	: 'Вы уверены, что хотите удалить данный пункт навигации?',//'确定要删除该导航吗？',
 'attach_del_sure'	: 'Вы уверены, что хотите удалить данный файл?',//'确定要删除该媒体文件吗？',
 'avatar_del_sure'	: 'Вы уверены, что хотите удалить данный аватар?',//'确定要删除头像吗？',
 'category_del_sure'	: 'Вы уверены, что хотите удалить данную категорию?',//'确定要删除该分类吗？',
 'user_del_sure'	: 'Вы уверены, что хотите удалить данного пользователя?',//'确定要删除该用户吗？',
 'template_del_sure'	: 'Вы уверены, что хотите удалить шаблон по умолчанию?',//'确定要删除该模板吗？'
 'plugin_reset_sure'	: 'Вы уверены, что хотите восстановить настройки по умолчанию для данного плагина? При этом все ваши предыдущие настройки будут сброшены!',//'确定要恢复组件设置到初始状态吗？这样会丢失你自定义的组件。',
 'plugin_del_sure'	: 'Вы уверены, что хотите удалить данный plugin?',//'确定要删除该插件吗？',
 'alias_link_error'	: 'Ошибочный алиас ссылки',//'链接别名错误',
 'alias_invalid_chars'	: 'Алиас должен содержать только латинсие буквы, цифры, подчёркивание и минус',//'别名错误，应由字母、数字、下划线、短横线组成',
 'alias_digital'	: 'Алиас не должен состоять из одних только цифр',//'别名错误，不能为纯数字',
 'alias_format_must_be'	: 'Недопустимый алиас. Он не должен содержать \'post\' или \'post-digits\'',//'别名错误，不能为\'post\'或\'post-数字\'',
 'alias_system_conflict'	: 'Недопустимый алиас (системный конфликт)',//'别名错误，与系统链接冲突',
 'alias_link_error_not_saved'	: 'Неверный алиас ссылки. Не может быть сохранено автоматически.',//'链接别名错误，自动保存失败',
// 'saving'		: 'Saving',//'正在保存',
 'saving'		: 'Сохранение...',//'正在保存中...',
 'saved_ok_time'	: 'Сохранено: ',//'保存于：',
 'save_system_error'	: 'Ошибка автосохранения... Сохранить не удалось.',//'网络或系统出现异常...保存可能失败',
 'too_quick'		: 'Вы выполняете действия слишком быстро!',//'请勿频繁操作！',
 'saving_in'		: '[Сохранение] ',//'[保存中] ',
 'saved_ok'		: '[Успешно сохранено] ',//'[保存成功] ',
 'save_failed'		: '[Ошибка сохранения] ',//'[保存失败] ',
 'paste_upload'		: 'Вставить загруженный файл ',//'粘贴上传 ',
 'uploading'		: 'Загрузка...',//'上传中...',
 'progress'		: 'Прогресс (байт): ',//'进度(bytes): ',
 'upload_ok_get_result'	: 'Загрузка успешно завершена! Получаю результат...',//'上传成功！正在获取结果...',
 'result_ok'		: 'Результат успешно получен!',//'获取结果成功！',
 'unknown_error'	: 'Непредвиденная ошибка',//'未知错误',
 'upload_failed_error'	: 'Ошибка загрузки. Неверный тип файла или сетевая ошибка',//'上传失败,图片类型错误或网络错误',

//----
 'backup_import_sure'	: 'Вы уверены, что хотите импортировать файл резервной копии?',//'你确定要导入该备份文件吗？',
 'page_del_sure'	: 'Вы уверены, что хотите удалить данную страницу?',//'你确定要删除该页面吗？',
 'title_empty'		: 'Заголовок не должен быть пустым',//'标题不能为空',
 'wysiwyg_switch'	: 'Пожалуйста, перейдите в WYSIWYG режим',//'请先切换到所见所得模式',
 'click_view_fullsize'	: 'Кликните для просмотра в полный размер',//'点击查看原图',
 'user_disable_sure'	: 'Вы уверены, что хотите забанить данного пользователя?',//'确定要禁用该用户吗？',
 'article_del_sure'	: 'Вы уверены, что хотите удалить данную статью?',//'确定要删除该篇文章吗？',
 'draft_del_sure'	: 'Вы уверены, что хотите удалить данный черновик? ',//'确定要删除该篇草稿吗？',
 'media_category_del_sure' : 'Вы уверены, что хотите удалить данную категорию?',//'确定要删除该资源分类吗？',
 'media_select'		: 'Выберите медиа-файл для перемещения',//'请选择要移动的资源',

//---------------------------
//include/lib/js/common_tpl.js
 'loading'		: 'Загрузка...',//'加载中...',
// 'loading'		: 'Loading...',//'加载中...',
 'max_140_bytes'	: '(не более 140 символов)',//'(回复长度需在140个字内)',
 'nickname_empty'	: '(Псевдоним не должен быть пустым)',//'(昵称不能为空)',
 'captcha_error'	: '(Ошибка проверочного кода)',//'(验证码错误)',
 'nickname_disabled'	: '(Данный псевдоним запрещён)',//'(不允许使用该昵称)',
 'nickname_exists'	: '(Данный псевдоним уже существует)',//'(已存在该回复)',
 'comments_disabled'	: '(Комментарии запрещены)',//'(禁止回复)',
 'comment_ok_moderation'	: '(Ваш комментарий успешно сохранён и ожидает проверки модератором.)',//'(回复成功，等待管理员审核)',
 'chinese_must_have'	: 'Текст комментария должен содержать китайские иероглифы!',//'评论内容需要包含中文！',
 'email_invalid'	: 'Неверный формат Email!',//'邮箱格式错误！',
 'url_invalid'		: 'Неверный формат URL!',//'网址格式错误！',

//---------------------------
//admin/views/js/dropzone.min.js
 'drag_message'		: 'Перетащите сюда изображение или кликните для выбора файла',//'拖动文件到这里，或者点击后选择上传',

//----------------
// The LAST key. DO NOT EDIT!!!
  '@' : '@'
};

//------------------------------
// Return the language var value
function lang(key) {
  if(LNG[key]) {
    val = LNG[key];
  } else {
    val = '{'+key+'}';
  }
  return val;
}
