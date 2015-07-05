<?PHP

class class_lang {
	var $language = "en";

	var $parser_options = array(
				"allow_html" => 0,
				"allow_mycode" => 1,
				"allow_smilies" => 0,
				"allow_imgcode" => 0,
				"filter_badwords" => 0
			);

	function load($sectionfile, $language=false) {
		global $parser, $cfg;
		if (!$language) { $language = $this->language; }
		foreach (explode(",",$sectionfile) as $section) {
			$section = trim($section);
			$lfile = $cfg['language_abs']."/{$language}/".$section.".lang.php";
			if($loadlang = simplexml_load_file($lfile)) {
			}
			elseif($loadlang = simplexml_load_file($this->path."/en/".$section.".lang.php")) {
				$this->language = "en";
			}
			else {
				die("Language file \"$section\" for \"{$language}\" does not exist or not loadable");
			}
			
			if($loadlang) {
				foreach($loadlang as $key => $val) {
					$val = $parser->parse_message(str_replace("'", "&#039;", $val), $this->parser_options);
					if(empty($this->$key) || $this->$key != $val) {
						$this->$key = $val;
					}
				}
			}
		}
	}

	function sprintf($string)
	{
		$arg_list = func_get_args();
		$num_args = count($arg_list);
		
		for($i = 1; $i < $num_args; $i++)
		{
			$string = str_replace('{'.$i.'}', $arg_list[$i], $string);
		}
		return $string;
	}

	function get_languages() {
		global $florensia;
/*
		$header = array();
		$querylang = MYSQL_QUERY("SHOW COLUMNS FROM flobase_language");
		while ($lang = MYSQL_FETCH_ARRAY($querylang)) {
			array_push($header, $lang[0]);
		}
*/
		$querylang = MYSQL_QUERY("SELECT * FROM flobase_language");
		while ($lang = MYSQL_FETCH_ASSOC($querylang)) {
			foreach ($lang as $headertitle => $langvalue) {
					$this->lang[$lang['languageid']]->$headertitle = $langvalue;
			}
		}
	}
	
	function exportfile($filename, $langid) {
		global $florensia;
		$langfile = $florensia->language_abs."/{$langid}/{$filename}.lang.php";
		
		if (!rename($langfile, $langfile.'_backup_'.date("Y.m.d-H.i.s"))) {
			$florensia->notice("Cannot rename old file for backup. Please verify your chmod settings!", "warning");
			return false;
		}
		if (!($newfile = fopen($langfile, 'a'))) {
			$florensia->notice("Cannot create new file. Please verify your chmod settings!", "warning");
			return false;
		}
		
		$newlangfile = new SimpleXMLElement("<{$filename} createdate=\"".date("Y-m-d H:i:s / U")."\"></{$filename}>");
		$querylangvar = MYSQL_QUERY("SELECT varname, lang_{$langid} FROM flobase_languagefiles WHERE filename='".mysql_real_escape_string($filename)."'");
		while ($langvar = MYSQL_FETCH_ARRAY($querylangvar)) {
			$newlangfile->$langvar['varname'] = $langvar['lang_'.$langid];
		}
		$newlangfile->asXML($langfile);
		$florensia->notice("Language file and backup for {$langid}/{$filename} successfully created", "successful");
		return true;
	}
}

?>