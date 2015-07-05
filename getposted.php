<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

	$options = array();
	if (!$_POST['names']) $_POST['names'] = $_GET['names'];
	if (!$_POST['text']) $_POST['text'] = $_GET['text'];

	if (strlen($_POST['text']) && array_key_exists($_POST['text'], $classquesttext->languagearray) && $_POST['text']!=$flolang->lang[$flolang->language]->prefered_questtextlang) $options['text'] = $_POST['text'];
	if (strlen($_POST['names']) && array_key_exists($_POST['names'], $stringtable->languagearray) && $_POST['names']!=$flolang->lang[$flolang->language]->prefered_stringtablelang) $options['names'] = $_POST['names'];

	if (strlen($_POST['search'])) { $options['search'] = $_POST['search']; }
	
	if (strlen($_POST['tabpreselect'])) { $options['tab'] = $_POST['tabpreselect']; }
	
	$pageanchor = false;
	if (strlen($_POST['anchor'])) $pageanchor = $_POST['anchor'];

	if ($_POST['page']==0 or !intval($_POST['page'])) $_POST['page']=1;
	switch ($_POST['getposted']) {
		case "questoverview": {
			if (!intval($_POST['level'])) $_POST['level'] = 0;
			if (!($_POST['questtype']=="all" OR $_POST['questtype']=="sea" OR $_POST['questtype']=="land")) $_POST['questtype'] = "all";

			$redirect = array("questoverview", "level-{$_POST['level']}-".bcadd($_POST['level'],9), "type-{$_POST['questtype']}", "page-{$_POST['page']}");
			break;
		}

		case "questdetails": {
			if (!strlen($_POST['questid'])) break;
			if (strlen($_POST['usernotes'])) { $options['usernotes'] = $_POST['usernotes']; }

			$redirect = array("questdetails", $_POST['questid'], $classquest->get_title($_POST['questid']));
			break;
		}

		case "100floor": {
			$redirect = array("100floor", "page-{$_POST['page']}");
			break;
		}

		case "itemsearch": {
			$redirect = array("itemoverview", "page-{$_POST['page']}");
			break;
		}

		case "itemcat": {
			if (strlen($_POST['order'])) $options['order'] = $_POST['order'];
			if (strlen($_POST['landclass'])) $options['landclass'] = $_POST['landclass'];
			if (strlen($_POST['seaclass'])) $options['seaclass'] = $_POST['seaclass'];
			if (strlen($_POST['itemtype'])) $options['itemtype'] = $_POST['itemtype'];
			$redirect = array("itemcategorie", $_POST['itemcat'], "page-{$_POST['page']}");
			break;
		}

		case "itemdetails": {
			$redirect = array("itemdetails", $_POST['itemid'], $stringtable->get_string($_POST['itemid']));
			break;
		}

		case "npcoverview": {
			$redirect = array("npcoverview", "level-{$_POST['level']}-".bcadd($_POST['level'],9), "type-{$_POST['type']}", "page-{$_POST['page']}");
			break;
		}

		case "npcdetails": {
			$redirect = array("npcdetails", $_POST['npcid'], $stringtable->get_string($_POST['npcid']));
			break;
		}

		case "mapdetails": {
			$redirect = array("mapdetails", $_POST['mapid'], $stringtable->get_string($_POST['mapid']));
			break;
		}

		case "mapoverview": {
			$redirect = array("mapoverview");
			break;
		}

		case "usernotes": {
			$options['usernotes'] = $_POST['usernotes'];

			switch ($_POST['section']) {
				case "quest": {
					$redirect = array("questdetails", $_POST['sectionid'], $classquest->get_title($_POST['sectionid']));
					break;
				}
				case "item": {
					$redirect = array("itemdetails", $_POST['sectionid'], $stringtable->get_string($_POST['sectionid']));
					break;
				}
				case "npc": {
					$redirect = array("npcdetails", $_POST['sectionid'], $stringtable->get_string($_POST['sectionid']));
					break;
				}
				case "gallery": {
					list($imagename) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT name FROM flobase_gallery WHERE galleryid='".intval($_POST['sectionid'])."'"));
					$redirect = array("gallery", "i", intval($_POST['sectionid']), $imagename);
					break;
				}
			}
			break;
		}

		case "skilloverviewdetails": {
			$classname = $florensia->get_classname($_POST['class']);
			$redirect = array("skilloverview", $_POST['class'], $classname[0]);
			break;
		}

		case "marketsearch": {
			$redirect = array("market", $_POST['itemcat']);
			if (strlen($_POST['cat'])) array_push($redirect, $_POST['cat']);
			array_push($redirect, "page-{$_POST['page']}");

			if (strlen($_POST['server'])) $options['server'] = $_POST['server'];
			break;
		}

		case "guides": {
			$redirect = array("guides");
			if (strlen($_POST['glang']) && $_POST['glang']!="all") $options['lang'] = $_POST['glang'];
			if (strlen($_POST['gorder']) && $_POST['gorder']!="votes") $options['order'] = $_POST['gorder'];
			if (count($_POST['gcat']) && !in_array("0", $_POST['gcat'])) $options['cat'] = join("-", $_POST['gcat']);
			break;
		}
		
		case "exptool": {
			if ($_POST['npctype']) {
				$redirect = array("exptool", intval($_POST['userlevel']), $_POST['npctype'], $_POST['mapid']);
				if ($_POST['inpc']) $options['inpc'] = 1;
				if ($_POST['boss']) $options['boss'] = 1;
				if (strlen($_POST['npc'])) $options['npc'] = $_POST['npc'];
				break;
			} else {
				$redirect = array("exptool", intval($_POST['userlevel']), "exp", $_POST['expclass'], $_POST['exp']);
				break;
			}
		}

		case "adminlog": {
			$redirect = array("adminlog", "page-{$_POST['page']}");
			if (strlen($_POST['timestamp'])) $options['timestamp'] = $_POST['timestamp'];
			if (strlen($_POST['section'])) $options['section'] = $_POST['section'];
			if (strlen($_POST['currentuser'])) $options['currentuser'] = $_POST['currentuser'];
			if (strlen($_POST['currentip'])) $options['currentip'] = $_POST['currentip'];
			if (strlen($_POST['logvalue'])) $options['logvalue'] = $_POST['logvalue'];
			if (intval($_POST['flagged'])) $options['flagged'] = $_POST['flagged'];
			break;
		}
		case "admintools": {
			$redirect = array("admintools", $_POST['tool'], "page-{$_POST['page']}");
			if (in_array($_POST['show'], array("notimplemented", "event", "removed"))) $options['show'] = $_POST['show'];
			break;
		}
		
		
		case "ranking": {
			$redirect = array("ranking");
			if (in_array($_POST['order'], array("sum", "sea", "land"))) $options['order'] = $_POST['order'];
			if (strlen($_POST['class'])>1) $options['class'] = $_POST['class'];
			if (strlen($_POST['server'])>1) $options['server'] = $_POST['server'];
			break;
		}
		case "guildranking": {
			$redirect = array("guildranking");
			if (in_array($_POST['order'], array("sum", "sea", "land", "name", "member"))) $options['order'] = $_POST['order'];
			if (strlen($_POST['server'])>1) $options['server'] = $_POST['server'];
			if (intval($_POST['member']) OR $_POST['member']=="0") $options['member'] = $_POST['member'];
			break;
		}
		
		case "guilddetails": {
			$redirect = array("guilddetails");
			if (strlen($_POST['search']) && strlen($_POST['server'])) {
				$queryguild = MYSQL_QUERY("SELECT guildid, guildname, server FROM flobase_guild WHERE guildname='".mysql_real_escape_string($_POST['search'])."' AND server='".mysql_real_escape_string($_POST['server'])."' AND memberamount!='0' LIMIT 1");	
				if ($guild = MYSQL_FETCH_ARRAY($queryguild)) {
					$redirect[] = $guild['guildid'];
					$redirect[] = $guild['server'];
					$redirect[] = $guild['guildname'];
					unset($options['search']);
				}
			} elseif (intval($_POST['guildid'])) {
				
				$queryguild = MYSQL_QUERY("SELECT guildid, guildname, server FROM flobase_guild WHERE guildid='".intval($_POST['guildid'])."' LIMIT 1");	
				if ($guild = MYSQL_FETCH_ARRAY($queryguild)) {
					$redirect[] = $guild['guildid'];
					$redirect[] = $guild['server'];
					$redirect[] = $guild['guildname'];
					
					if (in_array($_POST['order'], array("sum", "sea", "land", "name", "class", "grade"))) $options['order'] = $_POST['order'];
					if (preg_match("/^[a-z]+$/i", $_POST['tab'])) $options['tab'] = $_POST['tab'];
					
					/* bad place to put in force-update.. */
					if ($_POST['force']=="character" && strlen($_POST['forced_charname'])) {
						MYSQL_QUERY("UPDATE flobase_character SET forceupdate='1' WHERE lastupdate<'".bcsub(date("U"),60*60*3)."' AND charname='".mysql_real_escape_string($_POST['forced_charname'])."'");
						if(mysql_affected_rows()<1) new class_character($_POST['forced_charname']);
					}
					elseif ($_POST['force']=="guild") {
						MYSQL_QUERY("UPDATE flobase_character as c, flobase_character_data as d SET forceupdate='1' WHERE c.characterid=d.characterid AND lastupdate<'".bcsub(date("U"),60*60*3)."' AND guildid='{$guild['guildid']}'");
					}	
				}
			}
			break;
		}
		case "characterdetails": {
			$redirect = array("characterdetails");
			if (strlen($_POST['character'])) {
				$redirect[] = $_POST['character'];
				
				/* bad place to put in force-update.. */
				if ($_POST['force']=="character") {
					MYSQL_QUERY("UPDATE flobase_character SET forceupdate='1' WHERE lastupdate<'".bcsub(date("U"),60*60*3)."' AND charname='".mysql_real_escape_string($_POST['character'])."'");
				}
			}	
			break;
		}

		default: $redirect = array("news");
	}

	header("Location: ".$florensia->outlink($redirect, $options, array("language"=>FALSE, "escape"=>FALSE, "anchor"=>$pageanchor)));
	die;
?>