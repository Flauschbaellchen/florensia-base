<?php
/**
 * MyBB 1.6
 * Copyright 2010 MyBB Group, All Rights Reserved
 *
 * Website: http://mybb.com
 * License: http://mybb.com/about/license
 *
 * $Id: functions_modcp.php 5813 2012-04-20 13:41:04Z Tomm $
 */

/**
 * Check if the current user has permission to perform a ModCP action on another user
 *
 * @param int The user ID to perform the action on.
 * @param int the moderators user ID
 * @return boolean True if the user has necessary permissions
 */
function modcp_can_manage_user($uid)
{
	global $mybb;

	$user_permissions = user_permissions($uid);

	// Current user is only a local moderator or use with ModCP permissions, cannot manage super mods or admins
	if($mybb->usergroup['issupermod'] == 0 && ($user_permissions['issupermod'] == 1 || $user_permissions['cancp'] == 1))
	{
		return false;
	}
	// Current user is a super mod or is an administrator
	else if($user_permissions['cancp'] == 1 && ($mybb->usergroup['cancp'] != 1 || (is_super_admin($uid) && !is_super_admin($mybb->user['uid'])))) 
	{
		return false;
	}
	return true;
}

function fetch_forum_announcements($pid=0, $depth=1)
{
	global $mybb, $db, $lang, $theme, $announcements, $templates, $announcements_forum, $moderated_forums, $unviewableforums;
	static $forums_by_parent, $forum_cache, $parent_forums;

	if(!is_array($forum_cache))
	{
		$forum_cache = cache_forums();
	}
	if(!is_array($parent_forums) && $mybb->user['issupermod'] != 1)
	{
		// Get a list of parentforums to show for normal moderators
		$parent_forums = array();
		foreach($moderated_forums as $mfid)
		{
			$parent_forums = array_merge($parent_forums, explode(',', $forum_cache[$mfid]['parentlist']));
		}
	}
	if(!is_array($forums_by_parent))
	{
		foreach($forum_cache as $forum)
		{
			$forums_by_parent[$forum['pid']][$forum['disporder']][$forum['fid']] = $forum;
		}
	}

	if(!is_array($forums_by_parent[$pid]))
	{
		return;
	}

	foreach($forums_by_parent[$pid] as $children)
	{
		foreach($children as $forum)
		{
			if($forum['linkto'] || ($unviewableforums && in_array($forum['fid'], $unviewableforums)))
			{
				continue;
			}

			if($forum['active'] == 0 || !is_moderator($forum['fid']))
			{
				// Check if this forum is a parent of a moderated forum
				if(in_array($forum['fid'], $parent_forums))
				{
					// A child is moderated, so print out this forum's title.  RECURSE!
					$trow = alt_trow();
					eval("\$announcements_forum .= \"".$templates->get("modcp_announcements_forum_nomod")."\";");
				}
				else
				{
					// No subforum is moderated by this mod, so safely continue
					continue;
				}
			}
			else
			{
				// This forum is moderated by the user, so print out the forum's title, and its announcements
				$trow = alt_trow();
				
				$padding = 40*($depth-1);
				
				eval("\$announcements_forum .= \"".$templates->get("modcp_announcements_forum")."\";");
					
				if($announcements[$forum['fid']])
				{
					foreach($announcements[$forum['fid']] as $aid => $announcement)
					{
						$trow = alt_trow();
						
						if($announcement['enddate'] < TIME_NOW && $announcement['enddate'] != 0)
						{
							$icon = "<img src=\"{$theme['imgdir']}/minioff.gif\" alt=\"({$lang->expired})\" title=\"{$lang->expired_announcement}\"  style=\"vertical-align: middle;\" /> ";
						}
						else
						{
							$icon = "<img src=\"{$theme['imgdir']}/minion.gif\" alt=\"({$lang->active})\" title=\"{$lang->active_announcement}\"  style=\"vertical-align: middle;\" /> ";
						}
						
						$subject = htmlspecialchars_uni($announcement['subject']);
								
						eval("\$announcements_forum .= \"".$templates->get("modcp_announcements_announcement")."\";");
					}
				}
			}

			// Build the list for any sub forums of this forum
			if($forums_by_parent[$forum['fid']])
			{
				fetch_forum_announcements($forum['fid'], $depth+1);
			}
		}
	}
}

?>