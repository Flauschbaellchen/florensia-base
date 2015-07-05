<?PHP
require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("signature");
$sep = "-";


if (isset($_GET['siglang']) && !$_POST['do_create'])  { $_POST['siglang']=$_GET['siglang']; }
if ($_GET['allsignatures']==1 && !$_POST['do_create'])  { $_POST['get_all_signatures']="1";}
/*
 * slotselection
 */

$maxslots = 0;
$querysigslots = MYSQL_QUERY("SELECT count(id), characteramount FROM flobase_signature GROUP BY characteramount");
while ($sigslots = MYSQL_FETCH_ARRAY($querysigslots)) {
	$slotsoverview .= $flolang->sprintf($flolang->signature_slotsoverviewrow, $sigslots['count(id)'], $sigslots['characteramount'])."<br />";
	if ($maxslots<$sigslots['characteramount']) $maxslots=$sigslots['characteramount'];
}

if (isset($_GET['characters']) && !isset($_POST['do_create'])) {
	$i=1;
	foreach(explode($sep, $_GET['characters']) as $getchar) {
		if ($getchar=="") continue;
		$_POST['slot_'.$i] = $getchar;
		$i++;
	}
	$_POST['do_create']=true;
}

for ($i=1; $i<=$maxslots; $i++) {
		if (strlen($_POST['slot_'.$i])) {
			$characterslot = new class_character($_POST['slot_'.$i]);
			if (!$characterslot->is_valid()) {
				$errorcharnotice = $characterslot->get_errormsg();
			}
			else unset($errorcharnotice);
			if ($errorcharnotice) $errorcharnotice = " <span class='small' style='font-weight:normal;'><span style='color:#FF0000;'>(</span>{$errorcharnotice}<span style='color:#FF0000;'>)</span></span>";
		} else unset($errorcharnotice);
		
		$slotselect .= "<tr><td style='width:120px;'>".$flolang->sprintf($flolang->signature_slots, $i).":</td><td><input type='text' name='slot_{$i}' value='".$florensia->escape($_POST['slot_'.$i])."' maxlength='13' style='width:200px;'>{$errorcharnotice}</td></tr>";
}

/*
 * languageselection
 */
if ($flolang->lang[$_POST['siglang']]) $selected[$_POST['siglang']] = "selected='selected'";
foreach ($flolang->lang as $langkey => $langvalue) {
	if (!$flolang->lang[$langkey]->visible_flag) continue;
	$siglangselect .= "<option value='$langkey' ".$selected[$langkey].">".$flolang->lang[$langkey]->languagename."</option>";
}
$siglangselect = "
	<tr><td>{$flolang->signature_selectlang_title}</td><td>
		<select name='siglang'>
			<option value='0'>{$flolang->signature_selectlang_default}</option>
			$siglangselect
		</select>
	</td></tr>
";

/*
 * create signatures if do_create is set
 */

unset($create_slots, $sigoverview);
if (isset($_POST['do_create'])) {


	//get characters and slots
	for ($i=$maxslots; $i>0; $i--) {
		if ($create_slots OR $_POST['slot_'.$i]!="") {
			//-1 because array begins with 0
			if ($_POST['slot_'.$i]=="")  $_POST['slot_'.$i]="0";
			$create_slots[bcsub($i,1)]=$_POST['slot_'.$i];
		}
	}
	if (is_array($create_slots)) $create_slots = array_reverse($create_slots);

	//get all signatures?
	if ($_POST['get_all_signatures']=="1") {
		$bool = ">=";
		$checked = "checked='checked'";
		$pagelink_allsignatures = 1;
	}
	else {
		$bool = "=";
	}

	if (count($create_slots)>0) {
		if (!$flolang->lang[$_POST['siglang']]) $siglang = "0";
		else $siglang = $_POST['siglang'];

		$sigchars .= join($sep, $create_slots);

		$stringsig = "SELECT id, dimensions, author FROM flobase_signature WHERE characteramount{$bool}'".count($create_slots)."'";

		$pageselect = $florensia->pageselect(MYSQL_NUM_ROWS(MYSQL_QUERY($stringsig)), array("getsignature"), array("siglang"=>$siglang, "characters"=>$sigchars, "allsignatures"=>$pagelink_allsignatures), 10);

		$querysig = MYSQL_QUERY("$stringsig ORDER BY id LIMIT ".$pageselect['pagestart'].",10");
		while ($sig = MYSQL_FETCH_ARRAY($querysig)) {
			$sigoverview .= "
			<div class='small shortinfo_".$florensia->change()."'>
				<div style='margin-bottom:10px; border-bottom:1px solid; font-weight:bold;'>
					".$flolang->sprintf($flolang->signature_create_subtitle, $sig['dimensions'], $sig['author'])."
				</div>
				<div style='margin-bottom:10px;'><img src='".$florensia->signatureurl.$siglang.$sep.$sig['id'].$sep.urlencode($sigchars).".png' border='0' alt='Signature ".$sig['id']."'></div>
				<table style='width:100%'>
					<tr><td style='width:150px;'>{$flolang->signature_create_bcccode}</td><td><input type='text' readonly='readonly' style='width:99%' value='[url=http://www.florensia-base.com/characterdetails/".urlencode($sigchars)."][img]http://signature.florensia-base.com/".$siglang.$sep.$sig['id'].$sep.urlencode($sigchars).".png[/img][/url]'></td></tr>
					<tr><td style='width:150px;'>{$flolang->signature_create_htmlcode}</td><td><input type='text' readonly='readonly' style='width:99%' value='".$florensia->escape('<a href="http://www.florensia-base.com/characterdetails/'.urlencode($sigchars).'"><img src="http://signature.florensia-base.com/'.$siglang.$sep.$sig['id'].$sep.urlencode($sigchars).'.png" alt="Signature" border="0"></a>')."'></td></tr>
				</table>
			</div>	
			";
		}
		$sigoverview = "
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
			$sigoverview
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
		";
	}
}
else $checked = "checked='checked'";

$florensia->notice("<div class='warning' style='margin-bottom:10px;'>{$flolang->signature_api_failnotice}</div>");
$content = "
		<div class='subtitle' style='margin-bottom:10px;'>Characterdatabase &gt; Signatures</div>
		<div class='bordered' style='margin-bottom:10px;'>".str_replace('http://florensia-forumurl', $florensia->forumurl, $flolang->signature_instruction_howto)."</div>
		<div class='bordered' style='margin-bottom:10px;'>{$flolang->signature_instruction_advancedhint}</div>
		<div class='bordered' style='margin-bottom:30px;'>{$flolang->signature_slotsoverviewtitle}<br /><span class='small'>$slotsoverview</span></div>

		<div class='subtitle' style='margin-bottom:30px;'>
			<form action='{$florensia->root}/getsignature#signatures' method='post'>
				<table>
				$slotselect
				<tr><td colspan='2' style='height:5px;'></td></tr>
				$siglangselect
				<tr><td></td><td class='small' style='font-weight:normal;'><input type='checkbox' name='get_all_signatures' value='1' $checked style='padding:0px; margin:0px;'> {$flolang->signature_displayall}</td></tr>
				<tr><td colspan='2' style='height:5px;'></td></tr>
				<tr><td></td><td><input type='submit' name='do_create' style='width:200px;' value='{$flolang->signature_docreate}'></td></tr>
				</table>
				
			</form>
		</div>

		<a name='signatures'></a>
		$sigoverview
";


$florensia->sitetitle("Signature Generator");
$florensia->output_page($content);
?>
