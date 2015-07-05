<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("mod_usernotes")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Usernotes");

$content = "<div class='subtitle' style='margin-bottom:10px;'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Usernotes</div>";

$querynotes = MYSQL_QUERY("SELECT id, language FROM flobase_usernotes WHERE moderated='0' ORDER BY dateline DESC");
while ($notes = MYSQL_FETCH_ARRAY($querynotes)) {
	if (!$flouser->get_permission("mod_usernotes", $notes['language'])) continue;

	if ($_POST['do_update_'.$notes['id']] && $_POST['moderated_usernote_'.$notes['id']]) { $dummy = $classusernote->get_entry($notes['id']); continue; }
	$usernotescontent .= $classusernote->get_entry($notes['id']);
	$posts++;
}

if ($posts) {
	$content .= "
		<div class='bordered' style='text-align:center; margin-bottom:10px;'>There are $posts usernotes not moderated yet</div>
		<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
			$usernotescontent
		</form>
	";
}
else {
	$content .= "<div class='bordered' style='text-align:center'>There are no usernotes to moderate</div>";
}

$florensia->output_page($content);

?>