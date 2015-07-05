<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

if (!$flouser->get_permission("add_coordinates")) { $florensia->output_page($flouser->noaccess()); }

$flolang->load("npc");

$maplist = $misc->get_maplist();
if (!$_POST['mapid']) $_POST['mapid'] = $maplist[0]['mapid'];
foreach ($maplist as $key => $map) {
	if ($map['maplink']==FALSE) continue;
	if ($map['mapid']==$_POST['mapid']) $selected="selected='selected'";
	else unset($selected);

	$selectmap .= "<option value='{$map['mapid']}' style='padding-left:".bcmul($map['submap'],20)."px;' {$selected}>{$map['mapname']}</option>";
}

$npc = new floclass_npc($_GET['npcid']);
$content_select = "
<div class='shortinfo_".$florensia->change()."'>".$npc->shortinfo()."</div>
<div style='margin-top:20px;' class='bordered small'>{$flolang->mapcoordinates_add_description}</div>

<div style='margin-top:20px; margin-bottom:20px;'>
	<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post' style='text-align:center'>
		<select name='mapid'>$selectmap</select> <input class='quicksubmit' type='submit' value=''>
	</form>
</div>
";


$querymap = MYSQL_QUERY("SELECT LTWH FROM server_map WHERE mapid='".mysql_real_escape_string($_POST['mapid'])."'");
if ($map = MYSQL_FETCH_ARRAY($querymap)) {

			$LTWH = explode(",", $map['LTWH']);

			$javascript['crosshair']='
			<script type="text/javascript">
				var MapInfoHeight = '.$LTWH[3].';
				var MapInfoLeft = '.$LTWH[0].';
				var MapInfoWidth = '.$LTWH[2].';
				var MapInfoTop = '.$LTWH[1].';

				var inputline = new Array()

						function setCross(cx,cy) {
							rxy = Math.round((x/512)*MapInfoWidth+MapInfoLeft) +"," + Math.round(((y/512)*MapInfoHeight-MapInfoTop)*(-1));
							inputline.push(rxy);
							setInput();
						}
										
						function setCords(e) {
							x = (document.all) ? window.event.x + document.body.scrollLeft : e.pageX;
							y = (document.all) ? window.event.y + document.body.scrollTop : e.pageY;
										
							x -= document.images.imgMap.offsetParent.offsetLeft;
							y -= document.images.imgMap.offsetParent.offsetTop;
						}

						function getCords() {
							if (x>0 && y>0) setCross(x,y);
						}

						function delCords(line) {
							inputline.splice(line,1);
							setInput();
						}

						function setInput() {
							document.getElementsByName("rxy")[0].value = inputline.join(";");
							html = "";
							for (var line in inputline) {
								var coords = inputline[line].split(",");

								coords_x = Math.round(((coords[0]-MapInfoLeft)/MapInfoWidth*512)-4);
								coords_y = Math.round(((MapInfoTop-coords[1])/MapInfoHeight*512)-4-8*line);

								html = html + "<div style=\"position:relative; left:"+coords_x+"px; top:"+coords_y+"px; z-index:4; width:8px; height:8px; background-image:url('.$florensia->layer_rel.'/MiniMapNpc_visible.png);\" onClick=\"delCords("+line+")\"></div>";
							}
							document.getElementById("finalline").innerHTML = html;
						}

						document.onmousemove = setCords;
			</script>
			';

	$content_map = "
	<div style='width:512px; height:530px; margin:auto;'>
				<div id='finalline' style='position:absolute; z-index:4;'>
				</div>
		<div style='height:512px; background-repeat:no-repeat; background-position:center; background-image:url({$florensia->root}/imageprotection.php?picture=map&amp;mapid=".$florensia->escape($_POST['mapid'])."&amp;npcs=".$florensia->escape($_GET['npcid']).");'>
			<table style='position:relative; margin:auto;'><tr><td>
				<img name='imgMap' onClick='getCords()' src='{$florensia->root}/imageprotection.php?picture=map' border='0' alt='Do not copy!' style='position:relative; z-index:1; vertical-align:bottom; text-align:center;'>
			</td></tr></table>
		</div>
	</div>
	<div style='text-align:center;'>
		<form action='".$florensia->outlink(array("npcdetails", $_GET['npcid'], $stringtable->get_string($_GET['npcid'])))."' method='post'>
		<input name='rxy' type='hidden'>
		<input name='mapid' type='hidden' value='".$florensia->escape($_POST['mapid'])."'>
		<input type='submit' name='do_save_coordinates' value='".$flolang->sprintf($flolang->mapcoordinates_add_submit, $florensia->escape($stringtable->get_string($_GET['npcid'])))."'>
		</form>
	</div>
	<script type='text/javascript'>document.getElementsByName(\"rxy\")[0].value = \"\";</script>
	";
}

$content = $content_select.$javascript['crosshair'].$content_map;

$florensia->sitetitle("Add NPC coordinates of ".$florensia->escape($stringtable->get_string($_GET['npcid'])));
$florensia->output_page($content);

?>
