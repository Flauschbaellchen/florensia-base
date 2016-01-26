<?php
/**
 @ Permission Box
 @ 
 @ MyBB 1.4
*/

$plugins->add_hook('showthread_start', 'permission_box');
$plugins->add_hook('forumdisplay_start', 'permission_box');


function permbox_info()
{
    return array(
        "name" 			=> "Permission Box For MyBB 1.4",
        "description" 	=> "Adds a permission box on the bottom of a few pages, like in vBulletin",
        "website" 		=> "http://www.fijifriendster.com/?p=95",
        "author"  		=> "An00bIis",
        "authorsite"	=> "http://www.fijifriendster.com/",
        "version"		=> "1.00",
	  "guid"		=> "ba4db7e146033793ab32043628097c62",
    );
}
function permbox_install()
{
	global $db, $mybb, $lang;
	
	$template = array(
		"tid" 		=> "NULL",
		"title" 	=> "permission_box",
		"template" 	=> "<br />
<table border=\"0\" cellspacing=\"1\" cellpadding=\"{\$theme[\'tablespace\']}\" class=\"tborder\" style=\"width: 200px; margin-left: 0; font-size: 9pt;\">
	<tr>
		<td class=\"thead\">{\$lang->permissions}</td>
	</tr>
	<tr>
		<td class=\"trow1\">{\$lang->post_threads}<br />
		{\$lang->post_replies}<br />
		{\$lang->post_attach}<br />
		</td>
	</tr>
	<tr>
		<td class=\"trow2\">{\$lang->html}<br />
		{\$lang->mycode}<br />
		{\$lang->smilies}<br />
		{\$lang->img}</td>
		
	</tr>
</table>",
		"sid" 		=> "-1",
		"version" 	=> "127",
		"status" 	=> "0",
		"dateline" 	=> time(),
	);
	
	$db->insert_query("templates", $template);
	}
function permbox_is_installed()
{
	global $db;
	
	$query = $db->query("SELECT COUNT(*) AS num FROM ".TABLE_PREFIX."templates WHERE title='permission_box'");
	$num = $db->fetch_array($query);
	
	if($num['num'] > "0")
	{
		return true;
	}
	
	return false;
}
function permbox_uninstall()
{
	global $db;
	
	$db->query("DELETE FROM `".TABLE_PREFIX."templates` WHERE title='permission_box' AND sid='-1'");

}
function permbox_activate()
{
	global $db, $mybb;
	
	require_once(MYBB_ROOT.'/inc/adminfunctions_templates.php');
	
	find_replace_templatesets('forumdisplay', '#{\$threadslist}#', "$0\n{\$permission_box}");
	find_replace_templatesets('showthread', '#{\$footer}#', "{\$permission_box}\n$0");
}
function permbox_deactivate()
{
	require_once(MYBB_ROOT.'/inc/adminfunctions_templates.php');
	
	find_replace_templatesets('forumdisplay', '#\n{\$permission_box}#', "", 0);
	find_replace_templatesets('showthread', '#{\$permission_box}\n#', "", 0);
}
function permission_box()
{
	global $mybb, $templates, $lang, $db, $permission_box;
	
	$lang->load('permbox');

	if(isset($mybb->input['tid']))
	{
		$query = $db->query("SELECT fid FROM ".TABLE_PREFIX."threads WHERE tid='".$mybb->input['tid']."'");
		$forumid = $db->fetch_array($query);
		$mybb->input['fid'] = $forumid['fid'];
	}
	$query = $db->query("SELECT allowhtml, allowmycode, allowimgcode, allowsmilies FROM ".TABLE_PREFIX."forums WHERE fid='".$mybb->input['fid']."'");
	$forum_perms = $db->fetch_array($query);
	
	$lang->html = $lang->sprintf($lang->html, ($forum_perms['allowhtml'] == '1') ? $lang->on : $lang->off);
	$lang->mycode = $lang->sprintf($lang->mycode, ($forum_perms['allowmycode'] == '1') ? $lang->on : $lang->off);
	$lang->img = $lang->sprintf($lang->img, ($forum_perms['allowimgcode'] == '1') ? $lang->on : $lang->off);
	$lang->smilies = $lang->sprintf($lang->smilies, ($forum_perms['allowsmilies'] == '1') ? $lang->on : $lang->off);
	
	$query = $db->query("SELECT canpostthreads, canpostreplys, canpostattachments, caneditposts FROM ".TABLE_PREFIX."forumpermissions WHERE fid='".$mybb->input['fid']."' AND gid='".$mybb->usergroup['gid']."' LIMIT 1");
	if($db->num_rows($query) > 0) {
		$result = $db->fetch_array($query);
		
		$lang->post_threads = $lang->sprintf($lang->post_threads, ($result['canpostthreads'] == '1') ? $lang->can : $lang->cannot);
		$lang->post_replies = $lang->sprintf($lang->post_replies, ($result['canpostreplys'] == '1') ? $lang->can : $lang->cannot);
		$lang->post_attach = $lang->sprintf($lang->post_attach, ($result['canpostattachments'] == '1') ? $lang->can : $lang->cannot);
	
	} else {
		$lang->post_threads = $lang->sprintf($lang->post_threads, ($mybb->usergroup['canpostthreads'] == '1') ? $lang->can : $lang->cannot);
		$lang->post_replies = $lang->sprintf($lang->post_replies, ($mybb->usergroup['canpostreplys'] == '1') ? $lang->can : $lang->cannot);
		$lang->post_attach = $lang->sprintf($lang->post_attach, ($mybb->usergroup['canpostattachments'] == '1') ? $lang->can : $lang->cannot);
	}
	
	eval("\$permission_box = \"".$templates->get('permission_box')."\";");
}


?>