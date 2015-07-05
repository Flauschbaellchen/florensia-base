<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("mod_permission")) { $florensia->output_page($flouser->noaccess()); }



$permuserid = intval($_GET['userid']);
if ($flouser->userid != $permuserid) {
	$permuser = new class_user($permuserid);
	if (!$permuser->userid) $florensia->output_page("<div class='warning' style='text-align:center;'>No such user</div>");
	//user not valid
} else {
	//user watched own permission page
	$permuser = $flouser;
}

$permavailable = MYSQL_FETCH_ASSOC(MYSQL_QUERY("SELECT settings FROM flobase_defaults WHERE title='userpermissions_available'"));

$permavailable = explode(";", $permavailable['settings']);
foreach ($permavailable as $defaultsentry) {
	$defaultsentry = explode(":", $defaultsentry);
	if ($defaultsentry[1]) $defaults[$defaultsentry[0]] = explode(",", $defaultsentry[1]);
	else $defaults[$defaultsentry[0]]=array();
}

//saving new data
if ($_POST['do_save']) {
	$updatepermission = array();
	if ($_POST['inactive']) $updateinactive=1; else $updateinactive=0;
	if (!strlen($_POST['userrank'])) $_POST['userrank']=-1;
	
	foreach ($defaults as $permkey => $permsections) {
		if ($_POST[$permkey]=="default") continue;
		$updatepermsections = "";
		
		if (!strlen($_POST[$permkey.'_lifetime']) && strlen($permuser->permissions[$permkey]['lifetime'])) {
			$updatetime = $permuser->permissions[$permkey]['lifetime'];
		} elseif ($_POST[$permkey.'_lifetime']=="0" OR !strlen($permuser->permissions[$permkey]['lifetime'])) {
			$updatetime = 0;
		} else {
			$updatetime = date("U")+string2timestamp($_POST[$permkey.'_lifetime']);
		}
		
		if (count($permsections)) {
			$tmpupdatepermsections = array();
			if ($_POST[$permkey.'_section_*']) {
				$tmpupdatepermsections = array("*");
			} else {
				foreach($permsections as $tmpsection) {
					if ($_POST[$permkey.'_section_'.$tmpsection]) $tmpupdatepermsections[] = $tmpsection;
				}
			}
			//set string
			if (count($tmpupdatepermsections)) $updatepermsections = ":".join(",", $tmpupdatepermsections);
		}
		$updatepermission[] = "{$permkey}:".$_POST[$permkey].":{$updatetime}{$updatepermsections}";
	}
	if ($permuser->getdbentry) {
		$querystring="UPDATE flobase_user SET rank='".intval($_POST['userrank'])."', title='".mysql_real_escape_string($_POST['usertitle'])."', inactive={$updateinactive}, permissions='".mysql_real_escape_string(join(";", $updatepermission))."' WHERE userid={$permuser->userid}";
	} else {
		$querystring="INSERT INTO flobase_user (userid, rank, title, inactive, permissions) VALUES('{$permuser->userid}', '".intval($_POST['userrank'])."', '".mysql_real_escape_string($_POST['usertitle'])."', $updateinactive, '".mysql_real_escape_string(join(";", $updatepermission))."')";
	}
	if(MYSQL_QUERY($querystring)) { $flolog->add("user:permission", "{user:{$flouser->userid}} changed user permission for {user:{$permuser->userid}}"); }
	else $flolog->add("error:permission", "Mysql-Error while change user permission for {user:{$permuser->userid}}");
	//refresh all data
	$permuser = new class_user($permuser->userid);
}
//----

foreach ($defaults as $permkey => $permsections) {
	if ($flouserdata->defaultpermissions[$permkey]) {
		$defaults = $flouserdata->defaultpermissions[$permkey]['type'].":".$flouserdata->defaultpermissions[$permkey]['lifetime'].":".join(",", $flouserdata->defaultpermissions[$permkey]['sections']);
	} else $defaults = "-";



	unset($permsectionform, $checkedperm, $lifetimeleft);
	if (count($permsections)) {
		array_unshift($permsections, "*");
		foreach ($permsections as $permsectionkey) {
			if($permuser->get_permission($permkey, $permsectionkey)) $checked = "checked='checked'";
			else unset($checked);
			$permsectionform .= "<input type='checkbox' name='{$permkey}_section_{$permsectionkey}' value='1' style='margin-left:15px;' $checked>{$permsectionkey}";
		}
	}

	if (isset($permuser->userpermissions[$permkey])) {
		if ($permuser->userpermissions[$permkey]['lifetime']==0) $lifetimeleft = "(perm)";
		else $lifetimeleft = "(".date("d.m.y H:i", $permuser->userpermissions[$permkey]['lifetime']).")";
		
		if ($permuser->userpermissions[$permkey]['type'] == "revoke") { $checkedperm['revoke'] = "checked='checked'"; } else $checkedperm['grand'] = "checked='checked'";
		
	} else { $checkedperm['default'] = "checked='checked'"; }
	
	$permlist .= "
		<table class='shortinfo_".$florensia->change()."' style='width:100%;'><tr>
			<td style='width:120px;'><b>$permkey</b><br />{$defaults}</td>
			<td>
				<input type='radio' name='{$permkey}' value='default' {$checkedperm['default']}>Default
				<input type='radio' name='{$permkey}' value='revoke' {$checkedperm['revoke']} style='margin-left:15px;'>Revoke
				<input type='radio' name='{$permkey}' value='grand' {$checkedperm['grand']}>Grand
				{$permsectionform}
				<span style='margin-left:15px;'>Timespan</span> <input type='text' name='{$permkey}_lifetime' style='width:40px;'> {$lifetimeleft}
			
			</td>
		</tr></table>
	";
}


if ($permuser->inactive) { $statuschecked['inactive']="checked='checked'"; } else { $statuschecked['active']="checked='checked'"; }
$generaloption = "
<div class='bordered' style='padding:2px; margin-bottom:10px;'>
	<table>
		<tr><td style='width:150px;'>Title:</td><td><input type='text' name='usertitle' value='".$florensia->escape($permuser->title)."' style='width:300px;'></td></tr>
		<tr><td>Rank:</td><td><input type='text' name='userrank' value='".$permuser->rank."' style='width:300px;'></td></tr>
		<tr><td>Inactive:</td><td><input type='radio' name='inactive' value='1' {$statuschecked['inactive']}>Yes <input type='radio' name='inactive' value='0' {$statuschecked['active']}>No</td></tr>
	</table>
</div>
";


$content = "
<div class='subtitle'>".$flouserdata->get_username($permuser->userid)." &gt; Permission</div>
<form action='".$florensia->escape($florensia->request_uri())."' method='post'>
	<div class='small'>
		{$generaloption}
		{$permlist}
	</div>
<div><input type='submit' name='do_save' value='Save user permissions'></div>
</form>";


$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Permission");
$florensia->sitetitle($flouserdata->get_username($permuser->userid, array('rawoutput'=>true)));
$florensia->output_page($content);

?>