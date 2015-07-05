<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
$flolang->load("team");

	$querymember = MYSQL_QUERY("SELECT userid, title, inactive FROM flobase_user, forum_users WHERE userid=uid AND rank >= 0 ORDER BY inactive, rank, forum_users.username");
	while ($member = MYSQL_FETCH_ARRAY($querymember)) {
		$member['title'] = preg_replace('/\[([a-z]{2})\]/', "<img src='{\$florensia->layer_rel}/flags/png/\".\$flolang->lang['\\1']->flagid.\".png' alt='\".\$flolang->lang['\\1']->languagename.\"' title='\".\$flolang->lang['\\1']->languagename.\"' border='0'>", $member['title']);
		eval("\$member['title'] = \"{$member['title']}\";");

		$member['username'] = $flouserdata->get_username($member['userid']);
		$memberlist[$member['inactive']] .= "
			<div style='vertical-align:top' class='shortinfo_".$florensia->change()."'>
				<table style='width:100%;'>
					<tr>
						<td style='font-weight:bold; width:30%;'>".$member['username']."</td>
						<td class='small'>{$member['title']}</td>
					</tr>
				</table>
			</div>
		";
	}

	$content = "
		<div class='bordered' style='margin-bottom:20px;'>{$flolang->team_notice}</div>
		<div class='subtitle'>{$flolang->team_active_members_title}</div>
		".$memberlist[0]."
		<div class='subtitle' style='margin-top:20px;'>{$flolang->team_inactive_members_title}</div>
		".$memberlist[1]."
	";

	$florensia->sitetitle("Team");
	$florensia->output_page($content);

?>