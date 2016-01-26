<?php
/**
 * Edit Reason
 * Copyright 2008-2009 DougSD
 */
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("editpost_do_editpost_end", "editreason_run");

function editreason_info()
{
	return array(
		"name"		=> "Edit Reason",
		"description"	=> "Allows your users to put a reason why they edited their post.",
		"website"	=> "http://mods.mybboard.net/",
		"author"	=> "DougSD",
		"authorsite"	=> "http://community.mybboard.net/user-13144.html",
		"version"	=> "1.1",
		"guid" 		=> "3338c0b0c39408638a0e59aee2f92549",
		"compatibility" => "14*",
	);
}

function editreason_install()
{
	global $db;
	
	if($db->field_exists("editreason", "posts"))
	{
		return;
	}

	$db->query("ALTER TABLE ".TABLE_PREFIX."posts ADD editreason varchar(100) NOT NULL");
}	

function editreason_is_installed()
{
	global $db;
	
	if($db->field_exists("editreason", "posts"))
	{
		return true;
	}
	return false;
}

function editreason_uninstall()
{
	global $db;
	
	$db->query("ALTER TABLE ".TABLE_PREFIX."posts DROP editreason");	
}

function editreason_activate()
{
	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('postbit_editedby', '#'.preg_quote('{$post[\'editedprofilelink\']}.').'#', '{$post[\'editedprofilelink\']}. <em>Edit Reason: {$post[\'editreason\']}</em>');
	find_replace_templatesets("editpost", '#'.preg_quote('<td class="trow2"><input type="text" class="textbox" name="subject" size="40" maxlength="85" value="{$subject}" tabindex="1" /></td>
</tr>').'#', '<td class="trow2"><input type="text" class="textbox" name="subject" size="40" maxlength="85" value="{$subject}" tabindex="1" /></td>
</tr>
	<tr>
	<td class="trow2"><strong>Edit Reason</strong></td>
	<td class="trow2"><input type="text" class="textbox" name="editreason" size="40" maxlength="100" value="{$post[\'editreason\']}" tabindex="1" /></td>
	</tr>');
}

function editreason_deactivate()
{
	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('postbit_editedby', '#'.preg_quote(' <em>Edit Reason: {$post[\'editreason\']}</em>').'#', '', 0);
	find_replace_templatesets('editpost', '#'.preg_quote('<tr>
	<td class="trow2"><strong>Edit Reason</strong></td>
	<td class="trow2"><input type="text" class="textbox" name="editreason" size="40" maxlength="100" value="{$post[\'editreason\']}" tabindex="1" /></td>
	</tr>').'#', '', 0);
}

function editreason_run()
{
	global $db, $mybb, $tid, $pid;
	
	// Set the value to "N/A" if the input is blank
	if($mybb->input['editreason'] == "")
	{
		$mybb->input['editreason'] = "N/A";
	}
	
	$mybb->input['editreason'] = htmlspecialchars_uni($mybb->input['editreason']);
	
	// Inserts the reason into the database table
	$query = $db->simple_select("posts", "*", "pid='$pid'");
	$check = $db->fetch_array($query);
	if($check['pid'] == $pid)
	{
		$reason = array(
			"editreason" => $db->escape_string($mybb->input['editreason']),
		);
		$db->update_query("posts", $reason, "pid='$pid'");
	}
}