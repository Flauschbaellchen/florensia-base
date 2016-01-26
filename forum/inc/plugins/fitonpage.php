<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program.  
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * $Id: fitonpage.php 4 2010-09-09 08:38:49Z - G33K - $
 */
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("showthread_start", "fitonpage_run");
$plugins->add_hook("private_read", "fitonpage_run");
$plugins->add_hook("newreply_start", "fitonpage_run");
$plugins->add_hook("editpost_start", "fitonpage_run");
$plugins->add_hook("portal_start", "fitonpage_run");
$plugins->add_hook("moderation_split", "fitonpage_run");
$plugins->add_hook("moderation_mergeposts", "fitonpage_run");
$plugins->add_hook("moderation_deleteposts", "fitonpage_run");
$plugins->add_hook("admin_config_settings_change","fitonpage_settings_page");
$plugins->add_hook("admin_page_output_footer","fitonpage_settings_peeker");

function fitonpage_info()
{	
	global $plugins_cache, $db, $mybb;
	
	$codename = basename(__FILE__, ".php");
	$prefix = 'g33k_'.$codename.'_';
		
    $info = array(
        "name"				=> "Fit on Page",
        "description"		=> "Resizes embeded images in posts to fit the page and not run over.",
        "website"			=> "http://geekplugins.com/",
        "author"			=> "- G33K -",
        "authorsite"		=> "http://community.mybboard.net/user-19236.html",
        "version"			=> "2.3",
		"intver"			=> "230",
		"guid" 				=> "9eee98c4b7b0c9246386b7c207814c36",
		"compatibility" 	=> "14*,16*"
    );
    
    if(is_array($plugins_cache) && is_array($plugins_cache['active']) && $plugins_cache['active'][$codename])
    {
	    $result = $db->simple_select('settinggroups', 'gid', "name = '{$prefix}settings'", array('limit' => 1));
		$group = $db->fetch_array($result);
	
		if(!empty($group['gid']))
		{
			$info['description'] = "<i><small>[<a href=\"index.php?module=config/settings&action=change&gid=".$group['gid']."\">Configure Settings</a>]</small></i><br />".$info['description'];
		}
	}
    
    return $info;
}

function fitonpage_activate()
{
	global $db, $mybb, $cache;
	
	$codename = basename(__FILE__, ".php");
	$prefix = 'g33k_'.$codename.'_';
	
	$info = fitonpage_info();

	$query = $db->query("SELECT disporder FROM ".TABLE_PREFIX."settinggroups ORDER BY `disporder` DESC LIMIT 1");
	$disporder = $db->fetch_field($query, 'disporder')+1;

	$setting_group = array(
		'name' 			=>	$prefix.'settings',
		'title' 		=>	'Fit on Page',
		'description' 	=>	'Settings to configure the fit on page plugin',
		'disporder' 	=>	intval($disporder),
		'isdefault' 	=>	'no'
	);
	$db->insert_query('settinggroups', $setting_group);
	$gid = $db->insert_id();
	
	$settings = array(
		'enabled' 		=> array(
				'title' 			=> 'Enable/Disable', 
				'description' 		=> 'Enable/Disable resizing of images to fit on the page.',
				'optionscode'		=> 'onoff',
				'value'				=> '1'),
		'resize' 					=> array(
				'title'				=> 'Resize Width',
				'description'		=> 'Enter the maximum width above which the image will be scaled down to fit on the page.<br />For fluid layouts you can enter \'auto\' to automatically determine the screen size and scale down, DO NOT USE \'auto\' FOR FIXED LAYOUTS.<br />If you have a problem using \'auto\' then use an explicit width declaration for example 600.<br /><br />Note: Enter only a number or auto. For example if specifying a width enter the width in numbers only eg 600 and NOT 600px<br />Minimum value: 250',
				'optionscode'		=> 'text',
				'value'				=> 'auto'),
		'fluid' 					=> array(
				'title'				=> 'Fluid Content Percentage',
				'description'		=> 'For fluid content if you are using auto above, you can adjust the percentage of the screen width to use in tune with your fluid content.<br />A value of 90 works well with the default MyBB theme.<br />This setting is only valid if you set the resize width above as auto.',
				'optionscode'		=> 'text',
				'value'				=> '90'),
		'topbar_text_class' 		=> array(
				'title'				=> 'Info-bar text class',
				'description'		=> 'Set the class of the text shown in the info bar above the resized image.',
				'optionscode'		=> 'text',
				'value'				=> 'smalltext'),
		'topbar_bground'			=> array(
				'title'				=> 'Info-bar color',
				'description'		=> 'Set the color of the info bar shown above the resized image.',
				'optionscode'		=> 'text',
				'value'				=> 'FFFF99'),
		'topbar_icon'			=> array(
				'title'				=> 'Info-bar icon',
				'description'		=> 'Set the icon shown in the info bar above the resized image. Try to keep the icon size around 16x16.',
				'optionscode'		=> 'text',
				'value'				=> 'images/icons/information.gif')
	);
	
	$x = 1;
	foreach($settings as $name => $setting)
	{
		$insert_settings = array(
			'name' => $db->escape_string($prefix.$name),
			'title' => $db->escape_string($setting['title']),
			'description' => $db->escape_string($setting['description']),
			'optionscode' => $db->escape_string($setting['optionscode']),
			'value' => $db->escape_string($setting['value']),
			'disporder' => $x,
			'gid' => $gid,
			'isdefault' => 0
			);
		$db->insert_query('settings', $insert_settings);
		$x++;
	}

	rebuild_settings();
	
	// Insert Template elements
			
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	// remove
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("editpost", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "editpost";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("portal", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "portal";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_split", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_split";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_mergeposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_mergeposts";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_deleteposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_deleteposts";
-->
</script>
')."#i", '', 0);

	// Remove templates left over from any of the older versions of the plugin
	//v1.0
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=100"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar = "{$lang->fitonpage_topbar}";
-->
</script>
')."#i", '', 0);
	//2.0
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=200"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=200"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=200"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	//2.1
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	//2.2
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("portal", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "portal";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_split", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_split";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_mergeposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_mergeposts";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_deleteposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_deleteposts";
-->
</script>
')."#i", '', 0);
	
	// Now add
	find_replace_templatesets("showthread", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
</head>');
	find_replace_templatesets("private_read", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
</head>');
	find_replace_templatesets("newreply", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
</head>');
	find_replace_templatesets("editpost", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "editpost";
-->
</script>
</head>');
	find_replace_templatesets("portal", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "portal";
-->
</script>
</head>');
	find_replace_templatesets("moderation_split", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_split";
-->
</script>
</head>');
	find_replace_templatesets("moderation_mergeposts", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_mergeposts";
-->
</script>
</head>');
	find_replace_templatesets("moderation_deleteposts", "#".preg_quote('</head>')."#i", '<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_deleteposts";
-->
</script>
</head>');

	// Add MyCode to post images bypassing the resizer
	$mycoderesult = $db->simple_select('mycode', 'cid', "title = 'Image No Resize (Fit on Page Plugin)'", array('limit' => 1));
	$mycodegroup = $db->fetch_array($mycoderesult);
	
	if(empty($mycodegroup['cid']))
	{
		$new_mycode = array(
				'title'	=> $db->escape_string('Image No Resize (Fit on Page Plugin)'),
				'description' => $db->escape_string('Bypass the Fit on Page resizer, Image will not be resized to fit on page'),
				'regex' => $db->escape_string('\[imgnoresize\](.*?)\[/imgnoresize\]'),
				'replacement' => $db->escape_string('<img src="$1" class="no_fop" />'),
				'active' => 1,
				'parseorder' => 0
			);
		$cid = $db->insert_query("mycode", $new_mycode);
		$cache->update_mycode();
	}
}

function fitonpage_deactivate()
{
	global $db, $mybb, $cache;
	
	$codename = basename(__FILE__, ".php");
	$prefix = 'g33k_'.$codename.'_';
	
	$info = fitonpage_info();
	
	// Remove template elements	
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("editpost", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "editpost";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("portal", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "portal";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_split", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_split";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_mergeposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_mergeposts";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_deleteposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver='.$info['intver'].'"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_deleteposts";
-->
</script>
')."#i", '', 0);

	// Remove templates left over from any of the older versions of the plugin
	//v1.0
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=100"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar = "{$lang->fitonpage_topbar}";
-->
</script>
')."#i", '', 0);
	//2.0
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=200"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=200"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=200"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	//2.1
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	//2.2
	find_replace_templatesets("showthread", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "showthread";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("private_read", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "private_read";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("newreply", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "newreply";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("portal", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "portal";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_split", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_split";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_mergeposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_mergeposts";
-->
</script>
')."#i", '', 0);
	find_replace_templatesets("moderation_deleteposts", "#".preg_quote('<script type="text/javascript" src="jscripts/fitonpage.js?ver=210"></script>
<script type="text/javascript">
<!--
	var fitonpage_on = "{$mybb->settings[\'g33k_fitonpage_enabled\']}";
	var fitonpage_resize = "{$mybb->settings[\'g33k_fitonpage_resize\']}";
	var fitonpage_fluid = "{$mybb->settings[\'g33k_fitonpage_fluid\']}";
	var fitonpage_topbar_resized = "{$lang->fitonpage_topbar_resized}";
	var fitonpage_topbar_full = "{$lang->fitonpage_topbar_full}";
	var fitonpage_topbar_text_class = "{$mybb->settings[\'g33k_fitonpage_topbar_text_class\']}";
	var fitonpage_topbar_bground = "{$mybb->settings[\'g33k_fitonpage_topbar_bground\']}";
	var fitonpage_topbar_icon = "{$mybb->settings[\'g33k_fitonpage_topbar_icon\']}";
	var fitonpage_location = "mod_deleteposts";
-->
</script>
')."#i", '', 0);

	// Remove settings
	$result = $db->simple_select('settinggroups', 'gid', "name = '{$prefix}settings'", array('limit' => 1));
	$group = $db->fetch_array($result);
	
	if(!empty($group['gid']))
	{
		$db->delete_query('settinggroups', "gid='{$group['gid']}'");
		$db->delete_query('settings', "gid='{$group['gid']}'");
		rebuild_settings();
	}
	
	// Remove MyCode
	$mycoderesult = $db->simple_select('mycode', 'cid', "title = 'Image No Resize (Fit on Page Plugin)'", array('limit' => 1));
	$mycodegroup = $db->fetch_array($mycoderesult);
	
	if(!empty($mycodegroup['cid']))
	{
		$db->delete_query('mycode', "cid='{$mycodegroup['cid']}'");
		$cache->update_mycode();
	}
}

function fitonpage_run()
{
	global $db, $mybb, $lang;
	
	$codename = basename(__FILE__, ".php");
	$prefix = 'g33k_'.$codename.'_';
	
	$lang->load("fitonpage");
}

function fitonpage_settings_page()
{
	global $db, $mybb, $g33k_settings_peeker;
	
	$codename = basename(__FILE__, ".php");
	$prefix = 'g33k_'.$codename.'_';
	
	$query = $db->simple_select("settinggroups", "gid", "name='{$prefix}settings'", array('limit' => 1));
	$group = $db->fetch_array($query);
	$g33k_settings_peeker = ($mybb->input["gid"] == $group["gid"]) && ($mybb->request_method != "post");
}

function fitonpage_settings_peeker()
{
	global $g33k_settings_peeker;
	
	$codename = basename(__FILE__, ".php");
	$prefix = 'g33k_'.$codename.'_';
	
	if($g33k_settings_peeker)
		echo '<script type="text/javascript">
	Event.observe(window,"load",function(){
		load'.$prefix.'Peekers();
	});
	function load'.$prefix.'Peekers(){
		new Peeker($$(".setting_'.$prefix.'enabled"), $("row_setting_'.$prefix.'resize"), /1/, true);
		new Peeker($$(".setting_'.$prefix.'enabled"), $("row_setting_'.$prefix.'fluid"), /1/, true);		
		new Peeker($$(".setting_'.$prefix.'enabled"), $("row_setting_'.$prefix.'topbar_text_class"), /1/, true);		
		new Peeker($$(".setting_'.$prefix.'enabled"), $("row_setting_'.$prefix.'topbar_bground"), /1/, true);		
		new Peeker($$(".setting_'.$prefix.'enabled"), $("row_setting_'.$prefix.'topbar_icon"), /1/, true);		
	}
</script>';
}
?>