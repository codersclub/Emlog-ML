<?php
/**
 * Widget Sidebar Management
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';

//Display widget management panel
if($action == '')
{
	$wgNum = isset($_GET['wg']) ? intval($_GET['wg']) : 1;
	$widgets = $options_cache['widgets'.$wgNum] ? @unserialize($options_cache['widgets'.$wgNum]) : array();
	$widgetTitle = $options_cache['widget_title'] ? @unserialize($options_cache['widget_title']) : array();
	$custom_widget = $options_cache['custom_widget'] ? @unserialize($options_cache['custom_widget']) : array();
	$widgetTitle = array_map('htmlspecialchars', $widgetTitle);
	foreach ($custom_widget as $key => $val)
	{
		$custom_widget[$key] = array_map('htmlspecialchars', $val);
	}

	$customWgTitle = array();
	foreach ($widgetTitle as $key => $val)
	{
		if(preg_match("/^.*\s\((.*)\)/", $val, $matchs))
		{
			$customWgTitle[$key] = $matchs[1];
		}else{
			$customWgTitle[$key] = $val;
		}
	}

	//music
	$music = @unserialize($options_cache['music']);
	if(isset($music['auto']) && $music['auto'])
	{
		$auto1 = "checked=\"checked\"";
		$auto2 = '';
	}else{
		$auto2 = "checked=\"checked\"";
		$auto1 = '';
	}
	if(isset($music['randplay']) && $music['randplay'])
	{
		$randplay1 = "checked=\"checked\"";
		$randplay2 = '';
	}else{
		$randplay2 = "checked=\"checked\"";
		$randplay1 = '';
	}
	$content = '';
	if(isset($music['mlinks']) && $music['mlinks'])
	{
		foreach($music['mlinks'] as $key=>$val)
		{
			$content .= urldecode($val)."\t".$music['mdes'][$key]."\n";
		}
	}

	include getViews('header');
	require_once getViews('widgets');
	include getViews('footer');
	cleanPage();
}

//Modify the widget settings
if($action == 'setwg')
{
	$widgetTitle = @unserialize($options_cache['widget_title']);//The current title of all the widgets
	$widget = isset($_GET['wg']) ? $_GET['wg'] : '';	        //The widget to modify
	$wgTitle = isset($_POST['title']) ? $_POST['title'] : '';   //New widget name

	preg_match("/^(.*)\s\(.*/", $widgetTitle[$widget], $matchs);
	$realWgTitle = isset($matchs[1]) ? $matchs[1] : $widgetTitle[$widget];

	$widgetTitle[$widget] = $realWgTitle != $wgTitle ? $realWgTitle.' ('.$wgTitle.')' : $realWgTitle;
	$widgetTitle = addslashes(serialize($widgetTitle));

	updateOption('widget_title', $widgetTitle);

	switch ($widget)
	{
		case 'newcomm':
			$index_comnum = isset($_POST['index_comnum']) ? intval($_POST['index_comnum']) : 10;
			$comment_subnum = isset($_POST['comment_subnum']) ? intval($_POST['comment_subnum']) : 20;
			updateOption('index_comnum', $index_comnum);
			updateOption('comment_subnum', $comment_subnum);
			$CACHE->updateCache('comment');
			break;
		case 'twitter':
			$index_newtwnum = isset($_POST['index_newtwnum']) ? intval($_POST['index_newtwnum']) : 10;
			updateOption('index_newtwnum', $index_newtwnum);
			$CACHE->updateCache('newtw');
			break;
		case 'newlog':
			$index_newlog = isset($_POST['index_newlog']) ? intval($_POST['index_newlog']) : 10;
			updateOption('index_newlognum', $index_newlog);
			$CACHE->updateCache('newlog');
			break;
		case 'random_log':
			$index_randlognum = isset($_POST['index_randlognum']) ? intval($_POST['index_randlognum']) : 20;
			updateOption('index_randlognum', $index_randlognum);
			break;
		case 'music':
			$links = isset($_POST['mlinks']) ? htmlspecialchars(trim($_POST['mlinks'])) : '';
			$randplay = isset($_POST['randplay']) ? intval($_POST['randplay']) : 0;
			$auto = isset($_POST['auto']) ? intval($_POST['auto']) : 0;
			$music = array(
			'mlinks'=>array(),
			'mdes'=>array(),
			'auto'=>$auto,
			'randplay'=>$randplay
			);
			if($links)
			{
				$links = explode("\n",$links);
				foreach($links as $val)
				{
					$val = str_replace(array("\r","\n"),array('',''),$val);
					if(preg_match("/^(http:\/\/).+/i",$val)>0)
					{
						$mstr = preg_split ("/[\s,]+/", $val,2);
						$music['mlinks'][] = urlencode($mstr[0]);
						if(count($mstr) == 2)
						{
							$music['mdes'][] = $mstr[1];
						}else {
							$music['mdes'][] = '';
						}
					}else{
						formMsg($lang['music_link_invalid'],'javascript: window.history.back()',0);
					}
				}
			}
			$musicData = serialize($music);
			updateOption('music', $musicData);
			break;
		case 'custom_text':
			$custom_widget = $options_cache['custom_widget'] ? @unserialize($options_cache['custom_widget']) : array();
			$title = isset($_POST['title']) ? $_POST['title'] : '';
			$content = isset($_POST['content']) ? $_POST['content'] : '';
			$custom_wg_id = isset($_POST['custom_wg_id']) ? $_POST['custom_wg_id'] : '';//Widget id to modify
			$new_title = isset($_POST['new_title']) ? $_POST['new_title'] : '';
			$new_content = isset($_POST['new_content']) ? $_POST['new_content'] : '';
			$rmwg = isset($_GET['rmwg']) ? addslashes($_GET['rmwg']) : '';//Widget id to remove

			//Add a new custom widget
			if($new_content)
			{
				//Determine the widget index
				$i = 0;
				$maxKey = 0;
				if(is_array($custom_widget))
				{
					foreach ($custom_widget as $key => $val)
					{
						preg_match("/^custom_wg_(\d+)/", $key, $matches);
						$k = $matches[1];
						if($k > $i)
						{
							$maxKey = $k;
						}
						$i = $k;
					}
				}
				$custom_wg_index = $maxKey + 1;
				$custom_wg_index = 'custom_wg_'.$custom_wg_index;
				$custom_widget[$custom_wg_index] = array('title'=>$new_title,'content'=>$new_content);
				$custom_widget_str = addslashes(serialize($custom_widget));
				updateOption('custom_widget', $custom_widget_str);
			}elseif ($content){
				$custom_widget[$custom_wg_id] = array('title'=>$title,'content'=>$content);
				$custom_widget_str = addslashes(serialize($custom_widget));
				updateOption('custom_widget', $custom_widget_str);
			}elseif ($rmwg){
				for($i=1; $i<5; $i++)
				{
					$widgets = $options_cache['widgets'.$i] ? @unserialize($options_cache['widgets'.$i]) : array();
					if(is_array($widgets) && !empty($widgets))
					{
						foreach ($widgets as $key => $val)
						{
							if($val == $rmwg)
							{
								unset($widgets[$key]);
							}
						}
						$widgets_str = addslashes(serialize($widgets));
						updateOption("widgets$i", $widgets_str);
					}
				}
				unset($custom_widget[$rmwg]);
				$custom_widget_str = addslashes(serialize($custom_widget));
				updateOption('custom_widget', $custom_widget_str);
			}
			break;
	}
	$CACHE->updateCache('options');
	header("Location: ./widgets.php?activated=true");
}

//Save widget sorting
if($action == 'compages') {
	$wgNum = isset($_POST['wgnum']) ? intval($_POST['wgnum']) : 1;//Sidebar No. 1,2,3...
	$widgets = isset($_POST['widgets']) ? serialize($_POST['widgets']) : '';
	updateOption("widgets{$wgNum}", $widgets);
	$CACHE->updateCache('options');
	header("Location: ./widgets.php?activated=true&wg=$wgNum");
}

//Reset widget settings
if($action == 'reset') {
	$widget_title = array(
    	'blogger' => 'blogger',
    	'calendar' => $lang['calendar'],
    	'twitter' => $lang['twitters_last'],
    	'tag' => $lang['tags'],
    	'sort' => $lang['categories'],
    	'archive' => $lang['archive'],
    	'newcomm' => $lang['latest_comments'],
    	'newlog' => $lang['latest_posts'],
    	'random_log' => $lang['random_posts'],
    	'music' => $lang['music'],
    	'link' => $lang['links'],
    	'search' => $lang['search'],
    	'bloginfo' => $lang['blog_statistics'],
    	'custom_text' => $lang['widget_custom'],
	);
	$default_widget = array('calendar','archive','newcomm','link','search','bloginfo');

	$widget_title = serialize($widget_title);
	$default_widget = serialize($default_widget);

	updateOption("widget_title", $widget_title);
	updateOption("custom_widget", 'a:0:{}');
	updateOption("widgets1", $default_widget);

	$CACHE->updateCache('options');
	header("Location: ./widgets.php?activated=true");
}
