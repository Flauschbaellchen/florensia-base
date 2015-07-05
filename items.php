<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("item");
$flolang->load("droplist");
$flolang->load("skilltree");

if (!isset($_GET['itemid']) && !isset($_GET['search']) && !isset($_GET['itemcat'])) {
	$cat=0;
	$queryitemcategories = MYSQL_QUERY("SELECT itemcount, name_{$flolang->language}, name_table FROM flobase_item_categories WHERE (itemcount>'0' OR name_table IS NULL) AND vieworder<'100' ORDER BY vieworder, name");
	while ($itemcategories = MYSQL_FETCH_ARRAY($queryitemcategories)) {
		if ($itemcategories['name_table']) {
			$itemoverview[$cat]['catentries'][] = array("<a href='".$florensia->outlink(array("itemcategorie", $itemcategories['name_table']))."'>".$itemcategories['name_'.$flolang->language]."</a>", $flolang->sprintf($flolang->itemcount, $itemcategories['itemcount']));
		}
		else {
			$cat++;
			$itemoverview[$cat]['catname'] = $itemcategories['name_'.$flolang->language];
		}
	}

	for ($i=1; $i<=count($itemoverview); $i=$i+2) {
		unset($list1, $list2);
		foreach ($itemoverview[$i]['catentries'] as $itementries) {
			$list1 .= "<div style='float:right;' class='small'>{$itementries[1]}</div><div>{$itementries[0]}</div>";
		}
		if ($itemoverview[$i+1]['catentries']) {
			foreach ($itemoverview[$i+1]['catentries'] as $itementries) {
				$list2 .= "<div style='float:right;' class='small'>{$itementries[1]}</div><div>{$itementries[0]}</div>";
			}
		}
		if ($list1) $list1 = "<div class='subtitle'>{$itemoverview[$i]['catname']}</div>{$list1}";
		if ($list2) $list2 = "<div class='subtitle'>{$itemoverview[$i+1]['catname']}</div>{$list2}";
		
		$content .= "
			<div style='margin-top:15px;'><table style='width:100%;'>
				<tr><td style='width:50%; vertical-align:top; padding-right:4px;'>
					$list1
				</td><td style='vertical-align:top; padding-left:4px;'>
					$list2
				</td></tr>
			</table></div>";
	}

	$florensia->sitetitle("Itemdatabase");
	$florensia->sitetitle("Categories");
	$florensia->output_page($content);

}
elseif (isset($_GET['itemcat'])) {
		$querycategory = MYSQL_QUERY("SELECT * FROM flobase_item_categories WHERE name_table='".mysql_real_escape_string($_GET['itemcat'])."'");
		if ($category = MYSQL_FETCH_ARRAY($querycategory)) {

			$pageselectlinkoptions = array();
			if ($_GET['order']=="lvlland" && $category['orderby_lvlland']==1) { $orderby = $florensia->get_columnname("lvlland", "item").","; $orderbylink="&amp;order=lvlland"; }
			elseif ($_GET['order']=="lvlsea" && $category['orderby_lvlsea']==1) { $orderby = $florensia->get_columnname("lvlsea", "item").","; $orderbylink="&amp;order=lvlsea"; }
			else unset($orderby, $_GET['order'], $orderbylink);

			if (isset($_GET['order'])) { 
				$pageselectlinkoptions['order']= $_GET['order'];
				$orderselected[$_GET['order']]="selected='selected'";
			}
			if ($category['orderby_lvlsea']==1) { $orderby_lvloptions = "<option value='lvlsea' ".$orderselected['lvlsea'].">{$flolang->item_order_sealvl}</option>"; }
			if ($category['orderby_lvlland']==1) { $orderby_lvloptions .= "<option value='lvlland' ".$orderselected['lvlland'].">{$flolang->item_order_landlvl}</option>"; }
			if (isset($orderby_lvloptions)) {
				$orderby_lvloptions = "
					<select name='order' class='small'>
						<option value='name' ".$orderselected['name'].">{$flolang->item_order_name}</option>
						$orderby_lvloptions
					</select>
				";
			}
			if ($category['selectby_itemtype']) {
				if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT itemtypeid FROM flobase_item_types WHERE itemtypeid='".mysql_real_escape_string($_GET['itemtype'])."'"))==1) {
						$selectitemtype = $whereand.$florensia->get_columnname("itemtype", "item")." = '".mysql_real_escape_string($_GET['itemtype'])."'";
						$pageselectlinkoptions['itemtype'] = $_GET['itemtype'];
						$whereand="AND ";
				}
				$selectby_itemtypes = floclass_item::get_typeselect($category['selectby_itemtype']);
			}

			if ($category['selectby_landclasses']==1) {
				unset($orderselected);
				if (isset($_GET['landclass'])) {
					if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT id FROM flobase_landclass WHERE classid='".mysql_real_escape_string($_GET['landclass'])."'"))==1) {
						$orderselected[$_GET['landclass']]="selected='selected'";
						$selectclass = $whereand.$florensia->get_columnname("landclass", "item")." LIKE '%".mysql_real_escape_string($_GET['landclass'])."%'";
						$pageselectlinkoptions['landclass'] = $_GET['landclass'];
						$whereand="AND ";
					}

				}
				$selectby_classoptions = "
					<select name='landclass' class='small'>
						<option value='all' ".$orderselected['all'].">{$flolang->item_order_alllandclasses}</option>";

					$classlist = $florensia->get_classlist("landclass", 0);
					foreach ($classlist as $classvalue) {
						$selectby_classoptions .= "<option value='".$classvalue['classid']."' ".$orderselected[$classvalue['classid']].">".$classvalue['classname']."</option>";
					}

				$selectby_classoptions .= "						
					</select>
				";
			}
			elseif ($category['selectby_seaclasses']==1) {
				unset($orderselected);
				if (isset($_GET['seaclass'])) { 
					if ($class = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT * FROM flobase_seaclass WHERE classid='".mysql_real_escape_string($_GET['seaclass'])."'"))) {
						$orderselected[$_GET['seaclass']]="selected='selected'";
						$selectclass = $whereand.$florensia->get_columnname("seaclass", "item")." LIKE '%".mysql_real_escape_string($_GET['seaclass'])."%'";
						$pageselectlinkoptions['seaclass'] = $_GET['seaclass'];
						$whereand="AND ";
					}

				}
				$selectby_classoptions = "
					<select name='seaclass' class='small'>
						<option value='all' ".$orderselected['all'].">{$flolang->item_order_allseaclasses}</option>";
					$classlist = $florensia->get_classlist("seaclass", 0);
					foreach ($classlist as $classvalue) {
						$selectby_classoptions .= "<option value='".$classvalue['classid']."' ".$orderselected[$classvalue['classid']].">".$classvalue['classname']."</option>";
					}
				$selectby_classoptions .= "						
					</select>
				";
			}
			unset($workaround);
			if ($category['orderby_lvlsea']==1) { $workaround = $whereand.$florensia->get_columnname("lvlsea", "item")."!='0'"; $whereand="AND "; }
			if ($category['orderby_lvlland']==1) { $workaround .= $whereand.$florensia->get_columnname("lvlland", "item")."!='0'"; $whereand="AND "; }

			if ($whereand) $where = "AND $selectitemtype $selectclass $workaround";
			list($maxitems) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(*) FROM server_item_".$category['name_table']." as d, server_item_idtable as i, server_stringtable as s WHERE d.".$florensia->get_columnname("itemid", "item")."=i.itemid AND s.Code=i.itemid $where"));
			$pageselect = $florensia->pageselect($maxitems, array("itemcategorie", $category['name_table']), $pageselectlinkoptions);

			if ($maxitems>0) {
				$queryitemlist = MYSQL_QUERY("SELECT * FROM server_item_".$category['name_table']." as d, server_item_idtable as i, server_stringtable as s WHERE d.".$florensia->get_columnname("itemid", "item")."=i.itemid AND s.Code=i.itemid $where ORDER BY {$orderby} s.{$stringtable->language} LIMIT ".$pageselect['pagestart'].",".$florensia->pageentrylimit);
				while ($itemlist = MYSQL_FETCH_ARRAY($queryitemlist)) {
					$content .= $florensia->adsense(13);
					$item = $floclass->create_item($itemlist);
					if ($item->is_valid()) $content .="<div class='shortinfo_".$florensia->change()."'>".$item->shortinfo(array('marketlist'=>true))."</div>";
				}
			}
			
			if (!isset($content)) {
				$content = "
					<div style='text-align:center; margin-bottom:15px;' class='subtitle'><a href='".$florensia->outlink(array("itemoverview"))."'>{$flolang->backtooverview}</a></div>
					<div style='text-align:center; margin-bottom:25px;'>".$florensia->quick_select('itemcat', array('itemcat'=>$_GET['itemcat'], 'page'=>$_GET['page'], 'search'=>$_GET['search']), array($flolang->item_orderby=>$orderby_lvloptions, $flolang->item_select_class=>$selectby_classoptions, $flolang->item_select_itemtype=>$selectby_itemtypes), array('namesselect'=>1))."</div>
					<div style='text-align:center; margin-bottom:20px;' class='warning'>".$flolang->sprintf($flolang->item_categorie_noentry, $category['name_'.$flolang->language])."</div>
					<div style='text-align:center;'>{$flolang->quicksearch} ".$florensia->quicksearch(array('language'=>true))."</div>
				";
			}
			else {
				$content = "
				<div style='text-align:center; margin-bottom:5px;'>".$florensia->quick_select('itemcat', array('itemcat'=>$_GET['itemcat'], 'page'=>$_GET['page'], 'search'=>$_GET['search']), array($flolang->item_orderby=>$orderby_lvloptions, $flolang->item_select_class=>$selectby_classoptions, $flolang->item_select_itemtype=>$selectby_itemtypes), array('namesselect'=>1))."</div>
				<div class='subtitle' style='margin-bottom:10px;'><a href='".$florensia->outlink(array("itemoverview"))."'>{$flolang->item_maintitle}</a> &gt; ".$category['name_'.$flolang->language]."</div>
				<div style='margin-bottom:10px;'>".$pageselect['selectbar']."</div>
				$content
				<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
				";
			}

			$florensia->sitetitle("Itemdatabase");
			$florensia->sitetitle("Category ".$category['name_'.$flolang->language]);
			$florensia->output_page($content);
		}
		else {
			$content = "
					<div style='text-align:center; margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("itemoverview"))."'>{$flolang->backtooverview}</a></div>
					<div style='text-align:center;  margin-bottom:20px;'>{$flolang->quicksearch} ".$florensia->quicksearch(array('language'=>true))."</div>
					<div style='text-align:center;' class='warning'>{$flolang->item_nocategorie}</div>
			";
			$florensia->sitetitle("Itemdatabase");
			$florensia->sitetitle("Search");
			$florensia->output_page($content);
		}

}
elseif (isset($_GET['search'])) {
		foreach (explode(" ", $_GET['search']) as $keyword) {
			$searchstring[] = "{$stringtable->language} LIKE '%".get_searchstring($keyword,0)."%'";
		}
		$searchstring = join(" AND ", $searchstring);
		
		list($entries) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(*) FROM server_item_idtable as i, server_stringtable as s WHERE s.Code=i.itemid AND {$searchstring}"));
		$pageselect = $florensia->pageselect($entries, array("itemoverview"));

		if ($entries>0) {
			$querystringlist = MYSQL_QUERY("SELECT i.itemid FROM server_item_idtable as i, server_stringtable as s WHERE s.Code=i.itemid AND {$searchstring} ORDER BY s.{$stringtable->language} LIMIT ".$pageselect['pagestart'].",".$florensia->pageentrylimit);
			while ($itemlist = MYSQL_FETCH_ARRAY($querystringlist)) {
				$content .= $florensia->adsense(8);
				$item = $floclass->create_item($itemlist['itemid']);
				if ($item->is_valid()) $content .="<div class='shortinfo_".$florensia->change()."'>".$item->shortinfo(array('marketlist'=>true))."</div>";
			}
		} else { $content = "<div style='text-align:center' class='warning'>{$flolang->item_search_noentry}</div>"; }

		$content = "
			<div style='margin-bottom:5px; text-align:center'>".$florensia->quick_select('itemsearch', array('search'=>$_GET['search'], 'page'=>$_GET['page']), array(), $settings=array('namesselect'=>1))."</div>
			<div class='subtitle' style='margin-bottom:10px;'>".$flolang->sprintf($flolang->item_searching, $florensia->escape($_GET['search']))."</div>
			<div style='margin-bottom:10px;'>".$pageselect['selectbar']."</div>
			$content
			<div style='margin-top:10px;'>".$pageselect['selectbar']."</div>
		";

		$florensia->sitetitle("Itemdatabase");
		$florensia->sitetitle("Searching ".$florensia->escape($_GET['search']));
		$florensia->output_page($content);

}
elseif (isset($_GET['itemid'])) {
	$item = new floclass_item($_GET['itemid']);
	if ($item->is_valid()) {
		$tabbar['details'] = array("anchor"=>"details", "name"=>$flolang->tabbar_title_details, "desc"=>$flolang->tabbar_desc_details);
		$tabbar['droplist'] = array("anchor"=>"droplist", "name"=>$flolang->tabbar_title_droplist, "desc"=>"");
		$tabbar['questinfo'] = array("anchor"=>"questinfo", "name"=>$flolang->tabbar_title_questinfo, "desc"=>"");
		$tabbar['market'] = array("anchor"=>"market", "name"=>$flolang->tabbar_title_market, "desc"=>$classusermarket->get_tabdesc($_GET['itemid']));
		$tabbar['usernotes'] = array("anchor"=>"usernotes", "name"=>$flolang->tabbar_title_usernotes, "desc"=>"");
				
		$queryitemtablename = MYSQL_QUERY("SELECT name_{$flolang->language} FROM flobase_item_categories WHERE name_table='".$item->data['tableid']."'");
		list($itemtablename) = MYSQL_FETCH_ARRAY($queryitemtablename);

		unset($shopnpc, $questinfo, $skillcontent);

		$itemadditionmenu = $floaddition->get_additionmenu("item", $item->data[$florensia->get_columnname("itemid", "item")]);
		$itemtitlenotices = $floaddition->get_additionlist("item", $item->data[$florensia->get_columnname("itemid", "item")]);

		$characterimage['tabbar'] = array();
		$characterimage['classlist'] = array("w", "e", "n", "s");
		$characterimage['classlist'] = array("f"=>$characterimage['classlist'], "m"=>$characterimage['classlist']);
		$characterimage['imagelist'] = array();
		unset($characterimagevisible);
		foreach ($characterimage['classlist'] as $classgender => $classsignlist) {
			if ($classgender == "m") $classgender_image = "male"; else $classgender_image = "female";
			foreach ($classsignlist as $classsign) {
				$image_normal = $florensia->pictures_abs."/itemcharacter/item_h{$classsign}{$classgender}_{$item->data['characterpicture']}.gif";
				$image_ani = $florensia->pictures_abs."/itemcharacter/item_h{$classsign}{$classgender}_{$item->data['characterpicture']}_ani.gif";

				$characterimage['tabbar'][$classgender.$classsign] = array("anchor"=>"{$classgender}_{$classsign}", "name"=>"<img src='{$florensia->layer_rel}/icon_{$classsign}.png' border='0' style='height:16px;'>", "desc"=>"<img src='{$florensia->layer_rel}/gender_{$classgender_image}.gif' border='0'>");
				
				if (is_file($image_normal) OR is_file($image_ani)) {
					$characterimage['imagelist'][] = "<div name='{$classgender}_{$classsign}' {$characterimagevisible}>".$florensia->pictureprotection("item_h{$classsign}{$classgender}_".$item->data['characterpicture'], $stringtable->get_string($item->data[$florensia->get_columnname("itemid", "item")]), "itemcharacter")."</div>";
					$characterimagevisible = "style='display:none;'";
				}
				else { $characterimage['tabbar'][$classgender.$classsign]['inactive'] = true; }
			}
		}
		//not-items
			$image_normal = $florensia->pictures_abs."/itemcharacter/item_not_{$item->data['characterpicture']}.gif";
			$image_ani = $florensia->pictures_abs."/itemcharacter/item_not_{$item->data['characterpicture']}_ani.gif";

			$characterimage['tabbar']['not'] = array("anchor"=>"not", "name"=>"X", "desc"=>false);
				
			if (is_file($image_normal) OR is_file($image_ani)) {
				$characterimage['imagelist'][] = "<div name='not' {$characterimagevisible}>".$florensia->pictureprotection("item_not_".$item->data['characterpicture'], $stringtable->get_string($item->data[$florensia->get_columnname("itemid", "item")]), "itemcharacter")."</div>";
			}
			else { $characterimage['tabbar']['not']['inactive'] = true; }
		
		$characterimage['tabbar'] = $florensia->tabbar($characterimage['tabbar'], array('small'=>true));
		
		# description if available
		$querydescription = MYSQL_QUERY("SELECT {$stringtable->language} FROM server_description WHERE Code='".$item->data[$florensia->get_columnname("itemid", "item")]."'");
		if ($description = MYSQL_FETCH_ARRAY($querydescription)) {
				$itemdescription = "<div class='small' style='margin-top:5px; margin-bottom:2px;'>".$flolang->sprintf($flolang->itemdescription, $description[$stringtable->language])."</div>";
		}


		# npc-shops
		$querynpcshop = MYSQL_QUERY("SELECT npcid FROM server_storelist WHERE itemid='".$item->data[$florensia->get_columnname("itemid", "item")]."'");
		while ($npcshop = MYSQL_FETCH_ARRAY($querynpcshop)) {
			$shopnpc .= "<a href='".$florensia->outlink(array("npcdetails", $npcshop['npcid'], $stringtable->get_string($npcshop['npcid'])))."'>".$stringtable->get_string($npcshop['npcid'], array('protectionlink'=>1, 'protectionsmall'=>1))."</a><br />";
		}
		if (!$shopnpc) $shopnpc = $flolang->itemnosellingnpc;
		$npcshops = "<div class='small' style='margin-top:5px; margin-bottom:2px;'>".$flolang->sprintf($flolang->itemsellingnpc, $shopnpc)."</div>";


		if (strlen($item->data[$florensia->get_columnname("description", "item")])>2) {
			$querydescription = MYSQL_QUERY("SELECT {$stringtable->language} FROM server_description WHERE Code='".$item->data[$florensia->get_columnname("description", "item")]."'");
			if ($description = MYSQL_FETCH_ARRAY($querydescription)) {
				$additionaldescription = "<div class='small' style='margin-top:5px; margin-bottom:2px;'>".$flolang->sprintf($flolang->itemadditionaldesc, $description[$stringtable->language])."</div>";
			}

			$queryquestlist = MYSQL_QUERY("SELECT * FROM server_questlist WHERE questlistid='".$item->data[$florensia->get_columnname("description", "item")]."'");
			if ($questlist = MYSQL_FETCH_ARRAY($queryquestlist)) {
				if ($classquest->get_shortinfo($questlist, array('npclink'=>1))) {
						$questinfo = "
						<div class='shortinfo_".$florensia->change()."'>".$classquest->get_shortinfo($questlist, array('npclink'=>1))."</div>
						";
						$questinfo_amount++;
				}
			}

			if ($item->data['tableid']=="skillbookitem" OR $item->data['tableid']=="petskillstoneitem") {
				$skillbooks=array();
				//for ($i=1; $i<3; $i++) {
				//	if ($i==1) $dbselect = "skill";
				//	else $dbselect = "spell";
				$dbselect = "skill";
					$querydescription = MYSQL_QUERY("SELECT * FROM server_{$dbselect} WHERE ".mysql_real_escape_string($florensia->get_columnname("bookid", $dbselect))."='".mysql_real_escape_string($item->data[$florensia->get_columnname("description", "item")])."' ORDER BY ".$florensia->get_columnname("skilllevel", $dbselect)."");
					while ($description = MYSQL_FETCH_ARRAY($querydescription)) {
						if ($description[$florensia->get_columnname("skilllevel", $dbselect)]==0) continue;

						$javaskillbookid = preg_replace('/^[0a-z]+/','', $item->data[$florensia->get_columnname("itemid", "item")]);
						if ($javaskillbookid=="") $javaskillbookid=0;
						$skillbookscontent = "
						<div class='shortinfo_".$florensia->change()."' style='width:350px;'>
							<div style='float:right; width:30px; vertical-align:middle;'>
								<table style='width:100%'>
									<tr><td colspan='2'>[<span name='{$javaskillbookid}_level' style='text-align:center;'>1</span>]</td></tr>
									<tr>
										<td style='margin:1px;'><img onclick='skilltree($javaskillbookid,-1,".$description[$florensia->get_columnname("masterlevel", $dbselect)].")' id='{$javaskillbookid}_minus' src='{$florensia->layer_rel}/skill_minus_inactive.gif' border='0' alt='-'></td>
										<td style='margin:1px;'><img onclick='skilltree($javaskillbookid,1,".$description[$florensia->get_columnname("masterlevel", $dbselect)].")' id='{$javaskillbookid}_plus' src='{$florensia->layer_rel}/skill_plus_active.gif' border='0' alt='+'></td>
									</tr>
								</table>
							</div>
							<div style='display:none; margin-right:30px;' id='{$javaskillbookid}_0'><div style='min-height:55px;'><br />{$flolang->skilltree_placeholder_level_null}</div></div>";

							$display="inline";
							for ($i=1; $i<=$description[$florensia->get_columnname("masterlevel", $dbselect)]; $i++) {
									if ($i<10) $skilllevelpic="0$i";
									else $skilllevelpic=$i;
									$skillbookscontent .= "<div style='display:{$display}; margin-right:30px;' id='{$javaskillbookid}_$i'><div style='min-height:55px;'>".$florensia->detailsprotection(substr($description[$florensia->get_columnname("skillid", $dbselect)], 0, -2)."$skilllevelpic", $dbselect)."</div></div>";
									$display="none";
							}
						$skillbookscontent .= "</div>
						<script type='text/javascript'>skill['$javaskillbookid'] = 1;</script>";
						break;
					}
				//}
			}
		}

		if (strlen($item->data[$florensia->get_columnname("consumedescription", "item")])>2) {
			$querydescription = MYSQL_QUERY("SELECT {$stringtable->language} FROM server_description WHERE Code='".$item->data[$florensia->get_columnname("consumedescription", "item")]."'");
			if ($description = MYSQL_FETCH_ARRAY($querydescription)) {
				$additionaldescription .= "<div class='small' style='margin-top:5px; margin-bottom:2px;'>".$flolang->sprintf($flolang->itemadditionaldesc, $description[$stringtable->language])."</div>";
			}
		}

			###
			if ($item->data['tableid']=="questitem") {
				$queryquestlist = MYSQL_QUERY("SELECT * FROM server_questreferences as r, server_questlist as q WHERE refid='".$item->data[$florensia->get_columnname("itemid", "item")]."' AND q.questlistid=r.questid ORDER BY q.questlevel, q.questtitle_{$classquesttext->language}");
				while ($questlist = MYSQL_FETCH_ARRAY($queryquestlist)) {
					if ($classquest->get_shortinfo($questlist, array('npclink'=>1))) {
						$questinfo .= "
							<div class='shortinfo_".$florensia->change()."'>".$classquest->get_shortinfo($questlist, array('npclink'=>1))."</div>
						";
						$questinfo_amount++;
					}
				}
			}
			elseif ($item->data['tableid']=="recipeitem") {

				$koreancolumns = array();
				$querymaterialscolumns = MYSQL_QUERY("SELECT code_korean FROM flobase_item_columns WHERE code_english='recipe_material'");
				while ($materialcolumns = MYSQL_FETCH_ARRAY($querymaterialscolumns)) {
					$koreancolumns[] = $materialcolumns['code_korean'];
				}

				$queryrecipe = MYSQL_QUERY("SELECT * FROM server_item_recipeitem WHERE ".$florensia->get_columnname("itemid", "item")."='".$item->data[$florensia->get_columnname("itemid", "item")]."'");
				if ($recipe = MYSQL_FETCH_ARRAY($queryrecipe)) {
					$i=1;
					foreach ($koreancolumns as $columns) {
						if ($recipe[$columns]=="#") break;
						$recipematerials .= $recipe[$florensia->get_columnname("recipe_requirement_{$i}", "item")]." <a href='".$florensia->outlink(array("itemdetails", $recipe[$columns], $stringtable->get_string($recipe[$columns])))."'>".$stringtable->get_string($recipe[$columns], array('protectionsmall'=>1, 'protectionlink'=>1))."</a><br />";
						$i++;
					}
	
					$recipeoverview_requiered = "<div class='small' style='margin-bottom:5px; margin-top:5px;'>
										<div><b>{$flolang->item_recipe_targettitle}</b></div>
										<div><a href='".$florensia->outlink(array("itemdetails", $recipe[$florensia->get_columnname("recipe_target", "item")], $stringtable->get_string($recipe[$florensia->get_columnname("recipe_target", "item")])))."'>".$stringtable->get_string($recipe[$florensia->get_columnname("recipe_target", "item")], array('protectionlink'=>1, 'protectionsmall'=>1))."</a></div>
										<div><b>{$flolang->item_recipe_requirementtitle}</b></div>
										<div>$recipematerials</div>
									</div>";
				}
			}

			## if it can be created by recipe
			if (strlen($item->data['producedbyrecipe'])) {
				$recipe_targetticket = array();
				$recipelist = explode(",", $item->data['producedbyrecipe']);
				foreach ($recipelist as $recipe) {
					$recipe_targetticket[] = "<a href='".$florensia->outlink(array("itemdetails", $recipe, $stringtable->get_string($recipe)))."'>".$stringtable->get_string($recipe, array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
				}
				if (count($recipe_targetticket)) {
					$recipeoverview_targettickets = "
						<div class='small' style='margin-bottom:5px; margin-top:5px;'>
							<div><b>{$flolang->item_recipe_targetrecipetitle}</b></div>
							<div>".join("<br />", $recipe_targetticket)."</div>
						</div>
					";
				}
			}



			## if it is used by a recipe
			if (strlen($item->data['usedbyrecipe'])) {
				$usedbyrecipelist = array();
				$recipelist = explode(",", $item->data['usedbyrecipe']);
				foreach ($recipelist as $recipe) {
					$usedbyrecipelist[] = "<a href='".$florensia->outlink(array("itemdetails", $recipe, $stringtable->get_string($recipe)))."'>".$stringtable->get_string($recipe, array('protectionlink'=>1, 'protectionsmall'=>1))."</a>";
				}
					$recipeoverview_usedbyrecipe = "<div class='small' style='margin-bottom:5px; margin-top:5px;'>
										<div><b>{$flolang->item_recipe_usedbyrecipe}</b></div>
										<div>".join("<br />", $usedbyrecipelist)."</div>
									</div>";
			}
			
			## is it possible to get it as a questreward?/*
			unset($questrewardoverview);
			if (strlen($item->data['questreward'])) {
				$questrewardlist = array();
				$rewardlist = explode(",", $item->data['questreward']);
				foreach ($rewardlist as $quest) {
					list($questid, $amount) = explode("-", $quest);
					$questrewardlist[] = "<a href='".$florensia->outlink(array("questdetails", $questid, $classquest->get_title($questid)))."'>".$florensia->escape($classquest->get_title($questid))."</a> ({$amount}x)";
				}
					$questrewardoverview = "<div class='small' style='margin-bottom:5px; margin-top:5px;'>
										<div><b>{$flolang->item_questreward}</b></div>
										<div>".join("<br />", $questrewardlist)."</div>
									</div>";
			}
			
			
			if ($questinfo) {
				$questinfo = "<div class='subtitle' style='margin-top:15px;'>{$flolang->itemquestinfo}</div>$questinfo";
				$tabbar['questinfo']['desc'] = $flolang->sprintf($flolang->tabbar_desc_questinfo, $questinfo_amount);
			}
			else {
				$tabbar['questinfo']['inactive'] = true;
				$tabbar['questinfo']['desc'] = $flolang->tabbar_desc_questinfo_notfound;
			}


			/* upgraderules */
			/*	$upgradecontent['upgradelist'] = floclass_item::get_upgradeoverview($item->data[$florensia->get_columnname("upgraderule", "item")]);
				if ($upgradecontent['upgradelist']) {
					for ($i=1; $i<=10; $i++) {
						$tabbarupgrade['up_'.$i] = array("anchor"=>"up_{$i}", "name"=>"+{$i}", "desc"=>false);
					}
					$upgradecontent['tabbar'] = $florensia->tabbar($tabbarupgrade, array('small'=>true, 'desc'=>false));
					$upgradecontent['upgradelist'] = "
						<div class='small' style='margin-top:10px'>
							<div style='font-weight:bold;'>{$flolang->itemupgraderule}</div>
							<div style='font-size:85%'>{$upgradecontent['tabbar']['tabbar']}</div>
							{$upgradecontent['upgradelist']}
						</div>
					";
				}
			/*	*	*/

			// randombox ticket
			if (isset($item->data[$florensia->get_columnname("getrandomboxitem0id", "item")])) {
				for ($i=0; $i<100; $i++) {
					if ($item->data[$florensia->get_columnname("getrandomboxitem{$i}id", "item")]=="#" OR $item->data[$florensia->get_columnname("getrandomboxitem{$i}count", "item")]=="0" OR $item->data[$florensia->get_columnname("getrandomboxitem{$i}probability", "item")]=="0") break;
					elseif ($item->data[$florensia->get_columnname("getrandomboxitem{$i}id", "item")]=="money") {
						#$probability[$i] = $item->data[$florensia->get_columnname("getrandomboxitem{$i}probability", "item")];
						$getrandomboxitem .= "
						<div class='shortinfo_".$florensia->change()."'>
							<table width='100%' style='border-collapse:0px; border-spacing:0px; padding:0px;'>
								<tr>
									<td style='vertical-align:top; color:rgb(153,204,255);' colspan='3'>Gelt</td>
								</tr>
								<tr>
									<td rowspan='2' style='width:32px; vertical-align:top'></td>
									<td style='width:10px;'></td><td class='small' style='vertical-align:top'>{$flolang->itemrandomboxcount} ".$item->data[$florensia->get_columnname("getrandomboxitem{$i}count", "item")]."</td>
								</tr>";
						#		<tr>
						#			<td style='width:10px;'></td><td class='small' style='vertical-align:top'>{$flolang->itemrandomboxprobability}: ".bcdiv($item->data[$florensia->get_columnname("getrandomboxitem{$i}probability", "item")], 100, 3)."%</td>
						#		</tr>
						$getrandomboxitem.="	</table>
						</div>";
						continue;
					}
					#$probability[$i] = $item->data[$florensia->get_columnname("getrandomboxitem{$i}probability", "item")];
					#$getrandomboxitem .= "<div class='shortinfo_".$florensia->change()."'>".$classitem->get_shortinfo($item->data[$florensia->get_columnname("getrandomboxitem{$i}id", "item")], array(), array('Count'=>$item->data[$florensia->get_columnname("getrandomboxitem{$i}count", "item")], $flolang->itemrandomboxprobability=>bcdiv($item->data[$florensia->get_columnname("getrandomboxitem{$i}probability", "item")], 100, 3)."%"))."</div>";
					$itembox = new floclass_item($item->data[$florensia->get_columnname("getrandomboxitem{$i}id", "item")]);
					$getrandomboxitem .= "<div class='shortinfo_".$florensia->change()."'>".$itembox->shortinfo(array(), array('Count'=>$item->data[$florensia->get_columnname("getrandomboxitem{$i}count", "item")]))."</div>";
				}
			}
			if (isset($getrandomboxitem)) {
				$randomboxitem = "
					<div class='bordered' style='margin-top:5px;'>{$flolang->itemrandomboxsubtitle}</div>
					$getrandomboxitem
				";
			}
			
			//droplist
			$ignoredroplist = array('cloakitem', 'hatitem');
			/*if ($recipeoverview_targettickets) {
				//created by recipe, no drops
				$tabbar['droplist']['inactive'] = true; 
				$tabbar['droplist']['desc'] = $flolang->tabbar_desc_droplist_producedbyrecipe;
			}elseif*/
			if (!in_array($item->data['tableid'], $ignoredroplist)) {
				$droplist = "
					<div name='droplist' style='margin-bottom:15px; margin-top:15px;'>".$classdroplist->get_droplist($item->data[$florensia->get_columnname("itemid", "item")], "item")."</div>
				";
				$tabbar['droplist']['desc'] = $classdroplist->get_tabdesc($item->data[$florensia->get_columnname("itemid", "item")], "item");
			}
			else unset($tabbar['droplist']);
			
			//usernotes
			$usernotes = $classusernote->display($item->data[$florensia->get_columnname("itemid", "item")], "item");
			$tabbar['usernotes']['desc'] = $classusernote->get_tabdesc($item->data[$florensia->get_columnname("itemid", "item")], "item");
			
			
			//market
				//set var first because add/delete is performed while functions run. so it need to runs first
				$marketquickform = $classusermarket->quickform($item->data[$florensia->get_columnname("itemid", "item")]);
		
				$sellers=0;
				$buyers=0;
				$querytradelist = MYSQL_QUERY("SELECT userid, createtime, charname, timeout, itemid, itemamount, server, exchangetype, exchange, exchangegelt, marketlanguage FROM flobase_usermarket as m, flobase_character as c WHERE itemid='".mysql_real_escape_string($item->data[$florensia->get_columnname("itemid", "item")])."' AND m.characterid=c.characterid ORDER BY createtime");
				while ($tradelist = MYSQL_FETCH_ARRAY($querytradelist)) {
					$queryforumuser = MYSQL_QUERY("SELECT username FROM forum_users WHERE uid='".$tradelist['userid']."'");
					if (!($forumuser = MYSQL_FETCH_ARRAY($queryforumuser))) continue;
			
					unset($marketlanguage);
					$marketlanguagelist = explode(",", $tradelist['marketlanguage']);
					foreach ($marketlanguagelist as $languageid) {
						$marketlanguage .= "<img src='{$florensia->layer_rel}/flags/png/".$flolang->lang[$languageid]->flagid.".png' alt='".$flolang->lang[$languageid]->languagename."' title='".$flolang->lang[$languageid]->languagename."' border='0'> ";
					}
					if ($tradelist['exchange']!="") $tradelist['exchange'] = "<tr><td>{$flolang->market_details_info_exchange}</td><td>".$parser->parse_message($tradelist['exchange'], array("allow_mycode" =>1, "filter_badwords"=>1))."</td></tr>";
					else unset($tradelist['exchange']);
					if ($tradelist['exchangegelt']) $tradelist['exchangegelt'] = "<tr><td>Gelt:</td><td>".get_geltstring($tradelist['exchangegelt'], $tradelist['itemamount'])." Gelt</td></tr>";
					else unset($tradelist['exchangegelt']);
			
					if ($tradelist['exchangetype']=="sell") $sellers++;
					else $buyers++;
			
					$marketuserlist .= "
						<div class='subtitle small market_{$tradelist['exchangetype']}' style='font-weight:normal;'>
							<table style='width:100%'>
								<tr><td style='width:150px;'>{$flolang->market_details_info_user}</td><td><a href='{$florensia->forumurl}/user-".$tradelist['userid'].".html' target='_blank'>".$florensia->escape($forumuser['username'])."</a> (<a href='".$florensia->outlink(array("usermarket", $tradelist['userid'], $forumuser['username']))."'>".$flolang->sprintf($flolang->market_details_info_usermarketplace, $florensia->escape($forumuser['username']))."</a>)</td></tr>
								<tr><td>{$flolang->market_details_info_marketlanguage}</td><td>$marketlanguage</td></tr>
								<tr><td>{$flolang->market_details_info_server}</td><td><a href='".$florensia->outlink(array('statistics', $tradelist['server']))."'>".$tradelist['server']."</a></td></tr>
								<tr><td>{$flolang->market_details_info_character}</td><td><a href='".$florensia->outlink(array('characterdetails', $tradelist['charname']))."'>".$florensia->escape($tradelist['charname'])."</a></td></tr>
								{$tradelist['exchangegelt']}
								<tr><td>{$flolang->market_details_info_amount}</td><td>".$tradelist['itemamount']."</td></tr>
								<tr><td>{$flolang->market_details_info_timespan}</td><td>".date("m.d.", $tradelist['createtime'])."/".date("m.d.", $tradelist['timeout'])."</td></tr>
								{$tradelist['exchange']}
							</table>
						</div>
					";
				}
				if(!$marketuserlist) {
						$marketuserlist = "<div style='text-align:center' class='bordered small'>{$flolang->market_details_list_noentry}</div>";
				}
			
				$legend = "
					<div class='small' style='margin-bottom:10px;'>
						<table style='width:100%'><tr>
								<td class='market_sell subtitle' style='font-weight:normal; width:50%;'>{$flolang->market_subtitle_sell}: ".$flolang->sprintf($flolang->market_itemdetails_summary_sell, $sellers)."</td>
								<td class='market_buy subtitle' style='font-weight:normal;'>{$flolang->market_subtitle_buy}: ".$flolang->sprintf($flolang->market_itemdetails_summary_buy, $buyers)."</td>
							</tr></table>
					</div>
				";
				
				$marketoverview = "
					<div class='subtitle'>{$flolang->market_title_main}</div>
					<div class='small bordered' style='margin-bottom:15px;'>{$flolang->market_contactnotice}</div>
					<div>".$florensia->adsense(0)."</div>
					<div>
						<table style='width:100%'><tr>
							<td>
							{$legend}
							{$marketuserlist}
							</td>
							<td style='width:300px; padding-left:8px;'>{$marketquickform}</td>
						</tr></table>
					</div>
				";


		//final tabbar
		$tabbar = $florensia->tabbar($tabbar);
		$content = "	
			<div style='float:right; text-align:center;'>".$florensia->quick_select('itemdetails', array('itemid'=>$item->data[$florensia->get_columnname("itemid", "item")]), array(0=>$stringtable->get_select()))."</div>
			<div style='margin-bottom:3px; padding:1px;' class='subtitle'><a href='".$florensia->outlink(array("itemoverview"))."'>{$flolang->item_maintitle}</a> &gt; <a href='".$florensia->outlink(array("itemcategorie", $item->data['tableid']))."'>".$florensia->escape($itemtablename)."</a> &gt; ".$stringtable->get_string($item->data[$florensia->get_columnname("itemid", "item")], array('protection'=>1, 'itemgrade'=>$item->data[$florensia->get_columnname("raregrade", "item")]))."<br />".join("<br />", $itemtitlenotices)."</div>

			<div class='bordered' style='float:right; margin-top:10px; width:32px; height:32px; text-align:center; vertical-align:center;'>".$florensia->pictureprotection($item->data['itempicture'], $stringtable->get_string($item->data[$florensia->get_columnname("itemid", "item")]), "item")."</div>
			{$tabbar['tabbar']}
			<div name='details' style='vertical-align:top;'>
				<a name='details'></a>
				<div class='bordered' style='float:left; text-align:center; vertical-align:top; min-height:386px; width:250px;'>
						<div>".$characterimage['tabbar']['tabbar']."</div>
						".join("<br />", $characterimage['imagelist'])."
				</div>
				<div style='margin-left:260px; min-height:410px;'>
					<div class='subtitle'>".$flolang->sprintf($flolang->itemdetailssubtitle, $stringtable->get_string($item->data[$florensia->get_columnname("itemid", "item")], array('protection'=>1, 'itemgrade'=>$item->data[$florensia->get_columnname("raregrade", "item")])))."</div>
					<div>
						<table style='width:100%; border-collapse:0px; border-spacing:0px; padding-bottom:15px;'>
							<tr>
								<td style='vertical-align:top;'>
									<div>".$item->details()."</div>
									<div>".$item->get_upgradeoverview()."</div>
								</td>
								<td style='width:3px;'></td>
								<td style='vertical-align:top; width:250px;'>
									{$itemadditionmenu}
									{$itemdescription}
									{$additionaldescription}
									{$npcshops}
									{$recipeoverview_requiered}
									{$recipeoverview_targettickets}
									{$recipeoverview_usedbyrecipe}
									{$questrewardoverview}
								</td>
							</tr>
						</table>
					</div>
					{$skillbookscontent}
				</div>
			</div>
			<div name='details' style='margin-top:10px;'></div>
			<div name='details'>".$florensia->adsense(0)."</div>
			
			<a name='droplist'></a>
			{$droplist}
			<div name='droplist'>".$florensia->adsense(0)."</div>
			
			<a name='questinfo'></a>
			<div name='questinfo'>{$questinfo}</div>
			<div name='details'>{$randomboxitem}</div>
			
			<a name='market'></a>
			<div name='market'>{$marketoverview}</div>
			
			<a name='usernotes'></a>
			<div name='usernotes'>{$usernotes}</div>
			
			{$tabbar['jscript']}
			{$characterimage['tabbar']['jscript']}";

		$florensia->sitetitle("Itemdatabase");
		$florensia->sitetitle("Details ".$stringtable->get_string($item->data[$florensia->get_columnname("itemid", "item")]));
		$florensia->output_page($content);

	}
	else {
		$content = "
					<div style='text-align:center; margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("itemoverview"))."'>{$flolang->backtooverview}</a></div>
					<div style='text-align:center; margin-bottom:20px;'>{$flolang->quicksearch} ".$florensia->quicksearch(array('language'=>true))."</div>
					<div style='text-align:center;' class='warning'>{$flolang->item_noentry}</div>
		";
		$florensia->sitetitle("Itemdatabase");
		$florensia->sitetitle("Search");
		$florensia->output_page($content);
	}
}
else {
	$content = "
		<div style='text-align:center; margin-bottom:5px;' class='subtitle'><a href='".$florensia->outlink(array("itemoverview"))."'>{$flolang->backtooverview}</a></div>
		<div style='text-align:center;  margin-bottom:20px;'>{$flolang->quicksearch} ".$florensia->quicksearch(array('language'=>true))."</div>
	";
	$florensia->sitetitle("Itemdatabase");
	$florensia->sitetitle("Search");
	$florensia->output_page($content);
}
?>
