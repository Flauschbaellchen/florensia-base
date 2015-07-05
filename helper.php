<?PHP
date_default_timezone_set("Europe/Berlin");
/*
function bin2float ($bin) {
   $bin = pack('l', $bin);
   $float = (float) 0;

   // Read Exponent and Sign (+/-)
   $exponent = ord ($bin{3});
   if ($sign = $exponent & 128) $exponent -= 128;
   $exponent <<= 1;

   // Read the remaining bit for Exponent and loop through Mantissa, calculating the Fraction
   $fraction = (float) 1;
   $div = 1;
   for ($x=2; $x>=0; $x--) {
       $byte = ord ($bin{$x});
       for ($y=7; $y>=0; $y--) {
         if ($x==2 && $y==7) {
           if ($byte & (1 << $y)) $exponent += 1;
         } else {
           $div *= 0.5;
           if ($byte & (1 << $y)) $fraction += $div;
         }
       }
   }

   // 0 value check
   if (!$exponent && $fraction == 1) return 0;

   // Final calc, returning the converted float
   $exponent -= 127;

   $float = pow (2, $exponent) * $fraction;
   if ($sign) $float = -($float);

   return $float;
}

/* */

function bin2float ($bin) {
	$erg = 0;
	$dual = base_convert($bin,10,2);
	while (strlen($dual)<32) {
		$dual = "0$dual";
	}			//	 11111111111111111111111
//$dual = "0"."00000000"."11010000100000001000000";
	echo "$bin<br />";
	echo "Dual: $dual - (".strlen($dual)."bits)<br />";
	
	$sign = substr($dual, 0,1);
		if ($sign=="1") $sign="-";
		else $sign="";
	$mantisse = substr($dual, 1,8);
	$fractional = substr($dual, 9);
	
	$decmantisse = base_convert($mantisse,2,10);
	$exp = $decmantisse-127;

 	echo "Sign: $sign<br />";
 	echo "Mantisse: $mantisse<br />";
 	echo "Fractional: $fractional<br />";
 	echo "Exponent: $exp<br /><br />";
	
	if ($exp==128 && $fractional=="11111111111111111111111") {echo $sign."∞"; return; }
	elseif ($exp==128 && $fractional!="11111111111111111111111"){ echo "-"; return; }

	if ($exp==0) {
		$frontfractional=1;
		$backfractional=$fractional;
	}
	elseif ($exp<0) {
		$frontfractional = 0;
		$backfractional = "1$fractional";
		for ($i=-1; $i>$exp; $i--) {
			$backfractional = "0$backfractional";
		}
	}
	else {
		$frontfractional = "1".substr($fractional,0,$exp);
		$backfractional = substr($fractional,$exp);
	}
echo "$frontfractional,$backfractional<br />";

	$norm_number = explode(',',"$frontfractional,$backfractional");
	for ($i=0; $i<=1; $i++) {
		$split_number = substr(chunk_split($norm_number[$i],1, ","), 0, -1);
		$split_number = explode(",", $split_number);
		if ($i==0) $hoch=count($split_number)-1;
		else $hoch=-1;
		foreach ($split_number as $key => $number) {
			$erg += $number*pow(2,$hoch);
echo "$number * 2^$hoch<br />";
			$hoch--;
		}
echo "--------<br />";
	}
/*
	for ($i=$exp; $i<-1; $i++) {
	$fractional = "0$fractional";
		echo "!0,$fractional<br />";
	}
*/
/*	
	if ($exp>=0) {
			$fractional_positive = chunk_split(substr($fractional,0,$exp+1),1, ",");
			$fractional_positive = explode(",", $fractional_positive);
	
			foreach ($fractional_positive as $key => $fractional_positive_number) {
				if ($fractional_positive_number=="") continue;
				$erg += $fractional_positive_number*pow(2,bcsub(count($fractional_positive),$key+2));
				//echo "$fractional_positive_number mal 2^".bcsub(count($fractional_positive),$key+2)."<br />";
			}
	}
	else { $exp=0; }
			$fractional_positive = chunk_split(substr($fractional,$exp),1, ",");
//var_dump($fractional_positive);
			$fractional_positive = explode(",", $fractional_positive);
	
			$i=-1;
			foreach ($fractional_positive as $key => $fractional_positive_number) {
				if ($i==-1) { $i--; continue; }
				elseif ($fractional_positive_number=="") continue;
				
				$erg += $fractional_positive_number*pow(2,$i);
//				echo "$fractional_positive_number mal ".pow(2,$i)."<br />";
				$i--;
			}
*/
 	echo "==&gt; ".$sign.round($erg,2);
}
/* */

/*	*	*
MK-Time
*	*	*/


if (!isset($_POST['tag'])) $_POST['tag']=date("d");
if (!isset($_POST['monat'])) $_POST['monat']=date("m");
if (!isset($_POST['jahr'])) $_POST['jahr']=date("Y");
if (!isset($_POST['std'])) $_POST['std']=date("H");
if (!isset($_POST['min'])) $_POST['min']=date("i");
if (!isset($_POST['sek'])) $_POST['sek']=date("s");

if (intval($_GET['float'])) $_POST['float'] = intval($_GET['float']);
echo "
<div style='height:20px;'></div>

<div style='float:left; width:50%;'>
	<form action='" . htmlentities($_SERVER['PHP_SELF']) . "' method='POST'>

	<div style='text-align:center; width:100%; border:1px solid #000000;'><div style='background-color:#4A4A4A; color:#FFFFFF;'>Float-Bin Converter</div>
		<div style='height:30px;'></div>
		<table style='text-align:center; width:100%;'>
			<tr><td>Float: <input type='TEXT' name='float' value='".htmlentities($_POST['float'])."'><input type='SUBMIT'></td></tr>
			<tr><td style='text-align:left;'>";
			if ($_POST['float']) $floaterg = bin2float($_POST['float']);
			echo "
			</td></tr>
		</table>
		<div style='height:30px;'></div>
	</div>
	</form>
</div>
<div style='margin-left:51%;'>
	<div style='text-align:center; border:1px solid #000000;'>
		<div style='background-color:#4A4A4A; color:#FFFFFF;'>Time Converter</div>
		<div>
			<form action='" . htmlentities($_SERVER['PHP_SELF']) . "' method='post'>
				<table style='width:100%;'>
					<tr><td style='vertical-align:top;'>
						Tag: <input type='Text' name='tag' size='4' value='" . htmlentities($_POST['tag']) . "'><br />
						Monat: <input type='Text' name='monat' size='4' value='" . htmlentities($_POST['monat']) . "'><br />
						Jahr: <input type='Text' name='jahr' size='4' value='" . htmlentities($_POST['jahr']) . "'><br />
						<br />
						Std: <input type='Text' name='std' size='4' value='" . htmlentities($_POST['std']) . "'><br />
						Min: <input type='Text' name='min' size='4' value='" . htmlentities($_POST['min']) . "'><br />
						Sek: <input type='Text' name='sek' size='4' value='" . htmlentities($_POST['sek']) . "'><br />
						<br />
						<input type='Submit'>
					</td><td style='vertical-align:top;'>
					mkTime: <input type='Text' name='mktime' size='10' value='" . mktime($_POST['std'], $_POST['min'], $_POST['sek'], $_POST['monat'], $_POST['tag'], $_POST['jahr'])."'><br />
					<br />
					<input type='Submit'>
					</td></tr>
					<tr><td>==&gt; ".mktime($_POST['std'], $_POST['min'], $_POST['sek'], $_POST['monat'], $_POST['tag'], $_POST['jahr'])."</td><td>".date("d.m.Y - H:i:s", $_POST['mktime'])."</td></tr>
				</table>
			</form>
		</div>
	</div>
	
</div>
";

?>