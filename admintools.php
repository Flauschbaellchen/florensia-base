<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$tool = $_GET['tool'];

if (!$flouser->get_permission("access_admintools", $tool)) { $florensia->output_page($flouser->noaccess()); }
$florensia->sitetitle("Admin Tools");
$pagebar[] = "<a href='{$florensia->root}/admincp.php'>AdminCP</a>";
$pagebar[] = "<a href='{$florensia->root}/admintools.php'>Admin Tools</a>";
switch ($tool) {
	case "npcflags": {
		$florensia->sitetitle("NPC Flags");
		$pagebar[] = "NPC Flags";
		$pageselectlinkoptions = array();
		
		if (!in_array($_GET['show'], array("notimplemented", "event", "removed"))) {
			$dbwhere[] = "(flobase_addition.entrystatus='0' OR flobase_addition.entrystatus is NULL)";
		} else {
			$pageselectlinkoptions['show'] = $_GET['show'];
			$dbwhere[] = "flobase_addition.entrystatus LIKE '{$_GET['show']},%'";
		}
		if ($dbwhere) $dbwhere = "WHERE ".join(" AND ", $dbwhere);
		
		$querynpcstring = "FROM server_npc LEFT JOIN flobase_addition ON flobase_addition.classid=server_npc.".$florensia->get_columnname("npcid", "npc")." AND class='npc' {$dbwhere} ORDER BY name_{$stringtable->language}";
		list($amount) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(*) ".$querynpcstring));
		$pageselect = $florensia->pageselect($amount, array("admintools", "npcflags"), $pageselectlinkoptions);
		
		$querynpc = MYSQL_QUERY("SELECT * {$querynpcstring} LIMIT ".$pageselect['pagestart'].",".$florensia->pageentrylimit);
		while ($npc = MYSQL_FETCH_ARRAY($querynpc)) {
			$npc = new floclass_npc($npc);
			$npclist .= "
				<div class='shortinfo_".$florensia->change()."'>
					".$npc->shortinfo()."
				</div>
			";

		}
		
		$preselect[$_GET['show']] = "selected='selected'";
		$npcselect = "
			<select name='show'>
				<option value='0'>Normal</option>
				<option value='event' {$preselect['event']}>Event</option>
				<option value='notimplemented' {$preselect['notimplemented']}>Not Implemented</option>
				<option value='removed' {$preselect['removed']}>Removed</option>
			</select>			
		";
		$content = "
			<div class='subtitle'>".$florensia->quick_select("admintools", array("tool"=>"npcflags"), array("Show"=>$npcselect))."</div>
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
			{$npclist}
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
		";
		break;
	}
	default: {
		$content = "
		<div>
			<a href='".$florensia->outlink(array("admintools", "npcflags"))."'>NPC-Flags</a>
		</div>
		";
		
	}
}

$content = "
	<div class='subtitle' style='margin-bottom:10px;'>".join(" &gt; ", $pagebar)."</div>
	$content
";
$florensia->output_page($content);

?>