<?php
/**
 * My Question
 * Copyright 2008 Unlimited020
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook('showthread_start', 'myquestion_run');

function myquestion_info()
{
	return array(
		'name'			=> 'My Question',
		'description'	=> 'Allows thread authors and staff to lock a topic, and declare it as SOLVED.',
		'website'		=> 'http://mods.mybboard.net/view/my-question',
		'author'		=> 'Unlimited020',
		'authorsite'	=> 'http://community.mybboard.net/user-15817.html',
		'version'		=> '1.3',
		'compatibility'	=> '14*',
		'guid'			=> '7e02446603d3ae12a44302d97b03c8fc'
	);
}

function myquestion_activate()
{
	global $db;
	$db->query('ALTER TABLE '.$db->table_prefix.'threads ADD (
		`solved` BIGINT(30) UNSIGNED NOT NULL DEFAULT 0
	)');
	$db->query('ALTER TABLE '.$db->table_prefix.'threads ADD (
		`oldfid` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0
	)');
	$db->query('ALTER TABLE '.$db->table_prefix.'threads ADD (
		`oldsubject` VARCHAR(120) NOT NULL DEFAULT 0
	)');
	
	
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('showthread', '#'.preg_quote('{$thread[\'subject\']}</strong>').'#', '{$thread[\'subject\']}</strong>{$myquestion}');
	
	$new_template = ' (<a href="showthread.php?tid={$tid}&amp;action=solve">Close and mark thread as solved</a>) | (<a href="showthread.php?tid={$tid}&amp;action=unsolve">Mark thread as unsolved</a>)';
	$db->insert_query('templates', array(
		'title' => 'showthread_myquestion',
		'template' => $db->escape_string($new_template),
		'sid' => -1,
		'version' => 120
	));
	
	
	// add settings
	$settings_gid = $db->insert_query('settinggroups', array(
		'name' => 'myquestion',
		'title' => 'My Question Options',
		'disporder' => 50,
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_enable',
		'optionscode' => 'yesno',
		'value' => 1,
		'title' => 'Enable?',
		'description' => 'Globally, enable the My Question system? (You can disable it here without losing your settings).',
		'disporder' => 1,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_disabled',
		'optionscode' => 'text',
		'value' => 'The Administrator has disabled this feature.',
		'title' => 'Text when disabled',
		'description' => 'When the plugin is disabled, and the user visits the (un)solve URL, what should it output?',
		'disporder' => 2,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_moveforum',
		'optionscode' => 'text',
		'value' => '',
		'title' => 'Move solved thread',
		'description' => 'If you prefer that the solved thread will be moved to another forum instead of adding a prefix to it, specify that forum here. (Leave blank to ignore this setting, only enter the forum ID).',
		'disporder' => 3,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_prefixsolved',
		'optionscode' => 'text',
		'value' => '[SOLVED] ',
		'title' => 'Thread prefix when solved',
		'description' => 'When the thread is marked as solved, what will the prefix be? (Will be ignored if you filled in the previous setting).',
		'disporder' => 4,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_threadsolved',
		'optionscode' => 'text',
		'value' => 'This thread is already solved!',
		'title' => 'Thread already solved',
		'description' => 'When the thread is already solved, what should it output?',
		'disporder' => 5,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_threadunsolved',
		'optionscode' => 'text',
		'value' => 'This thread is already unsolved!',
		'title' => 'Thread already unsolved',
		'description' => 'When the thread is already unsolved, what should it output?',
		'disporder' => 6,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_redirectsolved',
		'optionscode' => 'text',
		'value' => 'Thread Solved',
		'title' => 'Text when redirecting',
		'description' => 'During the redirect, when the thread is marked as solved, what should it output?',
		'disporder' => 7,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_redirectunsolved',
		'optionscode' => 'text',
		'value' => 'Thread Unsolved',
		'title' => 'Text when redirecting (unsolved)',
		'description' => 'During the redirect, when the thread is marked as unsolved, what should it output?',
		'disporder' => 8,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_forums',
		'optionscode' => 'text',
		'value' => '',
		'title' => 'Enable in specific forums',
		'description' => 'Enable the plugin in specific forums? (Separated by commas without spaces, only enter the forum ID, leave blank to use in all forums).',
		'disporder' => 9,
		'gid' => $settings_gid
	));
	$db->insert_query('settings', array(
		'name' => 'myquestion_forumdisabled',
		'optionscode' => 'text',
		'value' => 'The Administrator has disabled this feature for this forum.',
		'title' => 'Text when disabled in forum',
		'description' => 'When the plugin is enabled in specific forums, and the user visits the (un)solve URL in a wrong forum, what should it output?',
		'disporder' => 10,
		'gid' => $settings_gid
	));

	rebuild_settings();
}

function myquestion_deactivate()
{
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('showthread', '#\{\$myquestion\}#', '', 0);
	
	global $db;
	$db->delete_query('templates', 'title = "showthread_myquestion"');
	
	$db->query('ALTER TABLE '.$db->table_prefix.'threads DROP `solved`');
	$db->query('ALTER TABLE '.$db->table_prefix.'threads DROP `oldfid`');
	$db->query('ALTER TABLE '.$db->table_prefix.'threads DROP `oldsubject`');
	
	$gid = $db->fetch_field($db->simple_select('settinggroups', 'gid', 'name="myquestion"'), 'gid');
	if($gid)
	{
		$db->delete_query('settings', 'gid='.$gid);
		$db->delete_query('settinggroups', 'gid='.$gid);
	}
	rebuild_settings();
}

function myquestion_run()
{
	global $mybb, $thread, $db;
	$arr = explode(',', $mybb->settings['myquestion_forums']);
	if($mybb->input['action'] == 'solve')
	{
		if($mybb->usergroup['cancp'] != 1 && $mybb->usergroup['issupermod'] != 1 && $thread['uid'] != $mybb->user['uid'])
		{
			error_no_permission();
		}
		elseif($thread['solved'] == 1)
		{
			error($mybb->settings['myquestion_threadsolved']);
		}
		elseif($mybb->settings['myquestion_enable'] == 0)
		{
			error($mybb->settings['myquestion_disabled']);
		}
		elseif(!in_array($thread['fid'], $arr) && $mybb->settings['myquestion_forums'] != "")
		{
			error($mybb->settings['myquestion_forumdisabled']);
		}
		elseif($mybb->settings['myquestion_moveforum'] != "")
		{
			$db->update_query('threads', array('solved' => 1), 'tid='.$thread['tid']);
			$db->update_query('threads', array('closed' => 1), 'tid='.$thread['tid']);
			$db->update_query('threads', array('oldfid' => $thread['fid']), 'tid='.$thread['tid']);
			$db->update_query('threads', array('fid' => $mybb->settings['myquestion_moveforum']), 'tid='.$thread['tid']);
			$db->update_query('posts', array('fid' => $mybb->settings['myquestion_moveforum']), 'tid='.$thread['tid']);
			redirect('showthread.php?tid='.$thread['tid'], $mybb->settings['myquestion_redirectsolved']);
		} else {
			$db->update_query('threads', array('solved' => 1), 'tid='.$thread['tid']);
			$db->update_query('threads', array('closed' => 1), 'tid='.$thread['tid']);
			$db->update_query('threads', array('oldsubject' => $thread['subject']), 'tid='.$thread['tid']);
			$thread_title = $thread['subject'];
			$thread_title_new = $mybb->settings['myquestion_prefixsolved'] . $thread_title;
			$db->update_query('threads', array('subject' => $thread_title_new), 'tid='.$thread['tid']);
			$db->update_query('posts', array('subject' => $thread_title_new), 'tid='.$thread['tid']);
			redirect('showthread.php?tid='.$thread['tid'], $mybb->settings['myquestion_redirectsolved']);
		}
	}
	elseif($mybb->input['action'] == 'unsolve')
	{
		if($mybb->usergroup['cancp'] != 1 && $mybb->usergroup['issupermod'] != 1 && $thread['uid'] != $mybb->user['uid'])
		{
			error_no_permission();
		}
		elseif($thread['solved'] == 0)
		{
			error($mybb->settings['myquestion_threadunsolved']);
		}
		elseif($mybb->settings['myquestion_enable'] == 0)
		{
			error($mybb->settings['myquestion_disabled']);
		}
		elseif(!in_array($thread['fid'], $arr) && $mybb->settings['myquestion_forums'] != "")
		{
			error($mybb->settings['myquestion_forumdisabled']);
		}
		elseif($mybb->settings['myquestion_moveforum'] != "")
		{
			$db->update_query('threads', array('solved' => 0), 'tid='.$thread['tid']);
			$db->update_query('threads', array('closed' => 0), 'tid='.$thread['tid']);
			$db->update_query('threads', array('fid' => $thread['oldfid']), 'tid='.$thread['tid']);
			$db->update_query('posts', array('fid' => $thread['oldfid']), 'tid='.$thread['tid']);
			redirect('showthread.php?tid='.$thread['tid'], $mybb->settings['myquestion_redirectunsolved']);
		} else {
			$db->update_query('threads', array('solved' => 0), 'tid='.$thread['tid']);
			$db->update_query('threads', array('closed' => 0), 'tid='.$thread['tid']);
			$db->update_query('threads', array('subject' => $thread['oldsubject']), 'tid='.$thread['tid']);
			$db->update_query('posts', array('subject' => $thread['oldsubject']), 'tid='.$thread['tid']);
			redirect('showthread.php?tid='.$thread['tid'], $mybb->settings['myquestion_redirectunsolved']);
		}
	}
	else
	{
		if($mybb->usergroup['cancp'] == 1 || $mybb->usergroup['issupermod'] == 1 || $thread['uid'] == $mybb->user['uid'])
		{
			if(in_array($thread['fid'], $arr) || $mybb->settings['myquestion_forums'] == "")
			{
				if($mybb->settings['myquestion_enable'] == 1)
				{
					global $myquestion, $templates, $tid;
					eval('$myquestion = "'.$templates->get('showthread_myquestion').'";');
				}
			}
		}
	}
}

?>
