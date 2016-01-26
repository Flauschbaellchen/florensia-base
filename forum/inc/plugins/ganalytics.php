<?php
/*
GOOGLE ANALYTiCS KODU EKLEME
*/

$plugins->add_hook("global_start", "ganalytics_global_start");

function ganalytics_info() {
	return array(
		"name"        => "GOOGLE ANALYTiCS KODU EKLEME",
		"description" => "Google Analytics sayac kodu icin eklenti",
		"website"     => "http://www.vezir.gen.tr",
		"author"      => "vezir",
		"authorsite"  => "http://www.vezir.gen.tr",
		"version"     => "1.4.20",
		'guid'        => '2c1d590ee9f0f586493471785b91e7ce',
		'compatibility' => '14*'
		);
	}

function ganalytics_install()
{
    global $settings, $mybb, $db;
	
if($db->field_exists("ganalyticsdb", "users"))
	{
	$db->write_query("ALTER TABLE ".TABLE_PREFIX."users DROP ganalyticsdb"); 
	}
	
    $settings_group = array(
        'gid'          => 'NULL',
        'name'         => 'ganalytics',
        'title'        => 'GOOGLE ANALYTiCS KODU EKLEME',
        'description'  => 'Google Analytics sayac kodu icin eklenti',
        'disporder'    => '2',
        'isdefault'    => 'no'
    );
    $db->insert_query('settinggroups', $settings_group);
    $gid = $db->insert_id();
	
    $setting = array(
        'sid'          => 'NULL',
        'name'         => 'kodd',
        'title'        => 'GOOGLE ANALYTiCS KODU EKLEME',
        'description'  => 'Google Analyticsin size verdigi kodunuzu yazin',
        'optionscode'  => 'textarea',
        'value'        => 'Bu yaziyi silin ve yerine Google Analytics kodunuzu yazin',
        'disporder'    => '1',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

	$db->write_query("ALTER TABLE ".TABLE_PREFIX."users ADD ganalyticsdb int NOT NULL default 0");

	rebuild_settings();
	
	$insertarray = array(
		"title" => "ganalyticskod",
		"template" => "<!-- G.Analytics Plugin ~ basla ~ www.vezir.gen.tr -->\n{\$mybb->settings[\'kodd\']}\n<!-- G.Analytics Plugin ~ son ~ www.vezir.gen.tr -->",
		"sid" => -1,
		"dateline" => TIME_NOW
	);
	
	$db->insert_query("templates", $insertarray);
}

function ganalytics_is_installed()
{
	global $db;
	
	if($db->field_exists("ganalyticsdb", "users"))
	{
		return true;
	}
	
	return false;
}

function ganalytics_activate()
{
	global $db;
	
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	
	find_replace_templatesets("header", "#<div id=\"container\">#", "{\$ganalyticskod}\n<div id=\"container\">");
}

function ganalytics_deactivate()
{
	global $db;
	
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	
	find_replace_templatesets("header", "#".preg_quote("{\$ganalyticskod}\n")."#i", "", 0);
}

function ganalytics_uninstall()
{
	global $db;
	
	if($db->field_exists("ganalyticsdb", "users"))
	{
		$db->write_query("ALTER TABLE ".TABLE_PREFIX."users DROP ganalyticsdb"); 
	}
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='ganalytics'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='kodd'");
	
	rebuild_settings();
	
	$db->delete_query("templates", "title = 'ganalyticskod'");
}

function ganalytics_global_start()
{
	global $db, $mybb, $templates, $ganalyticskod;
	
	eval("\$ganalyticskod = \"".$templates->get("ganalyticskod")."\";");
}
?>