<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

// $flolang->load("market");

if ($_GET['search']) {
	$content = "
		<div class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a></div>
		<div style='text-align:center; margin-bottom:15px;''>".$florensia->quicksearch(array('language'=>true))."</div>
	";

	$query = "SELECT forum_users.uid as userid, forum_users.username as username FROM forum_users, flobase_usermarket WHERE forum_users.uid=flobase_usermarket.userid AND forum_users.username LIKE '%".get_searchstring($_GET['search'],0)."%' GROUP BY forum_users.username";
	$queryforumuser = MYSQL_QUERY($query);
	$entries = MYSQL_NUM_ROWS($queryforumuser);
	if ($entries==1 && $globalentries>1) {
		$forumuser = MYSQL_FETCH_ARRAY($queryforumuser);
		header("Location: ".$florensia->outlink(array("usermarket", $forumuser['userid'], $forumuser['username']), array("escape"=>FALSE)));
		die;
	}
	elseif ($entries==0) {
		$content .= "
			<div class='bordered small' style='text-align:center; margin-bottom:15px;'>{$flolang->market_usermarket_search_ignorenotice}</div>
			<div style='text-align:center' class='warning'>{$flolang->market_noentries}</div>
		";
		$florensia->output_page($content);
	}

	$pageselect = $florensia->pageselect($entries, array("usermarket"));

	$queryforumuser = MYSQL_QUERY($query." LIMIT ".$pageselect['pagestart'].",".$florensia->pageentrylimit);
	while ($forumuser = MYSQL_FETCH_ARRAY($queryforumuser)) {
		$marketentries = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE userid='{$forumuser['userid']}'"));
		$userlist .= "<div class='shortinfo_".$florensia->change()." small'>
						<table style='width:100%'><tr>
							<td style='width:200px;'><a href='".$florensia->outlink(array("usermarket", $forumuser['userid'], $forumuser['username']))."'>".$florensia->escape($forumuser['username'])."</a></td>
							<td>".$flolang->sprintf($flolang->market_usermarket_search_user_entries, $marketentries)."</td>
						</tr></table>
					</div>";
	}
	if (!$userlist) $userlist = "<div style='text-align:center' class='warning'>{$flolang->market_noentries}</div>";

	$content .= "
			<div class='bordered small' style='text-align:center; margin-bottom:15px;'>{$flolang->market_usermarket_search_ignorenotice}</div>
			<div style='margin-bottom:10px;'>".$pageselect['selectbar']."</div>
			$userlist
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
	";

	$florensia->output_page($content);
}
elseif ($_GET['update']) {

	if (!$flouser->userid OR ($_GET['exchangetype']!="buy" && $_GET['exchangetype']!="sell")) header("Location: ".$florensia->outlink(array("usermarket", 0), array("escape"=>FALSE)));

	$queryupdate = MYSQL_QUERY("SELECT charname, timeout, itemamount, exchangegelt, marketlanguage, exchange FROM flobase_usermarket as m, flobase_character as c WHERE userid='{$flouser->userid}' AND itemid='".mysql_real_escape_string($_GET['update'])."' AND exchangetype='{$_GET['exchangetype']}' AND c.characterid=m.characterid");
	if ($update = MYSQL_FETCH_ARRAY($queryupdate)) {
		$florensia->sitetitle("Private market of {$flouser->user['username']} - Update ".$stringtable->get_string($_GET['update']));

		if (!$_POST['do_update']) {
				$oldtimeout=round(bcdiv(bcsub($update['timeout'], date("U")),60*60*24, 3));
				if ($oldtimeout>1) $oldoption = "<option value='".$oldtimeout."' selected='selected'>".$flolang->sprintf($flolang->market_quickform_timespan, $oldtimeout)."</option>"; 
				for ($i=1; $i<=30; $i++) {
					if ($oldtimeout<=$i && isset($oldoption)) { $timespan .= $oldoption; unset($oldoption); if ($i==$oldtimeout) continue; }
					$timespan .= "<option value='$i'>".$flolang->sprintf($flolang->market_quickform_timespan, $i)."</option>"; 
					if ($i>=7) $i=$i+6;
				}
		
				//languageselect
				foreach (explode(",",$update['marketlanguage']) as $languageid) {
					if (!$flolang->lang[$languageid]->visible_usermarket) continue;
					$marketlanguageselected[$flolang->lang[$languageid]->languageid]="selected='selected'";
				}
				foreach ($flolang->lang as $langkey => $langvalue) {
				if (!$flolang->lang[$langkey]->visible_usermarket) continue;
					$marketlanguage .= "<option value='".$flolang->lang[$langkey]->languageid."' ".$marketlanguageselected[$flolang->lang[$langkey]->languageid].">".$flolang->lang[$langkey]->languagename."</option>";
				}
				$marketlanguage = "
				<select name='marketlanguage[]' multiple='multiple' class='small'>
				$marketlanguage
				</select>";
		
				if (!$update['exchangegelt']) $update['exchangegelt']="";
		
				$content = "
					<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
					<div class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a> &gt; <a href='".$florensia->outlink(array("usermarket"))."'>".$flolang->sprintf($flolang->market_details_info_usermarketplace, $florensia->escape($mybb->user['username']))."</a> &gt; Update ".$stringtable->get_string($_GET['update'])."</div>
					<div class='small'>
						<table style='width:100%'>
							<tr><td>{$flolang->market_quickform_notice_character}</td></tr>
							<tr><td><input type='text' name='charactername' maxlength='100' value='".$florensia->escape($update['charname'])."'></td></tr>
							<tr><td>{$flolang->market_quickform_notice_timespan}</td></tr>
							<tr><td><select name='timeoutdays'>$timespan</select></td></tr>
							<tr><td>{$flolang->market_quickform_notice_amount}</td></tr>
							<tr><td><input type='text' name='itemamount' maxlength='3' style='width:20px;' value='{$update['itemamount']}'></td></tr>
							<tr><td>{$flolang->market_quickform_notice_exchangegelt}</td></tr>
							<tr><td><input type='text' name='exchangegelt' maxlength='10' value='{$update['exchangegelt']}'></td></tr>
							<tr><td>{$flolang->market_quickform_notice_marketlanguage}</td></tr>
							<tr><td>$marketlanguage</td></tr>
							<tr><td>{$flolang->market_quickform_notice_exchange}</td></tr>
							<tr><td><textarea name='exchange' style='width:70%; height:60px'>".$florensia->escape($update['exchange'])."</textarea></td></tr>
							<tr><td style='height:10px'></td></tr>
							<tr><td><input type='submit' name='do_update' value='Update ".$stringtable->get_string($_GET['update'])."'></td></tr>
						</table>
					</div>
					</form>
				";
		}
		else {
			$classusermarket->updateentry($_GET['exchangetype'], $_GET['update'], "update");
			$content .= "
				<div class='bordered' style='text-align:center; margin-top:20px;'>
					<a href='".$florensia->outlink(array("usermarket"))."' style='margin-right:20px'>{$flolang->market_usermarket_afterupdate_backtousermarket}</a>
					<a href='".$florensia->escape($_SERVER['REQUEST_URI'])."' style='margin-left:20px'>{$flolang->market_usermarket_afterupdate_backtoupdatepage}</a>
				</div>";
		}
	
		$florensia->output_page($content);
	}
	else header("Location: ".$florensia->outlink(array("usermarket", $flouser->userid, $flouser->user['username']), array("escape"=>FALSE)));
}
elseif (!isset($_GET['userid'])) header("Location: ".$florensia->outlink(array("usermarket", $flouser->userid, $flouser->user['username']), array("escape"=>FALSE)));
elseif (!intval($_GET['userid'])) {
	if ($_GET['userid']=="0" && !$flouser->userid) {
		$content = "
			<div class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a></div>
			<div class='bordered small' style='text-align:center; margin-bottom:20px;'>
				{$flolang->market_usermarket_error_notloggedin}
			</div>
			".$florensia->quicksearch(array('language'=>true));

		$florensia->output_page($content);

	}
	elseif ($flouser->userid) header("Location: ".$florensia->outlink(array("usermarket", $flouser->userid, $flouser->user['username']), array("escape"=>FALSE)));
	else { header("Location: ".$florensia->outlink(array("market"), array("escape"=>FALSE))); die; }
}
else {

	$queryforumuser = MYSQL_QUERY("SELECT uid, username FROM forum_users WHERE uid='".$_GET['userid']."'");
	if (!($forumuser = MYSQL_FETCH_ARRAY($queryforumuser))) header("Location: ".$florensia->outlink(array("market"), array("escape"=>FALSE)));

	$florensia->sitetitle("Private market of {$forumuser['username']}");
	$classusermarket->refresh();

	if ($forumuser['uid']==$flouser->userid) { 
		$ownmarket=true;
		//extend-select
		$timespan = "<option value='0'>--</option>"; 
		for ($i=1; $i<=30; $i++) {
			$timespan .= "<option value='$i'>".$flolang->sprintf($flolang->market_quickform_timespan, $i)."</option>"; 
			if ($i>=7) $i=$i+6;
		}

		//db-work
		foreach ($_POST as $postkey => $postvalue) {
			if (preg_match('/^(extend|delete)_(sell|buy)_([a-z0-9]+)$/', $postkey, $dbkey)) {
				if ($dbkey[1]=="delete" && $postvalue=="1") $classusermarket->updateentry($dbkey[2], $dbkey[3], $dbkey[1], 0);
				elseif ( $dbkey[1]=="extend" && $postvalue!="0" && $_POST['delete_'.$dbkey[2].'_'.$dbkey[3]]!="1") $classusermarket->updateentry($dbkey[2], $dbkey[3], $dbkey[1], $postvalue);
			}
		}
	}

	for ($i=1; $i<=2; $i++) {
		if ($i==1) $exchangetype="sell";
		else $exchangetype="buy";

		$x=0;
		$querynewest = MYSQL_QUERY("SELECT id as marketid, itemid, exchange, exchangetype FROM flobase_usermarket WHERE exchangetype='$exchangetype' AND userid='{$forumuser['uid']}' ORDER BY createtime DESC");
		while ($newest = MYSQL_FETCH_ARRAY($querynewest)) {
			$colorchange = $florensia->change();
			$item = new floclass_item($newest['itemid']);
			$marketshortinfo = $item->shortinfo(array("marketid"=>$newest['marketid'], 'fullmarketexchange'=>1))."
					<table class='small' style='width:100%'>
						<tr><td>".$parser->parse_message($newest['exchange'], array("allow_mycode" =>1, "filter_badwords"=>1))."</td></tr>
					</table>
			";

			if (!$ownmarket) { 
				$newestlist[$exchangetype]['content'] .= "
					<div class='shortinfo_{$colorchange}'>$marketshortinfo</div>";
			}
			else {
				$newestlist[$exchangetype]['content'] .= "
					<div style='margin-bottom:5px;'>
						<table style='width:100%' class='small'>
							<tr>
								<td class='shortinfo_{$colorchange}' style='vertical-align:top; width:650px;'>
									$marketshortinfo
								</td>
								<td style='width:10px;'></td>
								<td class='shortinfo_{$colorchange}' style='vertical-align:bottom;'>
									<table style='width:100%;'>
										<tr><td style='text-align:right;'>{$flolang->market_private_submit_delete}</td><td style='text-align:right;'><input type='checkbox' name='delete_{$newest['exchangetype']}_{$newest['itemid']}' value='1'></td></tr>
										<tr><td style='text-align:right;'>{$flolang->market_private_submit_extend}</td><td style='text-align:right;'><select name='extend_{$newest['exchangetype']}_{$newest['itemid']}'>$timespan</select></td></tr>
										<tr><td colspan='2' style='height:10px;'></td></tr>
										<tr><td colspan='2' style='text-align:center;'><input type='submit' name='do_update_all' value='{$flolang->market_private_submit_updateall}'></td></tr>
										<tr><td colspan='2' style='height:15px;'></td></tr>
										<tr><td colspan='2' style='text-align:right;'><a href='{$florensia->root}/usermarket.php?exchangetype=$exchangetype&amp;update={$newest['itemid']}'>{$flolang->market_usermarket_updatelink}</a></td></tr>
									</table>
								</td>
							</tr>
						</table>
					</div>";
			}
			$x++;
		}
		$newestlist[$exchangetype]['amount'] = $x;
	}

	//newest
	$content = "
		<div class='subtitle'><a href='".$florensia->outlink(array("market"))."'>{$flolang->market_title_main}</a> &gt; ".$flolang->sprintf($flolang->market_details_info_usermarketplace, $florensia->escape($forumuser['username']))."</div>
		
		<div class='small bordered' style='margin-bottom:15px;'>{$flolang->market_contactnotice}</div>
		{$newestlist['sell']['content']}
		{$newestlist['buy']['content']}
	";
	if (!$newestlist['sell']['content'] && !$newestlist['buy']['content']) $content .= "<div class='bordered' style='text-align:center;'>{$flolang->market_noentries}</div>";

	if ($ownmarket) {		
		$content = "
			<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
			$content
			</form>
		";
	}

	$content = "<div style='text-align:center; margin-bottom:15px;''>".$florensia->quicksearch(array('language'=>true))."</div>$content";
	$florensia->output_page($content);
}



?>