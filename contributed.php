<?php
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
$userid = intval($_GET['userid']);

if (!$userid) {
    if (!$flouser->userid) $florensia->output_page($flouser->noaccess());
    else header("Location: ".$florensia->outlink(array('contributed', $flouser->userid, $flouser->user['username']), array(), array("escape"=>FALSE)));
}

if (!$flouser->userid OR (($userid!=$flouser->userid) && !$flouser->get_permission("mod_coordinates") && !$flouser->get_permission("mod_droplist", "contributed") && !$flouser->get_permission("mod_usernotes"))) { $florensia->output_page($flouser->noaccess()); }

$flolang->load("contributed");


$contributeduser = new class_user($userid);
if (!$contributeduser->userid) $florensia->output_page("<div style='text-align:center;'>{$flolang->contributed_error_nosuchuser}</div>");
$username = $contributeduser->user['username'];

if ($userid==$flouser->userid OR $flouser->get_permission("mod_coordinates")) $tabbar['npccoords'] = array("anchor"=>"npccoords", "name"=>$flolang->contributed_tabbar_title_npccoords);
if ($userid==$flouser->userid OR $flouser->get_permission("mod_droplist", "contributed")) $tabbar['droplist'] = array("anchor"=>"droplist", "name"=>$flolang->contributed_tabbar_title_droplist);
if ($userid==$flouser->userid OR $flouser->get_permission("mod_usernotes")) $tabbar['usernotes'] = array("anchor"=>"usernotes", "name"=>$flolang->contributed_tabbar_title_usernotes);
if ($userid==$flouser->userid OR $flouser->get_permission("watch_log")) $tabbar['flags'] = array("anchor"=>"flags", "name"=>$flolang->contributed_tabbar_title_flags);

if ($userid==$flouser->userid OR $flouser->get_permission("mod_coordinates")) {
    $querymaps = MYSQL_QUERY("SELECT npcid, mapid FROM flobase_npc_coordinates WHERE contributed = '{$userid}' OR contributed LIKE '{$userid},%' OR contributed LIKE '%,{$userid}' ORDER BY mapid");
    $tabbar['npccoords']['desc'] = $flolang->sprintf($flolang->contributed_tabbar_desc_npccoords, MYSQL_NUM_ROWS($querymaps));
    while ($maps = MYSQL_FETCH_ARRAY($querymaps)) {
        $maplistcontent .= "
            <div class='small shortinfo_".$florensia->change()."'>
                <div style='float:left; width:300px;'><a href='".$florensia->outlink(array('npcdetails', $maps['npcid'], $stringtable->get_string($maps['npcid'])))."'>".$stringtable->get_string($maps['npcid'], array('protectionlink'=>1, 'protectionsmall'=>1))."</a></div>
                <div style='margin-left:310px;'><a href='".$florensia->outlink(array('mapdetails', $maps['mapid'], $stringtable->get_string($maps['mapid'])))."'>".$stringtable->get_string($maps['mapid'], array('protectionlink'=>1, 'protectionsmall'=>1))."</a></div>
            </div>
        ";
    }
    $maplistcontent = "
        <a name='npccoords'></a>
        <div name='npccoords'>
            {$maplistcontent}
        </div>
    ";
}

if ($userid==$flouser->userid OR $flouser->get_permission("mod_droplist", "contributed")) {

    $querydroplist = MYSQL_QUERY("SELECT i.{$stringtable->language} as itemname,
                                    n.{$stringtable->language} as npcname,
                                    d.thumpsup,
                                    d.thumpsdown,
                                    d.npcid,
                                    d.itemid,
                                    d.dropid,
                                    r.rating,
                                    r.timestamp,
                                    npc.*
                                FROM flobase_droplist as d,
                                    flobase_droplist_ratings as r,
                                    server_stringtable as n,
                                    server_stringtable as i,
                                    server_npc as npc
                                WHERE d.dropid=r.dropid
                                    AND r.userid='{$userid}'
                                    AND n.Code=d.npcid
                                    AND i.Code=d.itemid
                                    AND d.npcid=npc.".$florensia->get_columnname("npcid", "npc")."
                                ORDER BY npcname, itemname");
    $tmpnpc = "";
    $droplistcontent = "";
    $amountnpcs = 0;
    $amountvotes = MYSQL_NUM_ROWS($querydroplist);
    while ($droplist = MYSQL_FETCH_ARRAY($querydroplist)) {
        if ($tmpnpc != $droplist['npcid']) {
            $npc = new floclass_npc($droplist);
            $droplistcontent .= "<div class='shortinfo_0' {$margintop}>".$npc->shortinfo()."</div>";
            $amountnpcs++;
            $tmpnpc = $droplist['npcid'];
            $margintop = "style='margin-top:20px;'";
        }
        
	if ($droplist['rating']>0) { $uservote = "<img src='{$florensia->layer_rel}/thumpsup.gif' alt='ThumpsUp' style='width:10px; height:13px;'>"; }
	else { $uservote = "<img src='{$florensia->layer_rel}/thumpsdown.gif' alt='ThumpsDown' style='width:10px; height:13px;'>"; }
                
        $outlink = $florensia->outlink(array('itemdetails', $droplist['itemid'], $stringtable->get_string($droplist['itemid'])));
        $droplistcontent .= "
                    <div style='margin-left:20px;' class='shortinfo_1'>
                        $uservote {$droplist['thumpsup']}/{$droplist['thumpsdown']} - ".date("m.d.y", $droplist['timestamp'])." <a href='{$outlink}'>".$florensia->escape($droplist['itemname'])."</a>
                    </div>
        ";
    }
    
    $tabbar['droplist']['desc'] = $flolang->sprintf($flolang->contributed_tabbar_desc_droplist, $amountvotes, $amountnpcs);
   
    $droplistcontent = "
        <a name='droplist'></a>
        <div name='droplist' class='small'>
            {$droplistcontent}
        </div>
    ";
}


if ($userid==$flouser->userid OR $flouser->get_permission("mod_usernotes")) {
    if ($userid!=$flouser->userid) {
        if (!in_array("*", $flouser->get_permission("mod_usernotes"))) {
            foreach ($flouser->get_permission("mod_usernotes") as $lang) {
               $noteslanguages[] = "language='{$lang}'";
            }
            $noteslanguages = "AND (".join(" OR ", $noteslanguages).")";
        }
    }
    
    $querystring = "SELECT id FROM flobase_usernotes WHERE userid='{$userid}' {$noteslanguages}";
    $querynoteslist = MYSQL_QUERY($querystring);
    $amountnotes = MYSQL_NUM_ROWS($querynoteslist);
    
    $tabbar['usernotes']['desc'] = $flolang->sprintf($flolang->contributed_tabbar_desc_usernotes, $amountnotes);
    $pageselect = $florensia->pageselect($amountnotes, array("contributed", $userid, $username), array('anchor'=>'usernotes'), 10);
    
    $querynotes = MYSQL_QUERY($querystring." ORDER BY section, dateline DESC LIMIT ".$pageselect['pagestart'].",10");
    while ($notes = MYSQL_FETCH_ARRAY($querynotes)) {
	$noteslistcontent .= $classusernote->get_entry($notes['id']);
    }
    
    
    $noteslistcontent = "
        <a name='usernotes'></a>
        <div name='usernotes' class='small'>
            <div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
            {$noteslistcontent}
            <div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
        </div>
    ";
}



if ($userid==$flouser->userid OR $flouser->get_permission("watch_log")) {

    $querystring = "SELECT class, classid FROM flobase_addition WHERE entrystatus LIKE '%,{$userid},%'";
    $flagsquery = MYSQL_QUERY($querystring);
    $amountflags = MYSQL_NUM_ROWS($flagsquery);
    
    $tabbar['flags']['desc'] = $flolang->sprintf($flolang->contributed_tabbar_desc_flags, $amountflags);
    $pageselect = $florensia->pageselect($amountflags, array("contributed", $userid, $username), array('anchor'=>'flags'), 20);
    
    $flagsquery = MYSQL_QUERY($querystring." ORDER BY class LIMIT ".$pageselect['pagestart'].",20");
    while ($flags = MYSQL_FETCH_ARRAY($flagsquery)) {
        switch ($flags['class']) {
            case "npc": {
                $npc = new floclass_npc($flags['classid']);
                $flagscontent .= "<div class='shortinfo_".$florensia->change()."'>".$npc->shortinfo()."</div>";
                break;
            }
            case "item": {
                $item = new floclass_item($flags['classid']);
                $flagscontent .= "<div class='shortinfo_".$florensia->change()."'>".$item->shortinfo()."</div>";
                break;
            }
            case "quest": {
                $flagscontent .= "<div class='shortinfo_".$florensia->change()."'>".$classquest->get_shortinfo($flags['classid'])."</div>";
                break;
            }
        }
    }
    $flagscontent = "
        <a name='flags'></a>
        <div name='flags'>
            <div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
            {$flagscontent}
            <div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
        </div>
    ";
}





$tabbar = $florensia->tabbar($tabbar);
$content = "
    <div style='margin-bottom:5px;' class='subtitle'>".$flouserdata->get_username($contributeduser->userid)." &gt; {$flolang->contributed_pagetitle}</div>
    {$tabbar['tabbar']}
    {$maplistcontent}
    {$droplistcontent}
    {$noteslistcontent}
    {$flagscontent}
    {$tabbar['jscript']}
";

$florensia->sitetitle($flolang->contributed_pagetitle);
$florensia->sitetitle($florensia->escape($username));
$florensia->output_page($content);
?>