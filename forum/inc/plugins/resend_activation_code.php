<?php
/*
 *	Resend Activation Code 
 *	(c) 2007 by www.cgshelf.com
 *	Website: http://www.cgshelf.com
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("member_profile_end", "resend_activation_code");

// Plugin Info
function resend_activation_code_info() {
	return array(
		"name"			=> "Resend Activation Code",
		"description"	=> "Allows the administrators/super moderators to send the activation code mail from User CP.",
		"website"		=> "http://www.yaatrika.com/",
		"author"		=> "Albin Joseph",
		"authorsite"	=> "http://www.albeesonline.com",
		"version"		=> "3.0",
		"guid"			=> "08bd9d13eab0fc9cea2cc7ed8e549d4c",
		"compatibility" => "16*",
	);
}

function resend_activation_code_is_installed()
{
	global $db;
	$query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Resend_Activation_Code'");
	$gid = $db->fetch_field($query, "gid");

	if($gid)
   	{
		return true;
 	}
	return false;
 }

// Install plugin
function resend_activation_code_install() {
    global $db;
	
	// Get the display order.
	$query = $db->simple_select("settinggroups", "max(disporder) as rows");
	$rows = $db->fetch_field($query, "rows");

	// Add the settings
	$resendacode_group = array(
		"name" => "Resend_Activation_Code",
		"title" => "Resend Activation Code from UCP",
		"description" => "Enables the Admin/Super Mods to resend the activation code from UCP",
		"disporder" => $rows +1,
		"isdefault" => "0",
		);
	$db->insert_query("settinggroups", $resendacode_group);
	$gid = $db->insert_id();
	
	$resendacode_1 = array(
		"name" => "resendacode_enable",
		"title" => "On/Off",
		"description" => "Enable/Disable resend activation code from UCP by admin?",
		"optionscode" => "yesno",
		"value" => "1",
		"disporder" => "1",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $resendacode_1);
	
	$resendacode_2 = array(
		"name" => "resendcode_mod",
		"title" => "Allow super moderator to resend the activation code?",
		"description" => "Allows super moderators to resend the activation code<br /><small>By default only administrators are allowed to resend the activation code.</small>",
		"optionscode" => "yesno",
		"value" => "0",
		"disporder" => "2",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $resendacode_2);
}

// Activate plugin
function resend_activation_code_activate() {
	// Add a place holder for Resed Activation Code link.
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("member_profile", '#{\$usertitle}#', "{\$usertitle}{\$resendcode}");
	find_replace_templatesets("member_profile", '#{\$groupimage}#', "{\$resendform}{\$groupimage}");

	rebuild_settings();
}


// Install plugin
function resend_activation_code_deactivate() {
	// Revert the templates to the original state.
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("member_profile", '#{\$resendcode}#', '', 0);
	find_replace_templatesets("member_profile", '#{\$resendform}#', '', 0);

	rebuild_settings();
}

// Deactivate thge plugin
function resend_activation_code_uninstall()
{
	global $db;
	
    // Delte all out settings.
	$query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Resend_Activation_Code'");
	$g = $db->fetch_array($query);
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE gid='".$g['gid']."'");

	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='".$g['gid']."'");

}

// Resend Activation Code plugin.
function resend_activation_code() {
    global $mybb;
	
	//error_log("");

	// Check this plugin is On or Off
	if ($mybb->settings['resendacode_enable'] != "0") {
		global $memprofile, $resendcode, $resendform;
		
		// We need to show the link only if the user is 'Awaiting Activation'
		if ($memprofile['usergroup'] == 5) {

			if ($mybb->settings['resendcode_mod'] == "1") {

				if ($mybb->user['usergroup'] == 4 || $mybb->user['usergroup'] ==3 ) {
					$resendcode = " - <a href='javascript:document.frmreset.submit()'>Resend Activation Email</a>";
					$resendform = "<form name=frmreset action='member.php' method='post'><input type='hidden' name='email' value='" . $memprofile['email']. "' /><input type='hidden' name='action' value='do_resendactivation' /> </form>";
				}
			} // End if ($mybb->settings['resendcode_mod'] == "yes") {
			else {
				// Only admins
				if ($mybb->user['usergroup'] == 4)
				{
					$resendcode = " - <a href='javascript:document.frmreset.submit()'>Resend Activation Email</a>";
					$resendform = "<form name=frmreset action='member.php' method='post'><input type='hidden' name='email' value='" . $memprofile['email']. "' /><input type='hidden' name='action' value='do_resendactivation' /> </form>";
				}
			}// End else ($mybb->settings['resendcode_mod'] == "yes") {
		} // End if ($memprofile['usergroup'] == 5) {
	} // End if ($mybb->settings['resendacode_enable'] != "no") {
}



?>