<?PHP

class class_cache {
	var $cache_changed=array();
	var $cacheid;
	var $splitted=false;

	function write_cache($flocachevar, $flocachevalue) {
		$flocachevarstring = '$this->cache';
		$vararray = explode(",",$flocachevar);
		$changedvar = $vararray[0];
		if ($this->splitted) {
			$flocachevarstring.="['{$vararray[0]}']";
			$changedvar = $vararray[0];
			array_shift($vararray);
		}
		foreach ($vararray as $key => $flocachevar) {
			$flocachevarstring .="->".$this->escape_cachevar($flocachevar);
		}
		if (eval("$flocachevarstring = \" ".strtr($flocachevalue,array('"'=>'\"'))."\";")==NULL) { //space workaround for $this->object = "";
			array_push($this->cache_changed, $changedvar);
			return true;
		}
		else return false;
	}

	function get_cache($flocachevar, $asstring=TRUE) {
		$flocachevarstring = '$this->cache';
		$vararray = explode(",",$flocachevar);
		if ($this->splitted) {
			if (!isset($this->cache[$vararray[0]])) $this->load_splitcache($vararray[0]);
			$flocachevarstring.="['{$vararray[0]}']";
			array_shift($vararray);
		}
		foreach ($vararray as $key => $flocachevar) {
			$flocachevarstring .="->".$this->escape_cachevar($flocachevar);
		}
		if (!eval("if (!isset($flocachevarstring)) { return false; } else { \$var = $flocachevarstring; return true;} ")) return false;

		if ($asstring) { return "".trim($var); } //trim workaround for $this->object = "";
		else { return get_object_vars($this->trim_objectvalues($var, $var)); }
	}

	function escape_cachevar($var) {
		if (preg_match('/^[0-9]+/', $var)) $var = "number".$var;
		return strtr($var, array('%'=>'procent', ' ' => '_', "'"=>"apostrophe", '"'=>"twoapostrophe", '#'=>'rhomb', '.'=>'point'));
	}

	function trim_objectvalues($trimvar, $returnvar, $echo="") {
		foreach (get_object_vars($trimvar) as $varkey => $varvalue) {
			if (!strlen("".$varvalue)) { $this->trim_objectvalues($varvalue, $returnvar, "$echo->$varkey"); }
			else { eval("\$returnvar{$echo}->{$varkey} =  trim(\"\".\$varvalue);");	}
		}
		return $returnvar;
	}

	function save_cache() {
		if (!count($this->cache_changed)) return true;
		if (!$this->splitted) {
			if (MYSQL_QUERY("UPDATE cache_main SET cachedata='".mysql_real_escape_string($this->cache->asXML())."', lastchanged='".date("U")."' WHERE cacheid='{$this->cacheid}'")) return true;
			return false;
		}
		else {
			foreach ($this->cache as $splitcache => $val) {
				if (in_array($splitcache, $this->cache_changed)) {
					if (!MYSQL_QUERY("UPDATE cache_main SET cachedata='".mysql_real_escape_string($this->cache[$splitcache]->asXML())."', lastchanged='".date("U")."' WHERE cacheid='{$this->splitted}:{$splitcache}'")) {
						$error = true;
					}
				}
			}
			if ($error) return false;
			return true;
		}
	}

	function remove_cache() {
		if (MYSQL_QUERY("DELETE FROM cache_main WHERE cacheid='{$this->cacheid}'")) return true;
		return false;
	}

	function export_cache($file) {
		if ($this->cache->asXML($file)) return true;
		return false;
	}

	function load_cachefile($file) {
		if (!is_file($file)) return false;
		$this->cache = simplexml_load_file($file);
		if (!$this->cache) {
			$this->cache = new SimpleXMLElement("<Cache></Cache>");
			return false;
		}
		return true;
	}

	function load_cache($id="maincache") {
		$this->cacheid=$id;

		if ($cache = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT cachedata FROM cache_main WHERE cacheid='{$this->cacheid}' LIMIT 1"))) {
			$this->cache = simplexml_load_string($cache['cachedata']);
			if (!$this->cache) {
				$this->cache = new SimpleXMLElement("<Cache></Cache>");
			}
		} else {
			$this->cache = new SimpleXMLElement("<Cache></Cache>");
			MYSQL_QUERY("INSERT INTO cache_main (cacheid, cachedata, lastchanged) VALUES('{$this->cacheid}', '".mysql_real_escape_string($this->cache->asXML())."', '".date("U")."')");
		}
	}

	function load_splitcache($splitid) {
		if ($cache = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT cachedata FROM cache_main WHERE cacheid='{$this->splitted}:{$splitid}' LIMIT 1"))) {
			$splitcache = simplexml_load_string($cache['cachedata']);
			if (!$splitcache) {
				$splitcache = new SimpleXMLElement("<Cache></Cache>");
			}
		} else {
			$splitcache = new SimpleXMLElement("<Cache></Cache>");
			MYSQL_QUERY("INSERT INTO cache_main (cacheid, cachedata, lastchanged) VALUES('{$this->splitted}:{$splitid}', '".mysql_real_escape_string($splitcache->asXML())."', '".date("U")."')");
		}
		$this->cache[$splitid]=$splitcache;
	}
}
//cache
$flocache = new class_cache;
$flocache->splitted="maincache";
// $flocache->load_cache("maincache");

function create_characterkey() {
	$set = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
	$str = '';
	for($i = 1; $i <= mt_rand(20,32); ++$i) {
		$str .= $set[mt_rand(0, count($set)-1)];
	}
	return strtolower($str);
}

function get_geltstring($gelt, $amount=0) {
	if (!$amount) return number_format($gelt, 0, '.', ',');
	else return $amount."x".number_format($gelt, 0, '.', ',');
}

function get_searchstring($string, $regexp=1) {
	global $florensia;
	$string = urldecode($string);
/*
	$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
	$string = stripslashes($string);
*/
	if ($regexp) {
		$string = quotemeta($string);
		$string = strtr($string, array('\*'=>'.*', '\?'=>'.'));
	}
	else $string = strtr($string, array('*'=>'%', '?'=>'_'));

//	$string = strtr($string, array("'"=>"\'")); //global
	return mysql_real_escape_string($string);
}

/*
function bbc_code($entry) {
	$entry = preg_replace("#\[b\](.*)\[/b\]#Uis","<b>\\1</b>",$entry);
	$entry = preg_replace("#\[i\](.*)\[/i\]#Uis","<i>\\1</i>",$entry);
	$entry = preg_replace("#\[u\](.*)\[/u\]#Uis","<u>\\1</u>",$entry);
	if ($_GET['sid']=="a_news_update") {
	$entry = preg_replace("#\[img\](.*)\[/img\]#Uis","<img src=\"\\1\" border=\"0\" alt=\"\\1\">",$entry);
	}
	//$entry = preg_replace("\[mail\]([^\[]+)\[/mail\]","<a href=\"mailto:\\1\">\\1</a>",$entry);
	$entry = preg_replace("#\[url\]http://(.*)\[/url\]#Uis","<a href=\"http://\\1\" target=\"_blank\">\\1</a>",$entry);
	$entry = preg_replace("#\[url\](.*)\[/url\]#Uis","<a href=\"http://\\1\" target=\"_blank\">\\1</a>",$entry);
	$entry = preg_replace("#\[url=http://([^]]+)\](.*)\[/url\]#Uis","<a href=\"http://\\1\" target=\"_blank\">\\2</a>",$entry);
	$entry = preg_replace("#\[url=([^]]+)\](.*)\[/url\]#Uis","<a href=\"http://\\1\" target=\"_blank\">\\2</a>",$entry);

	$entry = preg_replace("#\[color=(\#[0-9a-z]{6})\](.*)\[/color\]#Uis","<span style=\"color:\\1;\">\\2</span>",$entry);
	$entry = preg_replace("#\[size=(-?)([0-9]+)\](.*)\[/size\]#Uis","<font size=\"\\1\\2\">\\3</font>",$entry);
	return $entry;
}
function bbc_code_decode($entry) {
	$entry = preg_replace("#<b>(.*)</b>#Uis","[b]\\1[/b]",$entry);
	$entry = preg_replace("#<i>(.*)</i>#Uis","[i]\\1[/i]",$entry);
	$entry = preg_replace("#<u>(.*)</u>#Uis","[u]\\1[/u]",$entry);
	$entry = preg_replace("#<img src=\"([^\\\"]+)\"[^>]+>#Uis", "[img]\\1[/img]", $entry);
	//$entry = preg_replace("\[img\]([^\[]+)\[/img\]","<img src=\"\\1\" border=\"0\">",$entry);
	//$entry = preg_replace("\[mail\]([^\[]+)\[/mail\]","<a href=\"mailto:\\1\">\\1</a>",$entry);
	$entry = preg_replace("#<a href=\"http://([^\\\"]+)\"[^>]*>(.*)</a>#Uis","[url=http://\\1]\\2[/url]",$entry);
	$entry = preg_replace("#<a href=\"([^\\\"]+)\"[^>]+>(.*)</a>#Uis","[url=http://\\1]\\2[/url]",$entry);

	$entry = preg_replace("#\<span style=\"color:(\#[0-9a-z]{6});\">(.*)</span>#Uis","[color=\\1]\\2[/color]",$entry);
	$entry = preg_replace("#\<font size=\"(-?)([0-9]+)\">(.*)</font>#Uis","[size=\\1\\2]\\3[/size]",$entry);
	return $entry;
}
*/

function bin2float ($bin) {
	$dual = base_convert($bin,10,2);
	while (strlen($dual)<32) {
	 $dual = "0$dual";
	}
	//echo "$bin<br />";
	//echo "Dual: $dual - (".strlen($dual)."bits)<br />";
	
	$sign = substr($dual, 0,1);
		if ($sign=="1") $sign="-";
		else $sign="";
	$mantisse = substr($dual, 1,8);
	$fractional = substr($dual, 9);
	
	$decmantisse = base_convert($mantisse,2,10);
	$exp = $decmantisse-127;
	
// 	echo "Sign: $sign<br />";
// 	echo "Mantisse: $mantisse<br />";
// 	echo "Fractional: $fractional<br />";
// 	echo "Exponent: $exp<br /><br />";

	if ($exp==128 && $fractional=="11111111111111111111111") return $sign."∞";
	elseif ($exp==128 && $fractional!="11111111111111111111111") return "-";

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

	$norm_number = explode(',',"$frontfractional,$backfractional");
	
	for ($i=0; $i<=1; $i++) {
		$split_number = substr(chunk_split($norm_number[$i],1, ","), 0, -1);
		$split_number = explode(",", $split_number);
		if ($i==0) $hoch=count($split_number)-1;
		else $hoch=-1;
		foreach ($split_number as $key => $number) {
			$erg += $number*pow(2,$hoch);
			$hoch--;
		}
	}

	return $sign.round($erg,2);
}
/* */

function imagetype($img, $width=0, $heigh=0) {
	$info = getimagesize($img);
	if (($info[2]==1 OR $info[2]==2 OR $info[2]==3) && ($width==0 OR $width>=$info[0]) && ($height==0 OR $height>=$info[1])) return true;
	else return false;
}


function popup($value, $settings="STICKY, MOUSEOFF", $otherscripts="") {
	if (strlen($settings)) $settings = ", {$settings}";
	return "onmouseover=\"overlib('".str_replace(array("\n", "\r", "&#039;", "'", '"'), array("", "", "\&#039;", "\'", "&quot;"), $value)."'{$settings}); {$otherscripts}\" onmouseout=\"return nd();\"";
}

function switchlayer($layer=array()) {
	foreach ($layer as $layerid => $layerpictures) {
		$layernames .= $comma.$layerid;
		$comma = ",";
	}
	foreach ($layer as $layerid => $layerpicture) {
		$layerdiv .= "<a href='javascript:switchlayer(\"{$layernames}\",\"{$layerid}\")'>$layerpicture</a>";
	}
	return "<div>$layerdiv</div>";
}

function string2timestamp($string) {
	$duration = 0;
	$temporal = 0;
	foreach (str_split(strtolower($string)) as $character) {
		if (preg_match("/^[0-9]$/", $character)) {
			if ($temporal == 0) {
				$temporal = $character;
			} else {
				$temporal = "{$temporal}{$character}";
			}
		}
		else {
			switch ($character) {
				case "s": {
					$duration = $duration + 1;
					$temporal = 0;
					break;
				}
				case "m": {
					$duration = $duration + $temporal*60;
					$temporal = 0;
					break;
				}
				case "h": {
					$duration = $duration + $temporal*3600;
					$temporal = 0;
					break;
				}
				case "d": {
					$duration = $duration + $temporal*86400;
					$temporal = 0;
					break;
				}
				case "w": {
					$duration = $duration + $temporal*604800;
					$temporal = 0;
					break;
				}
				case "q": {
					$duration = $duration + $temporal*18144000;
					$temporal = 0;
					break;
				}
				case "y": {
					$duration = $duration + $temporal*217728000;
					$temporal = 0;
					break;
				}
			}
		}
	}
	return $duration;
}

function timetamp2string($span, $min=false) {
	$d = bcdiv($span,86400,0);
	$span = $span%86400;
	$h = bcdiv($span,3600,0);
	$span = $span%3600;
	$m = bcdiv($span,60,0);
	$s = $span%60;
	
	$return = "";
	if ($d) $return .= $d."d ";
	if ($h OR $d) $return .= $h."h ";
	if ($m OR $h OR $d) $return .= $m."m ";
	if ($s OR $m OR $d OR $h) $return .= $s."s ";
	if (strlen($return) < 1) $return = "0s";
	if ($min) {
		$return = explode($min, $return);
		if (count($return)<2) return "<1{$min}";
		else return $return[0].$min;
	}
	return trim($return);
}
?>