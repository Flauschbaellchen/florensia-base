<?php
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("statistic,character,guild");
$florensia->sitetitle("Statistics");

$dbwhere = array();
$dblimit = 13;
if ($_GET['server'] && in_array($_GET['server'], $florensia->validserver)) {
    $statisticserver = $_GET['server'];
    $serverpagetitle = "&gt; ".$florensia->escape($_GET['server']); 
    $florensia->sitetitle($florensia->escape($_GET['server'])); 
}
else {
    unset($_GET['server'], $serverpagetitle);
    $statisticserver = "global";
}

    #load cached statistics into tmp variable
    $querystatistic = MYSQL_QUERY("SELECT content, statistic FROM flobase_statistics WHERE server='{$statisticserver}'");
    while ($tmpstatistic = MYSQL_FETCH_ARRAY($querystatistic)) {
        $statistic[$tmpstatistic['statistic']] = explode("\n", $tmpstatistic['content']);
    }
    
    
    foreach ($statistic['guild_mostmember'] as $cacheline) {
        # guildid, guild, server, amount
        $cacheline = explode("\t", $cacheline);
        $mostmemberguild .= "
        <tr>
            <td style='width:30px; text-align:center;'>{$cacheline[3]}</td>
            <td><a href='".$florensia->outlink(array('guilddetails', $cacheline[0], $cacheline[2], $cacheline[1]))."'>".$florensia->escape($cacheline[1])."</a></td>
            <td style='width:100px; text-align:right;'><a href='".$florensia->outlink(array('statistics', $cacheline[1]))."'>{$cacheline[2]}</a></td>
        </tr>";
    }
    
    foreach(array('guild_topland', 'guild_topsea') as $key) {
        foreach ($statistic[$key] as $cacheline) {
            # id, guildname, server, averagelandlevel, averagesealevel, memberamount
            $cacheline = explode("\t", $cacheline);
            $topguildlist[$key] .= "
            <tr>
                <td style='width:65px;'>{$cacheline[3]} <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'></td>
                <td style='width:65px;'>{$cacheline[4]} <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'></td>
                <td><a href='".$florensia->outlink(array('guilddetails', $cacheline[0], $cacheline[2], $cacheline[1]))."'>".$florensia->escape($cacheline[1])."</a></td>
                <td style='width:75px; text-align:left;'><a href='".$florensia->outlink(array('statistics', $cacheline[2]))."'>{$cacheline[2]}</a></td>
                <td style='width:80px; text-align:right;'>".$flolang->sprintf($flolang->guild_memberamount, $cacheline[5])."</td>
            </tr>";
        }
    }
    
    ########## $flolang->statistic_title_guildtopaveragelevel
    
    foreach(array('character_topland', 'character_topsea') as $key) {
        foreach ($statistic[$key] as $cacheline) {
            # charname, levelland, levelsea, guild, guildid, server
            $cacheline = explode("\t", $cacheline);
            $topcharlist[$key] .= "
            <tr>
                <td style='width:40px;'>{$cacheline[1]} <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'></td>
                <td style='width:40px;'>{$cacheline[2]} <img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'></td>
                <td><a href='".$florensia->outlink(array('characterdetails', $cacheline[0]))."'>{$cacheline[0]}</a></td>
                <td><a href='".$florensia->outlink(array('guilddetails', $cacheline[4], $cacheline[5], $cacheline[3]))."'>".$florensia->escape($cacheline[3])."</a></td>
                <td style='text-align:right;'><a href='".$florensia->outlink(array('statistics', $cacheline[5]))."'>{$cacheline[5]}</a></td>
            </tr>";
        }
    }
    /*
    foreach(array('character_toplevelerland', 'character_toplevelersea') as $key) {
        foreach ($statistic[$key] as $cacheline) {
            # charname, levelland, levelsea, guild, guildid, server, lastdifflevelland, lastdifflevelsea
            $cacheline = explode("\t", $cacheline);
            $topcharlist[$key] .= "
            <tr>
                <td style='width:75px;'>{$cacheline[1]} (+{$cacheline[6]}) <img src='{$florensia->layer_rel}/land.gif' style='height:11px;'></td>
                <td style='width:75px;'>{$cacheline[2]} (+{$cacheline[7]})<img src='{$florensia->layer_rel}/sealv.gif' style='height:11px;'></td>
                <td><a href='".$florensia->outlink(array('characterdetails', $cacheline[0]))."'>{$cacheline[0]}</a></td>
                <td><a href='".$florensia->outlink(array('guilddetails', $cacheline[4], $cacheline[5], $cacheline[3]))."'>".$florensia->escape($cacheline[3])."</a></td>
                <td style='text-align:right;'><a href='".$florensia->outlink(array('statistics', $cacheline[5]))."'>{$cacheline[5]}</a></td>
            </tr>";
        }
    }*/
    
    foreach ($statistic['character_classes'] as $cacheline) {
        # COUNT(charname), jobclass, gender
        $cacheline = explode("\t", $cacheline);
        if (!$cacheline[1]) continue;
        $characterclassestmp[$cacheline[1]][$cacheline[2]] = $cacheline[0];
    }
    
    foreach ($characterclassestmp as $jobclass => $genderamount) {
        $characterclasses .= "
        <tr>
            <td style='width:55px; text-align:right; padding-right:5px;'>".bcadd($genderamount['m'], $genderamount['f'])."</td>
            <td>{$jobclass}</td>
            <td>{$genderamount['f']} <img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='male' style='height:12px;'> + {$genderamount['m']} <img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'></td>
        </tr>";  
    }
    
    /*
    foreach(array("land", "sea") as $type) {
        foreach ($statistic['character_level'.$type] as $cacheline) {
            # COUNT(charname), jobclass, gender
            $cacheline = explode("\t", $cacheline);
            $characterclassestmp[$type][bcdiv($cacheline[1],10,0)][$cacheline[2]] = $cacheline[0];
        }
        
        foreach ($characterclassestmp[$type] as $level => $genderamount) {
            $topcharlist['character_level'.$type] .= "
            <tr>
                <td style='width:55px; text-align:right; padding-right:5px;'>".bcadd($genderamount['m'], $genderamount['f'])."</td>
                <td style='padding-left:10px; width:55px;'>".bcmul($level,10)."-".bcadd($level*10, 9)."</td>
                <td>{$genderamount['f']} <img src='{$florensia->layer_rel}/gender_female.gif' border='0' alt='male' style='height:12px;'> + {$genderamount['m']} <img src='{$florensia->layer_rel}/gender_male.gif' border='0' alt='male' style='height:12px;'></td>
            </tr>";  
        }
    }
            <tr><td style='height:10px;' colspan='3'></td></tr>
            <tr><td style='width:49%; vertical-align:top;'>
                <div class='subtitle'>Land</div>
                <table class='subtitle' style='width:100%'>{$topcharlist['character_levelland']}</table>
            </td><td></td><td style='width:49%; vertical-align:top;'>
                <div class='subtitle'>Sea</div>
                <table class='subtitle' style='width:100%'>{$topcharlist['character_levelsea']}</table>
            </td></tr>
            
    */
    
    foreach ($florensia->validserver as $server) {
        $validserverlinks[] = "<a href='".$florensia->outlink(array('statistics', $server))."'>{$server}</a>";
    }
    $content = "
    <div class='subtitle'>
        <a href='{$florensia->root}/statistics'>{$flolang->statistic_sitetitle}</a> {$serverpagetitle}<br />
        <span class='small' style='font-weight:normal'>".$flolang->sprintf($flolang->statistic_notice_createdon, date("m.d.Y - H:i", $statistic['statistic_timestamp'][0]))."</span>
    </div>
    
    <div class='subtitle small' style='font-weight:normal; margin-bottom:15px;'>".$flolang->sprintf($flolang->statistic_notice_seealso, join(", ", $validserverlinks))."</div>
    
    <div class='small'>
        <table style='width:100%'>
            <tr><td style='width:49%; vertical-align:top;'>
                <div style='float:right; margin-top:3px; margin-right:3px;'><a href='".$florensia->outlink(array('ranking'), array('order'=>'land'))."'>{$flolang->statistic_notice_watchfullranking}</a></div>
                <div class='subtitle'>{$flolang->statistic_title_chartopland}</div>
                <table class='subtitle' style='width:100%'>{$topcharlist['character_topland']}</table>
            </td><td></td><td style='width:49%; vertical-align:top;'>
                <div style='float:right; margin-top:3px; margin-right:3px;'><a href='".$florensia->outlink(array('ranking'), array('order'=>'sea'))."'>{$flolang->statistic_notice_watchfullranking}</a></div>
                <div class='subtitle'>{$flolang->statistic_title_chartopsea}</div>
                <table class='subtitle' style='width:100%'>{$topcharlist['character_topsea']}</table>
            </td></tr>";
 /*
            <tr><td style='height:10px;' colspan='3'></td></tr>
            <tr><td style='width:49%; vertical-align:top;'>
                <div class='subtitle'>{$flolang->statistic_title_chartoplevelerland}</div>
                <table class='subtitle' style='width:100%'>{$topcharlist['character_toplevelerland']}</table>
            </td><td></td><td style='width:49%; vertical-align:top;'>
                <div class='subtitle'>{$flolang->statistic_title_chartoplevelersea}</div>
                <table class='subtitle' style='width:100%'>{$topcharlist['character_toplevelersea']}</table>
            </td></tr>*/
$content .= "
            <tr><td style='height:10px;' colspan='3'></td></tr>
            <tr><td style='width:49%; vertical-align:top;'>
                <div class='subtitle'>{$flolang->statistic_title_charclasses}</div>
                <table class='subtitle' style='width:100%'>{$characterclasses}</table>
            </td><td></td><td style='width:49%; vertical-align:top;'>
                <div style='float:right; margin-top:3px; margin-right:3px;'><a href='".$florensia->outlink(array('guildranking'), array('order'=>'member'))."'>{$flolang->statistic_notice_watchfullranking}</a></div>
                <div class='subtitle'>{$flolang->statistic_title_guildmostmember}</div>
                <table class='subtitle' style='width:100%'>{$mostmemberguild}</table>
            </td></tr>

            <tr><td style='height:10px;' colspan='3'></td></tr>
            <tr><td style='width:49%; vertical-align:top;'>
                <div style='float:right; margin-top:3px; margin-right:3px;'><a href='".$florensia->outlink(array('guildranking'), array('order'=>'land'))."'>{$flolang->statistic_notice_watchfullranking}</a></div>
                <div class='subtitle'>{$flolang->statistic_title_guildtopland}</div>
                <table class='subtitle' style='width:100%'>{$topguildlist['guild_topland']}</table>
            </td><td></td><td style='width:49%; vertical-align:top;'>
                <div style='float:right; margin-top:3px; margin-right:3px;'><a href='".$florensia->outlink(array('guildranking'), array('order'=>'sea'))."'>{$flolang->statistic_notice_watchfullranking}</a></div>
                <div class='subtitle'>{$flolang->statistic_title_guildtopsea}</div>
                <table class='subtitle' style='width:100%'>{$topguildlist['guild_topsea']}</table>
            </td></tr>
        </table>
    </div>
    ";
    

$content .= '
<script src="'.$florensia->root.'/js/jquery.js" type="text/javascript"></script>
<script src="'.$florensia->root.'/js/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="'.$florensia->root.'/js/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
';


$data = json_decode(file_get_contents("{$florensia->root_abs}/api.statistics.json"));
foreach ($data as $sibling_key => $sibling_val) {
  $content .= '
  <div style="clear:both; margin-top:30px;"></div>
  <div id="'.$sibling_key.'" style="width:100%; height:500px;"></div>
  <div style="font-style:italic;">'.nl2br($florensia->escape($sibling_val->comment)).'</div>
  <script type="text/javascript">
    $(function () {
      $.plot($("#'.$sibling_key.'"),'; 
      $content .= json_encode($sibling_val->data).", ".json_encode($sibling_val->options);
      $content .= ');
    });
  </script>';
}

    
    $florensia->output_page($content);

?>
