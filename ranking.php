<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("statistic,character,guild");

if (!in_array($_GET['order'], array("land", "sea", "sum"))) $_GET['order']="sum";
$dbwhere[] = "levelsum>'15'";
$dbwhere[] = "lastupdate>='".bcsub(date("U"), 60*60*24*3)."'";

//level-sort
$preselectorder[$_GET['order']] = "selected='selected'";
$orderbyselect = "
	<select name='order'>
		<option value='sum' {$preselectorder['sum']}>{$flolang->statistic_ranking_quickselect_orderby_sum}</option>
		<option value='land' {$preselectorder['land']}>{$flolang->statistic_ranking_quickselect_orderby_landsea}</option>
		<option value='sea' {$preselectorder['sea']}>{$flolang->statistic_ranking_quickselect_orderby_sealand}</option>
	</select>
";


// classes
if (strlen($_GET['class'])>1) {
	$dbwhere[] = "jobclass='".mysql_real_escape_string($_GET['class'])."'";
	$preselectclass[$_GET['class']] = "selected='selected'";
}
$classfilter[] = "<option value='0'>{$flolang->statistic_ranking_quickselect_filterjobclass_all}</option>";
foreach ($florensia->get_classlist("landclass", 0) as $class) {
	$classfilter[] = "<option value='{$class['classname']}' ".$preselectclass[$class['classname']].">{$class['classname']}</option>";
}
$classfilter = "<select name='class'>".join("", $classfilter)."</select>";


// server
if (strlen($_GET['server'])>1) {
	$dbwhere[] = "server='".mysql_real_escape_string($_GET['server'])."'";
	$preselectserver[$_GET['server']] = "selected='selected'";
}
$serverfilter[] = "<option value='0'>{$flolang->statistic_ranking_quickselect_filterserver_all}</option>";
foreach ($florensia->validserver as $server) {
	$serverfilter[] = "<option value='{$server}' ".$preselectserver[$server].">{$server}</option>";
}
$serverfilter = "<select name='server'>".join("", $serverfilter)."</select>";


//db-switch
switch($_GET['order']) {
	case "sum": {
		$dborderby = "ORDER BY levelsum DESC, levelland DESC, charname";
		break;
	}
	case "land": {
		$dborderby = "ORDER BY levelland DESC, levelsea DESC, charname";
		break;
	}
	case "sea": {
		$dborderby = "ORDER BY levelsea DESC, levelland DESC, charname";
		break;
	}
}
if (count($dbwhere)) $dbwhere = "AND ".join(" AND ", $dbwhere);
else unset($dbwhere);

if (intval($_GET['page'])) $startpage = $_GET['page']*70-70;
else $startpage = 0;
$querycharacter = MYSQL_QUERY("SELECT SQL_CALC_FOUND_ROWS charname, levelland, levelsea, levelsum, guild, guildid, guildgrade, jobclass, gender, server, lastupdate  FROM flobase_character_data AS d, flobase_character AS c WHERE d.characterid = c.characterid $dbwhere $dborderby LIMIT {$startpage}, 70");
list($foundrows) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT FOUND_ROWS()"));

$pageselect = $florensia->pageselect($foundrows, array("ranking"), array("order"=>$_GET['order'], "class"=>$_GET['class'], "server"=>$_GET['server']), 70);
for ($i=$pageselect['pagestart']+1; $character = MYSQL_FETCH_ARRAY($querycharacter); $i++) {
	if ($character['gender']=="m") $gender = "<img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'>";
	else  $gender = "<img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='female' style='height:12px;'>";
        if (strlen($character['guild'])) {
            if ($character['guildid']) $guild = "<a href='".$florensia->outlink(array('guilddetails', $character['guildid'], $character['server'], $character['guild']))."'>".$florensia->escape($character['guild'])."</a>";
            else $guild = $florensia->escape($character['guild']);
	    if ($character['guildgrade']) $guild = class_character::guildgrade($character['guildgrade'])." ".$guild;
        }
        else $guild = "";
	    
	$characterlist .= $florensia->adsense(20);
	$characterlist .= "
		<div class='shortinfo_".$florensia->change()."'>
			<table style='width:100%'><tr>
				<td style='width:35px; text-align:right;'>{$i}.</td>
				<td style='width:50px; text-align:right;'>".intval($character['levelsum'])."</td>
				<td style='width:50px; text-align:right;'>".intval($character['levelland'])." <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'></td>
				<td style='width:50px; padding-right:10px; text-align:right;'>".intval($character['levelsea'])." <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'></td>
				<td>$gender <a href='".$florensia->outlink(array("characterdetails", $character['charname']))."'>".$florensia->escape($character['charname'])."</a></td>
				<td style='width:150px'>".$florensia->escape($character['jobclass'])."</td>
				<td style='width:140px'>{$guild}</td>
				<td style='width:90px'><a href='".$florensia->outlink(array('statistics', $character['server']))."'>".$florensia->escape($character['server'])."</a></td>
				<td style='text-align:right; padding-right:3px; width:100px'>".$flolang->sprintf($flolang->character_lastupdate, timetamp2string(date("U")-$character['lastupdate'], "m"))."</td>
			</tr></table>
		</div>
	";
}

foreach ($florensia->validserver as $server) {
	$validserverlinks[] = "<a href='".$florensia->outlink(array('statistics', $server))."'>{$server}</a>";
}

$content = "
<div class='subtitle'><a href='{$florensia->root}/statistics'>{$flolang->statistic_sitetitle}</a> &gt; {$flolang->statistic_ranking_sitetitle}</div>
<div class='subtitle small' style='font-weight:normal; margin-bottom:15px;'>".$flolang->sprintf($flolang->statistic_notice_seealso, join(", ", $validserverlinks))."</div>

<div class='bordered' style='font-weight:bold; margin-bottom:15px;'>
	".$florensia->quick_select("ranking", array(), array($flolang->statistic_ranking_quickselect_orderby=>$orderbyselect, $flolang->statistic_ranking_quickselect_filterjobclass=>$classfilter, $flolang->statistic_ranking_quickselect_filterserver=>$serverfilter))."
</div>
<div style='margin-bottom:8px;'>".$pageselect['selectbar']."</div>
<div class='subtitle' style='margin-bottom:7px;'>
	<table style='width:100%'><tr>
		<td style='width:35px; text-align:right;'>#</td>
		<td style='width:50px; text-align:right;'><img src='{$florensia->layer_rel}/land.gif' style='height:13px;'>+<img src='{$florensia->layer_rel}/sealv.gif' style='height:13px;'></td>
		<td style='width:50px; text-align:right;'><img src='{$florensia->layer_rel}/land.gif' style='height:13px;'></td>
		<td style='width:50px; padding-right:10px; text-align:right;'><img src='{$florensia->layer_rel}/sealv.gif' style='height:13px;'></td>
		<td>{$flolang->character_title_charname}</td>
		<td style='width:150px'>{$flolang->character_title_jobclass}</td>
		<td style='width:140px'>{$flolang->character_title_guild}</td>
		<td style='width:90px'>{$flolang->character_title_server}</td>
		<td style='text-align:right; padding-right:3px; width:100px'>{$flolang->character_title_lastupdate}</td>
	</tr></table>
</div>
<div class='small'>{$characterlist}</div>
<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
";

$florensia->sitetitle("Ranking");
$florensia->output_page($content);



?>
