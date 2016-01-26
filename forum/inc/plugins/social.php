<?php
/*
Plugin Social Sharing Button Version 1.0
(c) 2008 by jnd52
Website: http://www.maroonlife.com

*/

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("postbit", "social");

//Plugin Information
function social_info()
{
	return array(
		"name"        => "Social Sharing Button",
		"description" => "Displays social sharing button in posts",
		"website"     => "http://www.maroonlife.com",
		"author"      => "jnd52",
		"authorsite"  => "http://www.maroonlife.com",
		"version"     => "1.0",
		'guid'        => 'c11af77a47eabdb739aad1dcbcc29d7b',
		'compatibility' => '14*'
		);
}

// Plugin Activation
function social_activate() {

    global $db, $mybb;

	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("postbit", '#<div class=\"post_management_buttons float_right\">#', "<div class=\"post_management_buttons float_right\">{\$post['social1']}");
	find_replace_templatesets("postbit_classic", '#<td align=\"right\">#', "<td align=\"right\">{\$post['social1']}");
			
	// Plugin Settings Group
	$social_group = array(
		"gid" => "NULL",
		"name" => "Social Sharing Button",
		"title" => "Social Sharing Button",
		"description" => "Settings for the plugin.",
		"disporder" => "1",
		"isdefault" => "no",
		);
	$db->insert_query("settinggroups", $social_group);
	$gid = $db->insert_id();
	
	// Settings
	$social_1 = array(
		"sid" => "NULL",
		"name" => "social_code_onoff",
		"title" => "On/Off",
		"description" => "Do you want to show Social Sharing Button at all?",
		"optionscode" => "yesno",
		"value" => "1",
		"disporder" => "1",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $social_1);
	
	$social_2 = array(
		"sid" => "NULL",
		"name" => "social_code",
		"title" => "Optional Button Change",
		"description" => "Enter the HTML code for the a different button.",
		"optionscode" => "textarea",
		"value" => "<script type=\"text/javascript\">
addthis_pub  = \'jnd52\';
addthis_logo_color = \'800000\';
addthis_brand = \'Made by jnd52\';
addthis_options = \'email, facebook, myspace, digg, favorites, more\';
</script>
<a href=\"http://www.addthis.com/bookmark.php\" onmouseover=\"return addthis_open(this, \'\', \'[URL]\', \'[TITLE]\')\" onmouseout=\"addthis_close()\" onclick=\"return addthis_sendto()\"><img src=\"http://s9.addthis.com/button1-share.gif\" width=\"125\" height=\"16\" border=\"0\" alt=\"\" /></a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/152/addthis_widget.js\"></script>",
		"disporder" => "2",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $social_2);

	// settings.php
	rebuild_settings();
}

// Plugin Deactivation
function social_deactivate() {

    global $db, $mybb;

	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("postbit", '#\{\$post\[\'social1\'\]\}#', '', 0);
	find_replace_templatesets("postbit_classic", '#\{\$post\[\'social1\'\]\}#', '', 0);

	
	// Remove Groups
	$query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Social Sharing Button'");
	$g = $db->fetch_array($query);
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE gid='".$g['gid']."'");

	// Deletion
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='".$g['gid']."'");

	// Rebuilt settings.php
	rebuild_settings();
}

// Function
function social($post) 
	{
    	global $mybb;
    
   	 $post['social1'] = "";
   	 if ($mybb->settings['social_code_onoff'] != "0") 
		{
		$post['social1'] = "".stripslashes($mybb->settings['social_code'])."";
          		}
	else{
	$post['social1'] = "";
	}
	}
	



if(!function_exists("rebuild_settings")) {
	function rebuild_settings() {
		
        global $db;
		
        $query = $db->query("SELECT * FROM ".TABLE_PREFIX."settings ORDER BY title ASC");
		while($setting = $db->fetch_array($query)) {
			$setting['value'] = addslashes($setting['value']);
			$settings .= "\$settings['".$setting['name']."'] = \"".$setting['value']."\";\n";
		}
		$settings = "<?php\n/*********************************\ \n  DO NOT EDIT THIS FILE, PLEASE USE\n  THE SETTINGS EDITOR\n\*********************************/\n\n$settings\n?>";
		$file = fopen(MYBB_ROOT."/inc/settings.php", "w");
		fwrite($file, $settings);
		fclose($file);
		
	}
}
?>