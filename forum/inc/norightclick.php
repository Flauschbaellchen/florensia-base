<?php
/*
 No Right Click
 Copyright © Blake Miller (Snake) 2007 http://www.blakemiller.org
*/
// Start Hooks
$plugins->add_hook("pre_output_page", "norightclick_template");
// End Hooks

// Start Plugin Info
function norightclick_info()
{
	global $db,$mybb;
	return array(
		"name"			=> "No Right Click",
		"description"	=> "Disables right click on your web site. (IE adnFirefox supported)",
		"website"		=> "http://www.mybboard.com",
		"author"		=> "Snake",
		"authorsite"	=> "http://www.blakemiller.org",
		"version"		=> "1.0 Beta",
	);
}
// End Plugin Info

// Start Activation
function norightclick_activate() {
	global $db;
	
	// Make Settings Group
	$norightclick_settings = array(
		"gid"			=> NULL,
		"name"			=> "norightclick",
		"title"			=> "No Right Click",
		"description"	=> "Settings for No Right Click",
		"disporder"		=> "99",
		"isdefault"		=> "0",
	);
	// Insert Settings Group
	$db->insert_query("settinggroups", $norightclick_settings);
	$gid = $db->insert_id();
	
	// Make Settings
	$norightclick_1 = array(
		"sid"			=> NULL,
		"name"			=> "norightclick_message",
		"title"			=> "Message for Right Click Popup",
		"description"	=> "What should the message box say when some one right clicks?",
		"optionscode"	=> "text",
		"value"			=> "Right Click Disabled!",
		"disporder"		=> "1",
		"gid" => intval($gid),
	);
	
	// 
	$db->insert_query("settings", $norightclick_1);
	
	// Make Settings
	$norightclick_2 = array(
		"sid"			=> NULL,
		"name"			=> "norightclick_guests",
		"title"			=> "Guests Only",
		"description"	=> "Do you want this for guests only?",
		"optionscode"	=> "yesno",
		"value"			=> "1",
		"disporder"		=> "2",
		"gid" => intval($gid),
	);
	
	// 
	$db->insert_query("settings", $norightclick_2);
}
// End Activation

// Start Deavtivation
function norightclick_deactivate() {
	global $db;
	
	// Delete Settings Group
	$db->delete_query("settinggroups", "name='norightclick'");
	
	// Delete Settings
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='".$g['gid']."'");
}
// End Deavtivation

// Start Strip
function norightclick_template($page) {
global $db, $mybb;
	
	if ($mybb->settings['norightclick_guests'] != "0"){
		if ($mybb->usergroup['gid'] == "1") {
			$page = str_replace("</head>", "<script type=\"text/javascript\" src=\"jscripts/norightclick.js\"></script></head>", $page);
			return $page;
		}
	else{
		//do nothing
	}
	}
	
	else{
		$page = str_replace("</head>", "<script type=\"text/javascript\" src=\"jscripts/norightclick.js\"></script></head>", $page);
			return $page;

	}
	

}
// End Strip
?>