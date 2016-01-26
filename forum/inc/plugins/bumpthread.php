<?php

$plugins->add_hook('datahandler_post_insert_post', 'bumpthread_newpost');
$plugins->add_hook('datahandler_post_insert_thread', 'bumpthread_newthread');
$plugins->add_hook('showthread_start', 'bumpthread_run');
$plugins->add_hook('forumdisplay_start', 'bumpthread_foruminject');

function bumpthread_info()
{
	return array(
		'name'			=> 'Bump Thread',
		'description'	=> 'Allows users to bump their own threads without posting.',
		'website'		=> 'http://mybbhacks.zingaburga.com/',
		'author'		=> 'ZiNgA BuRgA',
		'authorsite'	=> 'http://zingaburga.com/',
		'version'		=> '1.01',
		'compatibility'	=> '14*',
		'guid'			=> '67f2595a3617b48098f26ed8b6fd6455'
	);
}

function bumpthread_activate()
{
	global $db;
	$db->query('ALTER TABLE '.$db->table_prefix.'threads ADD (
		`lastpostbump` BIGINT(30) UNSIGNED NOT NULL DEFAULT 0
	)');
	$db->query('UPDATE '.$db->table_prefix.'threads SET lastpostbump=lastpost');
	
	
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('showthread', '#'.preg_quote('{$thread[\'subject\']}</strong>').'#', '{$thread[\'subject\']}</strong>{$bumpthread}');
	
	$new_template = ' (<a href="showthread.php?tid={$tid}&amp;action=bump">Bump This Thread</a>)';
	$db->insert_query('templates', array(
		'title' => 'showthread_bumpthread',
		'template' => $db->escape_string($new_template),
		'sid' => -1,
		'version' => 120
	));
	
	
	// add settings
	$settings_gid = $db->insert_query('settinggroups', array(
		'name' => 'bumpthread',
		'title' => 'Bump Thread Options',
		'disporder' => 50,
	));
	$db->insert_query('settings', array(
		'name' => 'bumpthread_interval',
		'optionscode' => 'text',
		'value' => 30,
		'title' => 'Time Between Bumps',
		'description' => 'The time (in minutes) a user must wait before they are allowed to (re) bump their thread.',
		'disporder' => 1,
		'gid' => $settings_gid
	));
	rebuild_settings();
}

function bumpthread_deactivate()
{
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('showthread', '#\{\$bumpthread\}#', '', 0);
	
	global $db;
	$db->delete_query('templates', 'title = "showthread_bumpthread"');
	
	$db->query('ALTER TABLE '.$db->table_prefix.'threads DROP `lastpostbump`');
	
	$gid = $db->fetch_field($db->simple_select('settinggroups', 'gid', 'name="bumpthread"'), 'gid');
	if($gid)
	{
		$db->delete_query('settings', 'gid='.$gid);
		$db->delete_query('settinggroups', 'gid='.$gid);
	}
	rebuild_settings();
}

function bumpthread_newpost(&$ph)
{
	global $db;
	$db->update_query('threads', array('lastpostbump' => TIME_NOW), 'tid='.$ph->data['tid']);
}

function bumpthread_newthread(&$ph)
{
	$ph->thread_insert_data['lastpostbump'] = $ph->data['dateline'];
}

function bumpthread_run()
{
	global $mybb, $thread, $db;
	if($mybb->input['action'] == 'bump')
	{
		if($mybb->usergroup['cancp'] != 1 && $mybb->usergroup['issupermod'] != 1 && $thread['uid'] != $mybb->user['uid'])
			error_no_permission();
		
		if($thread['lastpostbump'] + intval($mybb->settings['bumpthread_interval'])*60 > TIME_NOW)
			error('You cannot bump this thread within '.intval($mybb->settings['bumpthread_interval']).' minute(s) of its last bump.');
		
		$db->update_query('threads', array('lastpostbump' => TIME_NOW), 'tid='.$thread['tid']);
		redirect('showthread.php?tid='.$thread['tid'], 'Thread Bumped');
	}
	else
	{
		// eval bump
		if($mybb->usergroup['cancp'] == 1 || $mybb->usergroup['issupermod'] == 1 || $thread['uid'] == $mybb->user['uid'])
		{
			global $bumpthread, $templates, $tid;
			eval('$bumpthread = "'.$templates->get('showthread_bumpthread').'";');
		}
	}
}

function bumpthread_foruminject()
{
	global $db;
	eval('
		class BumpThreadDummyDB extends '.get_class($db).'
		{
			function BumpThreadDummyDB(&$olddb)
			{
				$vars = get_object_vars($olddb);
				foreach($vars as $var => $val)
					$this->$var = $val;
			}
			
			function query($string, $hideerr=0)
			{
				$string = str_replace(\'t.lastpost\', \'t.lastpostbump\', $string);
				return parent::query($string, $hideerr);
			}
		}
	');
	$db = new BumpThreadDummyDB($db);
	
}


?>