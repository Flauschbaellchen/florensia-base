<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$florensia->sitetitle("Guildranking");
$flolang->load("statistic,character,guild");

//server-handling: filter, watch-also-links, jumpto
$serverfilter[] = "<option value='0'>{$flolang->statistic_ranking_quickselect_filterserver_all}</option>";
foreach($florensia->validserver as $server) {
    if ($_GET['server']==$server) {
        $selected="selected='selected'";
        $dbwhere[] = "server='".mysql_real_escape_string($server)."'";
    }
    else unset($selected);
    $jumptoguild .= "<option value='{$server}'>{$server}</option>";
    $serverfilter[] = "<option value='{$server}' {$selected}>{$server}</option>";
    $validserverlinks[] = "<a href='".$florensia->outlink(array('statistics', $server))."'>{$server}</a>";
}
$jumptoguild = "<input type='text' maxlength='13' name='search' value='".$florensia->escape($_GET['search'])."'> <select name='server'>{$jumptoguild}</select>";
$jumptoguild = "
    <div class='subtitle' style='text-align:center; margin-bottom:15px;'>{$flolang->guild_jumpto}<br />
        ".$florensia->quick_select("guilddetails", array(), array(0=>$jumptoguild))."
    </div>
";
$serverfilter = "<select name='server'>".join("", $serverfilter)."</select>";


if (!isset($_GET['member'])) $_GET['member']=3;
if (intval($_GET['member'])) $dbwhere[] = "memberamount>='".intval($_GET['member'])."'";
$memberfilter = "<input type='text' name='member' maxlength='3' style='width:25px; text-align:right;' value='".intval($_GET['member'])."'>";



//level-sort
if (!in_array($_GET['order'], array("land", "sea", "sum", "name", "member"))) $_GET['order']="sum";
$preselectorder[$_GET['order']] = "selected='selected'";
$orderbyselect = "
	<select name='order'>
		<option value='sum' {$preselectorder['sum']}>{$flolang->statistic_ranking_quickselect_orderby_averagesum}</option>
		<option value='land' {$preselectorder['land']}>{$flolang->statistic_ranking_quickselect_orderby_averagelandsea}</option>
		<option value='sea' {$preselectorder['sea']}>{$flolang->statistic_ranking_quickselect_orderby_averagesealand}</option>
		<option value='name' {$preselectorder['name']}>{$flolang->statistic_ranking_quickselect_orderby_guildname}</option>
		<option value='member' {$preselectorder['member']}>{$flolang->statistic_ranking_quickselect_orderby_guildmember}</option>
	</select>
";

//db-switch
switch($_GET['order']) {
	case "sum": {
		$dborderby = "ORDER BY averagelevel DESC, averagelandlevel, guildname";
		break;
	}
	case "land": {
		$dborderby = "ORDER BY averagelandlevel DESC, averagesealevel DESC, guildname";
		break;
	}
	case "sea": {
		$dborderby = "ORDER BY averagesealevel DESC, averagelandlevel DESC, guildname";
		break;
	}
	case "member": {
		$dborderby = "ORDER BY memberamount DESC, guildname";
		break;
	}
	case "name": {
		$dborderby = "ORDER BY guildname";
		break;
	}
}
$dbwhere[] = "memberamount!='0'";
if (count($dbwhere)) $dbwhere = "WHERE ".join(" AND ", $dbwhere);
else unset($dbwhere);

if (intval($_GET['page'])) $startpage = $_GET['page']*100-100;
else $startpage = 0;

$queryguild = MYSQL_QUERY("SELECT SQL_CALC_FOUND_ROWS g.guildid, guildname, server, memberamount, averagelandlevel, averagesealevel, averagelevel, misc_language, (SELECT COUNT(galleryid) FROM flobase_guild_gallery as ga WHERE ga.guildid=g.guildid) as gallery FROM flobase_guild as g {$dbwhere} {$dborderby} LIMIT {$startpage}, 100");
list($foundrows) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT FOUND_ROWS()"));
$pageselect = $florensia->pageselect($foundrows, array("guildranking"), array("order"=>$_GET['order'], "member"=>$_GET['member'], "server"=>$_GET['server']), 100);

for ($i=$pageselect['pagestart']+1; $guild = MYSQL_FETCH_ARRAY($queryguild); $i++) {
    $guildlist .= $florensia->adsense(30);
    if ($guild['gallery']) $guild['gallery'] = "<a href='".$florensia->outlink(array("gallery", "g", $guild['guildid'], $guild['server'], $guild['guildname']))."'>{$guild['gallery']} <img src='{$florensia->layer_rel}/icon_gallery.png' style='border:none; height:13px; vertical-align:bottom;'></a>";
    else unset($guild['gallery']);
    $guildlist .= "
    		<div class='shortinfo_".$florensia->change()."'>
			<table style='width:100%'><tr>
				<td style='width:40px; text-align:right;'>{$i}.</td>
				<td style='width:70px; text-align:right;'>{$guild['averagelevel']} ~</td>
				<td style='width:70px; text-align:right;'>{$guild['averagelandlevel']} <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'></td>
				<td style='width:70px; text-align:right;'>{$guild['averagesealevel']} <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'></td>
                                <td style='width:25px; text-align:right;'>".floclass_guild::get_language_pic($guild['misc_language'])."</td>
				<td style='padding-left:10px;'><a href='".$florensia->outlink(array("guilddetails", $guild['guildid'], $guild['server'], $guild['guildname']))."'>".$florensia->escape($guild['guildname'])."</a></td>
                                <td style='text-align:right; padding-right:10px;'>{$guild['gallery']}</td>
                                <td style='width:110px'><a href='".$florensia->outlink(array("statistics", $guild['server']))."'>".$florensia->escape($guild['server'])."</a></td>
				<td style='text-align:right; width:100px'>".$flolang->sprintf($flolang->guild_memberamount, $guild['memberamount'])."</td>
			</tr></table>
		</div>
    ";
}

$guildoverview = "

<div class='bordered' style='font-weight:bold; margin-bottom:15px;'>
	".$florensia->quick_select("guildranking", array(), array($flolang->statistic_ranking_quickselect_orderby=>$orderbyselect, $flolang->statistic_ranking_quickselect_filterserver=>$serverfilter, $flolang->statistic_ranking_quickselect_guildminmember=>$memberfilter))."
</div>

<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
<div class='subtitle' style='margin-bottom:7px;'>
    <table style='width:100%'><tr>
        <td style='width:40px; text-align:right;'>#</td>
	<td style='width:70px; text-align:right;'>~</td>
	<td style='width:70px; text-align:right;'><img src='{$florensia->layer_rel}/land.gif' style='height:13px;'></td>
	<td style='width:70px; text-align:right;'><img src='{$florensia->layer_rel}/sealv.gif' style='height:13px;'></td>
        <td style='width:25px;'></td>
	<td style='padding-left:10px;'>{$flolang->guild_title_guildname}</td>
        <td></td>
	<td style='width:110px'>{$flolang->guild_title_server}</td>
	<td style='text-align:right; width:100px'>{$flolang->guild_title_member}</td>
    </tr></table>
</div>
<div class='small'>{$guildlist}</div>
<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
";


    $content = "
    <div class='subtitle'><a href='{$florensia->root}/statistics'>{$flolang->statistic_sitetitle}</a> &gt; {$flolang->statistic_guildranking_sitetitle}</div>
    <div class='subtitle small' style='font-weight:normal; margin-bottom:15px;'>".$flolang->sprintf($flolang->statistic_notice_seealso, join(", ", $validserverlinks))."</div>
    {$jumptoguild}
    {$guildoverview}
    ";

$florensia->output_page($content);

?>
