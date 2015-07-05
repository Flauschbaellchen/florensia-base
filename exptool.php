<?php
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("tools");

$userlevel = intval($_GET['userlevel']);
$levelrange = 4;
$exp = intval(str_replace(array(",", "."), "", $_GET['exp']));

# => more then +9 levels and you got always 0% exp

    $maplist = $misc->get_maplist();
    $selected[$_GET['mapid']]="selected='selected'";
    foreach ($maplist as $key => $map) {
        $map['mapid'] = "".$map['mapid'];
            if ($map['maplink']==FALSE) continue;
            if ($map['mapid']==$_GET['mapid']) { $querymap = $map['mapid']; }
            $selectmap .= "<option value='{$map['mapid']}' style='padding-left:".bcmul($map['submap'],20)."px;' {$selected[$map['mapid']]}>{$map['mapname']}</option>";
    }
    if ($_GET['inpc']) $inpcchecked="checked='checked'";
    if ($_GET['mapid']=="alligntower") $checkedallmapsigntower = "selected='selected'";
    $selectmap = "
        <select name='mapid'>
            <option value='all'>{$flolang->tools_exp_selectmap_all}</option>
            <option value='alligntower' {$checkedallmapsigntower}>{$flolang->tools_exp_selectmap_all_igntower}</option>
            {$selectmap}
        </select><br />
        <input type='checkbox' name='inpc' value='1' $inpcchecked style='margin:2px 2px 0px 0px;'><span class='small'>{$flolang->tools_exp_selectmap_invisiblenpc}</span>
    ";
    
$userlevelinput = "<input type='text' value='{$userlevel}' name='userlevel' style='width:50px;'>";
$npcsearchstring = "<input type='text' value='".$florensia->escape($_GET['npc'])."' name='npc'>";
$expinput = "<input type='text' value='{$exp}' name='exp' style='width:50px;'>";

if (!in_array($_GET['expclass'], array("Land", "Sea"))) $_GET['expclass'] = "Land";
$selectedexpclass[$_GET['expclass']] = "checked='checked'";
$selectexpclass = "
    <input type='radio' name='expclass' value='Land' style='margin:0px;' {$selectedexpclass['Land']}> <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'>
    <input type='radio' name='expclass' value='Sea' style='margin:0px;' {$selectedexpclass['Sea']}> <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'>
";

$selectednpctype[$_GET['npctype']] = "selected='selected'";
$selectedboss[$_GET['boss']] = "checked='checked'";
$classsearchselect = "<select name='npctype'>
			<option value='AllMonsters' ".$selectednpctype['AllMonsters'].">{$flolang->tools_exp_selectnpctype_all}</option>
			<option value='LandMonster' ".$selectednpctype['LandMonster'].">{$flolang->tools_exp_selectnpctype_land}</option>
			<option value='SeaMonster' ".$selectednpctype['SeaMonster'].">{$flolang->tools_exp_selectnpctype_sea}</option>
                    </select><br />
                    <input type='checkbox' value='1' name='boss' ".$selectedboss['1']." style='margin:2px 2px 0px 0px;'><span class='small'>{$flolang->tools_exp_selectbosslike}</span>
                ";
                
$npcoptions = "
<div style='padding:5px;'>
    <table>
        <tr><td style='width:150px;'>{$flolang->tools_exp_selectuserlevel}:</td><td>{$userlevelinput}</td></tr>
        <tr><td>{$flolang->tools_exp_selectnpc_title}:</td><td>{$npcsearchstring}</td></tr>
        <tr><td>{$flolang->tools_exp_selectnpctype_title}:</td><td>{$classsearchselect}</td></tr>
        <tr><td>{$flolang->tools_exp_selectmap_title}:</td><td>{$selectmap}</td></tr>
        <tr><td>{$flolang->global_select_langnames}</td><td>".$stringtable->get_select()."</td></tr>
    </table>
</div>";

$expoptions = "
<div style='padding:5px;'>
    <table>
        <tr><td style='width:100px;'>{$flolang->tools_exp_selectuserlevel}:</td><td>{$userlevelinput}</td></tr>
        <tr><td>EXP-Points:</td><td>{$expinput} {$selectexpclass}</td></tr>
    </table>
</div>";

if (strlen($_GET['npc']) && $userlevel && $_GET['npctype']) {

    $dbwhere = array();
    $dbwhere[] = "npc_file!='CitizenChar' AND npc_file!='GuardChar' AND npc_file!='GwChar' AND npc_file!='MerchantChar'";
    if (!preg_match('/^[0-9]+$/', $_GET['npc'])) {
        foreach (explode(" ", $_GET['npc']) as $keyword) {
            $searchstring[] = "name_{$stringtable->language} LIKE '%".get_searchstring($keyword,0)."%'";
        }
        $dbwhere[] = join(" AND ", $searchstring);
    } else {
        $dbwhere[] = $florensia->get_columnname("level", "npc").">".bcsub($_GET['npc'], $levelrange)." AND ".$florensia->get_columnname("level", "npc")."<".bcadd($_GET['npc'], $levelrange);
    }
    
    if ($_GET['npctype'] == "LandMonster") {
        $dbwhere[] = $florensia->get_columnname("fielddividing", "npc")." = '0'";        
    } elseif ($_GET['npctype'] == "SeaMonster") {
        $dbwhere[] = $florensia->get_columnname("fielddividing", "npc")." = '1'";
    }
    if (!$_GET['boss']) $dbwhere[] = "npc_bossfactor<=".floclass_npc::$bossfactor;
    
    $mapjoin = "LEFT JOIN flobase_npc_coordinates ON server_npc.".$florensia->get_columnname("npcid", "npc")." = flobase_npc_coordinates.npcid";
    if ($querymap) {
        if ($_GET['inpc']) $inpc = " OR flobase_npc_coordinates.mapid is NULL";
        $dbwhere[] = "(flobase_npc_coordinates.mapid='".mysql_real_escape_string($querymap)."'{$inpc})";
    } elseif (!$_GET['inpc']) {
        $dbwhere[] = "flobase_npc_coordinates.mapid is NOT NULL";
        if ($_GET['mapid']=="alligntower") $dbwhere[] = "flobase_npc_coordinates.mapid!='AT001_000'";
    } else unset($mapjoin);

    $dbwhere = "WHERE ".join(" AND ", $dbwhere);
    
    $querystringnpc = "SELECT * FROM server_npc {$mapjoin} {$dbwhere} ORDER BY ".$florensia->get_columnname("exp", "npc")." DESC, name_{$stringtable->language} LIMIT 30";
    $querynpc = MYSQL_QUERY($querystringnpc);
    while ($npc = MYSQL_FETCH_ARRAY($querynpc)) {
        $npcclass = new floclass_npc($npc);
	if (intval($npc[$florensia->get_columnname("fielddividing", "npc")])) $expclass = "sea";
	else $expclass = "land";
        
        //get the difference between npc and user
        $expdifference = $userlevel-$npc[$florensia->get_columnname("level", "npc")];
        if ($expdifference>=20) {
            $expprocent = "<span style='color:#ff0000;'>0%</span>";
        }
        else {
/*
# levelexp-arrays, thanks to LittleTom/tomg86
-x bis -20: 0%
-19 bis -15: 50%
-14 bis -13: 80%
-12 bis - 9: 100%
-8 bis -7: 105%
-6 bis +y: 110%

50, 50, 50, 50, 50
80, 80
100, 100, 100, 100
105, 105
*/
            if ($expdifference<=6) {
               $exprate = 110;
            } elseif ($expdifference<=8) {
               $exprate = 105;
            } elseif ($expdifference<=12) {
               $exprate = 100;
            } elseif ($expdifference<=14) {
               $exprate = 80;
            } elseif ($expdifference<=19) {
               $exprate = 50;
            }
            //get final exp of npc after include the leveldifference
            $npc[$florensia->get_columnname("exp", "npc")] = bcmul($npc[$florensia->get_columnname("exp", "npc")],bcdiv($exprate,100,2));
            $expprocent = $florensia->get_exp($npc[$florensia->get_columnname("exp", "npc")], $userlevel, $expclass, 4);
        }
    //    echo $expprocent."---".bcdiv(floatval($expprocent),1,4).$npc[$florensia->get_columnname("npcid", "npc")]."<br>";
        $expid = bcdiv(floatval($expprocent),1,4).$npc[$florensia->get_columnname("npcid", "npc")];
        while (strlen($expid) < 25) { $expid = "0$expid"; }
        $npclist[$expid] = $npcclass->shortinfo(array(), array($flolang->tools_exp_itemshortview_exp_title=>$expprocent." (".$npc[$florensia->get_columnname("exp", "npc")].")"));
    }
    if (!$npclist) $contentnpclist = "<div class='warning' style='text-align:center;'>{$flolang->tools_exp_error_nothingfound}</div>";
    else {
        krsort($npclist); //sort it by key in reverse order
        foreach ($npclist as $npc) {
            $contentnpclist .= $florensia->adsense(10)."<div class='shortinfo_".$florensia->change()."'>{$npc}</div>";
        }
    }
} elseif ($userlevel && $exp) {
    if ($_GET['expclass']=="Land") { $exptable="land"; $expclass = "<img src='{$florensia->layer_rel}/land.gif' style='height:11px;'>"; }
    else { $exptable="sea"; $expclass = "<img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'>"; }
    $contentexppoints = "<div class='shortinfo_1'>".$flolang->sprintf($flolang->tools_exp_points_result, $userlevel, $florensia->get_exp($exp, $userlevel, $exptable), $exp, $expclass)."</div>";
}


$content = "
<div class='small' style='float:right; text-align:right; font-style:italic; margin:2px 2px 0px 0px;'>{$flolang->tools_exp_titlenotice}</div>
<div class='subtitle' style='margin-bottom:5px;'>Tools &gt; {$flolang->tools_exp_title}</div>
<div class='bordered small' style='margin-bottom:10px;'>".$flolang->sprintf($flolang->tools_exp_description, $levelrange)."</div>

<div class='small' style='margin-bottom:10px;'>
    <table style='width:100%; border-spacing:2px;'><tr>
        <td class='bordered' style='width:60%; padding:2px;'>".$florensia->quick_select("exptool", array(), array("<b>{$flolang->tools_exp_select_title}</b>"=>$npcoptions))."</td>
        <td class='bordered' style='padding:2px;'>".$florensia->quick_select("exptool", array(), array("<b>{$flolang->tools_exp_select_title_points}</b>"=>$expoptions))."</td>
    </tr></table>
</div>
{$contentnpclist}
{$contentexppoints}
";

$florensia->sitetitle("EXP-Calculator");
$florensia->output_page($content);



?>