<?PHP


if (!defined('is_florensia')) die('Hacking attempt');

class class_stringtable {
	var $language = "English";
	var $languagearray = array();

	function get_string($stringid=false, $settings=array()) {
		if (!$stringid) return "";
		$defaults = array('protection'=>0, 'protectionlink'=>0, 'protectionsmall'=>0, 'color'=>0, 'itemgrade'=>-1, 'maxlength'=>0, 'language'=>$this->language);
		$settings = array_merge($defaults, $settings);
		
			global $db, $flocache, $florensia, $flouser;

			if (!in_array($settings['language'], $this->languagearray)) $settings['language'] = $this->language;
			if ($settings['protectionlink']) $settings['protection']=1;
			if ($settings['protectionsmall']) $settings['protection']=1;

			//ignore protection for google-crawler - google wins always ._. - also set nonprotected strings for user with this permission
#			if ("Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)" == $_SERVER['HTTP_USER_AGENT'] OR $flouser->get_permission("watch_nonprotectedstrings")) {
#				$settings['protection'] = 0;
#			}

			if (!$settings['color']) {
				if ($settings['itemgrade']>=0) {
					switch ($settings['itemgrade']) {
						case "1": { $settings['color'] = "153,230,128"; break; }
						case "2": { $settings['color'] = "230,204,153"; break; }
						case "3": { $settings['color'] = "255,189,143"; break; }
						default: { $settings['color'] = "153,204,255"; }
						//special effects 107,250,171
						//jobs+female/male/all 196,237,176
					}
				} elseif ($settings['protectionlink']) $settings['color'] = "140,224,255";
			}

			//we don't need load the string now if it should be protected
#			if ($settings['protection']) {
#				//be sure vars are set correctly (to create successful all folders)
#				foreach ($settings as $skey => $svalue) {
#					if ($svalue===FALSE) $settings[$skey] = 0;
#					elseif ($svalue===TRUE) $settings[$skey] = 1;
#				}
#				return $this->get_protectedstring($stringid, $settings);
#			}


			//now working on non-protected strings
			
			//get string out of cache
#			$mystring = $flocache->get_cache("langcache,$stringid,{$settings['language']}");
			//not cached?
#			if ($mystring===FALSE) {
				$stringtablequery = MYSQL_QUERY("SELECT {$settings['language']} FROM server_stringtable WHERE Code = '".mysql_real_escape_string($stringid)."'", $db);
				if ($stringtableentry = MYSQL_FETCH_ARRAY($stringtablequery)) $mystring = $stringtableentry[$settings['language']];
				else $mystring = $stringid;
#				$flocache->write_cache("langcache,$stringid,{$settings['language']}",$mystring);
#			}

			//test if $mystring is longer then max.
			if ($settings['maxlength'] && strlen($mystring)>$settings['maxlength']) {
				$mystring = substr($mystring, 0, $settings['maxlength'])."...";
			}

			//return $mystring
			if ($settings['color']) return "<span style='color:rgb({$settings['color']})'>$mystring</span>";
			return $mystring;
	}
	
	function get_protectedstring($stringid, $settings=array()) {
		global $db, $florensia, $flocache;
		
		if (!$settings['color']) $settings['color'] = "255,255,255";
		$cachesubdir = "/cache/textprotection/lang_{$settings['language']}/small_{$settings['protectionsmall']}/color_".str_replace(",", "_", $settings['color'])."/length_{$settings['maxlength']}";

		//check if cached file is there, if not create it
		if (!is_file($florensia->root_abs.$cachesubdir."/{$stringid}.gif")) {
			//create dir recursively if not exist
			if (!is_dir($florensia->root_abs.$cachesubdir)) {
				mkdir($florensia->root_abs.$cachesubdir, 0755, TRUE);
			}
			
			//get string out of cache
			$mystring = $flocache->get_cache("langcache,$stringid,{$settings['language']}");
			//not cached?
			if ($mystring===FALSE) {
				$stringtablequery = MYSQL_QUERY("SELECT {$settings['language']} FROM server_stringtable WHERE Code = '".mysql_real_escape_string($stringid)."'", $db);
				if ($stringtableentry = MYSQL_FETCH_ARRAY($stringtablequery)) $mystring = $stringtableentry[$settings['language']];
				else $mystring = $stringid;
				$flocache->write_cache("langcache,$stringid,{$settings['language']}",$mystring);
			}

			$mystring = trim(html_entity_decode($mystring, ENT_QUOTES, 'UTF-8'));
			
			//test if $mystring is longer then max.
			if ($settings['maxlength'] && strlen($mystring)>$settings['maxlength']) {
				$mystring = substr($mystring, 0, $settings['maxlength'])."...";
			}
		
			$textbox = $this->textbox($mystring, $settings);
		
			$Teilgrafik = imagecreatetruecolor($textbox['fontwidth']+2, $textbox['fontheight']);
#			imageantialias($Teilgrafik, true);
		
			$colourwhite = imagecolorallocate($Teilgrafik, 73,111,150);
			imagefilledrectangle($Teilgrafik, 0, 0, $textbox['fontwidth']+2, $textbox['fontheight'], $colourwhite);
		
			//get color out of string
			$color = explode(",", $settings['color']);
			$color = imagecolorallocate ($Teilgrafik, $color[0], $color[1], $color[2]);
			imagecolortransparent($Teilgrafik, $colourwhite);
			imagettftext($Teilgrafik, $textbox['fontsize'], 0, 0, $textbox['fontheight']-3, $color, $textbox['fontfile'], $textbox['text']);

			imagegif($Teilgrafik, $florensia->root_abs.$cachesubdir."/{$stringid}.gif");
			imagedestroy($Teilgrafik);
		}
		
		//return final image
		return "<span style='vertical-align:bottom;'><img src='".$florensia->root.$cachesubdir."/{$stringid}.gif' border='0' alt='Do not copy!' style='vertical-align:bottom;'></span>";
	}

	function get_valid_languages() {
		global $florensia;

		$header = array();
		$querycolumns = MYSQL_QUERY("SHOW COLUMNS FROM server_stringtable");
		while ($columns = MYSQL_FETCH_ARRAY($querycolumns)) {
			array_push($header, $columns[0]);
		}

		foreach ($header as $headertitle) {
			if ($headertitle=="Code") continue;
			if (preg_match('/[a-zA-Z]+/',$headertitle)) {
				//workaround for not implementet "Taiwan" language
				if ($headertitle=="Taiwan") continue;
				$this->languagearray[$headertitle] = $headertitle;
			}
		}
	}

	function get_select() {
		$selectnameslanguage = "<select name='names' class='small'>";
			foreach ($this->languagearray as $key => $lang) {
				if ($key==$this->language) $selected="selected='selected'";
				else unset($selected);
				$selectnameslanguage .= "<option value='$lang' $selected>$lang</option>";
			}
			$selectnameslanguage .= "
		</select>";
		return $selectnameslanguage;
	}
	
	function textbox ($mystring="", $settings=array()) {
		global $florensia;
		switch($settings['language']) {
			case "English": { $fontfile="verdana.ttf"; break; }
			case "German": { $fontfile="verdana.ttf"; break; }
			case "Italian": { $fontfile="verdana.ttf"; break; }
			case "Spanish": { $fontfile="verdana.ttf"; break; }
			case "Portuguese": { $fontfile="verdana.ttf"; break; }
			case "French": { $fontfile="verdana.ttf"; break; }
			case "Turkish": { $fontfile="verdana.ttf"; break; }
			default: $fontfile="arialuni.ttf";
		}
		$textbox['fontfile'] = $florensia->fonts_abs."/".$fontfile;
	
		if ($settings['protectionsmall']) {$fontsize=8; }
		else { $fontsize=9; }
	
		$textwerte = imagettfbbox($fontsize, 0, $textbox['fontfile'], $mystring);
		$textbox['fontwidth'] = abs($textwerte['2']);
		$textbox['fontheight'] = abs($textwerte['5'])+2;
	
		$textbox['text']=$mystring;
		$textbox['language']=$settings['language'];
		$textbox['fontsize']=$fontsize;
	
		return $textbox;
	}
}

?>