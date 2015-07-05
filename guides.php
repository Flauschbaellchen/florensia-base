<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("guides");

//db-work
foreach ($_POST as $postkey => $postvalue) {
	if (preg_match('/^guide_(thumpsup|thumpsdown|withdraw)_([1-9][0-9]*)_x$/', $postkey, $dbkey)) {
		$florensia->notice($classguide->updateentry($dbkey[2], $dbkey[1]));
	}
}

if (($_POST['do_edit'] OR $_POST['do_add']) && $flouser->get_permission("guides", "edit")) {
	$dbupdate = array();
	$dbupdate['title'] = "'".mysql_real_escape_string($_POST['title'])."'";
	$dbupdate['author'] = "'".mysql_real_escape_string($_POST['author'])."'";
	
	foreach ($_POST['gcat'] as $newcat) {
		$cat = intval($newcat);
		if (!$cat) continue;
		$dbupdate['categories'][] = "-{$cat}-";
	}
	array_unique($dbupdate['categories']);
	$dbupdate['categories'] = "'".join("", $dbupdate['categories'])."'";
	
	if (in_array($_POST['linktype'], array("intern", "forum", "extern"))) $dbupdate['linktype'] = "'{$_POST['linktype']}'";
	else $dbupdate['linktype'] = "'extern'";
	$dbupdate['linkvar'] = "'".mysql_real_escape_string($_POST['linkvar'])."'";
	
	if ($flolang->lang[$_POST['language']]) $dbupdate['language'] = "'{$_POST['language']}'";
	else $dbupdate['language'] = "'en'";
	
	if ($_POST['do_edit']) {
		foreach ($dbupdate as $dbkey => $dbvalue) {
			$dbquery[] = "{$dbkey}={$dbvalue}";
		}
		
		if (!MYSQL_QUERY("UPDATE flobase_guides SET ".join(", ", $dbquery)." WHERE id='".intval($_POST['editgid'])."'")) {
			$florensia->notice($flolang->sprintf($flolang->guides_notice_edit_error, $florensia->escape($_POST['title'])), "warning");
			$flolog->add("error:guide", "MYSQL-Update-Error occurred while saving changes to {guide:".intval($_POST['editgid'])."}");
		} else {
			$florensia->notice($flolang->sprintf($flolang->guides_notice_edit_successful, $florensia->escape($_POST['title'])), "successful");
			$flolog->add("guide:edit", "{user:{$flouser->userid}} edited {guide:".intval($_POST['editgid'])."}");
		}
	} elseif ($_POST['do_add']) {
		$dbupdate['timeline'] = "'".date("U")."'";
		if (!MYSQL_QUERY("INSERT INTO flobase_guides (".join(", ", array_keys($dbupdate)).") VALUES(".join(", ", array_values($dbupdate)).")")) {
			$florensia->notice($flolang->sprintf($flolang->guides_notice_add_error, $florensia->escape($_POST['title'])), "warning");
			$flolog->add("error:guide", "MYSQL-INSERT-Error occurred while adding {guide:".intval($_POST['editgid'])."}");
		} else {
			$florensia->notice($flolang->sprintf($flolang->guides_notice_add_successful, $florensia->escape($_POST['title'])), "successful");
			$flolog->add("guide:new", "{user:{$flouser->userid}} added {guide:".mysql_insert_id()."}");
		}	
	}
} elseif ($_POST['do_delete'] && $flouser->get_permission("guides", "delete")) {
	if ($_POST['del1'] && $_POST['del2'])
	$queryguide = MYSQL_QUERY("SELECT id, title, thumpsup, thumpsdown FROM flobase_guides WHERE id='".intval($_POST['editgid'])."'");
	if ($guide = MYSQL_FETCH_ARRAY($queryguide)) {
		if (!MYSQL_QUERY("DELETE FROM flobase_guides WHERE id={$guide['id']}")) {
			$florensia->notice($flolang->sprintf($flolang->guides_notice_delete_error, $florensia->escape($guide['title'])), "warning");
			$flolog->add("error:guide", "MYSQL-Delete-Error occurred while deleting {guide:".intval($_POST['editgid'])."}");
		} else {
			$florensia->notice($flolang->sprintf($flolang->guides_notice_delete_successful, $florensia->escape($guide['title'])), "successful");
			$flolog->add("guide:delete", "{user:{$flouser->userid}} deleted \"".$florensia->escape($guide['title'])."\" ({$guide['thumpsup']}/{$guide['thumpsdown']}) (ID:".intval($guide['id']).")");
		}
	}
}
			
			
/* SEARCH BY TITLE */
if (strlen($_GET['search'])) {
	foreach (explode(" ", $_GET['search']) as $keyword) {
		$searchstring[] = "title LIKE '%".get_searchstring($keyword,0)."%'";
	}
	$dbwhere[] = join(" AND ", $searchstring);
}
$guidessearch = "<input type='text' name='search' value='".$florensia->escape($_GET['search'])."'>";

/* LANGUAGE FILTER */
$foundlang = false;
$queryguideslang = MYSQL_QUERY("SELECT language FROM flobase_guides GROUP BY language");
while ($glang = MYSQL_FETCH_ASSOC($queryguideslang)) {
	if ($_GET['lang']==$glang['language']) {
		$glangchecked = "checked='checked'";
		$foundlang=true;
	}
	else unset($glangchecked);
	$guideslanguage[] = "<span style='margin-right:5px;'><input type='radio' name='glang' value='{$glang['language']}' style='padding:0px;' {$glangchecked}><img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$glang['language']]->flagid.".png' alt='".$flolang->lang[$glang['language']]->languagename."' border='0'></span>";
}

if (!$foundlang) $glangchecked = "checked='checked'";
else $dbwhere[] = "language='{$_GET['lang']}'";
array_unshift($guideslanguage, "<span style='margin-right:5px;'><input type='radio' name='glang' value='all' style='padding:0px;' {$glangchecked}><span class='bordered'> ? </span></span>");

/* ORDER BY */
if (!in_array($_GET['order'], array("title", "votes", "date"))) $_GET['order'] = "votes";
switch ($_GET['order']) {
	case "title": {
		$dborderby = "ORDER BY title";
		break;
	}
	case "votes": {
		$dborderby = "ORDER BY thumpsup DESC, thumpsdown, title";
		break;
	}
	case "date": {
		$dborderby = "ORDER BY timeline DESC";
		break;
	}
}
$gorderchecked[$_GET['order']] = "checked='checked'";
$guidesorder = "
	<span style='margin-right:5px;'><input type='radio' name='gorder' value='votes' style='padding:0px;' ".$gorderchecked['votes'].">{$flolang->guides_orderby_votes}</span>
	<span style='margin-right:5px;'><input type='radio' name='gorder' value='title' style='padding:0px;' ".$gorderchecked['title'].">{$flolang->guides_orderby_name}</span>
	<span style='margin-right:5px;'><input type='radio' name='gorder' value='date' style='padding:0px;' ".$gorderchecked['date'].">{$flolang->guides_orderby_date}</span>
";

/* CATEGORIES */
$checkcategories = explode("-", $_GET['cat']);
if (!strlen($_GET['cat']) OR in_array("0", $checkcategories)) $gcatselectall = true;

$querygcat = MYSQL_QUERY("SELECT id, name_{$flolang->language} FROM flobase_guides_categories ORDER BY sortorder, name_{$flolang->language}");
for ($i=1; $gcat = MYSQL_FETCH_ARRAY($querygcat); $i++) {
	if (in_array($gcat['id'], $checkcategories)) {
		$gcatchecked = "checked='checked'";
		$dbwhere[] = "categories LIKE '%-{$gcat['id']}-%'";
	} else unset($gcatchecked);
	
	$guidescategorieselect[] = "<span style='margin-right:5px;'><input type='checkbox' name='gcat[]' value='{$gcat['id']}' style='padding:0px;' {$gcatchecked}>".$florensia->escape($gcat['name_'.$flolang->language])."</span>";
	
	$guidescategorie[$gcat['id']] = $florensia->escape($gcat['name_'.$flolang->language]);
	if ($i%5==0) $guidescategorieselect[] = "<br />";
}
//unshift "all"-selectbox and checked if selectall
//if ($gcatselectall) $gcatchecked = "checked='checked'";
//else unset($gcatchecked);
//array_unshift($guidescategorieselect, "<span style='margin-right:5px;'><input type='checkbox' name='gcat[]' value='0' style='padding:0px;' {$gcatchecked}>{$flolang->guides_select_categories_all}</span><br />");


$guidessearchbox = "
<div style='padding:2px;'>
    <table style='width:100%;'>
        <tr><td style='width:100px;'>{$flolang->guides_search_title}</td><td>{$guidessearch}</td></tr>
	<tr><td>{$flolang->guides_select_language_title}</td><td>".join("", $guideslanguage)."</td></tr>
	<tr><td>{$flolang->guides_orderby_title}</td><td>$guidesorder</td></tr>
	<tr><td>{$flolang->guides_select_categories_title}</td><td>".join("", $guidescategorieselect)."</td></tr>
    </table>
</div>";

$searchbar = $florensia->quick_select("guides", array(), array(0=>$guidessearchbox));



if ($dbwhere) $dbwhere = "WHERE ".join(" AND ", $dbwhere);
$queryguides = MYSQL_QUERY("SELECT * FROM flobase_guides {$dbwhere} {$dborderby}");
while ($guide = MYSQL_FETCH_ARRAY($queryguides)) {
	
	$colorchange = $florensia->change();
	unset($adminuserlist, $admineditbox);
	$guide['thumpsdown'] = 0-$guide['thumpsdown'];				
					
	if ($flouser->userid) {
		$uservotestatus = $classvote->votestatus($guide['userlist']);
		if ($uservotestatus) {				
			if ($uservotestatus['vote']) $guide['thumpsup'] = "<span style='text-decoration:underline;'>{$guide['thumpsup']}</span>";
			else $guide['thumpsdown'] = "<span style='text-decoration:underline;'>{$guide['thumpsdown']}</span>";
				
			$verifydetails = "
				".$flolang->sprintf($flolang->guides_quick_verify_alreadyverified_notice, date("m.d.Y", $uservotestatus['dateline']))."<br />
				<span style='font-weight:bold; font-size:150%;'>{$guide['thumpsup']}/{$guide['thumpsdown']}</span>
				<input type='image' name='guide_withdraw_{$guide['id']}' src='{$florensia->layer_rel}/withdraw.gif' style='background-color:transparent; border:0px; height:18px;'>
			";
		}
		else {
			$verifydetails = "
					<input type='image' name='guide_thumpsup_{$guide['id']}' src='{$florensia->layer_rel}/thumpsup.gif' style='background-color:transparent; border:0px; width:15px; height:18px;'>
					<span style='font-weight:bold; font-size:150%; margin-left:4px; margin-right:4px;'>{$guide['thumpsup']}/{$guide['thumpsdown']}</span>
					<input type='image' name='guide_thumpsdown_{$guide['id']}' src='{$florensia->layer_rel}/thumpsdown.gif' style='background-color:transparent; border:0px; width:15px; height:18px;'>
			";
		}
	}
	else { $verifydetails = "<span style='font-weight:bold; font-size:150%; margin-left:4px; margin-right:4px;'>{$guide['thumpsup']}/{$guide['thumpsdown']}</span><br />{$flolang->guides_quick_notloggedin}"; }
	
	if ($flouser->get_permission("guides", "contributed")) {
		$votestats = $classvote->votestats($guide['userlist']);
		$adminuserlist[] = "<span ".popup("<div class='shortinfo_{$colorchange}' style='width:300px'>{$votestats['display']}</div>", "LEFT, MOUSEOFF, STICKY").">C</span>";
		$adminuserlist[] = "<a href='{$florensia->root}/adminlog?section=guide&amp;logvalue=".urlencode("{guide:{$guide['id']}}")."' target='_blank'>L</a>";
	}
	if ($flouser->get_permission("guides", "edit")) {
		$adminuserlist[] = "<a href='javascript:switchlayer(\"guide_{$guide['id']},guide_{$guide['id']}_editbox\", \"guide_{$guide['id']}_editbox\")'>E</a>";
		unset($editcategories, $editlang, $editglinktypechecked);
		preg_match_all("/-([0-9]+)-/", $guide['categories'], $catmatches);
		$i=1;
		foreach ($guidescategorie as $gcid => $gcname) {
			if (in_array($gcid, $catmatches[1])) $gcatchecked = "checked='checked'";
			else unset($gcatchecked);
			$editcategories[] = "<span style='margin-right:5px;'><input type='checkbox' name='gcat[]' value='{$gcid}' style='padding:0px;' {$gcatchecked}>".$florensia->escape($gcname)."</span>";
			if ($i%5==0) $editcategories[] = "<br />";
			$i++;
		}
		
		foreach ($flolang->lang as $langid => $lang) {
			if ($guide['language']==$langid) $glangchecked = "checked='checked'";
			else unset($glangchecked);
			$editlang[] = "<span style='margin-right:5px;'><input type='radio' name='language' value='{$langid}' style='padding:0px;' {$glangchecked}><img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$langid]->flagid.".png' alt='".$flolang->lang[$langid]->languagename."' border='0'></span>";
		}
		
		$editglinktypechecked[$guide['linktype']] = "selected='selected'";
		
		$admineditbox = "
			<div id='guide_{$guide['id']}_editbox' class='shortinfo_{$colorchange} small' style='padding:5px; margin:2px; display:none;'>
				<form action='".$florensia->escape($florensia->request_uri())."' method='POST'>
					<div class='small' style='float:right;'>
						<input type='checkbox' name='del1' value='1' style='margin:0px;'>
						<input type='checkbox' name='del2' value='1' style='margin:0px 2px 0px 2px;'>
						<input type='submit' name='do_delete' value='{$flolang->guides_adminbox_button_do_delete}'>
					</div>
					<input type='hidden' name='editgid' value='{$guide['id']}'>
					<input type='text' name='title' value='".$florensia->escape($guide['title'])."' style='width:70%;'><br />
					<table class='small' style='width:100%; padding-left:15px;'>
					<tr><td colspan='2'>
						<select name='linktype'>
							<option value='intern' {$editglinktypechecked['intern']}>{$flolang->guides_adminbox_linktype_intern}</option>
							<option value='forum' {$editglinktypechecked['forum']}>{$flolang->guides_adminbox_linktype_forum}</option>
							<option value='extern' {$editglinktypechecked['extern']}>{$flolang->guides_adminbox_linktype_extern}</option>
						</select>
						<input type='text' name='linkvar' value='".$florensia->escape($guide['linkvar'])."' style='width:30%;'>
					</td></tr>
					<tr><td>{$flolang->guides_infobox_author}:</td><td><input type='text' name='author' value='".$florensia->escape($guide['author'])."' style='width:150px'></td></tr>
					<tr><td>{$flolang->guides_infobox_dateline}:</td><td>".date("m.d.y", $guide['timeline'])."</td></tr>
					<tr><td>{$flolang->guides_select_language_title}:</td><td>".join("", $editlang)."</td></tr>
					<tr><td>{$flolang->guides_infobox_categories}:</td><td>".join("", $editcategories)."</td></tr>
					</table>
					<div style='text-align:right'>
						<input type='submit' name='do_edit' value='{$flolang->guides_adminbox_button_do_edit}' style='margin-right:8px;'><a class='button' href='javascript:switchlayer(\"guide_{$guide['id']},guide_{$guide['id']}_editbox\", \"guide_{$guide['id']}\")'>{$flolang->guides_adminbox_button_do_cancel}</a>
					</div>
				</form>
			</div>
		";
	}
	
	if ($adminuserlist) {
		$adminuserlist = "<td class='small' style='width:14px; vertical-align:middle; text-align:center;'><div class='shortinfo_{$colorchange}'>".join("<br />", $adminuserlist)."</div></td>";
	}
	
	switch ($guide['linktype']) {
		case "forum": {
			$guidelink = "<a href='{$florensia->forumurl}/thread-".intval($guide['linkvar']).".html' target='_blank'>".$florensia->escape($guide['title'])."</a>";
			break;
		}
		case "intern": {
			$guidelink = "<a href='{$florensia->root}/".$florensia->escape($guide['linkvar'])."' target='_blank'>".$florensia->escape($guide['title'])."</a>";
			break;
		}
		case "extern": {
			$guidelink = "<a href='".$florensia->escape($guide['linkvar'])."' target='_blank'>".$florensia->escape($guide['title'])."</a>";
			break;
		}
		default: $guidelink = $florensia->escape($guide['title']);
	}
	
	
	
	if (preg_match("/^[1-9][0-9]*$/", $guide['author'])) $author = $flouserdata->get_username($guide['author']);
	else $author = $florensia->escape($guide['author']);

	if (!strlen($guide['categories'])) $categories = array("-");
	else {
		unset($categories);
		preg_match_all("/-([0-9]+)-/", $guide['categories'], $catmatches);
		foreach ($catmatches[1] as $catid) {
			$categories[] = $guidescategorie[$catid];
		}
	}
	
	$guidelist .= "
		<div style='margin-bottom:1px;'>
			<table style='width:100%;'>
				<tr>
					<td>
						<div id='guide_{$guide['id']}'>
							<table style='width:100%; border-spacing:2px;'><tr>
								<td class='shortinfo_{$colorchange}' style='vertical-align:top; width:650px;'>
									<div style='float:right; padding:2px;'><img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$guide['language']]->flagid.".png' alt='".$flolang->lang[$guide['language']]->languagename."' border='0'></div>
									<div style='font-weight:bold;'>$guidelink</div>
									<div class='small' style='padding-left:15px;'>
										{$flolang->guides_infobox_author}: $author<br />
										{$flolang->guides_infobox_dateline}: ".date("m.d.y", $guide['timeline'])."<br />
										{$flolang->guides_infobox_categories}: ".join(", ", $categories)."
									</div>
								</td>
								<td class='shortinfo_{$colorchange} small' style='text-align:center; vertical-align:middle;'>
									$verifydetails
								</td>
							</tr></table>
						</div>
						{$admineditbox}
					</td>
					{$adminuserlist}
				</tr>
			</table>
		</div>
	";
}
if (!$guidelist) $guidelist = "<div class='bordered' style='text-align:center'>{$flolang->guides_error_nothingfound}</div>";

if ($flouser->get_permission("guides", "edit")) {
	unset($editlang);
	$glangchecked = "checked='checked'";
	foreach ($flolang->lang as $langid => $lang) {
		$editlang[] = "<span style='margin-right:5px;'><input type='radio' name='language' value='{$langid}' style='padding:0px;' {$glangchecked}><img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$langid]->flagid.".png' alt='".$flolang->lang[$langid]->languagename."' border='0'></span>";
		unset($glangchecked);
	}
		
	$adminnewguidebox = "
		<div class='small' style='margin-bottom:7px;'>
			<div id='admin_new_guide' style='text-align:right'>
				[<a href='javascript:switchlayer(\"admin_new_guide,admin_new_guide_box\", \"admin_new_guide_box\")'>{$flolang->guides_admin_link_addnewguide}</a>]
			</div>
			<div id='admin_new_guide_box' class='shortinfo_".$florensia->change()."' style='padding:5px; margin:2px; display:none;'>
				<form action='".$florensia->request_uri()."' method='POST'>
					<input type='text' name='title' value='".$florensia->escape($guide['title'])."' style='width:70%;'><br />
					<table class='small' style='width:100%; padding-left:15px;'>
					<tr><td colspan='2'>
						<select name='linktype'>
							<option value='intern'>{$flolang->guides_adminbox_linktype_intern}</option>
							<option value='forum'>{$flolang->guides_adminbox_linktype_forum}</option>
							<option value='extern'>{$flolang->guides_adminbox_linktype_extern}</option>
						</select>
						<input type='text' name='linkvar' style='width:30%;'>
					</td></tr>
					<tr><td>{$flolang->guides_infobox_author}:</td><td><input type='text' name='author' style='width:150px'></td></tr>
					<tr><td>{$flolang->guides_infobox_dateline}:</td><td>".date("m.d.y", date("U"))."</td></tr>
					<tr><td>{$flolang->guides_select_language_title}:</td><td>".join("", $editlang)."</td></tr>
					<tr><td>{$flolang->guides_infobox_categories}:</td><td>".join("", $guidescategorieselect)."</td></tr>
					</table>
					<div style='text-align:right'>
						<input type='submit' name='do_add' value='{$flolang->guides_adminbox_button_do_add}' style='margin-right:8px;'><a class='button' href='javascript:switchlayer(\"admin_new_guide,admin_new_guide_box\", \"admin_new_guide\")'>{$flolang->guides_adminbox_button_do_cancel}</a>
					</div>
				</form>
			</div>
		</div>
	";
}

$content = "
	<div class='bordered small' style='margin-bottom:8px'>".str_replace('http://flobase-teamurl', "{$florensia->root}/team", $flolang->guides_newguide_notice)."</div>
	<div class='bordered small' style='margin-bottom:15px;'>
		{$searchbar}
	</div>
	{$adminnewguidebox}
	<form action='".$florensia->escape($florensia->request_uri())."' method='post'>
	{$guidelist}
	</form>
";
$florensia->sitetitle("Guides");
$florensia->output_page($content);
?>