<?PHP

class class_usermarket {

	function updateentry($exchangetype, $itemid, $action, $extendtime=0) {
		global $flouser, $flolang, $stringtable, $mybb, $florensia;
// 		$flolang->load("market");
		if ($exchangetype!="buy" && $exchangetype!="sell") return false;

		//not logged in
		if (!$mybb->user['uid']) {
			$florensia->notice($flolang->market_updateentry_error_notloggedin, "warning");
			return false;
		}
		//banned from market
		if (!$flouser->get_permission("add_usermarket")) {
			$florensia->notice($flolang->market_updateentry_error_banned, "warning");
			return false;
		}


		if ($action=="add" OR $action=="update") {
			if ($action == "add" && MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE userid='".$mybb->user['uid']."' AND exchangetype='$exchangetype' AND itemid='".mysql_real_escape_string($itemid)."'"))!=0) {
				$florensia->notice($flolang->market_updateentry_error_alreadysaved, "warning");
				return false;
			}
			//item doesn't exist
			elseif (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT itemid FROM server_item_idtable WHERE itemid='".mysql_real_escape_string($itemid)."'"))==0) {
				$florensia->notice($flolang->market_updateentry_error_existitem, "warning");
				return false;
			}

			$character = new class_character($_POST['charactername']);
			if (!$character->is_valid()) {
				if ($character->get_errormsg()=="timeout") $florensia->notice($flolang->market_updateentry_error_character_timeout, "warning");
				else $florensia->notice($flolang->market_updateentry_error_character_exists, "warning");
				return false;	
			}

			//finally, if character verified...
			$timeout = bcadd(date("U"),intval($_POST['timeoutdays'])*24*60*60);
			$itemamount = intval($_POST['itemamount']);
			if ($itemamount==0 OR $itemamount=="") $itemamount=1;
			foreach ($_POST['marketlanguage'] as $languageid) {
				if (!$flolang->lang[$languageid]->visible_usermarket) continue;
				$marketlanguage .= $comma.$languageid;
				$comma=",";
			}
			$exchangegelt = intval(str_replace(array(",", "."), "", $_POST['exchangegelt']));

			if ($action=="add") {
				if (!MYSQL_QUERY("INSERT INTO flobase_usermarket (userid, itemid, itemamount, exchange, exchangegelt, exchangetype, server, characterid, marketlanguage, timeout, createtime) VALUES('".$mybb->user['uid']."', '".mysql_real_escape_string($itemid)."', '$itemamount', '".mysql_real_escape_string($_POST['exchange'])."', '$exchangegelt', '$exchangetype', '".mysql_real_escape_string($character->data['server'])."', '{$character->data['characterid']}', '$marketlanguage', '$timeout', '".date("U")."')")) {
					$florensia->notice($flolang->market_updateentry_error_default, "warning");
					return false;
				}
				else {
					$florensia->notice($flolang->sprintf($flolang->market_updateentry_successful_add, $stringtable->get_string($itemid), intval($_POST['timeoutdays'])), "successful");
					return true;
				}
			}
			elseif ($action=="update") {
				if (!MYSQL_QUERY("UPDATE flobase_usermarket SET itemamount='$itemamount', exchange='".mysql_real_escape_string($_POST['exchange'])."', exchangegelt='$exchangegelt', server='".mysql_real_escape_string($character->data['server'])."', characterid='{$character->data['characterid']}', marketlanguage='$marketlanguage', timeout='$timeout' WHERE userid='{$mybb->user['uid']}' AND itemid='".mysql_real_escape_string($itemid)."' AND exchangetype='$exchangetype'")) {
					$florensia->notice($flolang->market_updateentry_error_default, "warning");
					return false;
				}
				else {
					$florensia->notice($flolang->sprintf($flolang->market_updateentry_successful_update, $stringtable->get_string($itemid)), "successful");
					return true;
				}
			}

		}
		elseif ($action=="delete") {
			MYSQL_QUERY("DELETE FROM flobase_usermarket WHERE userid='".$mybb->user['uid']."' AND exchangetype='$exchangetype' AND itemid='".mysql_real_escape_string($itemid)."'");
			$florensia->notice($flolang->sprintf($flolang->market_updateentry_successful_delete, $stringtable->get_string($itemid)), "successful");
			return true;
		}
		elseif ($action=="extend" && intval($extendtime)) {
			$maxtime = 2*30;
			$extendtimedb = intval($extendtime)*24*60*60;
			$verifyextend = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT timeout FROM flobase_usermarket WHERE userid='".$mybb->user['uid']."' AND exchangetype='$exchangetype' AND itemid='".mysql_real_escape_string($itemid)."'"));
			if (bcadd($verifyextend['timeout'],$extendtimedb) >= bcadd(date("U"), $maxtime*24*60*60)) {
				$florensia->notice($flolang->sprintf($flolang->market_updateentry_error_extend_timespan, $stringtable->get_string($itemid), intval($extendtime), $maxtime), "warning");
				return false;
			}

			MYSQL_QUERY("UPDATE flobase_usermarket SET timeout=timeout+$extendtimedb WHERE userid='".$mybb->user['uid']."' AND exchangetype='$exchangetype' AND itemid='".mysql_real_escape_string($itemid)."'");
			$florensia->notice($flolang->sprintf($flolang->market_updateentry_successful_extend, $stringtable->get_string($itemid), $extendtime), "successful");
			return true;
		}
	}

	function refresh() {
		global $florensia;
		MYSQL_QUERY("DELETE FROM flobase_usermarket WHERE timeout<='".date("U")."'");

		if (!is_file("{$florensia->root_abs}/market_en.rss") OR filemtime("{$florensia->root_abs}/market_en.rss")<bcsub(date("U"),600)) {
			$this->create_rss();
		}
	}

	function create_rss() {
		global $parser, $stringtable, $florensia;

			$rssfeed = new SimpleXMLElement('<rss version="2.0"></rss>');

			$rssfeed->channel->title = "Florensia-Base Market";
			$rssfeed->channel->link = "http://www.florensia-base.com";
			$rssfeed->channel->description = "Items to sell and buy on Florensia-Base";
			$rssfeed->channel->lastBuildDate = date(DATE_RSS);
			$rssfeed->channel->language = "en-en";

			$queryitem = MYSQL_QUERY("SELECT userid, itemid, exchangetype, exchange, exchangegelt, server, charname, createtime FROM flobase_usermarket as m, flobase_character as c WHERE c.characterid=m.characterid ORDER BY createtime DESC LIMIT 20");
			for ($i=0; $item = MYSQL_FETCH_ARRAY($queryitem); $i++) {

				if ($item['exchangetype']=="sell") $market_exchangestatus = "Selling: ";
				else $market_exchangestatus = "Buying: ";

				$parser_options = array(
					'allow_html' => 0,
					'allow_mycode' => 1,
					'allow_smilies' => 0,
					'allow_imgcode' => 0,
					'filter_badwords' => 1
				);

				$rssfeed->channel->item[$i]->title = "$market_exchangestatus".$stringtable->get_string($item['itemid'], array('language'=>'English'))." on {$item['server']} from {$item['charname']}";
				$rssfeed->channel->item[$i]->description = strip_tags($parser->parse_message($item['exchange'], $parser_options));
				$rssfeed->channel->item[$i]->link = "http://en.florensia-base.com/itemdetails/{$item['itemid']}/".$florensia->parse_link(array($stringtable->get_string($item['itemid'], array('language'=>"English"))))."#market";
				$rssfeed->channel->item[$i]->pubDate = date(DATE_RSS, $item['createtime']);
			}
			$rssfeed->asXML("{$florensia->root_abs}/market_en.rss");
	}

	function quickform($itemid) {
		global $stringtable, $mybb, $flouser, $florensia, $flolang;
		$this->refresh();
		$flolang->load("market");

		if ($_POST['add_buy']) $this->updateentry("buy", $itemid, "add");
		elseif ($_POST['delete_buy']) $this->updateentry("buy", $itemid, "delete");
		elseif ($_POST['add_sell']) $this->updateentry("sell", $itemid, "add");
		elseif ($_POST['delete_sell']) $this->updateentry("sell", $itemid, "delete");

		if ($mybb->user['uid']) {
			/*	wishlist	*/
			if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE userid='".$mybb->user['uid']."' AND exchangetype='buy' AND itemid='".mysql_real_escape_string($itemid)."'"))!=0) {
				$wishlistdelete = "<input type='submit' name='delete_buy' value='".$flolang->sprintf($flolang->market_quickform_submit_wishlist_delete, $stringtable->get_string($itemid))."'>";
			}
			elseif ($flouser->get_permission("add_usermarket")) $wishlistadd = "<input type='submit' name='add_buy' value='".$flolang->sprintf($flolang->market_quickform_submit_wishlist_add, $stringtable->get_string($itemid))."'>";
	
			/* sales listing	*/
			if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE userid='".$mybb->user['uid']."' AND exchangetype='sell' AND itemid='".mysql_real_escape_string($itemid)."'"))!=0) {
				$salelistdelete = "<input type='submit' name='delete_sell' value='".$flolang->sprintf($flolang->market_quickform_submit_saleslisting_delete, $stringtable->get_string($itemid))."'>";
			}
			elseif ($flouser->get_permission("add_usermarket")) $salelistadd = "<input type='submit' name='add_sell' value='".$flolang->sprintf($flolang->market_quickform_submit_saleslisting_add, $stringtable->get_string($itemid))."'>";
	
			if ($flouser->get_permission("add_usermarket") && ($wishlistadd OR $salelistadd)) {
					//form
					$timespanpreselected = array(7=>" selected='selected'");
					for ($i=1; $i<=30; $i++) {
						$timespan .= "<option value='$i'{$timespanpreselected[$i]}>".$flolang->sprintf($flolang->market_quickform_timespan, $i)."</option>"; 
						if ($i>=7) $i=$i+6;
					}
					//languageselect
					$marketlanguageselected[$flolang->language]="selected='selected'";
					foreach ($flolang->lang as $langkey => $langvalue) {
						if (!$flolang->lang[$langkey]->visible_usermarket) continue;
						$marketlanguage .= "<option value='".$flolang->lang[$langkey]->languageid."' ".$marketlanguageselected[$flolang->lang[$langkey]->languageid].">".$flolang->lang[$langkey]->languagename."</option>";
					}
					$marketlanguage = "
					<select name='marketlanguage[]' multiple='multiple' class='small'>
						$marketlanguage
					</select>";

						$marketform = "
						<div class='subtitle small' style='font-weight:normal;'>
							<div style='text-align:center; font-weight:bold;'>{$flolang->market_quickform_notice_title}</div>
							<div id='expand_marketform'>
								<div style='border-top:1px solid;'>
									<table style='width:100%'>
										<tr><td>{$flolang->market_quickform_notice_character}</td></tr>
										<tr><td><input type='text' name='charactername' maxlength='100'></td></tr>
										<tr><td>{$flolang->market_quickform_notice_timespan}</td></tr>
										<tr><td><select name='timeoutdays'>$timespan</select></td></tr>
										<tr><td>{$flolang->market_quickform_notice_amount}</td></tr>
										<tr><td><input type='text' name='itemamount' value='1' maxlength='3' style='width:20px;'></td></tr>
										<tr><td>{$flolang->market_quickform_notice_exchangegelt}</td></tr>
										<tr><td><input type='text' name='exchangegelt' maxlength='10'></td></tr>
										<tr><td>{$flolang->market_quickform_notice_marketlanguage}</td></tr>
										<tr><td>$marketlanguage</td></tr>
										<tr><td>{$flolang->market_quickform_notice_exchange}</td></tr>
										<tr><td><textarea name='exchange' style='width:95%; height:60px'></textarea></td></tr>
									</table>
								</div>
								<div style='text-align:center; padding-bottom:3px;'>$wishlistadd $salelistadd</div>
							</div>
						</div>
						";
			}
			elseif (!$flouser->get_permission("add_usermarket")) $marketform = "<div class='subtitle small' style='font-weight:normal; text-align:center'>{$flolang->market_updateentry_error_banned}</div>";
			
			if ($wishlistdelete OR $salelistdelete) {
				$deletebuttoms = "<div class='subtitle' style='font-weight:normal; text-align:center; margin-bottom:10px;'>{$wishlistdelete} &nbsp; {$salelistdelete}</div>";
				
			}
		}
		else $marketform = "<div class='subtitle small' style='font-weight:normal; text-align:center'>{$flolang->market_quickform_error_notloggedin}</div>";

		//statistics
		$sellers = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE exchangetype='sell' AND itemid='".mysql_real_escape_string($itemid)."'"));
		$buyers = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE exchangetype='buy' AND itemid='".mysql_real_escape_string($itemid)."'"));

			/*$return = "
				<form action='".$florensia->escape($_SERVER['REQUEST_URI'])."' method='post'>
					<div class='subtitle small' style='text-align:center'>{$flolang->market_title_main}</div>
					<div class='bordered small'>
						<table style='width:100%;'>
							<tr><td style='width:50%; text-align:center;'><span style='font-weight:bold;'>{$flolang->market_subtitle_buy}:</span> <a href='".$florensia->outlink(array("market", $itemid, $stringtable->get_string($itemid)))."'>".$flolang->sprintf($flolang->market_itemdetails_summary_buy, $buyers)."</a><br />$wishlistdelete</td><td style='text-align:center;'><span style='font-weight:bold;'>{$flolang->market_subtitle_sell}:</span> <a href='".$florensia->outlink(array("market", $itemid, $stringtable->get_string($itemid)))."'>".$flolang->sprintf($flolang->market_itemdetails_summary_sell, $sellers)."</a><br />$salelistdelete</td></tr>
						</table>
					</div>
					$marketform
				</form>
			";*/
			$return = "
				<form action='".$florensia->escape($florensia->request_uri(array(), 'market'))."' method='post'>
					{$deletebuttoms}
					{$marketform}
				</form>
			";
		return $return;
	}

	function get_tabdesc($itemid) {
		global $flolang;
		
		$sellers = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE exchangetype='sell' AND itemid='".mysql_real_escape_string($itemid)."'"));
		$buyers = MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_usermarket WHERE exchangetype='buy' AND itemid='".mysql_real_escape_string($itemid)."'"));
		return $flolang->sprintf($flolang->tabbar_desc_market, $buyers, $sellers);
	}
}


?>
