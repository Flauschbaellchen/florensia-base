<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("mod_changelog")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Version History");


$historylimit = 5;

if ($_POST['create_history']) {
	$createtime = mktime(0,0,0, $_POST['new_create_month'], $_POST['new_create_day'], $_POST['new_create_year']);
	MYSQL_QUERY("INSERT INTO flobase_versionhistory (date, changes) VALUES('$createtime', '".mysql_real_escape_string($_POST['new_changes'])."')");
}
elseif ($_POST['update_history']) {
	$queryhistory = MYSQL_QUERY("SELECT id FROM flobase_versionhistory ORDER BY date DESC, id DESC LIMIT $historylimit");
	while ($history = MYSQL_FETCH_ARRAY($queryhistory)) {
		$updatetime = mktime(0,0,0, $_POST[$history['id'].'_create_month'], $_POST[$history['id'].'_create_day'], $_POST[$history['id'].'_create_year']);
		MYSQL_QUERY("UPDATE flobase_versionhistory SET date='$updatetime', changes='".mysql_real_escape_string($_POST[$history['id'].'_changes'])."' WHERE id='".$history['id']."'");
	}
}
elseif ($_POST['delete_history']) {
	$queryhistory = MYSQL_QUERY("SELECT id FROM flobase_versionhistory ORDER BY date DESC, id DESC LIMIT $historylimit");
	while ($history = MYSQL_FETCH_ARRAY($queryhistory)) {
		if ($_POST[$history['id'].'_delete']!=1) continue;
		MYSQL_QUERY("DELETE FROM flobase_versionhistory WHERE id='".$history['id']."'");
	}
}


$queryhistory = MYSQL_QUERY("SELECT id, date, changes FROM flobase_versionhistory ORDER BY date DESC, id DESC LIMIT $historylimit");
while ($history = MYSQL_FETCH_ARRAY($queryhistory)) {
	$historylist .= "
		<div class='shortinfo_".$florensia->change()."' style='margin-bottom:15px;'>
			<table style='width:100%;'>
				<tr><td style='font-weight:bold; border-bottom:1px solid;'><input type='text' name='{$history['id']}_create_month' value='".date("m", $history['date'])."' maxlength='2' style='width:20px'>.<input type='text' name='{$history['id']}_create_day' value='".date("d", $history['date'])."' maxlength='2' style='width:20px'>.<input type='text' name='{$history['id']}_create_year' value='".date("Y", $history['date'])."' maxlength='4' style='width:30px'></td><td style='text-align:right; border-bottom:1px solid;'><input type='checkbox' name='{$history['id']}_delete' value='1'><input type='submit' name='delete_history' value='Delete selected versions'><input type='submit' name='update_history' value='Update all versions'></td></tr>
				<tr><td style='height:7px' colspan='2'></td></tr>
				<tr><td class='small' colspan='2'><textarea name='{$history['id']}_changes' style='width:100%; height:200px;'>".$florensia->escape($history['changes'])."</textarea></td></tr>
			</table>
		</div>
	";
}


$content = "
<form action='{$florensia->root}/adminhistory.php' method='post'>
		<div class='subtitle' style='margin-bottom:15px;'>
			<table style='width:100%;'>
				<tr><td style='font-weight:bold; border-bottom:1px solid;'><input type='text' name='new_create_month' value='".date("m")."' maxlength='2' style='width:20px'>.<input type='text' name='new_create_day' maxlength='2' value='".date("d")."' style='width:20px'>.<input type='text' name='new_create_year' value='".date("Y")."' maxlength='4' style='width:30px'></td><td style='text-align:right; border-bottom:1px solid;'><input type='submit' name='create_history' value='Create new version'></td></tr>
				<tr><td style='height:7px' colspan='2'></td></tr>
				<tr><td class='small' colspan='2'><textarea name='new_changes' style='width:100%; height:200px;'></textarea></td></tr>
			</table>
		</div>
$historylist
</form>
"; 

$florensia->output_page($content);

?>