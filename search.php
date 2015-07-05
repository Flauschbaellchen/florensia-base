<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');
$nosearch = "
	<div style='text-align:center; margin-top:40px; margin-bottom:20px;' class='warning'>{$flolang->nosearchstarted}</div>
	<div style='text-align:center'>".$florensia->quicksearch(array('language'=>true))."</div>
";

	//if (strlen($_POST['quicksearch'])>2 && preg_match('/[^*?]/', $_POST['quicksearch'])) {

			$options = array();

			if (strlen($_POST['names']) && array_key_exists($_POST['names'], $stringtable->languagearray) && $_POST['names']!=$flolang->lang[$flolang->language]->prefered_stringtablelang) $options['names'] = $_POST['names'];
			$options['search']=$_POST['quicksearch'];

			switch ($_POST['quicksearchid']) {
				case "quests": {
					$redirect = array("questoverview");
					break;
				}
				case "items": {
					$redirect = array("itemoverview");
					break;
				}
				case "npcs": {
					$redirect = array("npcoverview");
					break;
				}
				case "market": {
					$redirect = array("market");
					break;
				}
				case "usermarket": {
					$redirect = array("usermarket");
					break;
				}
				case "characterdetails": {
					$redirect = array("characterdetails", $_POST['quicksearch']);
					unset($options['search']);
					break;
				}
				case "guides": {
					$redirect = array("guides");
					break;
				}
				case "guilddetails": {
					$redirect = array("guilddetails");
					$guildquery = MYSQL_QUERY("SELECT guildid, guildname, server FROM flobase_guild WHERE guildname LIKE '".mysql_real_escape_string($_POST['quicksearch'])."' AND memberamount!='0' LIMIT 2");
					if ($guild = MYSQL_FETCH_ARRAY($guildquery)) {
						if (!($dummy = MYSQL_FETCH_ARRAY($guildquery))) {
							array_push($redirect, $guild['guildid'], $guild['server'], $guild['guildname']);
							unset($options['search']);
						}
					}
					break;
				}
				case "gallery": {
					$redirect = array("gallery");
					break;
				}				
				case "gallery_character": {
					$redirect = array("gallery", "c", $_POST['quicksearch']);
					unset($options['search']);
					break;
				}
				case "gallery_guild": {
					$redirect = array("gallery");
					if ($guild = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT guildid, guildname, server FROM flobase_guild WHERE guildname LIKE '".mysql_real_escape_string($_POST['quicksearch'])."' LIMIT 1"))) {
						array_push($redirect, "g", $guild['guildid'], $guild['server'], $guild['guildname']);
					} else array_push($redirect, "g", 0);
					unset($options['search']);
					break;
				}
				case "gallery_user": {
					$redirect = array("gallery");
					if ($user = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT uid, username FROM forum_users WHERE username LIKE '".mysql_real_escape_string($_POST['quicksearch'])."' LIMIT 1"))) {
						array_push($redirect, "u", $user['uid'], $user['username']);
					} else array_push($redirect, "u", 0);
					unset($options['search']);
					break;
				}
				case "gallery_tag": {
					$redirect = array("gallery", "t", $_POST['quicksearch']);
					unset($options['search']);
					break;
				}
				
				default: {
					$florensia->output_page($nosearch);
					exit;
				}
			}

			header("Location: ".$florensia->outlink($redirect, $options, array("language"=>FALSE, "escape"=>FALSE)));
			die;
	//}
	//else $florensia->output_page($nosearch);

?>