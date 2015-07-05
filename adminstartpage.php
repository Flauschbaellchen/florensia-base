<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("mod_startpage")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Startpage");
$flolang->load("index");
	$newstemplate = '
	<tr>
		<td style=\'width:70px; vertical-align:top;\'>$dateline</td>
		<td>$news_title</td>
		<td style=\'width:90px; text-align:right; vertical-align:top;\'>$news_replies</td>
		<td style=\'width:100px; text-align:right; vertical-align:top;\'>$news_views</td>
	</tr>
	';

	if ($flouser->get_permission("mod_startpage", $_POST['addtidlang']) OR $flouser->get_permission("mod_startpage", $_POST['deltidlang'])) {
		//delete_tid
		if (isset($_POST['deletetid']) && $_POST['deltidverify']==1 && $flolang->lang[$_POST['deltidlang']] && intval($_POST['deltid'])!="" &&is_int(intval($_POST['deltid']))) {
			unset($dbentry);
			foreach (explode('|', $flolang->lang[$_POST['deltidlang']]->startpage) as $startpagevalue) {
				if (preg_match("/^".intval($_POST['deltid'])."-.+$/", $startpagevalue)) continue;
				$dbentry .= "|".$startpagevalue;
			}
			$dbentry = preg_replace('/^\|/', '', $dbentry);

			if ($querydeltid = MYSQL_QUERY("UPDATE flobase_language SET startpage='".mysql_real_escape_string($dbentry)."' WHERE languageid='".$_POST['deltidlang']."'")) {
				$notice = "<div class='bordered' style='text-align:center'>Entry successfully deleted</div>";
			}
			else { $notice = "<div class='warning' style='text-align:center'>An error occurred while trying to delete your entry</div>"; }
			$flolang->get_languages();

		}
		//add_tid
		elseif (isset($_POST['addtid']) && $flolang->lang[$_POST['addtidlang']] && intval($_POST['addtidid'])!="" && is_int(intval($_POST['addtidid'])) && $_POST['addtidtitle']!="") {
			unset($dbentry);
			$i=1;
			$add=0;
			foreach (explode('|', $flolang->lang[$_POST['addtidlang']]->startpage) as $startpagevalue) {
				$dbentry .= "|".$startpagevalue;
				if ($i==$_POST['addafter']) { $dbentry .= "|".$_POST['addtidid']."-".$_POST['addtidtitle']; $add=1; }
				$i++;
			}
			if ($add==0) $dbentry .= "|".$_POST['addtidid']."-".$_POST['addtidtitle'];
			$dbentry = preg_replace('/^\|/', '', $dbentry);

			if ($queryaddtid = MYSQL_QUERY("UPDATE flobase_language SET startpage='".mysql_real_escape_string($dbentry)."' WHERE languageid='".$_POST['addtidlang']."'")) {
				$notice = "<div class='bordered' style='text-align:center'>Entry successfully added</div>";
			}
			else { $notice = "<div class='warning' style='text-align:center'>An error occurred while trying to add your entry</div>"; }
			$flolang->get_languages();
		}

		$content = $notice;
	}

	foreach ($flolang->lang as $langkey => $langvalue) {
		if (!$flouser->get_permission("mod_startpage", $langkey)) continue;

		unset ($contentlang);
		$contentlang .= "<div class='subtitle' style='text-align:center; margin-top:30px;'>".$flolang->lang[$langkey]->languagename."</div>";
			$i=1;
			unset($addafter);
			foreach (explode('|', $flolang->lang[$langkey]->startpage) as $startpagevalue) {
				preg_match('/^([0-9]+)-(.+)$/', $startpagevalue, $startpagethread);

				unset($news_temp);
				$queryforumnews = MYSQL_QUERY("SELECT * FROM forum_threads WHERE fid='".intval($startpagethread[1])."' AND visible>-2 ORDER BY dateline DESC LIMIT 5");
				while ($forumnews = MYSQL_FETCH_ARRAY($queryforumnews)) {
					if ($forumnews['dateline']>bcsub(date("U"),60*60*24*2)) $dateline = "<span style='font-weight:bold; color:#FF0000;'>".date("m.d.Y", $forumnews['dateline'])."</span>";
					else $dateline = date("m.d.Y", $forumnews['dateline']);
					$news_title = $flolang->sprintf($flolang->news_title, "<a href='".$florensia->forumurl."/thread-".$forumnews['tid'].".html' target='_blank'>".$forumnews['subject']."</a>", $forumnews['username']);
					$news_replies = $flolang->sprintf($flolang->news_replies, $forumnews['replies']);
					$news_views = $flolang->sprintf($flolang->news_views, $forumnews['views']);
					eval("\$news_temp .= \"$newstemplate\";");
				}
				$contentlang .= "
				<div style='float:right; width:150px; margin-top:5px;' class='small'>
					<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
						<div style='text-align:right; margin-bottom:2px;'>
							<input type='hidden' name='deltid' value='".intval($startpagethread[1])."'>
							<input type='hidden' name='deltidlang' value='$langkey'>
							<input type='checkbox' name='deltidverify' value='1'>
							<input type='submit' name='deletetid' value='Delete' style='vertical-align:top; width:100px;'>
						</div>
					</form>
				</div>
				<div style='border-bottom:1px #88a9d4 solid; font-weight:bold; margin-top:5px;'>".$startpagethread[2]." <span style='font-weight:normal;' class='small'>(fid: ".intval($startpagethread[1]).")</span></div>
				<div>
					<table style='width:100%; border-collapse:0px; border-spacing:0px; padding:0px;' class='small'>
						$news_temp
					<tr>
						<td style='text-align:right' colspan='4'><a href='http://forum.florensia-base.com/forum-".intval($startpagethread[1]).".html' target='_blank'>{$flolang->news_listall}</a></td>
					</tr>
					</table>
				</div>";
				$addafter .= "<option value='$i'>".$startpagethread[2]."</option>";
				$i++;
			}
		$content .= $contentlang;

		$content .= "
				<div style='margin-top:5px;' class='small'>
					<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
						<div style='margin-bottom:2px;'>
							<input type='hidden' name='addtidlang' value='$langkey'>
							<input type='text' name='addtidid' value='fid number' style='width:70px;'>
							<input type='text' name='addtidtitle' value='Description' style='width:150px;'>
							Add after:
							<select name='addafter'>
								$addafter
								<option value='0' selected='selected'>At last</option>
							</select>
							<input type='submit' name='addtid' value='Add new threads' style='vertical-align:top;'>
						</div>
					</form>
				</div>
		";
	}
	$content = "<div class='subtitle'><a href='{$florensia->root}/admincp.php'>AdminCP</a> &gt; Startpage</div>
		$content";

	$florensia->output_page($content);
?>