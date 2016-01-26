<?php

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("modcp_start", "userbanforum_modcp_nav");
$plugins->add_hook("modcp_start", "userbanforum_modcp");
$plugins->add_hook("global_end", "userbanforum_global");

function userbanforum_info()
{
	return array(
		"name"			=> "Specific forum banning",
		"description"	=> "A plugin that allows you to ban users from specific forums",
		"website"		=> "http://mods.mybboard.net/view/specific-forum-banning",
		"author"		=> "Tikitiki",
		"authorsite"	=> "http://thetikitiki.com",
		"version"		=> "1.0.1",
		"compatibility" => "14*",
		"guid"			=> "bdc1347c9caf40f0e4638911b9038b66",
	);
}

function userbanforum_install()
{
	global $db;
	
	if(!$db->table_exists("forumbans"))
	{
		$db->query("CREATE TABLE ".TABLE_PREFIX."forumbans (
			bid int(10) unsigned NOT NULL auto_increment,
			uid int(10) unsigned NOT NULL default 0,
			fid int(10) unsigned NOT NULL default 0,
			childlist text NOT NULL default '',
			active int(1) NOT NULL default 1,
			KEY active (active),
			KEY uid (uid),
			KEY fid (fid),
			PRIMARY KEY (bid)
		);");
	}
}

function userbanforum_is_installed()
{
	global $db;
	
	if($db->table_exists('forumbans'))
	{
		return true;
	}
	
	return false;
}

function userbanforum_activate()
{
}

function userbanforum_deactivate()
{
}

function userbanforum_uninstall()
{
	$db->drop_table("forumbans");
}

function userbanforum_modcp_nav()
{
	if(is_moderator() == true)
	{
		global $modcp_nav;
		
		$modcp_nav = str_replace("<tr><td class=\"trow1 smalltext\"><a href=\"modcp.php?action=warninglogs\"", "<tr><td class=\"trow1 smalltext\"><a href=\"modcp.php?action=specificbanuser\" class=\"modcp_nav_item modcp_nav_banning\">Forum Banning</a></td></tr>\n
<tr><td class=\"trow1 smalltext\"><a href=\"modcp.php?action=warninglogs\"", $modcp_nav);
	}
}

function userbanforum_global()
{
	if(strpos($_SERVER['PHP_SELF'], "newthread.php") !== false || strpos($_SERVER['PHP_SELF'], "newreply.php") !== false)
	{
		global $db, $mybb;
		
		if($mybb->input['tid'])
		{
			$query = $db->simple_select("threads", "fid", "tid='{$mybb->input['tid']}'", array('limit' => 1));
			$fid = $db->fetch_field($query, "fid");
		}
		else
		{
			$fid = $mybb->input['fid'];
		}
		
		if(!$fid)
		{
			return;
		}
		
		$query = $db->simple_select("forumbans", "COUNT(bid) as count", "active='1' AND CONCAT(',',childlist,',') LIKE 
		'%,$fid,%' AND uid='{$mybb->user['uid']}'");
		if($db->fetch_field($query, "count") > 0)
		{		
			error_no_permission();
		}
	}
}

function userbanforum_modcp()
{
	global $mybb;
	
	if($mybb->input['action'] != "specificbanuser" || is_moderator() != true)
	{
		return;
	}
	
	global $db, $templates, $footer, $headerinclude, $header, $modcp_nav, $theme, $jumpfcache, $forum_cache;
	
	
	if($mybb->input['do'] == "delete")
	{
		// Verify incoming POST request
		verify_post_check($mybb->input['my_post_key']);
			
		$bid = intval($mybb->input['bid']);
		
		if(!$bid)
		{
			echo "Error: Bad 'bid' input.";
			die;
		}
		
		$query = $db->simple_select("forumbans", "bid", "bid='{$bid}'", array('limit' => 1));
		$bid = $db->fetch_field($query, "bid");
		
		if(!$bid)
		{
			echo "Error: Bad 'bid' input.";
			die;
		}
		
		$db->update_query("forumbans", array('active' => 0), "bid='{$bid}'", 1);
		redirect("modcp.php?action=specificbanuser", "Successfully deleted the ban");
	}
	
	if($mybb->input['do'] == "edit")
	{
		$bid = intval($mybb->input['bid']);
		
		if(!$bid)
		{
			echo "Error: Bad 'bid' input.";
			die;
		}
		
		$query = $db->simple_select("forumbans", "fid", "bid='{$bid}'", array('limit' => 1));
		$fid = $db->fetch_field($query, "fid");
		
		if(!$fid)
		{
			echo "Error: Bad 'bid' input.";
			die;
		}
		
		if($mybb->request_method == "post")
		{
			// Verify incoming POST request
			verify_post_check($mybb->input['my_post_key']);
			
			if(!$errors)
			{
				$db->update_query("forumbans", array('fid' => $mybb->input['fid'], 'childlist' => $mybb->input['fid'].",".implode(',', get_child_list($mybb->input['fid']))), "bid='{$bid}'", 1);
				redirect("usercp.php?action=specificbanuser", "Successfully edited banned user");
			}
		}
	
		$forum_select = build_forum_jump("", $fid, 1, '', 0, '', "fid");
		
		$edit = "
		<html>
<head>
<title>{$mybb->settings['bbname']} - {$lang->modlogs}</title>
{$headerinclude}
</head>
<body>
	{$header}
		<form action=\"modcp.php?action=specificbanuser&amp;do=edit\" method=\"post\">
		<input type=\"hidden\" name=\"my_post_key\" value=\"{$mybb->post_code}\" />
		<input type=\"hidden\" name=\"bid\" value=\"{$bid}\" />
		<table border=\"0\" cellspacing=\"{$theme['borderwidth']}\" cellpadding=\"{$theme['tablespace']}\" class=\"tborder\">
						<tr>
							<td class=\"thead\" colspan=\"2\"><strong>Edit Ban #{$bid}</strong></td>
						</tr>
						<tr>
							<td class=\"trow1\" width=\"25%\"><strong>Forum:</td>
							<td class=\"trow1\" width=\"75%\">
								{$forum_select}
							</td>
						</tr>
					</table>
					<br />
					<div align=\"center\">
						<input type=\"submit\" value=\"Update Ban\" />
					</div>
				</td>
			</tr>
		</table>
		</form>
	{$footer}
</body>
</html>";
		
		output_page($edit);
		return;
	}
	
	$errors = false;
	
	if($mybb->request_method == "post")
	{
		// Verify incoming POST request
		verify_post_check($mybb->input['my_post_key']);
	
		$query = $db->simple_select("forums", "COUNT(fid) as count", "fid='{$mybb->input['fid']}'", array('limit' => 1));
		if($db->fetch_field($query, "count") != 1)
		{
			$errors = true;
		}
		
		$query = $db->simple_select("users", "uid", "username='".$db->escape_string($mybb->input['username'])."'", array('limit' => 1));
		$uid = $db->fetch_field($query, "uid");
		
		if(!$uid)
		{
			$errors = true;
		}
		
		$permissions = user_permissions($uid);
		
		// Permission to edit this ban?
		if(($mybb->usergroup['issupermod'] == "yes" && $permissions['issupermod'] == "yes") || is_moderator($mybb->input['fid'], "", $uid) == true || $mybb->user['uid'] == $uid)
		{
			error_no_permission();
		}
		
		if($errors == false)
		{
			$db->insert_query("forumbans", array('uid' => $uid, 'fid' => $mybb->input['fid'], 'childlist' => $mybb->input['fid'].",".@implode(',', get_child_list($mybb->input['fid']))), 1);
			redirect("modcp.php?action=specificbanuser", "Successfully banned user from the specified forum.");
		}
	}
	
	add_breadcrumb("Specific Forum Banning", "modcp.php?action=specificbanuser");
	
	$query = $db->query("
		SELECT b.*, u.username, f.name
		FROM ".TABLE_PREFIX."forumbans b
		LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid=b.uid)
		LEFT JOIN ".TABLE_PREFIX."forums f ON (f.fid=b.fid)
		WHERE b.active='1'
	");
	while($ban = $db->fetch_array($query))
	{
		$trow = alt_trow();
		
		$results .= "<tr><td class=\"{$trow}\"><a href=\"".build_profile_link(htmlspecialchars_uni($ban['username']), $ban['uid'])."</a></td>
<td class=\"{$trow}\"><a href=\"".get_forum_link($ban['fid'])."\">".htmlspecialchars_uni($ban['name'])."</a></td>
<td class=\"{$trow}\" align=\"center\"><a href=\"modcp.php?action=specificbanuser&amp;do=edit&amp;bid={$ban['bid']}&amp;my_post_key={$mybb->post_code}\">Edit</a> | <a href=\"modcp.php?action=specificbanuser&amp;do=delete&amp;bid={$ban['bid']}&amp;my_post_key={$mybb->post_code}\">Delete</a></td></tr>";
	}
	
	if(!$results)
	{
		$results = "<tr><td colspan=\"3\" class=\"trow1\" align=\"center\">Currently no banned users</td></tr>";	
	}

	$forum_select = build_forum_jump("", $mybb->input['fid'], 1, '', 0, '', "fid");
	
	$userbanforum = "<html>
<head>
<title>{$mybb->settings['bbname']} - {$lang->modlogs}</title>
{$headerinclude}
</head>
<body>
	{$header}
	<form action=\"modcp.php?action=specificbanuser\" method=\"post\">
	<input type=\"hidden\" name=\"my_post_key\" value=\"{$mybb->post_code}\" />
		<table width=\"100%\" border=\"0\" align=\"center\">
			<tr>
				{$modcp_nav}
				<td valign=\"top\">
					<table border=\"0\" cellspacing=\"{$theme['borderwidth']}\" cellpadding=\"{$theme['tablespace']}\" class=\"tborder\">
						<tr>
							<td class=\"thead\" align=\"center\" colspan=\"5\"><strong>Users banned from specific forums</strong></td>
						</tr>
						<tr>
							<td class=\"tcat\"><span class=\"smalltext\"><strong>Username</strong></span></td>
							<td class=\"tcat\" align=\"center\"><span class=\"smalltext\"><strong>Forum</strong></span></td>
							<td class=\"tcat\" align=\"center\"><span class=\"smalltext\"><strong>Action</strong></span></td>
						</tr>
						{$results}
					</table>
					<br />
					<table border=\"0\" cellspacing=\"{$theme['borderwidth']}\" cellpadding=\"{$theme['tablespace']}\" class=\"tborder\">
						<tr>
							<td class=\"thead\" colspan=\"2\"><strong>Ban User From a Specific Forum</strong></td>
						</tr>
						<tr>
							<td class=\"trow1\" width=\"25%\"><strong>Username: </strong></td>
							<td class=\"trow1\" width=\"75%\"><input type=\"text\" name=\"username\" id=\"username\" value=\"{$username}\" size=\"20\" /></td>
						</tr>
						<tr>
							<td class=\"trow1\" width=\"25%\"><strong>Forum:</td>
							<td class=\"trow1\" width=\"75%\">
								{$forum_select}
							</td>
						</tr>
					</table>
					<br />
					<div align=\"center\">
						<input type=\"submit\" value=\"Ban User\" />
					</div>
				</td>
			</tr>
		</table>
	</form>
{$footer}
<script type=\"text/javascript\" src=\"jscripts/autocomplete.js?ver=1400\"></script>
<script type=\"text/javascript\">
<!--
	if(use_xmlhttprequest == \"1\")
	{
		new autoComplete(\"username\", \"xmlhttp.php?action=get_users\", {valueSpan: \"username\"});
	}
// -->
</script>
</body>
</html>";

	//eval("\$userbanforum = \"".$templates->get("userbanforum")."\";");
	output_page($userbanforum);
}

if(!function_exists('get_child_list'))
{
	/**
	* Generate an array of all child and descendant forums for a specific forum.
	*
	* @param int The forum ID
	* @param return Array of descendants
	*/
	function get_child_list($fid)
	{
		static $forums_by_parent;
		
		$forums = array();
		if(!is_array($forums_by_parent))
		{
			$forum_cache = cache_forums();
			foreach($forum_cache as $forum)
			{
				if($forum['active'] != "no")
				{
					$forums_by_parent[$forum['pid']][$forum['fid']] = $forum;
				}
			}
		}
		if(!is_array($forums_by_parent[$fid]))
		{
			return;
		}
		$pid = $forum['fid'];
		foreach($forums_by_parent[$fid] as $forum)
		{
			$forums[] = $forum['fid'];
			$children = get_child_list($forum['fid']);
			if(is_array($children))
			{
				$forums = array_merge($forums, $children);
			}
		}
		return $forums;
	}
}

?>