<?PHP

class class_log {
	var $parser_options = array(
				"allow_html" => 0,
				"allow_mycode" => 1,
				"allow_smilies" => 0,
				"allow_imgcode" => 0,
				"filter_badwords" => 1
			);
	

	function add($section, $text) {
		global $flouser;
		if (!MYSQL_QUERY("INSERT INTO flobase_log (section, timestamp, currentuser, currentip, logvalue) VALUES('".mysql_real_escape_string($section)."', '".date("U")."', '".$flouser->userid."', '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."', '".mysql_real_escape_string($text)."')")) return false;
		return mysql_insert_id();
	}
	
	function parse($logentry) {
		global $florensia, $parser;
		
		$logentry = $parser->parse_message($logentry, $this->parser_options);
		while ($parsedlog != $logentry) {
			if ($parsedlog) $logentry = $parsedlog;
			$parsedlog = preg_replace_callback("/{(([a-z]+):([a-z0-9_]+))}/i", 'logparsebridge', $logentry);
		}
		return $parsedlog;
	}
}

	function logparsebridge($matches) {
		global $flouserdata, $strintable, $florensia, $stringtable, $classquest;
		
		switch ($matches[2]) {
			case "user": {
				$return = $flouserdata->get_username($matches[3]);
				break;
			}
			case "npc": {
				$return = "<a href='".$florensia->outlink(array("npcdetails", $matches[3], $stringtable->get_string($matches[3])))."' target='_blank'>".$stringtable->get_string($matches[3], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
				break;
			}
			case "item": {
				$return = "<a href='".$florensia->outlink(array("itemdetails", $matches[3], $stringtable->get_string($matches[3])))."' target='_blank'>".$stringtable->get_string($matches[3], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
				break;
			}
			case "dropid": {
				$drop = MYSQL_FETCH_ASSOC(MYSQL_QUERY("SELECT n.English as npcname, d.npcid, i.English as itemname, d.itemid FROM server_stringtable as n, server_stringtable as i, flobase_droplist as d WHERE dropid=".intval($matches[3])." AND d.npcid=n.Code AND d.itemid=i.Code LIMIT 1"));
				$return = "<a href='".$florensia->outlink(array("npcdetails", $drop['npcid'], $drop['npcname']))."' target='_blank'>".$florensia->escape($drop['npcname'])."</a> ({$drop['npcid']})/<a href='".$florensia->outlink(array("itemdetails", $drop['itemid'], $drop['itemname']))."' target='_blank'>".$florensia->escape($drop['itemname'])."</a> ({$drop['itemid']})";
				break;
			}
			case "quest": {
				$return = "<a href='".$florensia->outlink(array("questdetails", $matches[3], $classquest->get_title($matches[3])))."' target='_blank'>".$classquest->get_title($matches[3])."</a>";
				break;
			}
			case "map": {
				$return = "<a href='".$florensia->outlink(array("mapdetails", $matches[3], $stringtable->get_string($matches[3])))."' target='_blank'>".$stringtable->get_string($matches[3], array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
				break;
			}
			case "timestamp": {
				$return = date("m.d.y/H:i:s", $matches[3]);
				break;
			}
			case "usernote": {
				$return = "note";
				break;
			}
			case "guide": {
				$guide = MYSQL_FETCH_ASSOC(MYSQL_QUERY("SELECT title FROM flobase_guides WHERE id=".intval($matches[3])." LIMIT 1"));
				if (!$guide['title']) $guide['title'] = "???";
				$return = "&quot;".$florensia->escape($guide['title'])."&quot;";
				break;
			}
			case "gallery": {
				$gallery = MYSQL_FETCH_ASSOC(MYSQL_QUERY("SELECT name FROM flobase_gallery WHERE galleryid=".intval($matches[3])." LIMIT 1"));
				if (!strlen($gallery['name'])) $gallery['name'] = "-";
				$return = "&quot;<a href='".$florensia->outlink(array("gallery", "i", intval($matches[3]), $gallery['name']))."'>".$florensia->escape($gallery['name'])."</a>&quot;";
				break;
			}
			case "guild": {
				$guild = MYSQL_FETCH_ASSOC(MYSQL_QUERY("SELECT guildid, guildname, server, memberamount FROM flobase_guild WHERE guildid=".intval($matches[3])." LIMIT 1"));
				if (!$guild) $return = "-";
				else {
					if ($guild['memberamount']) $return = "<a href='".$florensia->outlink(array("guilddetails", $guild['guildid'], $guild['server'], $guild['guildname']))."'>".$florensia->escape($guild['guildname'])."</a>";
					else $return = "<a class='archiv' href='".$florensia->outlink(array("guilddetails", $guild['guildid'], $guild['server'], $guild['guildname']))."'>".$florensia->escape($guild['guildname'])."</a>";
				}
				break;
			}
			case "character": {
				$return = "<a href='".$florensia->outlink(array("characterdetails", $matches[3]))."'>".$florensia->escape($matches[3])."</a>";
				break;
			}
			case "characterid": {
				$character = new class_character(intval($matches[3]));
				$return = "<a href='".$florensia->outlink(array("characterdetails", $character->data['charname']))."'>".$florensia->escape($character->data['charname'])."</a>";
				break;
			}
			case "characterverification": {
				$return = "<a href='{$florensia->root}/admincharacterverification.php?s=".intval($matches[3])."' target='_blank'>request</a>";
				break;
			}
			case "log": {
				$return = "";
				break;
			}
			default: $return = "({$matches[2]}:{$matches[3]})";
		}
		return $return." ({$matches[3]})";
		
	}
	
?>