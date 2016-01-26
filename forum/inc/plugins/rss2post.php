<?php
/*
	RSS To Post 1.2.2
	
	This plugin checks RSS feeds at a specified interval and posts new entries onto the forum.
	For MyBB 1.4.x.
	
	Copyright © 2006, 2008 Dennis Tsang (http://dennistt.net)
	
	==========
	LICENSE
	==========
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function rss2post_info()
{
	global $lang;
	rss2post_load_language();
	
	return array(
		"name"			=> "RSS To Post",
		"description"	=> $lang->rss2post_desc,
		"website"		=> "http://www.dennistt.net",
		"author"		=> "DennisTT",
		"authorsite"	=> "http://www.dennistt.net",
		"version"		=> "1.2.2",
		"guid"			  => "1f9334bcd93a74783b4b968891dbfd4f",
		"compatibility"   => "14*",
	);
}

// Helper function to load the language variables
function rss2post_load_language()
{
	global $lang;
	if(!defined('DENNISTT_RSS2POST_LANG_LOADED'))
	{
		$lang->load('rss2post', false, true);
		
		if(!isset($lang->rss2post))
		{
			$lang->rss2post = 'RSS To Post';
			$lang->rss2post_desc = 'Parses RSS feeds and posts new items onto a specified forum.  For MyBB 1.4.<br /><br />See the "RSS To Post" section in the ACP Configuration Menu to configure this plugin.';
			$lang->can_manage_rss2post = 'Can Manage "RSS To Post" Plugin';
			
			$lang->rss2post_task_desc = 'Grabs new RSS entries from specified feeds, and posts them on the forum.  Required by "RSS To Post" plugin.';
			
			$lang->rss2post_feeds = 'Feeds';
			$lang->rss2post_feeds_desc = 'Manage the feeds aggregated by RSS To Post Plugin';
			$lang->rss2post_settings = 'Settings';
			$lang->rss2post_faq = 'FAQ';
			$lang->rss2post_faq_desc = 'Frequently asked questions, and troubleshooting';
			$lang->rss2post_error_log = 'Error Log';
			
			$lang->rss2post_faq_troubleshooting_header = 'Troubleshooting and Frequently Asked Questions';
			$lang->rss2post_faq_no_entries = 'Entries aren\'t being posted!';
			$lang->rss2post_faq_no_entries_a1 = 'Check that the RSS To Post task file (inc/tasks/rss2post_task.php) is uploaded.';
			$lang->rss2post_faq_no_entries_a2 = 'Check that there is a RSS To Post task in the <a href="index.php?module=tools/tasks">Task Manager</a>.  The interval at which this task runs should be set to the time you want the feeds to be checked; it is recommended run once per hour.';
			$lang->rss2post_faq_no_entries_a3 = 'Check the contents of the <a href="index.php?module=tools/cache&action=view&title=rss2post_errors">error log</a>.  If there were errors retrieving or posting the entries, it will be logged here.';
			$lang->rss2post_faq_disable = 'How can I disable RSS To Post without losing all the feed information?';
			$lang->rss2post_faq_disable_desc = 'For now, you can disable the RSS To Post task in the <a href="index.php?module=tools/tasks">Task Manager</a>.';
			$lang->rss2post_faq_url = 'I get the error, "There was a problem accessing the URL." in the Error Log';
			$lang->rss2post_faq_url_fopen = 'Your allow_url_fopen PHP setting is currently <strong>enabled</strong>.  This indicates that for some reason the RSS To Post task could not access the feed URL.  Did you enter the URL correctly?  Is the feed accessible from your browser?  Currently RSS To Post does not support password-protected feeds.';
			$lang->rss2post_faq_url_nofopen = 'Your allow_url_fopen PHP setting is currently <strong>disabled</strong>.  RSS To Post will not be able to function with this disabled.  Please ask your host to enable this setting.  Find more information about this on the <a href="http://ca3.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen">PHP.net website</a>';
			$lang->rss2post_credits = 'Credits';
			$lang->rss2post_credits_desc = 'Credits to Vojtech Semecky for "lastRSS" parser class.';
			$lang->rss2post_author = 'Author';
			//$lang->rss2post_translator = 'English translation by <a href="http://dennistt.net">DennisTT</a>.';
			
			$lang->rss2post_feeds_url = 'URL';
			$lang->rss2post_feeds_forum = 'Forum';
			$lang->rss2post_feeds_uid = 'Post User ID';
			$lang->rss2post_feeds_striphtml = 'Strip HTML';
			$lang->rss2post_feeds_delete = 'Delete';
			$lang->rss2post_error_no_feeds = 'No RSS feeds to display';
			$lang->rss2post_feeds = 'Feeds';
			$lang->rss2post_feeds_add = 'Add New Feed';
			$lang->rss2post_save_changes = 'Save Changes';
			$lang->rss2post_choose_forum = 'Choose a forum';
			
			$lang->rss2post_error_invalid_fid = 'One or more of the forum IDs you have provided is invalid.  You must choose an open forum.';
			$lang->rss2post_error_invalid_uid = 'One or more of the user IDs you have provided is invalid.';
			$lang->rss2post_warning = 'WARNING: ';
			$lang->rss2post_error_no_task_file = 'The RSS To Post task file (inc/tasks/rss2post_task.php) does not exist.  You <em>must</em> upload this file for RSS To Post to function.';
			$lang->rss2post_error_task_nonexist = 'The RSS To Post task file (inc/tasks/rss2post_task.php) has not been added to the task system.  <a href="index.php?module=config/rss2post&amp;action=addtask">Add it to the task system</a>.';
			$lang->rss2post_error_task_disabled = 'The RSS To Post task is currently disabled.  Feeds will <em>not</em> be processed.  <a href="index.php?module=tools/tasks&amp;action=enable&amp;tid={1}">Enable the task</a>.';
			$lang->rss2post_updated = 'RSS To Post feeds updated successfully.';
			$lang->rss2post_error_allow_url_fopen = 'Your allow_url_fopen PHP setting is currently <strong>disabled</strong>.  RSS To Post will NOT function.  See the <a href="index.php?module=config/rss2post">FAQ</a>';
			$lang->rss2post_task_inserted = 'The RSS To Post task was successfully added.';
			$lang->rss2post_task_exists = 'The RSS To Post task already exists.';
			
			$lang->task_rss2post_task_ran = 'The RSS To Post task succesfully ran.';
			
			$lang->admin_log_config_rss2post_add = 'Added RSS To Post feed #{1}: {2}';
			$lang->admin_log_config_rss2post_update = 'Updated RSS To Post feed #{1}: {2}';
			$lang->admin_log_config_rss2post_delete = 'Deleted RSS To Post feed #{1}: {2}';
		}
		
		define('DENNISTT_RSS2POST_LANG_LOADED', 1);
	}
}

function rss2post_activate()
{
	global $db, $cache, $message;
	
	$cache->update('rss2post_errors', null);
	$db->query("
		CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."rss2post` (
		  `fid` int(10) unsigned NOT NULL auto_increment,
		  `url` varchar(255) NOT NULL,
		  `forum` int(10) unsigned NOT NULL,
		  `uid` int(10) unsigned NOT NULL default '0',
		  `striphtml` tinyint(1) unsigned NOT NULL default '0',
		  PRIMARY KEY  (`fid`)
		)
		");

	$info = rss2post_info();

/* 	$setting_group_array = array(
		'name' => str_replace(' ', '_', 'dennistt_'.strtolower($info['name'])),
		'title' => "$info[name] (DennisTT)",
		'description' => "Settings for the $info[name] plugin",
		'disporder' => 1,
		'isdefault' => 'no',
		);
	$db->insert_query('settinggroups', $setting_group_array);
	$group = $db->insert_id();

	$settings = array(
		'rss2post_userid' => array('User ID', 'User ID of the user who appears to be posting the threads.', 'text', '1'),
		'rss2post_striphtml' => array('Strip HTML', 'If the feed content contains HTML should it be stripped?', 'yesno', '1'),
		);

	$i = 1;
	foreach($settings as $name => $sinfo)
	{
		$setting_array = array(
			'name' => $name,
			'title' => $sinfo[0],
			'description' => $sinfo[1],
			'optionscode' => $sinfo[2],
			'value' => $sinfo[3],
			'gid' => $group,
			'disporder' => $i,
			'isdefault' => 0
			);
			
		$db->insert_query('settings', $setting_array); 
		$i++;
	}
	rebuild_settings(); */
	
	change_admin_permission('config', 'rss2post');
	
	// Check the task file
	if(!file_exists(MYBB_ROOT.'inc/tasks/rss2post_task.php'))
	{
		global $lang;
		rss2post_load_language();
		
		$message .= '<br /><br />';
		$message .= $lang->rss2post_warning;
		$message .= $lang->rss2post_error_no_task_file;
	}
	else
	{
		rss2post_add_task_helper();
	}
}

function rss2post_deactivate()
{
	global $db, $mybb;

	$info = rss2post_info();

/* 	$result = $db->simple_select("settinggroups", "gid", "title = '{$info['name']} (DennisTT)'", array('limit' => 1));
	$group = $db->fetch_array($result);
	
	if(!empty($group['gid']))
	{
		$db->delete_query("settinggroups", "gid='{$group['gid']}'");
		$db->delete_query("settings", "gid='{$group['gid']}'");
		rebuild_settings();
	}
 */
	if($db->table_exists('rss2post'))
	{
		$db->query("DROP TABLE ".TABLE_PREFIX."rss2post");
	}
	$db->delete_query("datacache", "title='rss2post_errors'");
	
	// Remove the task(s)
	// Save previous module and action
	$prev_module = $mybb->input['module'];
	$prev_action = $mybb->input['action'];
	
	// Set module and action for task manager
	$mybb->input['module'] = 'tools/tasks';
	$mybb->input['action'] = 'delete';
	
	$query = $db->simple_select('tasks', 'tid, title', 'file=\'rss2post_task\'');
	while($task = $db->fetch_array($query))
	{
		log_admin_action($task['tid'], $task['title']);
	}
	
	// Actually delete 
	$db->delete_query('tasks', 'file=\'rss2post_task\'');
	
	// Reset module and action
	$mybb->input['module'] = $prev_module;
	$mybb->input['action'] = $prev_action;
	change_admin_permission('config', 'rss2post', -1);
}

// ACP Configuration Menu Item
$plugins->add_hook("admin_config_menu", "rss2post_admin_config_menu");
function rss2post_admin_config_menu(&$sub_menu)
{
	// Load the language
	global $lang;
	rss2post_load_language();
	
	// Get the last submenu key
	$sub_menu_keys = array_keys($sub_menu);
	$last_id = intval(array_pop($sub_menu_keys));
	
	$menu_item = array('id' => 'rss2post', 'title' => $lang->rss2post, 'link' => 'index.php?module=config/rss2post');
	
	// Add the new menu item
	if($last_id)
	{
		$last_id += 10;
		$sub_menu["$last_id"] = $menu_item;
	}
	else
	{
		$sub_menu[] = $menu_item;
	}
}

// ACP Configuration Menu Action Handler
$plugins->add_hook("admin_config_action_handler", "rss2post_admin_config_action_handler");
function rss2post_admin_config_action_handler(&$actions)
{
	$actions['rss2post'] = array('active' => 'rss2post', 'file' => 'settings.php');
}

// ACP Configuration Menu Permissions
$plugins->add_hook("admin_config_permissions", "rss2post_admin_config_permissions");
function rss2post_admin_config_permissions(&$admin_permissions)
{
	// Load the language
	global $lang;
	rss2post_load_language();
	
	$admin_permissions['rss2post'] = $lang->can_manage_rss2post;
}

// ACP Admin Log stuff
$plugins->add_hook("admin_tools_get_admin_log_action", "rss2post_admin_tools_get_admin_log_action");
function rss2post_admin_tools_get_admin_log_action(&$plugin_array)
{
	global $lang;
	rss2post_load_language();
}

$plugins->add_hook("admin_load", "rss2post_admin");
function rss2post_admin()
{
	global $mybb, $db, $page;
	
	if($page->active_action == "rss2post")
	{
		// Load the language
		global $lang;
		rss2post_load_language();
		
		$page->add_breadcrumb_item($lang->rss2post);
		$page->output_header($lang->rss2post);
		
		// Warnings for task stuff
		$query = $db->simple_select('tasks', 'tid, enabled', 'file=\'rss2post_task\'');
		$task = $db->fetch_array($query);
		if(!file_exists(MYBB_ROOT.'inc/tasks/rss2post_task.php'))
		{
			$page->output_alert($lang->rss2post_error_no_task_file);
		}
		elseif(!$db->num_rows($query))
		{
			$page->output_alert($lang->rss2post_error_task_nonexist);
		}
		elseif(!$task['enabled'])
		{
			$page->output_alert($lang->sprintf($lang->rss2post_error_task_disabled, $task['tid']));
		}

		// allow_url_fopen warning
		if(!intval(ini_get('allow_url_fopen')))
		{
			$page->output_alert($lang->rss2post_error_allow_url_fopen);
		}
		
		$action = (isset($mybb->input['action'])) ? $mybb->input['action'] : 'config';
		
		// Plugin setting group
		// $info = rss2post_info();
		// $query = $db->simple_select('settinggroups', 'gid', 'name=\''.str_replace(' ', '_', 'dennistt_'.strtolower($info['name'])).'\'', array('limit' => 1));
		// $gid = $db->fetch_field($query, 'gid');
		
		$sub_tabs['config'] = array(
			'title' => $lang->rss2post_feeds,
			'link' => 'index.php?module=config/rss2post',
			'description' => $lang->rss2post_feeds_desc
		);
		
		// $sub_tabs['settings'] = array(
			// 'title' => $lang->rss2post_settings,
			// 'link' => 'index.php?module=config/settings&amp;action=change&gid='.$gid
		// );
		
		$sub_tabs['error_log'] = array(
			'title' => $lang->rss2post_error_log,
			'link' => 'index.php?module=tools/cache&action=view&title=rss2post_errors'
		);
		
		$sub_tabs['faq'] = array(
			'title' => $lang->rss2post_faq,
			'link' => 'index.php?module=config/rss2post&amp;action=faq',
			'description' => $lang->rss2post_faq_desc
		);

		$page->output_nav_tabs($sub_tabs, $action);
		
		if($action == 'addtask')
		{
			if(rss2post_add_task_helper())
			{
				flash_message($lang->rss2post_task_inserted, 'success');
			}
			else
			{
				flash_message($lang->rss2post_task_exists, 'success');
			}
			admin_redirect("index.php?module=config/rss2post");
		}
		elseif($action == 'faq')
		{
			echo "<h3>{$lang->rss2post_faq_troubleshooting_header}</h3>";
			echo "<h4>{$lang->rss2post_faq_no_entries}</h4>";
			echo '<ul>';
			echo "<li>{$lang->rss2post_faq_no_entries_a1}</li>";
			echo "<li>{$lang->rss2post_faq_no_entries_a2}</li>";
			echo "<li>{$lang->rss2post_faq_no_entries_a3}</li>";
			echo '</ul>';
			echo "<h4>{$lang->rss2post_faq_disable}</h4>";
			echo "<p>{$lang->rss2post_faq_disable_desc}</p>";
			echo "<h4>{$lang->rss2post_faq_url}</h4>";
			if(intval(ini_get('allow_url_fopen')))
			{
				echo "<p>{$lang->rss2post_faq_url_fopen}</p>";
			}
			else
			{
				echo "<p>{$lang->rss2post_faq_url_nofopen}</p>";
			}
			echo "<h3>{$lang->rss2post_credits}</h3>";
			echo "<p>{$lang->rss2post_credits_desc}</p>";
			echo "<p>{$lang->rss2post_translator}</p>";
			echo "<h3>{$lang->rss2post_author}</h3>";
			echo '<p><a href="http://dennistt.net">DennisTT</a> is the author of this plugin.  English support can be requested at his forum at <a href="http://dennistt.net/forum">http://dennistt.net/forum</a>.<br />';
			echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<fieldset>
<legend>Donate via PayPal</legend>
<p>If you like this plugin, please consider donating a few dollars to my PayPal account to support the development of this, and other plugins for MyBB.  Thanks!</p>
<div align="center">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="paypal@dennistt.net">
<input type="hidden" name="item_name" value="MyBB Plugin by DennisTT - RSS To Post">
<input type="hidden" name="item_number" value="1.2.1">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="CAD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="CA">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</div>
</fieldset>
</form>';
			echo '</p>';
		}
		else
		{
			$errors = null;
			// Update existing feed info
			if($mybb->request_method == "post")
			{
				if(is_array($mybb->input['url']))
				{
					foreach($mybb->input['url'] as $fid => $url)
					{
						$fid = intval($fid);
						// Delete if requested
						if((isset($mybb->input['delete'][$fid]) && $mybb->input['delete'][$fid] == 'true') || !trim($url))
						{
							$query = $db->simple_select('rss2post', '*', "fid='{$fid}'", array('limit' => 1));
							$feed = $db->fetch_array($query);
							
							if($feed['fid'])
							{
								$mybb->input['action'] = 'delete';
								$log = array('fid' => $fid, 'url' => $feed['url']);
								log_admin_action($log);
								
								$db->delete_query("rss2post", "fid='{$fid}'", 1);
								continue;
							}
						}

						// Validate variables
						$forum = intval($mybb->input['forum'][$fid]);
						$query = $db->simple_select("forums", "fid", "type='f' AND fid='{$forum}'");
						if($db->num_rows($query) == 0)
						{
							$errors['invalid_fid'] = $lang->rss2post_error_invalid_fid;
							continue;
						}
						
						$uid = intval($mybb->input['postuid'][$fid]);
						if(!user_exists($uid))
						{
							$errors['invalid_uid'] = $lang->rss2post_error_invalid_uid;
							continue;
						}
						
						$update_array = array(
							'url' => $db->escape_string($url),
							'forum' => $forum,
							'uid' => $uid,
							'striphtml' => (!empty($mybb->input['striphtml'][$fid])) ? 1 : 0,
							);
						$db->update_query("rss2post", $update_array, "fid='{$fid}'");
						
						if($db->affected_rows())
						{
							$mybb->input['action'] = 'update';
							$log = array('fid' => $fid, 'url' => $url);
							log_admin_action($log);
						}
					}
				}
				if(!empty($mybb->input['newurl']) && $mybb->input['newurl'] != 'http://')
				{
					$forum = intval($mybb->input['newforum']);
					$query = $db->simple_select("forums", "fid", "type='f' AND fid='{$forum}'");
					if($db->num_rows($query) == 0)
					{
						$errors['invalid_fid'] = $lang->rss2post_error_invalid_fid;
					}
					else
					{
						$uid = intval($mybb->input['newuid']);
						if(!user_exists($uid))
						{
							$errors['invalid_uid'] = $lang->rss2post_error_invalid_uid;
						}
						else
						{
							$insert_array = array(
								'url' => $db->escape_string($mybb->input['newurl']),
								'forum' => $forum,
								'uid' => $uid,
								'striphtml' => (!empty($mybb->input['newstriphtml'])) ? 1 : 0,
								);
							$db->insert_query("rss2post", $insert_array);
							
							// Log admin action
							$mybb->input['action'] = 'add';
							$log = array('fid' => $db->insert_id(), 'url' => $mybb->input['newurl']);
							log_admin_action($log);
							
							// Reset new feed info
							unset($mybb->input['newurl']);
							unset($mybb->input['newforum']);
						}
					}
				}
				
				if(!$errors)
				{
					flash_message($lang->rss2post_updated, 'success');
					admin_redirect("index.php?module=config/rss2post");
				}
			}
			
			$form = new Form("index.php?module=config/rss2post", "post", "add");

			if($errors)
			{
				$page->output_inline_error($errors);
			}

			$table = new Table;
			$table->construct_header($lang->rss2post_feeds_url, array('style' => 'width: 40%'));
			$table->construct_header($lang->rss2post_feeds_forum, array('style' => 'width: 30%'));
			$table->construct_header($lang->rss2post_feeds_uid, array('style' => 'width: 10%'));
			$table->construct_header($lang->rss2post_feeds_striphtml, array('style' => 'width: 10%; text-align: center;'));
			$table->construct_header($lang->rss2post_feeds_delete, array('style' => 'width: 10%; text-align: center;'));
			
			$query = $db->simple_select('rss2post');
			if($db->num_rows($query) > 0)
			{
				while($row = $db->fetch_array($query))
				{
					$table->construct_cell($form->generate_text_box("url[{$row['fid']}]", ($mybb->input['url'][$row['fid']]) ? $mybb->input['url'][$row['fid']] : $row['url'], array('style' => 'width: 95%')));
					$table->construct_cell($form->generate_forum_select("forum[{$row['fid']}]", ($mybb->input['forum'][$row['fid']]) ? $mybb->input['forum'][$row['fid']] : $row['forum']));
					$table->construct_cell($form->generate_text_box("postuid[{$row['fid']}]", ($mybb->input['postuid'][$row['fid']]) ? $mybb->input['postuid'][$row['fid']] : $row['uid'], array('style' => 'width: 90%')));
					$table->construct_cell($form->generate_check_box("striphtml[{$row['fid']}]", 'true', null, array('checked' => ($mybb->input['striphtml'][$row['fid']] || $row['striphtml']) ? 1 : 0)), array('class' => 'align_center'));
					$table->construct_cell($form->generate_check_box("delete[{$row['fid']}]", 'true'), array('class' => 'align_center'));
					$table->construct_row(array('class' => $page->get_alt_bg()));
				}
			}
			else
			{
				$table->construct_cell($lang->rss2post_error_no_feeds, array('colspan' => 5));
				$table->construct_row(array('class' => $page->get_alt_bg()));
			}
			$table->output($lang->rss2post_feeds);
			
			// New feed
			$table = new Table;
			$table->construct_header($lang->rss2post_feeds_url, array('style' => 'width: 40%'));
			$table->construct_header($lang->rss2post_feeds_forum, array('style' => 'width: 30%'));
			$table->construct_header($lang->rss2post_feeds_uid, array('style' => 'width: 10%'));
			$table->construct_header($lang->rss2post_feeds_striphtml, array('style' => 'width: 10%; text-align: center;'));
			$table->construct_header('', array('style' => 'width: 10%'));
			
			$table->construct_cell($form->generate_text_box("newurl", ($mybb->input['newurl']) ? $mybb->input['newurl'] : 'http://', array('style' => 'width: 95%')));
			$table->construct_cell($form->generate_forum_select("newforum", ($mybb->input['newforum']) ? $mybb->input['newforum'] : -1, array('main_option' => $lang->rss2post_choose_forum)));
			$table->construct_cell($form->generate_text_box("newuid", ($mybb->input['newuid']) ? $mybb->input['newuid'] : 1, array('style' => 'width: 90%')));
			$table->construct_cell($form->generate_check_box("newstriphtml", 'true'), array('class' => 'align_center'));
			$table->construct_cell('');
			$table->construct_row(array('class' => $page->get_alt_bg()));
			$table->output($lang->rss2post_feeds_add);

			$buttons[] = $form->generate_submit_button($lang->rss2post_save_changes);
			$form->output_submit_wrapper($buttons);
			$form->end();		
		}
		
		echo '<br /><small>RSS To Post Plugin &copy; 2006-'.COPY_YEAR.' <a href="http://dennistt.net">DennisTT</a>.</small>';
		
		$page->output_footer(true);
	}
}

// Adds a task to the task system if it doesn't exist already
function rss2post_add_task_helper()
{
	global $db, $lang, $mybb;
	require_once MYBB_ROOT.'inc/functions_task.php';
	
	rss2post_load_language();
	
	// Check if an existing task exists
	$query = $db->simple_select('tasks', 'COUNT(tid) as count', 'file=\'rss2post_task\'');
	if(!$db->fetch_field($query, 'count'))
	{
		// Save previous module and action
		$prev_module = $mybb->input['module'];
		$prev_action = $mybb->input['action'];
		
		// Set module and action for task manager
		$mybb->input['module'] = 'tools/tasks';
		$mybb->input['action'] = 'add';
		
		// Make a nice task
		$insert_task = array(
			'title' => $lang->rss2post,
			'description' => $lang->rss2post_task_desc,
			'file' => 'rss2post_task',
			'minute' => 15,
			'hour' => '*',
			'day' => '*',
			'month' => '*',
			'weekday' => '*',
			'lastrun' => 0,
			'enabled' => 1,
			'logging' => 1,
			'locked' => 0,
			);
		$insert_task['nextrun'] = fetch_next_run($insert_task);
		
		$db->insert_query('tasks', $insert_task);
		$tid = $db->insert_id();
		
		log_admin_action($tid, $lang->rss2post);
		
		// Reset module and action
		$mybb->input['module'] = $prev_module;
		$mybb->input['action'] = $prev_action;
		
		return true;
	}
	return false;
}

?>