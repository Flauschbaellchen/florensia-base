<?php
/*
Plugin Ads after first post
(c) 2005-2008 by MyBBoard.de
Website: http://www.mybboard.de
*/
$plugins->add_hook("postbit", "adsafp");

//Informationen zum Plugin
function adsafp_info()
{
	return array(
		"name"        => "Ads after first post",
		"description" => "Displays ads after the posts in your forums.",
		"website"     => "http://www.mybboard.de",
		"author"      => "MyBBoard.de",
		"authorsite"  => "http://www.mybboard.de",
		"version"     => "2.2",
		"guid"        => "1c7274c3dd8a6ad850eac910dbd58e4c",
        "compatibility" => "14*"
		);
}

// Aktivierung
function adsafp_activate() {

    global $db;

	// Variablen für dieses Plugin einfügen
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("postbit", '#</tbody>
</table>#', "</tbody>
</table>{\$post['adsaf']}");
	find_replace_templatesets("postbit_classic", '#</table>
	</td>
</tr>
</table>#', "</table>
	</td>
</tr>
</table>{\$post['adsaf']}");
		
	// Einstellungsgruppe hinzufügen
	$adsafp_group = array(
		"gid" => "NULL",
		"name" => "Ads after first post",
		"title" => "Ads after first post",
		"description" => "Settings for the plugin.",
		"disporder" => "1",
		"isdefault" => "no",
		);
	$db->insert_query("settinggroups", $adsafp_group);
	$gid = $db->insert_id();
	
	// Einstellungen hinzufügen
	$adsafp_1 = array(
		"sid" => "NULL",
		"name" => "adsafp_code_onoff",
		"title" => "Activate/Deactivate",
		"description" => "Do you want to show ads after posts?",
		"optionscode" => "yesno",
		"value" => "0",
		"disporder" => "1",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $adsafp_1);

    $adsafp_2 = array(
		"sid" => "NULL",
		"name" => "adsafp_groups",
		"title" => "Usergroups",
		"description" => "Please enter the IDs of the usergroups that should see ads seperated with commas (0 = all groups).",
		"optionscode" => "text",
		"value" => "0",
		"disporder" => "2",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $adsafp_2);
	
	$adsafp_3 = array(
		"sid" => "NULL",
		"name" => "adsafp_align",
		"title" => "Alignment",
		"description" => "Choose the alignment.",
		"optionscode" => "radio\r\n1=Left\r\n2=Center\r\n3=Right",
		"value" => "2",
		"disporder" => "3",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $adsafp_3);
	
	$adsafp_4 = array(
		"sid" => "NULL",
		"name" => "adsafp_mode",
		"title" => "Mode",
		"description" => "Where do you want to show the ads?",
		"optionscode" => "radio\r\n1=After first post on each page (Default)\r\n2=After the first post and then after every x posts\r\n3=After every x posts",
		"value" => "1",
		"disporder" => "4",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $adsafp_4);
	
	$adsafp_5 = array(
		"sid" => "NULL",
		"name" => "adsafp_afterxposts",
		"title" => "Number of posts",
		"description" => "Enter the number of posts after that you want to display the ads (only necessary for the second mode)",
		"optionscode" => "text",
		"value" => "5",
		"disporder" => "5",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $adsafp_5);
	
	$adsafp_6 = array(
		"sid" => "NULL",
		"name" => "adsafp_code",
		"title" => "Code",
		"description" => "Enter the HTML code for the ads.",
		"optionscode" => "textarea",
		"value" => "",
		"disporder" => "6",
		"gid" => intval($gid),
		);
	$db->insert_query("settings", $adsafp_6);
	
	// settings.php erneuern
	rebuild_settings();
}

// Deaktivierung
function adsafp_deactivate() {

    global $db;

	// Variablen von dieses Plugin entfernen
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("postbit", "#{\$post['adsaf']}#", "", 0);
	find_replace_templatesets("postbit_classic", "#{\$post['adsaf']}#", "", 0);
	
	// Einstellungsgruppen löschen
	$query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Ads after first post'");
	$g = $db->fetch_array($query);
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE gid='".$g['gid']."'");

	// Einstellungen löschen
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='".$g['gid']."'");

	// Rebuilt settings.php
	rebuild_settings();
}

// Funktionen
function adsafp($post) {

    global $mybb, $postcounter;

    $post['adsaf'] = "";
    $adgroups = explode(",", $mybb->settings['adsafp_groups']);
    if($mybb->settings['adsafp_code_onoff'] != 0 && ($mybb->settings['adsafp_groups'] == 0 || in_array($mybb->user['usergroup'], $adgroups))) {

        // Alignment
        switch ($mybb->settings['adsafp_align']) {
        case 1:
            $ads_align = "left";
            break;
        case 2:
            $ads_align = "center";
            break;
        case 3:
            $ads_align = "right";
            break;
        }

        // Ads after first post
        if ($mybb->settings['adsafp_mode'] == 1) {
            if (($postcounter - 1) % $mybb->settings['postsperpage'] == 0) {
                $post['adsaf'] = "<div class=\"adsafp\" style=\"text-align:".$ads_align.";\">".stripslashes($mybb->settings['adsafp_code'])."</div>";
            }
        }

        // Ads after first post and then every x posts
        if ($mybb->settings['adsafp_mode'] == 2) {
            if ($postcounter == "1" || ($postcounter - 1) % ($mybb->settings['adsafp_afterxposts']) == 0) {
                $post['adsaf'] = "<div class=\"adsafp\" style=\"text-align:".$ads_align.";\">".stripslashes($mybb->settings['adsafp_code'])."</div>";
            }
        }
    
        // Ads after every x posts
        if ($mybb->settings['adsafp_mode'] == 3) {
            if ($postcounter % ($mybb->settings['adsafp_afterxposts']) == 0) {
                $post['adsaf'] = "<div class=\"adsafp\" style=\"text-align:".$ads_align.";\">".stripslashes($mybb->settings['adsafp_code'])."</div>";
            }
        }
    }
}

// Einstellungen erneuern
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