<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
if (!$flouser->get_permission("access_admincp") OR !$flouser->get_permission("create_sqldiff")) { $florensia->output_page($flouser->noaccess()); }

$florensia->sitetitle("AdminCP");
$florensia->sitetitle("Create Sql-Diff");

require_once("{$florensia->root_abs}/class_diff.php");
#-------------------------------------------------------------------------
#MYSQL-Diff
#-------------------------------------------------------------------------
$oldtable = "florensia_1323873988";


#$tablelist[0]['server_timestamp']['searchkey']="timestamp";

$tablelist[1]['server_100floor']['searchkey']=$florensia->get_columnname("server_100floor_stepid", "misc");
$tablelist[1]['server_100floor']['ignore']="id";

$tablelist[2]['server_exptable_land']['searchkey']=$florensia->get_columnname("server_exptable_land_level", "misc");
$tablelist[2]['server_exptable_land']['ignore']="id";

$tablelist[3]['server_exptable_sea']['searchkey']=$florensia->get_columnname("server_exptable_sea_level", "misc");
$tablelist[3]['server_exptable_sea']['ignore']="id";

$tablelist[4]['server_map']['searchkey']="mapid";

$tablelist[5]['server_npc']['searchkey']=$florensia->get_columnname("npcid", "npc");
$tablelist[5]['server_npc']['columnnametable']="npc";
$tablelist[5]['server_npc']['ignore']="id,npc_bossfactor";

$tablelist[6]['server_seal_breakcost']['searchkey']="costid";

$tablelist[7]['server_skill']['searchkey']=$florensia->get_columnname("skillid", "skill");
$tablelist[7]['server_skill']['ignore']="id";

#$tablelist[8]['server_spell']['searchkey']=$florensia->get_columnname("skillid", "spell");
#$tablelist[8]['server_spell']['ignore']="id";

#$tablelist[9]['server_storelist']['searchkey']="npcid";
#$tablelist[9]['server_storelist']['ignore']="id";

#$tablelist[10]['server_stringtable']['searchkey']="Code";

#$tablelist[11]['server_description']['searchkey']="Code";

$tablelist[12]['server_questlist']['searchkey']="questlistid";
$tablelist[12]['server_questlist']['ignore']="id,questtitle_EN,questtitle_ESP,questtitle_FR,questtitle_GER,questtitle_IT,questtitle_PT,questtitle_TR,questtitle_KR,questtitle_CN,questtitle_TW,questtitle_JP";
#$tablelist[12]['server_questlist']['parsexml']="questlistxml";

	$i=13;
	$queryitemtables = MYSQL_QUERY("SHOW TABLES FROM florensia LIKE 'server_item_%'");
	while ($itemtables = MYSQL_FETCH_ARRAY($queryitemtables)) {
		if ($itemtables[0]=="server_item_idtable" OR $itemtables[0]=="server_item_effect") continue;
		$tablelist[$i][$itemtables[0]]['searchkey']=$florensia->get_columnname("itemid", "item");
		$tablelist[$i][$itemtables[0]]['columnnametable']="item";
		$tablelist[$i][$itemtables[0]]['ignore']="id,name_Korea,name_Japan,name_English,name_German,name_Italian,name_Spanish,name_Portuguese,name_French,name_Turkish,name_China";
		$i++;
	}

	$queryitemtables = MYSQL_QUERY("SHOW TABLES FROM florensia LIKE 'server_questtext_%'");
	while ($itemtables = MYSQL_FETCH_ARRAY($queryitemtables)) {
		$tablelist[$i][$itemtables[0]]['searchkey']="questlistid";
		$tablelist[$i][$itemtables[0]]['ignore']="id";
		$i++;
	}
/* */
//NOT IMPLEMENTED: server_skilltrees, server_items_idtable, server_upgraderule, server_seal_option

	//missbrauch der cache-funktion -> einfacheres einschreiben und ggf. workarounds fuer keys mit nummern/sonderzeichen
	$sqldiff = new class_cache;
	$sqldiff->load_cache("sqldiff");
	$sqldiff->write_cache("timestamp",date("U"));

	$dbold = MYSQL_CONNECT($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpasswd'], TRUE) or die("No Database connection");
	MYSQL_SELECT_DB("$oldtable", $dbold) or die("No Database connection");
	MYSQL_QUERY("SET NAMES 'utf8'", $dbold);

	foreach ($tablelist as $globalkey => $value) {
		foreach ($tablelist[$globalkey] as $table => $tablesettings) {

			$diffentries = 0;
			$newentries = 0;
			unset($allentries, $exceptkeys, $content);
                        $allentries = array();
			$querytabledata = MYSQL_QUERY("SELECT * FROM $table ".$tablelist[$globalkey][$table]['dbwhere'], $db);
			while ($tabledata = MYSQL_FETCH_ASSOC($querytabledata)) {
	
				$querytabledata_old = MYSQL_QUERY("SELECT * FROM $table WHERE ".$tablelist[$globalkey][$table]['searchkey']."='".$tabledata[$tablelist[$globalkey][$table]['searchkey']]."'", $dbold);
				if ($tabledata_old = MYSQL_FETCH_ASSOC($querytabledata_old)) {
	
					foreach (explode(",",$tablelist[$globalkey][$table]['ignore']) as $ignorekey) {
						unset($tabledata[$ignorekey],$tabledata_old[$ignorekey]); 
					}
	
					$arraydiff = array_diff_assoc($tabledata, $tabledata_old);
					$arraydiff_old = array_diff_assoc($tabledata_old, $tabledata);
					if (count($arraydiff) OR count($arraydiff_old)) {

					$parsexml = explode(",",$tablelist[$globalkey][$table]['parsexml']);
	
						unset($diffcontent);
						$diffarray = array_merge($arraydiff, $arraydiff_old);
						foreach ($diffarray as $diffkey => $diffvalue) {
							if (!in_array($diffkey, $parsexml)) {
								if ($tablelist[$globalkey][$table]['columnnametable']) $diffkey_english = $florensia->get_columnname_backwards($diffkey,$tablelist[$globalkey][$table]['columnnametable']);
								else $diffkey_english = $diffkey;
	
								$sqldiff->write_cache("sql,{$globalkey},{$table},diff,".$tabledata[$tablelist[$globalkey][$table]['searchkey']].",{$diffkey},oldversion",$tabledata_old[$diffkey]);
								$sqldiff->write_cache("sql,{$globalkey},{$table},diff,".$tabledata[$tablelist[$globalkey][$table]['searchkey']].",{$diffkey},newversion",$tabledata[$diffkey]);
	
								$diffcontent .= "<tr>
												<td style='width:33%; border-right:1px solid;'>{$diffkey}::{$diffkey_english}</td>
												<td style='width:33%; border-right:1px solid;'>".$florensia->escape($tabledata_old[$diffkey])."</td>
												<td>".$florensia->escape($tabledata[$diffkey])."</td>
											</tr>
								";
							}
							else {
								$diff->diff_parse_xml(simplexml_load_string($tabledata_old[$diffkey]), simplexml_load_string($tabledata[$diffkey]), "sql,{$globalkey},{$table},diff,".$tabledata[$tablelist[$globalkey][$table]['searchkey']].",{$diffkey},__diffxmlparse__", $diffkey);
							}
						}
	
						$content['diff'] .= "<div class='bordered small' style='margin-top:7px;'>
										<b>DIFF</b>: $table/".$tablelist[$globalkey][$table]['searchkey']." => ".$stringtable->get_string($tabledata[$tablelist[$globalkey][$table]['searchkey']])." (".$tabledata[$tablelist[$globalkey][$table]['searchkey']].")<br />
										<table class='small' style='width:100%'>
										$diffcontent
										</table>								
									</div>";
						$diffentries++;
					}
	
				}
				else {
					//eintrag wurde neu angelegt
					$sqldiff->write_cache("sql,{$globalkey},{$table},new,".$tabledata[$tablelist[$globalkey][$table]['searchkey']],$tabledata[$tablelist[$globalkey][$table]['searchkey']]);
					$content['new'] .= "<div class='bordered small'><b>NEW</b> $table/".$tablelist[$globalkey][$table]['searchkey']." => ".$stringtable->get_string($tabledata[$tablelist[$globalkey][$table]['searchkey']])." (".$tabledata[$tablelist[$globalkey][$table]['searchkey']].")</div>";
					$newentries++;
				}
	
				$allentries[] = $tabledata[$tablelist[$globalkey][$table]['searchkey']];
			}
	
			//search for deleted entries
			unset($comma);
			foreach ($allentries as $searchkeys) {
				$exceptkeys .= $comma.$tablelist[$globalkey][$table]['searchkey']."!='".mysql_real_escape_string($searchkeys)."'";
				$comma = " AND ";
			}
                        if ($exceptkeys) $exceptkeys = "WHERE $exceptkeys";

			$querytabledata_old = MYSQL_QUERY("SELECT ".$tablelist[$globalkey][$table]['searchkey']." FROM $table $exceptkeys", $dbold);
			$delentries = MYSQL_NUM_ROWS($querytabledata_old);
			while ($tabledata_old = MYSQL_FETCH_ASSOC($querytabledata_old)) {
				$sqldiff->write_cache("sql,{$globalkey},{$table},del,".$tabledata_old[$tablelist[$globalkey][$table]['searchkey']],$tabledata_old[$tablelist[$globalkey][$table]['searchkey']]);
				$content['del'] .= "<div class='bordered small'><b>DEL</b> $table/".$tablelist[$globalkey][$table]['searchkey']." => ".$stringtable->get_string($tabledata_old[$tablelist[$globalkey][$table]['searchkey']])." (".$tabledata_old[$tablelist[$globalkey][$table]['searchkey']].")</div>";
			}

			#---------------
			//any diffs made? if yes than set settings for later use, if no than ignore whole table
			if ($diffentries OR $newentries OR $delentries) {
				$sqldiff->write_cache("sql,{$globalkey},{$table},settings,searchkey",$tablelist[$globalkey][$table]['searchkey']);
				$sqldiff->write_cache("sql,{$globalkey},{$table},settings,ignore",$tablelist[$globalkey][$table]['ignore']);
				$sqldiff->write_cache("sql,{$globalkey},{$table},settings,columnnametable",$tablelist[$globalkey][$table]['columnnametable']);
				$sqldiff->write_cache("sql,{$globalkey},{$table},settings,parsexml",$tablelist[$globalkey][$table]['parsexml']);
	
				$finalcontent .= "
					<div class='subtitle' style='margin-top:12px;'>{$table} Diff: $diffentries/New: $newentries/Del: $delentries</div>
					{$content['diff']}
					{$content['new']}
					{$content['del']}
				";
			}
	
		}
	}

	$sqldiff->export_cache("{$florensia->root_abs}/sqldiff_".$sqldiff->get_cache("timestamp").".php");
	$florensia->output_page($finalcontent);

?>
