<?php
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}


$plugins->add_hook("admin_tools_menu_logs", "pmadmin_admin_nav");
$plugins->add_hook("admin_tools_permissions", "pmadmin_admin_permissions");
$plugins->add_hook("admin_tools_action_handler", "pmadmin_action_handler");
$plugins->add_hook("admin_load", "pmadmin_admin");

function pmadmin_info()
{
	global $lang;
	
	$lang->load("tools_pms", false, true);
	
	/**
	 * Array of information about the plugin.
	 * name: The name of the plugin
	 * description: Description of what the plugin does
	 * website: The website the plugin is maintained at (Optional)
	 * author: The name of the author of the plugin
	 * authorsite: The URL to the website of the author (Optional)
	 * version: The version number of the plugin
	 * guid: Unique ID issued by the MyBB Mods site for version checking
	 * compatibility: A CSV list of MyBB versions supported. Ex, "121,123", "12*". Wildcards supported.
	 */
	return array(
		"name"			  => $lang->pma,
		"description"	  => $lang->pma_description,
		"website"		  => "http://mods.mybboard.net/view/private-message-admin",
		"author"		  => "Aaron",
		"authorsite"	  => "http://www.mybbplus.com",
		"version"		  => "2.4.3",
    	"guid"			  => "0eadbea40ccda7c24215c5c5fb5ac0e8",
		"compatibility"   => "14*",
	);
}

function pmadmin_activate()
{
	change_admin_permission('tools', 'pms');
}

function pmadmin_deactivate()
{
	change_admin_permission('tools', 'pms', -1);
}

/**
 * ADDITIONAL PLUGIN INSTALL/UNINSTALL ROUTINES
 *
 * _install():
 *   Called whenever a plugin is installed by clicking the "Install" button in the plugin manager.
 *   If no install routine exists, the install button is not shown and it assumed any work will be
 *   performed in the _activate() routine.
 *
 * function hello_install()
 * {
 * }
 *
 * _is_installed():
 *   Called on the plugin management page to establish if a plugin is already installed or not.
 *   This should return TRUE if the plugin is installed (by checking tables, fields etc) or FALSE
 *   if the plugin is not installed.
 *
 * function hello_is_installed()
 * {
 *		global $db;
 *		if($db->table_exists("hello_world"))
 *  	{
 *  		return true;
 *		}
 *		return false;
 * }
 *
 * _uninstall():
 *    Called whenever a plugin is to be uninstalled. This should remove ALL traces of the plugin
 *    from the installation (tables etc). If it does not exist, uninstall button is not shown.
 *
 * function hello_uninstall()
 * {
 * }
 *
 * _activate():
 *    Called whenever a plugin is activated via the Admin CP. This should essentially make a plugin
 *    "visible" by adding templates/template changes, language changes etc.
 *
 * function hello_activate()
 * {
 * }
 *
 * _deactivate():
 *    Called whenever a plugin is deactivated. This should essentially "hide" the plugin from view
 *    by removing templates/template changes etc. It should not, however, remove any information
 *    such as tables, fields etc - that should be handled by an _uninstall routine. When a plugin is
 *    uninstalled, this routine will also be called before _uninstall() if the plugin is active.
 *
 * function hello_deactivate()
 * {
 * }
 */

function pmadmin_action_handler(&$action)
{
	$action['pms'] = array('active' => 'pms', 'file' => '');
}

function pmadmin_admin_nav(&$sub_menu)
{
	global $mybb, $lang;
	
	$lang->load("tools_pms", false, true);
		
	end($sub_menu);
	$key = (key($sub_menu))+10;
	
	if(!$key)
	{
		$key = '60';
	}
	
	$sub_menu[$key] = array('id' => 'pms', 'title' => $lang->admin_nav, 'link' => "index.php?module=tools/pms");
}

function pmadmin_admin_permissions(&$admin_permissions)
{
  	global $db, $mybb, $lang;
		
	$lang->load("tools_pms", false, true);
		
	$admin_permissions['pms'] = $lang->can_manage_pms;
}

function pmadmin_admin()
{
	global $mybb, $db, $page, $lang;
	
	if($page->active_action != "pms")
	{
		return;
	}
	
	$page->add_breadcrumb_item($lang->pma);
	
	if($mybb->input['action'] == "delete")
	{
		require_once MYBB_ROOT."inc/functions_user.php";
		$deletepm = $mybb->input['log'];
	
		if(empty($deletepm))
		{
			flash_message($lang->error_deletepm, 'error');
			admin_redirect("index.php?module=tools/pms");
		}
		
		$pms_in = '';
		foreach($deletepm as $key => $val)
		{
			$pms_in .= $comma.$key;
			$comma = ',';
		}
	
		$query = $db->simple_select("privatemessages", "pmid, uid", "pmid IN ({$pms_in})");
		while($pm = $db->fetch_array($query))
		{
			$pms[$pm['pmid']] = $pm['uid'];
		}
		
		$db->delete_query("privatemessages", "pmid IN ({$pms_in})");
		
		if(!is_array($pms))
		{
			$pms = array();
		}
		
		$pms_recount = array();
		foreach($pms as $pmid => $uid)
		{
			$pms_recount[$uid] = $uid;
		}
		
		foreach($pms_recount as $uid)
		{
			update_pm_count($uid);
		}
		
		// Log admin action
		log_admin_action($pms);
		
		flash_message($lang->success_deleted_pms, 'success');
		admin_redirect("index.php?module=tools/pms");
	}
	
	if($mybb->input['action'] == "view")
	{		
		$query = $db->query("
			SELECT pm.*, fu.username AS from_username, tu.username as to_username, fu.usergroup as from_usergroup, fu.displaygroup as from_displaygroup, tu.usergroup as to_usergroup, tu.displaygroup as to_displaygroup
			FROM ".TABLE_PREFIX."privatemessages pm
			LEFT JOIN ".TABLE_PREFIX."users fu ON (fu.uid=pm.fromid)
			LEFT JOIN ".TABLE_PREFIX."users tu ON (tu.uid=pm.toid)
			WHERE pmid='{$mybb->input['pmid']}' {$additional_sql_criteria}
		");
		$log = $db->fetch_array($query);
	
		if(!$log['pmid'])
		{
			exit;
		}
		
		if($log['folder'] == 2 || $log['folder'] == 3)
		{
			// Sent Items or Drafts Folder Check
			$recipients = unserialize($log['recipients']);
			
			if(count($recipients['to']) > 1 || (count($recipients['to']) == 1 && count($recipients['bcc']) > 0))
			{
				$uids = array_merge($recipients['to'], $recipients['bcc']);
				$uids = array_unique($uids);
				$uids = implode(',', $uids);
				if($uids)
				{
					$query = $db->simple_select("users", "username, usergroup, displaygroup, uid", "uid IN({$uids})", array('order_by' => 'username', 'order_dir' => 'asc'));
					while($user = $db->fetch_array($query))
					{
						$users[$user['uid']] = format_name($user['username'], $user['usergroup'], $user['displaygroup']);
					}
				}
			
				$log['to_username'] = implode(', ', $users);			
			}
		}
		else
		{	
			$log['to_username'] = htmlspecialchars_uni($log['to_username']);
		}
		$log['from_username'] = htmlspecialchars_uni($log['from_username']);
		$log['subject'] = htmlspecialchars_uni($log['subject']);
		$log['dateline'] = date($mybb->settings['dateformat'], $log['dateline']).", ".date($mybb->settings['timeformat'], $log['dateline']);
		$log['message'] = nl2br(htmlspecialchars_uni($log['message']));
	
		?>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head profile="http://gmpg.org/xfn/1">
		<title><?php echo $lang->pm_log_viewer; ?></title>
		<link rel="stylesheet" href="styles/<?php echo $page->style; ?>/main.css" type="text/css" />
		<link rel="stylesheet" href="styles/<?php echo $page->style; ?>/popup.css" type="text/css" />
	</head>
	<body id="popup">
		<div id="popup_container">
		<div class="popup_title"><a href="#" onClick="window.close();" class="close_link"><?php echo $lang->close_window; ?></a><?php echo "Private Message Log Viewer"; ?></div>
	
		<div id="content">
		<?php
		$table = new Table();
	
		$table->construct_cell("To:");
		$table->construct_cell(format_name($log['to_username'], $log['to_usergroup'], $log['to_displaygroup']));
		$table->construct_row();
	
		$table->construct_cell("From:");
		$table->construct_cell(format_name($log['from_username'], $log['from_usergroup'], $log['from_displaygroup']));
		$table->construct_row();
	
		$table->construct_cell("Subject:");
		$table->construct_cell($log['subject']);
		$table->construct_row();
	
		$table->construct_cell("Date:");
		$table->construct_cell($log['dateline']);
		$table->construct_row();
	
		$table->construct_cell($log['message'], array("colspan" => 2));
		$table->construct_row();
	
		$table->output($lang->private_message);
	
		?>
		</div>
	</div>
	</body>
	</html>
		<?php
	}
	
	if(!$mybb->input['action'])
	{		
		require MYBB_ROOT."inc/class_parser.php";
		$parser = new postParser;
		
		$per_page = 20;

		if($mybb->input['page'] && $mybb->input['page'] > 1)
		{
			$mybb->input['page'] = intval($mybb->input['page']);
			$start = ($mybb->input['page']*$per_page)-$per_page;
		}
		else
		{
			$mybb->input['page'] = 1;
			$start = 0;
		}
	
		$additional_criteria = array();
		$errors = array();
	
		// Begin criteria filtering
		if($mybb->input['subject'])
		{
			$additional_sql_criteria .= " AND pm.subject LIKE '%".$db->escape_string($mybb->input['subject'])."%'";
			$additional_criteria[] = "subject=".htmlspecialchars_uni($mybb->input['subject']);
		}
		
		if(!$mybb->input['folder'])
		{
			$mybb->input['folder'] = 1;
		}
		
		if($mybb->input['folder'] == 5)
		{
			$additional_sql_criteria .= " AND pm.folder > 4";
			$additional_criteria[] = "folder > 4";
		}
		else
		{
			$additional_sql_criteria .= " AND pm.folder = '".intval($mybb->input['folder'])."'";
			$additional_criteria[] = "folder=".intval($mybb->input['folder']);
		}
	
		if($mybb->input['fromuid'])
		{
			$query = $db->simple_select("users", "uid, username", "uid='".intval($mybb->input['fromuid'])."'");
			$user = $db->fetch_array($query);
			$from_filter = $user['username'];
			$additional_sql_criteria .= " AND pm.fromid='".intval($mybb->input['fromuid'])."'";
			$additional_criteria[] = "fromid=".intval($mybb->input['fromuid']);
		}
		else if($mybb->input['from_username'])
		{
			$query = $db->simple_select("users", "uid, username", "LOWER(username)='".my_strtolower($mybb->input['from_username'])."'");
			$user = $db->fetch_array($query);
			$from_filter = $user['username'];
	
			if(!$user['uid'])
			{
				$errors[] = $lang->error_invalid_user;
				$from_filter = $mybb->input['from_username'];
			}
			else
			{
				$additional_sql_criteria .= "AND pm.fromuid='{$user['uid']}'";
				$additional_criteria[] = "fromuid={$user['uid']}";
			}
		}
	
		if($mybb->input['touid'])
		{
			$query = $db->simple_select("users", "uid, username", "uid='".intval($mybb->input['touid'])."'");
			$user = $db->fetch_array($query);
			$to_filter = $user['username'];
			$additional_sql_criteria .= " AND pm.toid='".intval($mybb->input['touid'])."'";
			$additional_criteria[] = "toid=".intval($mybb->input['touid'])."";
		}
		else if($mybb->input['to_username'])
		{
			$query = $db->simple_select("users", "uid, username", "LOWER(username)='".my_strtolower($mybb->input['to_username'])."'");
			$user = $db->fetch_array($query);
			$to_filter = $user['username'];
	
			if(!$user['uid'])
			{
				$errors[] = $lang->error_invalid_user;
				$to_filter = $mybb->input['to_username'];
			}
			else
			{
				$additional_sql_criteria .= "AND pm.toid='{$user['uid']}'";
				$additional_criteria[] = "toid={$user['uid']}";
			}
		}
	
		if($additional_critera)
		{
			$additional_critera = implode("&amp;", $additional_critera);
			$additional_critera = "&amp;".$additional_critera;
		}
		
		$page->output_header("Private Message Admin");
		
		if(!empty($errors))
		{
			$page->output_inline_error($errors);
		}
		
		$form = new Form("index.php?module=tools/pms&amp;action=delete", "post");
		
		$table = new Table;
		$table->construct_header($form->generate_check_box("checkall", 1, '', array('class' => 'checkall')), array("width" => "1%"));
		$table->construct_header($lang->subject, array("class" => "align_center", "colspan" => 2));
		$table->construct_header($lang->from, array("class" => "align_center", "width" => "20%"));
		$table->construct_header($lang->to, array("class" => "align_center", "width" => "20%"));
		$table->construct_header($lang->date_sent, array("class" => "align_center", "width" => "20%"));
	
		$query = $db->query("
			SELECT pm.*, fu.username AS from_username, tu.username as to_username, fu.usergroup as from_usergroup, fu.displaygroup as from_displaygroup, tu.usergroup as to_usergroup, tu.displaygroup as to_displaygroup
			FROM ".TABLE_PREFIX."privatemessages pm
			LEFT JOIN ".TABLE_PREFIX."users fu ON (fu.uid=pm.fromid)
			LEFT JOIN ".TABLE_PREFIX."users tu ON (tu.uid=pm.toid)
			WHERE 1=1 {$additional_sql_criteria}
			ORDER BY pm.dateline DESC
			LIMIT {$start}, {$per_page}
		");
		while($log = $db->fetch_array($query))
		{
			$table->construct_cell($form->generate_check_box("log[{$log['pmid']}]", 1, ''));
			$log['subject'] = htmlspecialchars_uni($log['subject']);
			$log['dateline'] = date($mybb->settings['dateformat'], $log['dateline']).", ".date($mybb->settings['timeformat'], $log['dateline']);

			$log['subject'] = htmlspecialchars_uni($log['subject']);
			
			$msgalt = $msgfolder = '';
			// Determine Folder Icon
			if($log['status'] == 0)
			{
				$msgfolder = 'new_pm.gif';
				$msgalt = $lang->new_pm;
			}
			elseif($log['status'] == 1)
			{
				$msgfolder = 'old_pm.gif';
				$msgalt = $lang->old_pm;
			}
			elseif($log['status'] == 3)
			{
				$msgfolder = 're_pm.gif';
				$msgalt = $lang->reply_pm;
			}
			elseif($log['status'] == 4)
			{
				$msgfolder = 'fw_pm.gif';
				$msgalt = $lang->fwd_pm;
			}
			
			$table->construct_cell("<img src=\"../images/{$msgfolder}\" alt=\"{$msgalt}\" title=\"{$msgalt}\" />", array("width" => 1));
			$table->construct_cell("<a href=\"javascript:MyBB.popupWindow('index.php?module=tools/pms&amp;action=view&amp;pmid={$log['pmid']}', 'log_entry', 450, 450);\">{$log['subject']}</a>");
			
			$table->construct_cell("<div class=\"float_right\"><a href=\"index.php?module=tools/pms&amp;fromuid={$log['fromid']}\"><img src=\"styles/{$page->style}/images/icons/find.gif\" title=\"{$lang->find_pms_by_user}\" alt=\"{$lang->find}\" /></a></div><div>".build_profile_link(format_name($log['from_username'], $log['from_usergroup'], $log['from_displaygroup']), $log['fromid'])."</div>");
			
			if($mybb->input['folder'] == 2 || $mybb->input['folder'] == 3)
			{
				// Sent Items or Drafts Folder Check
				$recipients = unserialize($log['recipients']);
				if(count($recipients['to']) > 1 || (count($recipients['to']) == 1 && count($recipients['bcc']) > 0))
				{
					$table->construct_cell($lang->multiple_recipients);
				}
				else if(count($recipients['to']) == 1)
				{
					$table->construct_cell("<div class=\"float_right\"><a href=\"index.php?module=tools/pms&amp;touid={$log['toid']}\"><img src=\"styles/{$page->style}/images/icons/find.gif\" title=\"{$lang->find_pms_by_user}\" alt=\"{$lang->find}\" /></a></div><div>".build_profile_link(format_name($log['to_username'], $log['to_usergroup'], $log['to_displaygroup']), $log['toid'])."</div>");
				}
				else
				{
					$table->construct_cell($lang->not_sent);
				}
			}
			else
			{
				$table->construct_cell("<div class=\"float_right\"><a href=\"index.php?module=tools/pms&amp;touid={$log['toid']}\"><img src=\"styles/{$page->style}/images/icons/find.gif\" title=\"{$lang->find_pms_by_user}\" alt=\"{$lang->find}\" /></a></div><div>".build_profile_link(format_name($log['to_username'], $log['to_usergroup'], $log['to_displaygroup']), $log['toid'])."</div>");
			}
			
			$table->construct_cell($log['dateline'], array("class" => "align_center"));
			
			$table->construct_row();
		}
		
		if($table->num_rows() == 0)
		{
			$table->construct_cell($lang->no_pms_found, array("colspan" => "6"));
			$table->construct_row();
			$table->output($lang->pm_log);
		}
		else
		{
			$table->output($lang->pm_log);
			$buttons[] = $form->generate_submit_button($lang->delete_selected, array('onclick' => "return confirm('{$lang->confirm_delete_pms}');"));
			$form->output_submit_wrapper($buttons);
		}
		
		$form->end();
		
		$query = $db->simple_select("privatemessages pm", "COUNT(pm.pmid) as logs", "1=1 {$additional_sql_criteria}");
		$total_rows = $db->fetch_field($query, "logs");
	
		echo "<br />".draw_admin_pagination($mybb->input['page'], $per_page, $total_rows, "index.php?module=tools/pms&amp;page={page}{$additional_criteria}");
		
		$buttons = array();
		
		$form = new Form("index.php?module=tools/pms", "post");
		$form_container = new FormContainer($lang->filter_pm_logs);
		
		$folders = array(1 => $lang->inbox, 2 => $lang->sent_items, 3 => $lang->draft, 4 => $lang->trash_can, 5 => $lang->other);
		
		$form_container->output_row($lang->folder, "", $form->generate_select_box('folder', $folders, $mybb->input['folder'], array('id' => 'folder')), 'folder');	
		
		$form_container->output_row($lang->subject_contains, "", $form->generate_text_box('subject', $mybb->input['subject'], array('id' => 'subject')), 'subject');	

		$form_container->output_row($lang->from, "", $form->generate_text_box('from_username', $from_filter, array('id' => 'from_username')), 'from_username');

		$form_container->output_row($lang->to, "", $form->generate_text_box('to_username', $to_filter, array('id' => 'to_username')), 'to_username');
		$form_container->end();
		
		// Autocompletion for usernames
		echo '
		<script type="text/javascript" src="../jscripts/autocomplete.js?ver=140"></script>
		<script type="text/javascript">
		<!--
			new autoComplete("to_username", "../xmlhttp.php?action=get_users", {valueSpan: "username"});
			new autoComplete("from_username", "../xmlhttp.php?action=get_users", {valueSpan: "username"});
		// -->
	</script>';
		
		$buttons[] = $form->generate_submit_button($lang->filter_pm_logs);
		$form->output_submit_wrapper($buttons);
		
		$form->end();
	
		$page->output_footer();
	}
	
	exit;
}

?>