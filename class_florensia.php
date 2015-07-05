<?PHP

if (!defined('is_florensia')) die('Hacking attempt');

class class_florensia {
	var $root;

	var $sitetitle = array("Florensia Base");
	var $notices = array();

	var $googleanalytics;
	var $googleadsense;
	var $adsensecount=0;
	
	var $validserver = array("Bergruen", "LuxPlena", "Pardioc", "Karanir");

	var $pageentrylimit = 40;

	var $charapi = "../working";
	
	var $default_parser_options = array(
			"allow_html" => 0,
			"allow_mycode" => 1,
			"allow_smilies" => 0,
			"allow_imgcode" => 0,
			"filter_badwords" => 1
		);

	function quick_select($section, $hiddengetsarray=array(), $showgetsarray=array(), $settings=array()) {
		global $stringtable,$classquesttext, $flolang, $florensia;
		
		if ($settings['questtextselect']) { $showgetsarray[$flolang->global_select_langquesttext] = $classquesttext->get_select(); }
		if ($settings['namesselect']) { $showgetsarray[$flolang->global_select_langnames] = $stringtable->get_select(); }
		if ($settings['serverselect']) { $showgetsarray[$flolang->global_select_server] = $this->get_serverselect(); }

		foreach ($hiddengetsarray as $getkey => $getvalue) {
			if (strlen($getvalue)==0) continue;
			$hiddengets .= "<input type='hidden' name='$getkey' value='".$this->escape($getvalue)."'>";
		}
		foreach ($showgetsarray as $getkey => $getvalue) {
			if (!strlen($getvalue)) continue;
			if (!is_int($getkey)) $getkey .= ":";
			else unset($getkey);
			$showgetsform .= trim(str_replace("\n", '', $getkey.$getvalue))." ";
		}
		
		if ($settings['anchor']) $tabpreselect = "<input type='hidden' name='anchor' value='".$this->escape($settings['anchor'])."'>";
		return "<form action='".$florensia->root."/getposted.php?names={$stringtable->language}&amp;text={$classquesttext->language}' method='POST'>{$tabpreselect}<input type='hidden' name='getposted' value='".$this->escape($section)."'>{$hiddengets}{$showgetsform}&nbsp;<input type='image' src='{$this->layer_rel}/searchstart.png' style='height:15px; width:17px; vertical-align:bottom;' border='0'></form>";
	}
/*
	function pageselect($entries, $linkarray, $linkoptionarray=array(), $entrylimit=0) {
		global $flolang;
		if (strlen($_GET['search'])) $linkoptionarray['search']=$_GET['search'];
		if (!$entrylimit) $entrylimit=$this->pageentrylimit;
			if (!$_GET['page'] OR !is_numeric($_GET['page'])) $_GET['page']=1;
			$pagestart = ($_GET['page']*$entrylimit)-$entrylimit;
			if ($pagestart>$entries) $pagestart=1;

			$select['selectbar'] = "";
			$select['pagestart'] = $pagestart;

			if ($entrylimit<$entries) {
				unset($comma, $selectsite);
				for ($i=$entrylimit; $i<$entries+$entrylimit; $i+=$entrylimit) {
					if ($_GET['page']!=ceil($i/$entrylimit)) {
						$selectsite .= "<a href='".$this->outlink(array_merge($linkarray, array("page-".ceil($i/$entrylimit))), $linkoptionarray)."' class='pagination'>".ceil($i/$entrylimit)."</a>";
					}
					else $selectsite .= "<span class='pagination_current'>".ceil($i/$entrylimit)."</span>";
					$breaklength++;
					if ($breaklength>30) {
						$selectsite .= "</div><div style='margin-bottom:7px; margin-top:5px;'>";
						$breaklength=1;
					}
				}
				$select['selectbar'] = "<div style='margin-bottom:7px; margin-top:5px;'><span class='small'>{$flolang->global_pages} </span>$selectsite</div>";
			}


			return $select;
	}
*/	
	function pageselect($total_items, $linkarray, $linkoptionarray=array(), $per_page=0) {
		global $flolang;
		if ($total_items==0) {
			return array(
				'selectbar'=>"",
				'pagestart'=>1
			);
			
		}
		if (!$per_page) $per_page=$this->pageentrylimit;
		$page = $_GET['page'];
			
		if (strlen($_GET['search'])) $linkoptionarray['search']=$_GET['search'];
		if (!$per_page) $per_page=$this->pageentrylimit;
		if (!$page OR !is_numeric($page)) $page=1;
		$pagestart = ($page*$per_page)-$per_page;
		if ($pagestart>$total_items) $pagestart=1;
	
		$select['selectbar'] = "";
		$select['pagestart'] = $pagestart;
				
		$anchor = array('anchor'=>$linkoptionarray['anchor']);
		unset($linkoptionarray['anchor']);
		$url = $this->outlink(array_merge($linkarray, array("page-{page}")), $linkoptionarray, $anchor);
	//	if($total_items <= $per_page) {
	//		return;
	//	}
	
		$pages = ceil($total_items / $per_page);
		$pagination = "<span class='small'>{$flolang->global_pages} </span>";
	
		if($page > 1) {
			$prev = $page-1;
			$prev_page = $this->fetch_page_url($url, $prev);
			//$pagination .= "<a href='{$prev_page}' class='pagination_previous'>&laquo; {$lang->previous}</a> ";
		}
	
		// Maximum number of "page bits" to show
		if(!$mybb->settings['maxmultipagelinks']) {
			$mybb->settings['maxmultipagelinks'] = 5;
		}
		
		$max_links = $mybb->settings['maxmultipagelinks'];
		$from = $page-floor($mybb->settings['maxmultipagelinks']/2);
		$to = $page+floor($mybb->settings['maxmultipagelinks']/2);
	
		if($from <= 0) {
			$from = 1;
			$to = $from+$max_links-1;
		}
	
		if($to > $pages) {
			$to = $pages;
			$from = $pages-$max_links+1;
			if($from <= 0) {
				$from = 1;
			}
		}
	
		if($to == 0) { $to = $pages; }
		if($from > 2) {
			$first = $this->fetch_page_url($url, 1);
			$pagination .= "<a class='pagination' href='{$first}'>1</a>... ";
		}
	
		for($i = $from; $i <= $to; ++$i) {
			$page_url = $this->fetch_page_url($url, $i);
			if($page == $i) {
				$pagination .= "<span class='pagination_current'>{$i}</span> ";
			} else {
				$pagination .= "<a class='pagination' href='{$page_url}'>{$i}</a> ";
			}
		}
	
		if($to < $pages) {
			$last = $this->fetch_page_url($url, $pages);
			$pagination .= "... <a class='pagination' href='{$last}'>{$pages}</a>";
		}
		if($page < $pages) {
			$next = $page+1;
			$next_page = $this->fetch_page_url($url, $next);
			//$pagination .= " <a href='{$next_page}'>{$lang->next} &raquo;</a>";
		}
		$select['selectbar'] = "<div style='margin-bottom:7px; margin-top:5px;'>$pagination</div>";
		return $select;
	}
		
	function fetch_page_url($url, $page)
	{
		// If no page identifier is specified we tack it on to the end of the URL
		if(strpos($url, "{page}") === false) {
			if(strpos($url, "?") === false) {
				$url .= "?";
			}
			else {
				$url .= "&amp;";
			}
			$url .= "page=$page";
		}
		else {
			$url = str_replace("{page}", $page, $url);
		}
		return $url;
	}

	function tabbar($tabs=array(), $settings=array()) {			
		//tabbar
		$defaultsettings = array("desc"=>true);
		$settings = array_merge($defaultsettings, $settings);
		$tabkeys = array();
		
		$tabbarcss['normal'] = array("tabbar"=>"margin-bottom: 10px; padding-top:10px;",
					  "ul"=>"list-style: none; padding: 0; margin: 0; height: 40px; border-bottom: 1px solid #81A2C4;",
					  "li"=>"float: left; padding-right: 5px;",
					  "li_a"=>"float:left; border: 1px solid #81A2C4; border-bottom: 0; display: block; font-size: 140%; padding: 3px 8px 4px; height: 32px;",
					  "li_inactive"=>"color:#a2a8ae; float: left; border: 1px solid #a2a8ae; border-bottom: 0; display: block; font-size: 140%; padding: 3px 8px 4px; height: 32px;");
		$tabbarcss['small'] = array("tabbar"=>"margin-bottom: 5px; padding-top:2px;",
					  "ul"=>"list-style: none; padding: 0; margin: 0; height: 36px; border-bottom: 1px solid #81A2C4;",
					  "li"=>"float: left; padding-right: 5px;",
					  "li_a"=>"float:left; border: 1px solid #81A2C4; border-bottom: 0; display: block; font-size: 120%; padding: 3px 2px 4px; height: 28px;",
					  "li_inactive"=>"color:#a2a8ae; float: left; border: 1px solid #a2a8ae; border-bottom: 0; display: block; font-size: 140%; padding: 3px 2px 4px; height: 28px;");
		$tabbarcss['small_nodesc'] = array("tabbar"=>"margin-bottom: 5px; padding-top:2px;",
					  "ul"=>"list-style: none; padding: 0; margin: 0; height: 24px; border-bottom: 1px solid #81A2C4;",
					  "li"=>"float: left; padding-right: 5px;",
					  "li_a"=>"float:left; border: 1px solid #81A2C4; border-bottom: 0; display: block; font-size: 120%; padding: 3px 2px 4px; height: 16px;",
					  "li_inactive"=>"color:#a2a8ae; float: left; border: 1px solid #a2a8ae; border-bottom: 0; display: block; font-size: 140%; padding: 3px 2px 4px; height: 16px;");
		
		if (!$settings['small'] && $settings['desc']) {
			$tabbarstyle = "normal";
		} elseif ($settings['small'] && $settings['desc']) {
			$tabbarstyle = "small";
		} elseif ($settings['small'] && !$settings['desc']) {
			$tabbarstyle = "small_nodesc";
		}
		
		
		$active="tabbar_active";
		#$active_colorworkaround = "color:#396087;";
		foreach ($tabs as $tab) {
			if ($tab['inactive'] OR $tab['link']) continue;
			$tabkeys[] = $tab['anchor'];
		}
		foreach ($tabs as $tabname => $tab) {
			if (!$settings['desc']) $tab['desc'] = false;
			
			if (!$tab['inactive']) {
				if ($tab['link']) $link = "href='{$tab['link']}'";
				else $link = "href='#{$tab['anchor']}' onclick='tabbar(\"{$tab['anchor']}\", \"".join(",",$tabkeys)."\");'";
				$tabbar .= "
				<li style='text-align:center; ".$tabbarcss[$tabbarstyle]['li']."'>
					<a id='tab_{$tabname}' style='{$active_colorworkaround} ".$tabbarcss[$tabbarstyle]['li_a']."' {$link}>{$tab['name']}<br/>
						<span style='font-size:55%;'>{$tab['desc']}</span>
					</a>
				</li>";
			} else {
				$tabbar .= "
				<li style='text-align:center; ".$tabbarcss[$tabbarstyle]['li']."'>
					<div style='".$tabbarcss[$tabbarstyle]['li_inactive']."'>{$tab['name']}<br/>
						<span style='font-size:55%;'>{$tab['desc']}</span>
					</div>
				</li>";
			}
			unset($active, $active_colorworkaround);
		}
		
		//starttab
		//$parsed_url = parse_url("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		/*echo "http://www.florensia-base.com".$_SERVER['REQUEST_URI']."<br/>";
		$parsed_url = parse_url("http://www.florensia-base.com".$_SERVER['REQUEST_URI']);
		var_dump($parsed_url);
		echo "<br>";
		var_dump(in_array($parsed_url['fragment'], $tabkeys));
		if (is_array($parsed_url) && in_array($parsed_url['fragment'], $tabkeys)) $starttab = $parsed_url['fragment'];
		elseif (in_array($settings['starttab'], $tabs)) $starttab = $settings['starttab'];
		else $starttab = $tabkeys[0];*/
		
		$return['tabbar'] = "<div style='".$tabbarcss[$tabbarstyle]['tabbar']."'><ul style='".$tabbarcss[$tabbarstyle]['ul']."'>{$tabbar}</ul></div>";
		$return['jscript'] = "<script type='text/javascript'>
				var tabs_av = '".join(",", $tabkeys)."'.split(',');
				var tab = tabs_av[0];
				tabbar(document.location.hash.replace('#', ''), \"".join(",",$tabkeys)."\");
			</script>";
		return $return;
	}

	function change() {
		if ($this->colorchange==1) { $this->colorchange=0; return 0; }
		$this->colorchange=1; return 1;
	}

	function sitetitle($title) {
		array_unshift($this->sitetitle, $this->escape($title));
	}

	function notice($text="", $css="") {
		if ($text=="") return;
		$this->notices[count($this->notices)] = "<div class='small $css' style='text-align:center;'>$text</div>";
	}

	function escape($string, $double_encode=FALSE) {
		return htmlentities($string, ENT_QUOTES, 'UTF-8', $double_encode);
	}

	function quicksearch($settings=array()) {
		global $stringtable, $flolang;
		
		$defaults = array("automargin"=>true, "language"=>false);
		$settings = array_merge($defaults, $settings);
		
		preg_match('/([^\/]+)\.php$/', $_SERVER['SCRIPT_FILENAME'], $file);
		if ($file[1]=="gallery") {
			if ($_GET['character']) $file[1]="gallery_character";
			elseif ($_GET['guild']) $file[1]="gallery_guild";
			elseif ($_GET['user']) $file[1]="gallery_user";
			elseif ($_GET['tag']) $file[1]="gallery_tag";
		}

		$selected[$file[1]]=" selected='selected'";
		$qicksearchselectid = "<select name='quicksearchid'>
				<option value='items'".$selected['items'].">{$flolang->quicksearch_items}</option>
				<option value='npcs'".$selected['npcs'].">{$flolang->quicksearch_npcs}</option>
				<option value='quests'".$selected['quests'].">{$flolang->quicksearch_quests}</option>
 				<option value='guides'".$selected['guides'].">{$flolang->quicksearch_guides}</option>
				<option disabled='disabled'></option>
				<option value='market'".$selected['market'].">{$flolang->market_title_main}</option>
				<option value='usermarket'".$selected['usermarket'].">{$flolang->market_title_usermarket_search}</option>
				<option disabled='disabled'></option>
				<option value='characterdetails'".$selected['characterdetails'].">{$flolang->quicksearch_ingamecharacter}</option>
				<option value='guilddetails'".$selected['guilddetails'].">{$flolang->quicksearch_ingameguild}</option>
				<option disabled='disabled'></option>
				<option value='gallery'".$selected['gallery'].">{$flolang->quicksearch_gallery}</option>
				<option value='gallery_character'".$selected['gallery_character'].">{$flolang->quicksearch_gallery_character}</option>
				<option value='gallery_guild'".$selected['gallery_guild'].">{$flolang->quicksearch_gallery_guild}</option>
				<option value='gallery_user'".$selected['gallery_user'].">{$flolang->quicksearch_gallery_user}</option>
				<option value='gallery_tag'".$selected['gallery_tag'].">{$flolang->quicksearch_gallery_tag}</option>
			</select>";

		if ($settings['automargin']) $automargin="margin:auto;";
		if ($settings['language']) $languageselect = "<td>".$stringtable->get_select()."</td>";

		$quicksearch = "
			<table style='border-collapse:collapse; border-spacing:0px; padding:0px; $automargin' class='small'>
				<tr><td>
					<form action='{$this->root}/search.php' method='POST'>
						<table style='border-collapse:collapse; border-spacing:0px; padding:0px; margin:auto' class='small'>
							<tr>
								<td><input type='text' name='quicksearch' value='".$this->escape($_GET['search'])."' style='width:100px'></td>
								<td>{$qicksearchselectid}</td>
								$languageselect
								<td><input type='image' src='{$this->layer_rel}/searchstart.png' style='height:15px; width:17px; vertical-align:bottom;' border='0'></td>
							</tr>
						</table>
					</form>
				</td></tr>
			</table>";

		return $quicksearch;
	}


	function get_columnname($code_english, $table="npc") {
		if ($table=="monster") $table="npc"; //workaround if i forgot sth.
		elseif ($table=="spell") $table="skill"; //spell have same columns as skill - just if this will change
		global $flocache;
		if ($flocache->get_cache("$table,columname,$code_english")) {
			return $flocache->get_cache("$table,columname,$code_english");
		}

		$queryitemcolum = MYSQL_QUERY("SELECT code_korean FROM flobase_{$table}_columns WHERE code_english='$code_english'");
		if ($itemcolum = MYSQL_FETCH_ARRAY($queryitemcolum)) {
			$return = $itemcolum['code_korean'];
		}
		else {
			$return = $code_english;
		}
		$flocache->write_cache("$table,columname,$code_english", $return);
		return $return;
	}


	function get_columnname_backwards($code_korean, $table="npc") {
		global $flolang;
		if ($table=="monster") $table="npc"; //workaround if i forgot sth.
		elseif ($table=="spell") $table="skill"; //spell have same columns as skill - just if this will change
		global $flocache, $db;
		if ($flocache->get_cache("$table,columname_backwards,$code_korean,{$flolang->language}")) {
			return $flocache->get_cache("$table,columname_backwards,$code_korean,{$flolang->language}");
		}

		$queryitemcolum = MYSQL_QUERY("SELECT name_{$flolang->language} FROM flobase_{$table}_columns WHERE code_korean='$code_korean'", $db);
		if ($itemcolum = MYSQL_FETCH_ARRAY($queryitemcolum)) {
			$return = $itemcolum['name_'.$flolang->language];
		}
		else {
			$return = $code_korean;
		}
		$flocache->write_cache("$table,columname_backwards,$code_korean,{$flolang->language}", $return);
		return $return;
	}

	function detailsprotection($detailsid, $act="monster") {
		return  "
					<div>
						<img src='{$this->root}/detailsprotection.php?{$act}id={$detailsid}&amp;view=1' border='0' alt='Do not copy!' style='vertical-align:bottom; text-align:left;'>
					</div>";
#		return  "
#					<div style='background-repeat:no-repeat; background-position:left; background-image:url({$this->root}/detailsprotection.php?{$act}id={$detailsid}&amp;view=1);'>
#						<img src='{$this->root}/detailsprotection.php?{$act}id=$detailsid' border='0' alt='Do not copy!' style='vertical-align:bottom; text-align:left;'>
#					</div>";
	}

	function pictureprotection($pictureid, $imagetitle, $act="item", $settings=array()) {
		if ($act=="spell") $act="skill"; //spell have same columns as skill - just if this will change
		if ($act=="spellbig") $act="skillbig"; //spell have same columns as skill - just if this will change
		foreach ($settings as $settingkey => $settingvalue) {
			$settinglink .= "&amp;$settingkey=$settingvalue";
		}
		switch ($act) {
			case "spell" :
			case "skill" :
				{ $protectionpicture = "{$this->layer_rel}/protect_24_24.gif"; break; }
			case "monster" :
				{ $protectionpicture = "{$this->layer_rel}/protect_50_50.gif"; break; }
			case "spellbig" :
			case "skillbig" :
			case "item" :
				{ $protectionpicture = "{$this->layer_rel}/protect_32_32.gif"; break; }
			case "character" :
			case "itemcharacter" :
				{ $protectionpicture = "{$this->layer_rel}/protect_250_350.gif"; break; }
			case "map" :
				{ $protectionpicture = "{$this->layer_rel}/protect_512_512.gif"; break; }	
		}
		
		if ($settings['maxheight']=="30") $protectionpicture = "{$this->layer_rel}/protect_30_30.gif";
		
		if ($act=="character" OR $act=="itemcharacter") {
			if (is_file($this->pictures_abs."/$act/{$pictureid}_ani.gif")) {
					return  "
					<div style='background-repeat:no-repeat; background-position:left; background-image:url(".$this->pictures_rel."/$act/{$pictureid}_ani.gif);'>
						<img src='{$this->root}/imageprotection.php?picture=$act{$settinglink}' border='0' alt='Do not copy!' title='$imagetitle' style='vertical-align:bottom; text-align:left;'>
					</div>";
			}
		}

		if (!$protectionpicture) {
		return  "
					<div>
						<img src='{$this->root}/imageprotection.php?picture=$act&amp;{$act}id={$pictureid}{$settinglink}' border='0' alt='Do not copy!' title='$imagetitle' style='vertical-align:bottom; text-align:left;'>
					</div>";
		}					
		return  "
					<div style='background-repeat:no-repeat; background-position:left; background-image:url({$this->root}/imageprotection.php?picture=$act&amp;{$act}id={$pictureid}{$settinglink});'>
						<img src='{$protectionpicture}' border='0' alt='Do not copy!' title='$imagetitle' style='vertical-align:bottom; text-align:left;'>
					</div>";
	}

	function mapprotection($mapid, $imagetitle, $npcs="", $settings=array()) {
		if ($npcs!="") $npclocations = "&amp;npcs=$npcs";
		foreach ($settings as $settingkey => $settingvalue) {
			if ($settingkey=="imageid") { $imageid="id='{$settingvalue}'"; continue; }
			$settinglink .= "&amp;$settingkey=$settingvalue";
		}
		return  "
					<div {$imageid} style='background-repeat:no-repeat; background-position:center; background-image:url({$this->root}/imageprotection.php?picture=map&amp;mapid={$mapid}{$settinglink}{$npclocations});'>
						<img src='{$this->root}/imageprotection.php?picture=map{$settinglink}' border='0' alt='Do not copy!' title='$imagetitle' style='vertical-align:bottom; text-align:center;'>
					</div>";

#		return  "
#					<div {$imageid}>
#						<img src='{$this->root}/imageprotection.php?picture=map&amp;mapid={$mapid}{$settinglink}{$npclocations}' border='0' alt='Do not copy!' title='$imagetitle' style='vertical-align:bottom; text-align:center;'>
#					</div>";
#					

	}

	function get_classname($classid, $catclass="land") {
		global $flocache, $flolang;
		$return = array();
		if ($classid=="WPENS" && $catclass=="land") {
			array_push($return,$flolang->all_land_classes);
			return $return;
		}
		elseif ($classid=="ARGHM" && $catclass=="sea") {
			array_push($return,$flolang->all_sea_classes);
			return $return;
		}

		$class = chunk_split($classid,1, ",");
		$class = explode(",", $class);
		foreach ($class as $key => $classid) {
			if ($classid=="") continue;
			if ($flocache->get_cache("classname,classid,$catclass,$classid,{$flolang->language}")) {
				array_push($return,$flocache->get_cache("classname,classid,$catclass,$classid,{$flolang->language}"));
			}
			else {
				$queryclass = MYSQL_QUERY("SELECT name_".$flolang->language." as name FROM flobase_{$catclass}class WHERE classid='".mysql_real_escape_string($classid)."'");
				if ($classname = MYSQL_FETCH_ARRAY($queryclass)) {
					$flocache->write_cache("classname,classid,$catclass,$classid,{$flolang->language}",$classname['name']);
					array_push($return,$classname['name']);
				}
				else {
					$flocache->write_cache("classname,classid,$catclass,$classid,{$flolang->language}", "");
				}
			}
		}
		return $return;
	}

	function get_classlist ($type="landclass", $withship=1) {
		global $flolang;
		$skilltree=",skilltree";
		/* Ignore Masterclass OR skilltree if sea*/
		if ($type=="landclass") $ignore_masterclass = "WHERE classid!='0' AND classid!='1' AND classid!='2' AND classid!='3' AND classid!='4' AND classid!='5' AND classid!='6' AND classid!='7'";
		else unset($skilltree);
		$i=0;
		$queryclasslist = MYSQL_QUERY("SELECT classid, name_".$flolang->language."$skilltree FROM flobase_{$type} $ignore_masterclass ORDER BY name_".$flolang->language."");
		while ($classlist = MYSQL_FETCH_ARRAY($queryclasslist)) {
			if (!$withship && $classlist['classid']=="#") continue;
			$return[$i]['classid'] = $classlist['classid'];
			$return[$i]['classname'] = $classlist['name_'.$flolang->language];
			$return[$i]['skilltree'] = $classlist['skilltree'];
			$i++;
		}
		return $return;
	}

	function get_exp($expamout, $level, $table="land", $round=3) {
		if (intval($expamout)<=0) return "0%";
		if ($table!="land") $table="sea";
		$levelstart = $level-1;
		$levelend = intval($level);

		$queryexp = MYSQL_QUERY("SELECT ".$this->get_columnname("server_exptable_{$table}_level", "misc").",".$this->get_columnname("server_exptable_{$table}_exp", "misc")." FROM server_exptable_{$table} WHERE ".$this->get_columnname("server_exptable_{$table}_level", "misc")."='lv$levelstart' OR ".$this->get_columnname("server_exptable_{$table}_level", "misc")."='lv$levelend'");
		while ($exp = MYSQL_FETCH_ARRAY($queryexp)) {
			$return['exp_'.$exp[$this->get_columnname("server_exptable_{$table}_level", "misc")]] = $exp[$this->get_columnname("server_exptable_{$table}_exp", "misc")];
		}

		$expdifference = $return['exp_lv'.$levelend]-$return['exp_lv'.$levelstart];

		$expdifference = bcdiv(100,bcdiv($expdifference,$expamout, $round),$round);
		return floatval($expdifference)."%";
	}

	function get_server($server=0) {
		if (is_int($server) && $server==0) return;
		if (intval($server)) {
			$queryserver = MYSQL_QUERY("SELECT servername FROM flobase_serverlist WHERE serverid='".intval($server)."' LIMIT 1");
			$server = MYSQL_FETCH_ARRAY($queryserver);
			return $server['servername'];
		}
		else {
			$queryserver = MYSQL_QUERY("SELECT serverid FROM flobase_serverlist WHERE servername LIKE '".mysql_real_escape_string($server)."' LIMIT 1");
			$server = MYSQL_FETCH_ARRAY($queryserver);
			return $server['serverid'];
		}
	}

	function get_serverselect() {
		global $flolang;

		$queryserver = MYSQL_QUERY("SELECT serverid, servername FROM flobase_serverlist ORDER BY servername");
		while ($server = MYSQL_FETCH_ARRAY($queryserver)) {
			if ($_GET['server']==$server['serverid']) $selected = "selected='selected'";
			else unset($selected);
			$return .= "<option value='".$server['serverid']."' $selected>".$server['servername']."</option>";
		}
		return "
 			<select name='server'>
			<option value='all'>{$flolang->global_select_server_all}</option>
			$return
			</select>
		";
	}

	function login($settings=array()) {
		global $mybb, $florensia, $flolang, $flouser;
		if ($settings['blanklinks']) $blanklinks = " target='_blank'";
		if (!$mybb->user['uid']) {
			return "
			<form action='".$this->escape($mybb->input['url'])."' method='post'{$blanklinks}>
				<input type='text' class='textbox' name='username' maxlength='30' style='width: 80px;' />
				<input type='password' class='textbox' name='password' style='width: 80px;' />
				<input type='submit' class='button' name='submit' value='{$flolang->login}' />
				<input type='hidden' name='action' value='do_login' />
				<input value='".$this->escape($_SERVER['REQUEST_URI'])."' name='url' type='hidden' />
			</form>";
		}
		else {
			return "<div class='small'>{$flolang->userbar_hi} {$mybb->user['username']} - <a href='{$this->root}/index.php?action=logout&amp;logoutkey=".$mybb->user['logoutkey']."'{$blanklinks}>{$flolang->logout}</a></div>";
		}
	}

	function adsense($entry=10, $force=FALSE, $bordered=TRUE) {
		global $mybb, $flolang;
		if (!$force && ($mybb->user['uid']!=0 OR !$this->googleadsense OR $this->adsensecount>=2)) return;
		if (!isset($this->adsense) && $entry!=0) { $this->adsense=$entry; return; }

		$this->adsense = bcsub($this->adsense,1);

		if ($force OR $this->adsense<=0 OR $entry==0) {
			$this->adsense=$entry;
			$this->adsensecount = bcadd($this->adsensecount,1);

			if ($bordered) $border="class='bordered'";
			if (!$force) $registertodisable = "<br /><span class='small'><a href='{$this->forumurl}/member.php?action=register'>{$flolang->registertoblockads}</a></span>";
/*			//switch between google and other sponsors
			$rand = mt_rand(0,100);

			if ($rand<20) {
					return "
						<div style='margin:10px; padding:5px; text-align:center; min-height:60px' $border>
							<a href='http://www.florensia-radio.de/' target='_blank'><img src='{$this->root}/sponsors/floradio.jpg' border='0'></a>
							{$registertodisable}
						</div>
					";
			}
*/
			return "
				<div style='margin:10px; padding:5px; text-align:center; min-height:60px' $border>
						<script type=\"text/javascript\"><!--
						google_ad_client = \"pub-6078603438096544\";
						google_ad_slot = \"8511910989\";
						google_ad_width = 468;
						google_ad_height = 60;
						//-->
						</script>
						<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>
						{$registertodisable}						
				</div>
			";
		}
	}
	
	function request_uri($newoptions=array(), $anchor=FALSE) {
		$splituri = explode("?", $_SERVER['REQUEST_URI']);
		$options = explode("#", $splituri[1]);
		
		foreach (explode("&", $options[0]) as $splitoption) {
			$splitoption = explode("=", $splitoption);
			$oldoptions[$splitoption[0]] = $splitoption[1];
		}
		foreach (array_merge($oldoptions, $newoptions) as $optionkey => $optionvalue) {
			if ($optionvalue!==FALSE && strlen($optionvalue)) $urioptions[] = "{$optionkey}={$optionvalue}";
		}
		
		$uri = $splituri[0];
		if (count($urioptions)) $uri .= "?".join("&", $urioptions);
		if ($anchor) $uri .= "#{$anchor}";
		return $uri;
	}

	function parse_link($linkarray) {
		if (!is_array($linkarray)) return "";
		foreach ($linkarray as &$v) {
			$v = str_replace(array(" ", "~"), array("_", "-"), preg_replace("/[;.,:\(\)\[\]\/\\\^?=&<>'\"!%#]/i", "", html_entity_decode($v, ENT_QUOTES, 'UTF-8')));
		}
		return join("/", $linkarray);
	}

	function outlink($subsets=array(), $options=array(), $settings=array()) {
		$defaultsettings = array("escape"=>TRUE, "language"=>TRUE);
		$settings = array_merge($defaultsettings, $settings);
		
		global $stringtable, $classquesttext, $flolang;

		if ($settings['language'] && $classquesttext->language!=$flolang->lang[$flolang->language]->prefered_questtextlang) $options['text'] = $classquesttext->language;
		if ($settings['language'] && $stringtable->language!=$flolang->lang[$flolang->language]->prefered_stringtablelang) $options['names'] = $stringtable->language;

		$link = $this->parse_link($subsets);

		if (count($options)) {
			foreach ($options as $okey => $ovalue) {
				if (!strlen($ovalue)) continue;
				$toption[] = "{$okey}=".urlencode($ovalue);
			}
			if (count($toption)) $link = $link."?".join("&",$toption);
		}
		
		if ($settings['anchor']) $link = $link."#".$this->escape($settings['anchor']);

		#delete "missing" variables and set http://
		$link = $this->root."/".str_replace("//", "/", $link);

		if ($settings['escape']) return $this->escape($link);
		else return $link;
	}

	function get_menubar($class, $settings=array()) {
		global $flolang, $flouser, $flouserdata;
                /* if forum returned before we created an valid user*/
                if (!isset($flouser)) {
                  require_once("{$this->root_abs}/class_user.php");
                  $flouserdata = new class_userdata();
                  $flouser = new class_user(0);
                }
                /* end workaround */
		if ($class=="userbar" && !$flouser->userid) return "<a href='{$this->forumurl}/member.php?action=register'{$blanklinks}>{$flolang->register}</a>";
		
		$defaultsettings = array('blanklinks'=>FALSE, 'align'=>'left');
		$settings = array_merge($defaultsettings, $settings);
		if ($settings['blanklinks']) $blanklinks = " target='_blank'";
		if ($settings['align']=="right") { $dropdownclass = "dropdown2"; } else { $dropdownclass = "dropdown"; }
		
		
		$querymenubarsection = MYSQL_QUERY("SELECT id,standalone, name_".$flolang->language." as name, pagelink, permission FROM flobase_menubar WHERE classname='".mysql_real_escape_string($class)."' AND section=1 ORDER BY rankid, name_".$flolang->language);
		while ($menubarsection = MYSQL_FETCH_ARRAY($querymenubarsection)) {
			if (!trim($menubarsection['name'])) continue;
			//check permissions
			$permgrand = true;
			if (strlen($menubarsection['permission'])) {
				$permgrand = false;
				foreach(explode(";", $menubarsection['permission']) as $permsection) {
					if ($flouser->get_permission($permsection)) { $permgrand = true; break; }
				}
			}
			if (!$permgrand) continue;

			$link = explode('|', $menubarsection['pagelink']);
			$link[1] = str_replace(array("{userid}", "{username}"), array($flouser->userid, $flouser->user['username']), $link[1]);
			switch ($link[0]) {
				case "intern": { $menubarlink = "{$this->root}/".$link[1]; break; }
				case "forum": { $menubarlink = "{$this->forumurl}/".$link[1]; break; }
				case "extern": { $menubarlink = $link[1]; break; }
				default: { $menubarlink= "{$this->root}/index.php"; $menubarsection['name'].=" (No link specified)"; }
			}
			if ($link[0]!="intern" && !$blanklinks) $linktarget = "target='_blank'";
			else unset($linktarget);

			if ($menubarsection['standalone']) $menubar .= "<li><a href='$menubarlink' {$linktarget} {$blanklinks}>".$this->escape($menubarsection['name'])."</a></li>";
			else {
				$menubar .= "<li><a href='$menubarlink' {$linktarget} {$blanklinks} class='dir'>".$menubarsection['name']."</a>
							<ul class='bordered'>";

				$querymenubarsubsection = MYSQL_QUERY("SELECT name_".$flolang->language." as name, pagelink, permission FROM flobase_menubar WHERE classname='".mysql_real_escape_string($class)."' AND sectionid={$menubarsection['id']} ORDER BY rankid, name_".$flolang->language);
				while ($menubarsubsection = MYSQL_FETCH_ARRAY($querymenubarsubsection)) {
					if (!trim($menubarsubsection['name'])) continue;
					//check permissions
					$permgrand = true;
					if (strlen($menubarsubsection['permission'])) {
						$permgrand = false;
						foreach(explode(";", $menubarsubsection['permission']) as $permsection) {
							if ($flouser->get_permission($permsection)) { $permgrand = true; break; }
						}
					}
					if (!$permgrand) continue;

					$link = explode('|', $menubarsubsection['pagelink']);
					$link[1] = str_replace(array("{userid}", "{username}"), array($flouser->userid, $flouser->user['username']), $link[1]);
					switch ($link[0]) {
						case "intern": { $menubarlink = "{$this->root}/".$link[1]; break; }
						case "forum": { $menubarlink = "{$this->forumurl}/".$link[1]; break; }
						case "extern": { $menubarlink = $link[1]; break; }
						default: { $menubarlink= "{$this->root}/index.php"; $menubarsection['name'].=" (No link specified)"; }
					}
					if ($link[0]!="intern" && !$blanklinks) $linktarget = "target='_blank'";
					else unset($linktarget);

					$menubar .= "<li><a href='$menubarlink' {$linktarget} {$blanklinks}>".$this->escape($menubarsubsection['name'])."</a></li>
					";
				}

				$menubar .= "</ul></li>
				";
			}
		}
		return "
			<ul class='$dropdownclass dropdown-horizontal'>
				{$menubar}
			</ul>
		";
	}
	function output_page($content="", $settings=array()) {
		global $mybb, $flocache, $flolang;
		
		//headers
		$headers = array("header_1.jpg:footer_1.jpg", "header_2.jpg:footer_2.jpg", "header_3.png:footer_3.png", "header_4.jpg:footer_4.jpg");
		//default:

		$this->headerimage = 1;
		if ($mybb->user['uid']) {
			$lastheader = explode('-', $mybb->user['fid7']);
			if (!preg_match('/^[0-9]+-[0-9]+$/',$mybb->user['fid7']) OR date("jnY", $lastheader[0])!=date("jnY")) {
				$this->headerimage = rand(1,count($headers));
				MYSQL_QUERY("UPDATE forum_userfields SET fid7='".date("U")."-{$this->headerimage}' WHERE ufid='{$mybb->user['uid']}'");
			}
			else {
				$this->headerimage = $lastheader[1];
			}
		}
		else {
			if ($flocache->get_cache("visitor,guests,headerimage,".getenv("REMOTE_ADDR"))===FALSE) {
				$this->headerimage = rand(1,count($headers));
				$flocache->write_cache("visitor,guests,headerimage,".getenv("REMOTE_ADDR"),$this->headerimage);
			}
			else {
				$this->headerimage = $flocache->get_cache("visitor,guests,headerimage,".getenv("REMOTE_ADDR"));
			}
		}
		$headerselect = explode(":", $headers[bcsub($this->headerimage,1)]);
		$headerimage = "{$this->layer_rel}/header/".$headerselect[0];
		$footerimage = "{$this->layer_rel}/footer/".$headerselect[1];


		//christmas-special
		//$headerimage = "{$this->layer_rel}/header/header_christmasspecial.jpg";
		//$footerimage = "{$this->layer_rel}/footer/footer_christmasspecial.jpg";
		

		if ($this->cache) { $flocache->save_cache(); }
		else { $flocache->remove_cache(); }

		if ($content=="" && !count($this->notices)) { 
			$content = "<div style='text-align:center; padding-top:50px;'>{$flolang->content_error}</div>";
		}

		foreach ($this->notices as $noticetext) {
			$notices .= $noticetext;
		}
		if ($notices) {
			$notices = "
				<div class='subtitle' style='font-weight:normal; margin-bottom:15px;'>
					<table style='width:100%'><tr>
						<td style='width:50px; min-height:40px; background-image:url({$this->layer_rel}/icon_chat.png); background-repeat:no-repeat; background-position:center;'></td>
						<td style='vertical-align:middle;'>{$notices}</td>
					</tr></table>
				</div>
			";
		}

		echo "
		<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
		\"http://www.w3.org/TR/html4/loose.dtd\">
		<html>
			<head>
				<title>".join(" - ", $this->sitetitle)."</title>
				<meta name=\"verify-v1\" content=\"jstu9BbKxY9arFQGfsAANFfIpW1HGAz16XafDieYL54=\" />
				<meta name='author' content='Noxx'>
				<meta name='copyight' content='(c) 2008-".date("Y")." by florensia-base.com'>
				<meta name='language' content='{$flolang->language}'>
				<meta name='revisit-after' content='daily'>
				<meta name='keywords' content='{$flolang->crawler_keywords},".array_shift($this->sitetitle)."'>
				<meta name='description' content='{$flolang->crawler_description}'>
				<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
				<link rel='shortcut icon' href='{$this->layer_rel}/favicon.ico'>
				<link rel='stylesheet' type='text/css' href='{$this->root}/css.css?".filectime("{$this->root_abs}/css.css")."'>
				<link rel='stylesheet' type='text/css' href='{$this->root}/dropdownv3.css'>
				<link rel='alternate' type='application/rss+xml' title='English Market Feed' href='http://en.florensia-base.com/market_en.rss'>
				<script type='text/javascript' src='{$this->root}/js/overlib/overlib.js'><!-- overLIB (c) Erik Bosrup --></script>
				<script src='{$this->root}/javascript.js?".filectime("{$this->root_abs}/javascript.js")."' type='text/javascript'></script>
				<!--<script type='text/javascript' src='https://apis.google.com/js/plusone.js'></script>-->

				<!--[if lt IE 8]>
				<script src='http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js' type='text/javascript'></script>
				<script src='{$this->root}/javascript_ie.js' type='text/javascript'></script>
				<![endif]-->
			</head>
			<body id='body' style='margin:0px;' bgcolor='#37658a'>
			<div id='overDiv' style='position:absolute; visibility:hidden; z-index:1000;'></div>";
			if (is_file("{$this->root}/experimental")) echo "<div class='warning' style='text-align:center; background-color:#4C8AB1; margin:0px;'>Experimental Version</div>";

			echo "
			<div style='background-color:#1c3e54; background-position:center; background-image:url({$this->layer_rel}/flobase_slice_02.jpg); background-repeat:repeat-y; min-width:990px;'>
				<div style='background-position:top; background-image:url({$this->layer_rel}/flobase_slice_05.jpg); background-repeat:repeat-x;'>
					<div style='background-position:bottom; background-image:url({$this->layer_rel}/flobase_slice_04.jpg); background-repeat:repeat-x;'>
						<div style='width:990px; margin:auto; background-position:top; background-image:url({$headerimage}); background-repeat:no-repeat'>
							<div style='background-position:bottom; background-image:url({$footerimage}); background-repeat:no-repeat;'>
			
								<div style='margin-left:75px; margin-right:75px;'>
									<div style='float:left; padding-top:5px;'>";
										foreach ($flolang->lang as $langkey => $langname) {
											if (!$flolang->lang[$langkey]->visible_flag) continue;
											echo "<a href='http://".$flolang->lang[$langkey]->languageid.".florensia-base.com".$this->escape($_SERVER['REQUEST_URI'])."'><img src='{$this->layer_rel}/flags/png/".$flolang->lang[$langkey]->flagid.".png' alt='".$flolang->lang[$langkey]->languagename."' title='".$flolang->lang[$langkey]->languagename."' border='0'></a>
											";
										}

									echo "
										<a href=\"http://www.addthis.com/bookmark.php?v=250&amp;username=noxx000\" class=\"addthis_button_compact\" style=\"margin-left:15px;\">
											<img src=\"{$this->layer_rel}/addthis-plus.gif\" width=\"16\" height=\"16\" alt=\"Share\" style=\"border:0\"/>
										</a>
										<script type=\"text/javascript\">var addthis_config = {\"data_track_clickback\":true};</script>
										<script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=noxx000\"></script>
										<!--<g:plusone size='small' count='false'></g:plusone>-->
										<a href='http://www.twitter.com/Flobase' target='_blank'><img src='http://twitter-badges.s3.amazonaws.com/t_mini-a.png' alt='Follow Flobase on Twitter' style='border:none;'/></a>
									</div>
									<div style='margin-left:430px; padding-top:3px;'>".$this->quicksearch(array("automargin"=>false, "language"=>true))."</div>
									<div class='sitemenu' style='margin-top:200px; text-align:right;'>
										".$this->login(array('blanklinks'=>$settings['blanklinks']))."
									</div>
									<div style='margin-top:20px;' class='sitemenu'>
										<div style='float:right; text-align:right;'>
											".$this->get_menubar("userbar", array('blanklinks'=>$settings['blanklinks'], 'align'=>'right'))."
										</div>
										<div>
											".$this->get_menubar("menubar", array('blanklinks'=>$settings['blanklinks']))."
										</div>
									</div>
									<div style='margin-top:75px; margin-bottom:50px; min-height:390px;'>
										$notices
										$content
									</div>
									<div style='margin-left:347px; margin-right:10px; text-align:right; height:60px; margin-bottom:10px;'>";
										if ($this->googleadsense) { echo $this->adsense(0, TRUE, FALSE); } 
									echo "
									</div>
									<div style='margin-left:437px; color:#000000;' class='small'>
											<a href='".$this->outlink(array("legalnotice"))."' style='color:#000000'>&copy;florensia-base.com</a>&nbsp;
											<a href='mailto:nwwy[at]gmx[dot]de' style='color:#000000'>&copy;Layout flipp</a>&nbsp;
											&copy;Game Burda:IC/Netts
									</div>
								</div>
			
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style='height:40px;'></div>";

			if (is_file("{$this->root}/experimental")) echo "<div class='warning' style='text-align:center; background-color:#37658A; margin:0px;'>Experimental Version</div>";

			echo "
				<div style='visibility:collapse'><a href='{$this->root}/antibot.php' style='color:#37658a;'>Antibot</a></div>
				
			";
                                if ($this->googleanalytics) {
                                                        ?>
                                                          <script type="text/javascript">

                                                            var _gaq = _gaq || [];
                                                            _gaq.push(['_setAccount', 'UA-2936193-4']);
                                                            _gaq.push(['_setDomainName', 'florensia-base.com']);
                                                            _gaq.push(['_trackPageview']);

                                                            (function() {
                                                              var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                                                              ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                                                              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                                                            })();

                                                          </script>
							  <!-- Piwik -->
							  <script type="text/javascript">
							  var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.penya.de/" : "http://piwik.penya.de/");
							  document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
							  </script><script type="text/javascript">
							  try {
							  var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 3);
							  piwikTracker.trackPageView();
							  piwikTracker.enableLinkTracking();
							  } catch( err ) {}
							  </script><noscript><p><img src="http://piwik.penya.de/piwik.php?idsite=3" style="border:0" alt="" /></p></noscript>
							  <!-- End Piwik Tracking Code -->
                                                        <?PHP 
                                }
                        echo "
			</body>
		</html>
		";
		exit;
	}

}

?>
