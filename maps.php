<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("map,npc");

/* set language to EN */
// $stringtable->language="English";
// $classquesttext->language="EN";
/* * */

if (isset($_GET['mapid'])) {

		$querymap = MYSQL_QUERY("SELECT LTWH, parentmap, mapid FROM server_map WHERE mapid='".mysql_real_escape_string($_GET['mapid'])."'");
		if ($map = MYSQL_FETCH_ARRAY($querymap)) {
			$queryparentmap = MYSQL_QUERY("SELECT parentmap, mapid, mappicture FROM server_map WHERE mapid='".$map['parentmap']."'");
			while ($parentmap = MYSQL_FETCH_ARRAY($queryparentmap)) {
				if (is_file($florensia->images_abs."/".strtolower($parentmap['mappicture']."_Minimap_{$classquesttext->language}.png"))) { $link = "<a href='".$florensia->outlink(array("mapdetails", $parentmap['mapid'], $stringtable->get_string($parentmap['mapid'])))."'>".$stringtable->get_string($parentmap['mapid'], array('protectionlink'=>1))."</a>"; }
				else { $link = $stringtable->get_string($parentmap['mapid'], array('protection'=>1)); }

				$parentmapbar = $link.$arrow.$parentmapbar;
				$arrow = " &gt; ";
				if ($parentmap['parentmap']) $queryparentmap = MYSQL_QUERY("SELECT parentmap, mapid, mappicture FROM server_map WHERE mapid='".$parentmap['parentmap']."'");
			}
			$parentmapbar .= $arrow.$stringtable->get_string($map['mapid']);

//NPC-pointes
			$LTWH = explode(",", $map['LTWH']);
			$MapInfoLeft = $LTWH[0];
			$MapInfoTop = $LTWH[1];
			$MapInfoWidth = $LTWH[2];
			$MapInfoHeight = $LTWH[3];
		
			$preselectednpcs = explode("-", $_GET['npcs']);
			$npclist = array('side'=>array(), 'below'=>array());
			$npctable = "side";
			$contributedusers = array();
			$querynpccoords = MYSQL_QUERY("SELECT coordinates, npcid, npc_bossfactor, npc_picture, ".$florensia->get_columnname("level", "npc").", server_stringtable.{$stringtable->language} AS name FROM flobase_npc_coordinates, server_npc LEFT JOIN server_stringtable ON (server_stringtable.Code=server_npc.".$florensia->get_columnname("npcid", "npc").") WHERE (flobase_npc_coordinates.npcid=server_npc.".$florensia->get_columnname("npcid", "npc").") AND mapid='".mysql_real_escape_string($_GET['mapid'])."' ORDER BY name");
			for ($i=0; $npccoords = MYSQL_FETCH_ARRAY($querynpccoords); $i++) {
				$npccoords['coordinates'] = explode(";", $npccoords['coordinates']);
				$divcolor = $florensia->maptoolcolors[$i%count($florensia->maptoolcolors)];
				
				if (in_array($npccoords['npcid'], $preselectednpcs)) {
						$visible = "block";
						$checked = "checked='checked'";
						$selectednpcs[] = $npccoords['npcid']; #twice to make sure no invalid IDs are puhsed into the javascript-generated link
				}
				else {
						$visible = "none";
						unset($checked);
				}
					
				if ($npccoords['npc_bossfactor']>floclass_npc::$bossfactor) $npcname = "<span style='color:rgb(".floclass_npc::$bosscolor.");'>".$florensia->escape($npccoords['name'])."</span>";
				else $npcname = $florensia->escape($npccoords['name']);
				foreach ($npccoords['coordinates'] as $coordsvalue) {
					$coordsvalue = explode(",", $coordsvalue);
					$x = $coordsvalue[0];
					$y = $coordsvalue[1];
					$contributeduser[] = $coordsvalue[2];
					
					$left = round(($x-$MapInfoLeft)/$MapInfoWidth*512)-4;
					$top = round(($MapInfoTop-$y)/$MapInfoHeight*512)-4;

				if ($flouser->get_permission("access_admincp")) $moderationcoordnotice = "<br/>{$coordsvalue[0]},{$coordsvalue[1]}<br/>".$flouserdata->get_username($coordsvalue[2])." [{$coordsvalue[2]}]";
				else unset($moderationcoordnotice);
				
					$npcjavadivs .= "
						<div name='".$npccoords['npcid']."' style='border:1px solid; display:{$visible}; position:absolute; left:{$left}px; top:{$top}px; z-index:4; width:6px; height:6px; background-color:rgb($divcolor);' ".popup("<div class='small' style='background-color: #496f96; padding-left:2px;'>{$npcname} ({$flolang->npc_shortinfo_level} ".$npccoords[$florensia->get_columnname("level", "npc")]."){$moderationcoordnotice}</div>", "CENTER")."></div>
					";
				}
				
				if ($i>12) $npctable = "below"; #12 npcs are on the right of the map, all other below
				$npclist[$npctable][] = "
					<div class='bordered' style='border-color:rgb({$divcolor}); margin-bottom:8px; min-height:30px;'>
						<div style='float:left; width:20px; margin-top:5px;'>
								<input id='check_{$npccoords['npcid']}' type='checkbox' value='1' {$checked} onchange='switchcoordinates(\"{$npccoords['npcid']}\")'>
						</div>
						<div style='float:left; width:30px;'>
								<a href='".$florensia->outlink(array("npcdetails", $npccoords['npcid'], $npccoords['name']))."'>".$florensia->pictureprotection($npccoords['npc_picture'], $npccoords['name'], "monster", array('maxheight'=>30))."</a>
						</div>
						<div style='margin-left:55px;'>
								<a href='".$florensia->outlink(array("npcdetails", $npccoords['npcid'], $npccoords['name']))."'>{$npcname}</a><br />
								<span class='small'>{$flolang->npc_shortinfo_level} ".$npccoords[$florensia->get_columnname("level", "npc")]."</span>
						</div>
					</div>
				";
			}
			
			#npc table below map
			unset($npctable);
			$i=-1;
			foreach($npclist['below'] as $npc) {
				$i++;
				$npctable[$i%3] .= $npc;
			}
			if ($i>=0) {
				$npctable = "
				<div>
				    <table style='width:100%;'>
						<tr><td style='width:33%;'>{$npctable[0]}</td><td style='width:33%;'>{$npctable[1]}</td><td>{$npctable[2]}</td></tr>
				    </table>
				</div>";
			} else unset($npctable);

			#contributed by list
			if (is_array($contributeduser)) {
				foreach (array_unique($contributeduser) as $userid) {
					$contributedlist[] = $flouserdata->get_username(intval($userid));
				}
				$contributedlist = "<div class='bordered small' style='margin-top:10px;'>{$flolang->contributedby} ".join(", ", $contributedlist)."</div>";
			}
			
			// <div class='bordered small' style='margin-bottom:5px;'>{$flolang->map_display_javamouseover_tipp}</div>
			$content = "
				<script type=\"text/javascript\">
				function unique(a) {
				   var r = new Array();
				   o:for(var i = 0, n = a.length; i < n; i++) {
				      for(var x = 0, y = r.length; x < y; x++) {
					 if(r[x]==a[i]) continue o;
				      }
				      r[r.length] = a[i];
				   }
				   return r;
				}";
				if (count($selectednpcs)) {
						$content .= "var npclink = \"".join("-", $selectednpcs)."\".split(\"-\");";
						$predefinedpermlink = $florensia->outlink(array('mapdetails', $_GET['mapid'], $stringtable->get_string($_GET['mapid'])), array('npcs'=>join("-", $selectednpcs)));
				}
				else {
						$content .= "var npclink = new Array();";
						$predefinedpermlink = $florensia->outlink(array('mapdetails', $_GET['mapid'], $stringtable->get_string($_GET['mapid'])));
				}

				$content .= "				
					function switchcoordinates(npcid) {				
						if (!document.getElementsByName(npcid)[0]) return;
						for (var divnumber in document.getElementsByName(npcid)) {
							var test = divnumber.search(/^[0-9]+$/);
							if (test == -1) continue;
							document.getElementsByName(npcid)[divnumber].style.display = document.getElementById(\"check_\"+npcid).checked ? \"block\" : \"none\";
						}
						if (document.getElementById(\"check_\"+npcid).checked) {
						        npclink.push(npcid)
							npclink = unique(npclink);
						} else {
							if (npclink.indexOf(npcid)>-1) npclink.splice(npclink.indexOf(npcid),1);
						}
						if (npclink.length>0) {
							document.getElementById('npclink').value=document.URL.split(\"?\")[0] + \"?npcs=\"+npclink.join(\"-\");
						} else {
							document.getElementById('npclink').value=document.URL.split(\"?\")[0];
						}
					}
				</script>
				<div style='margin-bottom:5px; text-align:center;'>".$florensia->quick_select('mapdetails', array('mapid'=>$map['mapid']), array(), array('namesselect'=>1))."</div>
				<div style='margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("mapoverview"))."'>Mapoverview</a> &gt; $parentmapbar</div>
				
				<div style='text-align:center; margin-bottom:5px; margin-top:2px;'><input id='npclink' type='text' style='width:98%;' readonly='readonly' value='{$predefinedpermlink}'></div>
				<div style='width:34%; float:right;'>".join("\n", $npclist['side'])."</div>
				<div style='width:512px; height:520px;'>
					<div id='finalline' style='position:absolute; z-index:4;'>
						{$npcjavadivs}
					</div>
					<div style='height:512px; background-repeat:no-repeat; background-position:center; background-image:url({$florensia->root}/imageprotection.php?picture=map&amp;mapid=".$florensia->escape($map['mapid']).");'>
						<table style='position:relative; margin:auto;'><tr><td>
							<img src='{$florensia->root}/imageprotection.php?picture=map' border='0' alt='Do not copy!' style='position:relative; z-index:1; vertical-align:bottom; text-align:center;'>
						</td></tr></table>
					</div>
				</div>
				{$npctable}
				{$contributedlist}
				".$classusernote->display($map['mapid'], "map");
			
			
// <div class='warning' style='text-align:center'>{$flolang->coordnotice}</div>

			$florensia->sitetitle("Mapdetails");
			$florensia->sitetitle($stringtable->get_string($map['mapid']));
			$florensia->output_page($content);

		}
		else { header("Location: ".$_SERVER['PHP_SELF']); }

}
else {

		if ($flocache->get_cache("maplisthtml,content,{$stringtable->language}") == FALSE) {
			$maplist= $misc->get_maplist();
			foreach ($maplist as $key => $map) {
				if ($map['maplink']==FALSE) $map['maplink'] = $stringtable->get_string($map['mapid'], array('protection'=>1));
				$content .= "<div style='padding-left:".bcmul($map['submap'],40)."px;'>&uarr; {$map['maplink']}</div>";
			}
			$flocache->write_cache("maplisthtml,content,{$stringtable->language}", $content);
		}
		else $content = $flocache->get_cache("maplisthtml,content,{$stringtable->language}");

		$content = "
				<div style='margin-bottom:5px; text-align:center;'>".$florensia->quick_select('mapoverview', array(), array(), array('namesselect'=>1))."</div>
				{$content}
		";

		$florensia->sitetitle("Maps Overview");
		$florensia->output_page($content);
}

?>