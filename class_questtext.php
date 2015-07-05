<?PHP

if (!defined('is_florensia')) die('Hacking attempt');

class class_questtext {
	var $language = "EN";
	var $languagearray = array();
	var $languagelink = "";

	function get_string($questid="") {
		if ($questid=="") return false;
		$language = $this->language;
		global $flolang, $florensia;
		$flolang->load("quest");
		$key = "$questid";

		$queryquesttexttables = MYSQL_QUERY("SELECT questtextxml FROM server_questtext_{$this->language} WHERE questlistid='".mysql_real_escape_string($key)."'");
		if ($questtexttables = MYSQL_FETCH_ARRAY($queryquesttexttables)) {
			$return = strtr(str_replace(array('Mission1=',
					  'Mission2=',
					  'Mission3=',
					  'desc=',
					  'preDlg=',
					  'startDlg=',
					  'runDlg=',
					  'finishDlg='),
				    array("<br /><b>".strtr($flolang->questtext_subtitle_Mission1, "_", " ")."</b><br />",
					  "<br /><b>".strtr($flolang->questtext_subtitle_Mission2, "_", " ")."</b><br />",
					  "<br /><b>".strtr($flolang->questtext_subtitle_Mission3, "_", " ")."</b><br />",
					  "<br /><b>".strtr($flolang->questtext_subtitle_desc, "_", " ")."</b><br />",
					  "<br /><b>".strtr($flolang->questtext_subtitle_preDlg, "_", " ")."</b><br />",
					  "<br /><b>".strtr($flolang->questtext_subtitle_startDlg, "_", " ")."</b><br />",
					  "<br /><b>".strtr($flolang->questtext_subtitle_runDlg, "_", " ")."</b><br />",
					  "<br /><b>".strtr($flolang->questtext_subtitle_finishDlg, "_", " ")."</b><br />"),
				    $florensia->escape($questtexttables['questtextxml'])),
				array('\n'=>'<br />'));
		}
		else { 
			$return = $flolang->questtext_notext;
		}
		return "<div class='subtitle small' style='padding:0px 5px 15px 5px; font-weight:normal'>{$return}</div>";
	}

	function get_valid_languages() {
		global $florensia;
		$queryquesttexttables = MYSQL_QUERY("SHOW TABLES FROM {$florensia->dbname} LIKE 'server_questtext_%'");
		while ($questtexttables = MYSQL_FETCH_ARRAY($queryquesttexttables)) {
			//if (strtr($questtexttables[0], array('server_questtext_'=>''))=="TR") continue;
			$this->languagearray[strtr($questtexttables[0], array('server_questtext_'=>''))] = strtr($questtexttables[0], array('server_questtext_'=>''));
		}
	}

	function get_select() {
		$selectquesttextlanguage = "
			<select name='text' class='small'>";
				foreach ($this->languagearray as $key => $lang) {
					if ($key==$this->language) $selected="selected='selected'";
					else unset($selected);
					$selectquesttextlanguage .= "<option value='$lang' $selected>$lang</option>";
				}
				$selectquesttextlanguage .= "
			</select>
		";
		return $selectquesttextlanguage;
	}
}

?>