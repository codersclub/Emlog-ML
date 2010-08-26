<?php
/**
 * Blogger Profile
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';
require_once EMLOG_ROOT.'/model/class.user.php';

if ($action == '') {
	$emUser = new emUser();
	$row = $emUser->getOneUser(UID);
	extract($row);
	$icon = '';
	if ($photo && file_exists($photo))
	{
		$imgsize = chImageSize($photo,ICON_MAX_W,ICON_MAX_H);
		$icon = "<img src=\"{$photo}\" width=\"{$imgsize['w']}\" height=\"{$imgsize['h']}\" border=\"1\" /><a href=\"javascript: em_confirm(0, 'avatar');\">[".$lang['photo_delete']."]</a>";
	}
	include getViews('header');
	require_once(getViews('blogger'));
	include getViews('footer');
	cleanPage();
}

if ($action == 'update') {
	$emUser = new emUser();
	$photo = isset($_POST['photo']) ? addslashes(trim($_POST['photo'])) : '';
	$nickname = isset($_POST['name']) ? addslashes(trim($_POST['name'])) : '';
	$email = isset($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
	$description = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
	$photo_type = array('gif', 'jpg', 'jpeg','png');
	if($_FILES['photo']['size'] > 0)
	{
		$usericon = uploadFile($_FILES['photo']['name'], $_FILES['photo']['error'], $_FILES['photo']['tmp_name'], $_FILES['photo']['size'], $_FILES['photo']['type'], $photo_type, 1);
	}else{
		$usericon = $photo;
	}
	$emUser->updateUser(array('nickname'=>$nickname, 'email'=>$email, 'photo'=>$usericon, 'description'=>$description), UID);
	$CACHE->updateCache('user');
	header("Location: ./blogger.php?active_edit=true");
}

if ($action == 'delicon') {
	$query = $DB->query("select photo from ".DB_PREFIX."user");
	$icon = $DB->fetch_array($query);
	$icon_1 = $icon['photo'];
	if(file_exists($icon_1)){
		$icon_2 = str_replace('thum-', '', $icon_1);
		if($icon_2 != $icon_1 && file_exists($icon_2)){
			unlink($icon_2);
		}
		$icon_3 = preg_replace("/^(.*)\/(.*)$/", "\$1/thum52-\$2", $icon_2);
		if($icon_3 != $icon_2 && file_exists($icon_3)){
			unlink($icon_3);
		}
		unlink($icon_1);
	}
	$DB->query("UPDATE ".DB_PREFIX."user SET photo='' ");
	$CACHE->updateCache('user');
	header("Location: ./blogger.php?active_del=true");
}

if ($action == 'update_pwd') {
	require_once EMLOG_ROOT.'/lib/class.phpass.php';

	$emUser = new emUser();

	$login = isset($_POST['username']) ? addslashes(trim($_POST['username'])) : '';
	$newpass = isset($_POST['newpass']) ? addslashes(trim($_POST['newpass'])) : '';
	$oldpass = isset($_POST['oldpass']) ? addslashes(trim($_POST['oldpass'])) : '';
	$repeatpass = isset($_POST['repeatpass']) ? addslashes(trim($_POST['repeatpass'])) : '';

	$PHPASS = new PasswordHash(8, true);
	$ispass = checkPassword($oldpass, $userData['password']);

	if(!$ispass)
	{
		formMsg($lang['wrong_current_password'],'javascript:history.go(-1);',0);
	}elseif(!empty($login) && $emUser->isUserExist($login, UID)){
		formMsg($lang['username_allready_exists'],'javascript:history.go(-1);',0);
	}elseif(strlen($newpass)>0 && strlen($newpass) < 6){
		formMsg($lang['password_short'],'javascript:history.go(-1);',0);
	}elseif(!empty($newpass) && $newpass != $repeatpass){
		formMsg($lang['password_not_equal'],'javascript:history.go(-1);',0);
	}

	if(!empty($newpass) && empty($login)) // Change Password Only
	{
		$newpass = $PHPASS->HashPassword($newpass);
		$emUser->updateUser(array('password'=>$newpass), UID);
		formMsg($lang['password_modified_ok'],'./',1);
	}elseif(!empty($newpass) && !empty($login)) //Change password and login name
	{
		$newpass = $PHPASS->HashPassword($newpass);
		$emUser->updateUser(array('username'=>$login, 'password'=>$newpass), UID);
		formMsg($lang['login_and_password_modified_ok'],'./',1);
	}elseif(empty($newpass) && !empty($login)) //Modify the login name
	{
		$emUser->updateUser(array('username'=>$login), UID);
		formMsg($lang['login_modified_ok'],'./',1);
	}else{
		formMsg($lang['enter_items'],'javascript:history.go(-1);',0);
	}
}
