<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("hundredfloor");

	$pageselect = $florensia->pageselect(100, array("100floor"), array(), 10);

	$count=$pageselect['pagestart']+1;
	$queryfloor = MYSQL_QUERY("SELECT * FROM server_100floor LIMIT ".$pageselect['pagestart'].",10");
	while ($floor = MYSQL_FETCH_ARRAY($queryfloor)) {

		$floorcontent .= $florensia->adsense(5);

		$floorcontent .= "<div class='subtitle' style='margin-top:20px;'>".$flolang->sprintf($flolang->tower_floortitle, $count)."</div>";
		for ($i=1; $i<=10; $i++) {
			if ($floor[$florensia->get_columnname("server_100floor_npcid{$i}", "misc")] == "#") continue;
			$npc = new floclass_npc($floor[$florensia->get_columnname("server_100floor_npcid{$i}", "misc")]);
			$floorcontent .="<div class='shortinfo_".$florensia->change()."'>".$npc->shortinfo(array(), array($flolang->tower_shortview_amount=>$floor[$florensia->get_columnname("server_100floor_npcamount{$i}", "misc")]))."</div>";
		}
		$count++;
	}
	$content = "
		<div style='text-align:center; margin-bottom:5px;'>".$florensia->quick_select('100floor', array('page'=>$_GET['page']), array(), array('namesselect'=>1))."</div>
		<div style='margin-bottom:5px;' class='subtitle'><a href='{$florensia->root}/misc.php'>{$flolang->mainmenu_misc}</a> &gt; 100 Floor Tower</div>
		<div style='margin-bottom:10px;'>".$pageselect['selectbar']."</div>
		$floorcontent
		<div style='margin-bottom:10px;'>".$pageselect['selectbar']."</div>
	";

	$florensia->sitetitle("100 Floor Tower");
	$florensia->output_page($content);

?>