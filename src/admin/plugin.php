<?php
/**
 * Plugin management
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';
require_once EMLOG_ROOT.'/model/class.plugin.php';

$plugin = isset($_GET['plugin']) ? $_GET['plugin'] : '';

if($action == '' && !$plugin)
{
	$emPlugin = new emPlugin();

	$plugins = $emPlugin->getPlugins();

	include getViews('header');
	require_once(getViews('plugin'));
	include getViews('footer');
	cleanPage();
}
//Activate
if ($action == 'active')
{
	$emPlugin = new emPlugin($plugin);
	if ($emPlugin->active_plugin($active_plugins) ){
	    $CACHE->updateCache('options');
	    header("Location: ./plugin.php?active=true");
	} else {
	    header("Location: ./plugin.php?active_error=true");
	}
}
//Deactivate
if($action == 'inactive')
{
	$emPlugin = new emPlugin($plugin);
	$emPlugin->inactive_plugin($active_plugins);
	$CACHE->updateCache('options');
	header("Location: ./plugin.php?inactive=true");
}
//Load the plug-in configuration page
if ($action == '' && $plugin)
{
	include getViews('header');
	require_once "../content/plugins/{$plugin}/{$plugin}_setting.php";
	plugin_setting_view();
	include getViews('footer');
}
//Save plug-in settings
if ($action == 'setting')
{
	if(!empty($_POST))
	{
		require_once "../content/plugins/{$plugin}/{$plugin}_setting.php";
		if(false === plugin_setting())
		{
		    header("Location: ./plugin.php?plugin={$plugin}&error=true");
		}else{
		    header("Location: ./plugin.php?plugin={$plugin}&setting=true");
		}
	}else{
	    header("Location: ./plugin.php?plugin={$plugin}&error=true");
	}
}
//禁用所有插件
if($action == 'reset'){
    updateOption('active_plugins', 'a:0:{}');
	$CACHE->updateCache('options');
	header("Location: ./plugin.php?inactive=true");
}
