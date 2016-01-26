<?php

if(!defined("IN_MYBB"))
	die("This file cannot be accessed directly.");

$plugins->add_hook('editpost_start', 'adminpedit_editpost');
$plugins->add_hook('editpost_do_editpost_start', 'adminpedit_do_editpost_start');

function adminpedit_info()
{
	return array(
		'name'			=> 'Admin Post Edit',
		'description'	=> 'Gives more options to admins when editing posts/threads.',
		'website'		=> 'http://mybbhacks.zingaburga.com/',
		'author'		=> 'ZiNgA BuRgA',
		'authorsite'	=> 'http://zingaburga.com/',
		'version'		=> '1.1',
		'compatibility'	=> '14*',
		'guid'			=> '4661af5161be4b6c4e43ec46edbcc959'
	);
}


function adminpedit_activate()
{
	global $db;
	
	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('editpost', '#'.preg_quote('<input type="hidden" name="action" value="do_editpost" />').'#', '{$adminopt}<input type="hidden" name="action" value="do_editpost" />');
	
	$db->insert_query('templates', array(
		'title' => 'editpost_adminopt',
		'template' => $db->escape_string(
'<br /><input type="hidden" name="adminpedit_active" value="1" />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="3"><strong>Admin Options</strong></td>
</tr>
<tr>
<td width="50%" class="trow1">
<strong>Edit Timestamp</strong>
<br /><span class="smalltext">This follows the <a href="http://www.unixtimestamp.com/">Unix timestamp</a> format</span>
</td>
<td class="trow1"><input type="text" name="posttime" value="{$dateline}" class="textbox" /></td>
</tr>
<tr>
<td width="50%" class="trow2">
<strong>Post User</strong>
<br /><span class="smalltext">The user who made this post</span>
</td>
<td class="trow2"><input type="text" name="postusername" value="{$postusername}" class="textbox" />
<br /><label><input type="checkbox" name="postreguser" value="yes" class="checkbox"{$regusercheck} /> Post is made by a registered user</label></td>
</tr>
<tr>
<td width="50%" class="trow1">
<strong>Post IP</strong>
<br /><span class="smalltext">This is the IP address from which the user made this post.</span>
</td>
<td class="trow1"><input type="text" name="postip" value="{$ipaddress}" class="textbox" /></td>
</tr>
{$editopt}
</table>
'
		),
		'sid' => -1,
		'version' => '1400'
	));
}

function adminpedit_deactivate()
{
	global $db;
	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('editpost', '#'.preg_quote('{$adminopt}').'#', '', 0);
	$db->delete_query('templates', 'sid=-1 AND title = "editpost_adminopt"');
}

function adminpedit_editpost()
{
	global $mybb;
	if($mybb->usergroup['cancp'] != 1) return;
	
	global $templates, $post, $adminopt, $theme;
	
	// attempt to detect screwy templates
	if($templates->cache['editpost'] && !strpos($templates->cache['editpost'], '$adminopt'))
		$templates->cache['editpost'] = str_replace('<input type="hidden" name="action" value="do_editpost" />', '{$adminopt}<input type="hidden" name="action" value="do_editpost" />', $templates->cache['editpost']);
	
	// uid, username, dateline, ipaddress, edituid, edittime
	if($post['uid'] || $mybb->input['postreguser'] == 'yes')
		$regusercheck = ' checked="checked"';
	else
		$regusercheck = '';
	if($mybb->input['postusername'])
		$postusername = htmlspecialchars_uni($mybb->input['postusername']);
	else
		$postusername = htmlspecialchars_uni($post['username']);
	
	if($mybb->input['posttime'])
		$dateline = intval($mybb->input['posttime']);
	else
		$dateline = $post['dateline'];
	
	if($mybb->input['postip'])
		$ipaddress = htmlspecialchars($mybb->input['postip']);
	else
		$ipaddress = $post['ipaddress'];
	
	// silent/clear editing
	$editopt = '';
	if($mybb->settings['showeditedbyadmin'] == 1)
		$editopt .= '<br /><label><input type="radio" name="editopt" value="silent"'.($mybb->input['editopt'] == 'silent' ? ' checked="checked"' : '').' /> Perform Silent Edit (don\'t show &quot;Last edited by&quot; line)</label>';
	if($post['edituid'])
		$editopt .= '<br /><label><input type="radio" name="editopt" value="reset"'.($mybb->input['editopt'] == 'reset' ? ' checked="checked"' : '').' /> Remove &quot;Last edited by&quot; line for this post.</label>';
	
	if($editopt)
		$editopt = '<tr><td class="trow2" width="50%"><strong>Edit Options</strong></td><td class="trow2"><label><input type="radio" name="editopt" value="normal"'.($mybb->input['editopt'] != 'silent' && $mybb->input['editopt'] != 'reset' ? ' checked="checked"' : '').' /> Normal edit.</label>'.$editopt.'</td></tr>';
	eval('$adminopt = "'.$templates->get('editpost_adminopt').'";');
}

function adminpedit_do_editpost_start()
{
	global $mybb;
	if($mybb->usergroup['cancp'] != 1 || $mybb->input['adminpedit_active'] != '1') return;
	if($mybb->input['editopt'] == 'silent')
		$mybb->settings['showeditedbyadmin'] = 'no';
	$GLOBALS['plugins']->add_hook('editpost_do_editpost_end', 'adminpedit_do_editpost');
}

function adminpedit_do_editpost()
{
	global $post, $thread, $db, $mybb;
	$update_array = array();
	if($mybb->input['posttime'])
		$update_array['dateline'] = intval($mybb->input['posttime']);
	if($mybb->input['postusername'])
		$update_array['username'] = $db->escape_string($mybb->input['postusername']);
	
	if($mybb->input['postreguser'] == 'yes')
	{
		// saerch for the UID
		$uid = $db->fetch_field($db->simple_select('users', 'uid', 'LOWER(username)="'.strtolower($update_array['username']).'"'), 'uid');
		if(!$uid) error('The specified username could not be found!');
		$update_array['uid'] = $uid;
	}
	else
	{
		$update_array['uid'] = 0;
	}
	
	$thread_updates = $update_array;
	
	if($mybb->input['postip'])
	{
		if(!preg_match('#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$#', $mybb->input['postip']))
			error('Invalid IP specified');
		$postip = array_map('intval', explode('.', $mybb->input['postip']));
		if($postip[0] > 255 || $postip[1] > 255 || $postip[2] > 255 || $postip[3] > 255)
			error('Invalid IP specified');
		
		$update_array['ipaddress'] = implode('.', $postip);
	}
	
	if($mybb->input['editopt'] == 'reset')
	{
		$update_array['edituid'] = 0;
		$update_array['edittime'] = 0;
	}
	
	// if it's the first post, we should update the thread...
	$firstcheck = $db->fetch_array($db->simple_select("posts", "pid", "tid='{$thread['tid']}'", array("limit" => 1, "order_by" => "dateline", "order_dir" => "asc")));
	if($firstcheck['pid'] == $post['pid'])
	{
		$db->update_query('threads', $thread_updates, 'tid='.$thread['tid']);
	}

	$db->update_query('posts', $update_array, 'pid='.$post['pid']);
}
?>