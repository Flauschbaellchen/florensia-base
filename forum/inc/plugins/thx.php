<?php

/**
 * Thank you Thank you 3.0.2
 *
 * Thank you 3.0.1b
 *  Arash_j13
 *  
 Email: 	Arash.j13@gmail.com
 WebSite:  WWW.CodeCorona.com
*   	
*  April 13, 2007
* 
* **************************
*  Mod: 		Thank You 3.0.4
*  Fixed by:	Hamid Nozari
*  Website:		www.shceg.com
* 
*  March 6, 2008
* 
* **************************
*
*  Mod: 		Thank You 3.0.6
*  Upgraded for MyBB 1.4 by:	AmirH Hassaneini,Hamed Arfaee
*  Website:		www.iranvig.com
* 
*  September 3, 2008
*
* **************************
*
*  Mod: 		Thank You 3.0.7
*  Update :	AmirH Hassaneini
*  Website:		www.iranvig.com
* 
*  March 17, 2009
*/ 

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
//thx_activate();

$plugins->add_hook("postbit", "thx");
$plugins->add_hook("xmlhttp", "do_action");
$plugins->add_hook("showthread_start","direct_action");
$plugins->add_hook("search_do_search_process","do_search");
$plugins->add_hook("member_profile_end","search_lng");

function thx_info() 
{
	return array(
		'name'			=> '<font color=Red>ThankYou</font>',
		'description'	=> 'Add a thank you note to a certain post.<br />',
		'website'		=> 'http://mods.mybboard.com/',
		'author'		=> 'Arash_j13<br />Update: Hamid Nozari<br>Upgrade: AmirH Hassaneini,Hamed Arfaee',
		'authorsite'	=> 'http://www.iranvig.com/',
		'version'		=> '3.0.7',
		'guid'        	=> '89288ed4e11a7fa98cc8c278a24c0de5'
	);
}

function thx_activate()
{
	global $theme, $db;
	$db->query("CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."thx (
		txid INT UNSIGNED NOT NULL AUTO_INCREMENT ,
		uid int( 10 ) NOT NULL ,
		adduid int( 10 ) NOT NULL ,
		pid int( 10 ) NOT NULL ,
		time bigint(30) NOT NULL,
		PRIMARY KEY ( txid ) 
		) TYPE = MYISAM ;"
	);

	
	if(!$db->field_exists("thx","users"))		
		$db->query("ALTER TABLE ".TABLE_PREFIX."users ADD `thx` INT NOT NULL DEFAULT 0 ,
			ADD `thxcount` INT NOT NULL DEFAULT 0 ");

	if(!$db->field_exists("thx","posts"))
		$db->query("ALTER TABLE ".TABLE_PREFIX."posts ADD `thx` INT(1) NOT NULL DEFAULT '0'");
	
	
	$db->query("UPDATE ".TABLE_PREFIX."users u SET 
			u.thx=(SELECT COUNT(*) FROM ".TABLE_PREFIX."thx t WHERE t.adduid=u.uid) ,
			u.thxcount=(SELECT COUNT(*) FROM ".TABLE_PREFIX."thx t WHERE t.uid=u.uid)"); 
	
	$db->query("UPDATE ".TABLE_PREFIX."posts p SET
			p.thx=(SELECT COUNT(*) FROM ".TABLE_PREFIX."thx t WHERE t.pid=p.pid)");
	
	
	require '../inc/adminfunctions_templates.php';	
	find_replace_templatesets("postbit_classic", '#'.preg_quote('		</table>').'#', '		</table>{$post[\'thxdsp\']}');
	find_replace_templatesets("postbit", '#'.preg_quote('</tbody>').'#', '<table>{$post[\'thxdsp\']}</table></tbody>');
	
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'button_quote\']}').'#', '{$post[\'button_quote\']}{$post[\'thanks\']}');
	find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'button_quote\']}').'#', '{$post[\'button_quote\']}{$post[\'thanks\']}');
	
	find_replace_templatesets("member_profile", '#'.preg_quote('{$lang->find_posts}').'#', '{$lang->find_posts}</a> - <a href="search.php?action=finduser&amp;uid={$uid}&amp;sortby=thanked">{$memprofile[\'find_thanked\']}');

		// Templates für dieses Plugin einfügen
	$templatearray = array(
		"tid" => "0",
		"title" => "postbit_thxcount",
		"template" => "<br><br><span class=\"smalltext\">{\$lang->thx_thank} {\$post[\'thank_count\']}<br />
													{\$post[\'thanked_count\']}<br /></span>",
		"sid" => "-1",
		);
	
	$db->insert_query("templates", $templatearray);

	find_replace_templatesets("headerinclude","#".preg_quote('{$newpmmsg}').'#',
							'<script type="text/javascript" src="jscripts/thx.js?ver=1400"></script>
							{$newpmmsg}');
						
	//installing settings
	$thx_group = array(
		"gid"			=> "0",
		"name"			=> "Thank you",
		"title"			=> "Thank you",
		"description"	=> "Displays ThankYou note below each post.",
		"disporder"		=> "3",
		"isdefault"		=> "yes",
	);
			
	$db->insert_query("settinggroups", $thx_group);
	$gid = $db->insert_id();	
	
	$thx_setting_1 = array(
		"sid"			=> "0",
		"name"			=> "thx_active",
		"title"			=> "Activate/Deactivate plugin",
		"description"	=> "Activate or Deactivate plugin but dont delete table",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '1',
		"gid"			=> intval($gid),
	);
	
	$thx_setting_2 = array(
		"sid"			=> "0",
		"name"			=> "thx_count",
		"title"			=> "Show/Hide Counter",
		"description"	=> "Show/Hide ThankYou Counter in user profile",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '2',
		"gid"			=> intval($gid),
	);
	
	$db->insert_query("settings", $thx_setting_1);
	$db->insert_query("settings", $thx_setting_2);
	rebuild_settings();
}

function thx_deactivate()
{
	global $theme, $db;
	require '../inc/adminfunctions_templates.php';

	//$db->query("drop TABLE ".TABLE_PREFIX."thx");  // comment this line to save your users thanks so you could use them later!
	
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'thxdsp\']}').'#', '', 0);
	find_replace_templatesets("postbit", '#'.preg_quote('<table>{$post[\'thxdsp\']}</table>').'#', '', 0);
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'thanks\']}').'#', '', 0);
	find_replace_templatesets("postbit", '#'.preg_quote('{$post[\'thanks\']}').'#', '', 0);
	find_replace_templatesets("postbit_classic", '#'.preg_quote('{$post[\'thxcount\']}').'#', '', 0);
	
	find_replace_templatesets("member_profile", '#'.preg_quote('</a> - <a href="search.php?action=finduser&amp;uid={$uid}&amp;sortby=thanked">{$memprofile[\'find_thanked\']}').'#', '', 0);
	
	find_replace_templatesets("headerinclude","#".
					preg_quote('<script type="text/javascript" src="jscripts/thx.js?ver=1400"></script>').'#','',0);


	$db->delete_query("settings","name='thx_count'");
	$db->delete_query("settings","name='thx_active'");
	$db->delete_query("settinggroups","name='Thank you'");
	$db->delete_query("templates","title='postbit_thxcount'");
	if($db->field_exists("thx","posts"))
		$db->query("ALTER TABLE ".TABLE_PREFIX."posts DROP thx");
	if($db->field_exists("thx","users"))
		$db->query("ALTER TABLE ".TABLE_PREFIX."users DROP thx , DROP thxcount");
	
	rebuild_settings();
	
}


function thx($post) {
	
	
	global $theme, $db, $mybb, $lang , $altbg,$templates;
	if($mybb->settings['thx_active']=="0")
		return;
		
	$lang->load("thx");

	$b=0; //dose user thank this post?
	$entries=build_thank($post['pid'],$b);
	
 	if($mybb->user['uid'] != 0 && $mybb->user['uid'] != $post['uid']) 
	{
		if(!$b)  //show thank button 
			$post['thanks'] = "<a id=\"a{$post['pid']}\" onclick=\"javascript: ThankYou.thx({$post['pid']}); return false; \" href=\"showthread.php?action=thank&tid={$post['tid']}&pid={$post['pid']}\">
			<img 
			src=\"{$theme['imgdir']}/postbit_thx.gif\" border=\"0\" alt=\"$lang->thx_main\" id=\"i{$post['pid']}\" /></a>";
		else 
			$post['thanks'] = "<a id=\"a{$post['pid']}\" onclick=\"javascript: ThankYou.rthx({$post['pid']}); return false; \" href=\"showthread.php?action=remove_thank&tid={$post['tid']}&pid={$post['pid']}\">
			<img src=\"{$theme['imgdir']}/postbit_rthx.gif\" border=\"0\" 
				alt=\"$lang->thx_remove\" id=\"i{$post['pid']}\" /></a>";
	
	}


	
	
		
	$display= $entries ?  "": "none" ;
		
	
	$post['thxdsp'] =  "<tr class=\"$altbg\" id=\"thx{$post['pid']}\" style=\"display:$display\"><td align=\"center\">
							<span class=\"smalltext\"><img src=\"{$theme['imgdir']}/thxarrow.gif\" border=\"0\"> $lang->thx_givenby</span></td>
							<td align=right id=\"thx_list{$post['pid']}\">$entries</td></tr>";
	
	if($mybb->settings['thx_count']=="1")
	{
		$query=$db->simple_select("users","thx ,thxcount ","uid={$post['uid']}");
		$count1=$db->fetch_array($query);
		
		$query=$db->simple_select("posts","count(*) as count","thx > '0' AND uid={$post['uid']}"); 
		$count3=$db->fetch_array($query);
		
		$post['thank_count']=$count1['thx'];

		$post['thanked_count']=$lang-> sprintf($lang->thx_thanked_count,$count1['thxcount'],$count3['count']);
		eval("\$post['thxcount'] = \"".$templates->get("postbit_thxcount")."\";");
		$post['user_details'].=$post['thxcount'];
	}
	
	
}

function do_action() {
	global $theme, $mybb, $db, $lang;
	
	if( ($mybb->input['action'] != "thankyou"  &&  $mybb->input['action'] != "remove_thankyou")
		|| $mybb->request_method != "post")
		return;
		
	
	$lang->load("thx");
	$pid=intval($mybb->input['pid']);
	

	if ($mybb->input['action'] == "thankyou")
	{
		do_thank($pid);
	} 
	else 
		del_thank($pid);
		
	$nonead=0;
	$list=build_thank($pid,$nonead);
	header('Content-Type: text/xml');
	$output="<thankyou>
				<list><![CDATA[$list]]></list>
				<display>".($list ? "1" : "0")."</display>
				<image>{$mybb->settings['bburl']}/{$theme['imgdir']}/";
	$output.=$mybb->input['action'] == "thankyou" ? "postbit_rthx.gif": "postbit_thx.gif";  			
	$output.="</image>
			 </thankyou>";
	echo $output;
	
}

function direct_action()
{
	
	global $theme, $mybb,$lang;
	if($mybb->input['action'] != "thank"  &&  $mybb->input['action'] != "remove_thank")
		return;
		
	$lang->load("thx");
	$pid=intval($mybb->input['pid']);
	
	if ($mybb->input['action'] == "thank" )
		do_thank($pid); 
	else
		del_thank($pid);
		
	redirect($_SERVER['HTTP_REFERER']);
	
}

function build_thank($pid,&$is_thx)
{
	$is_thx=0;
	global $theme, $db,$mybb;
	$query=$db->query( "SELECT  th.* , u.username ,u.usergroup ,u.displaygroup
						FROM ".TABLE_PREFIX."thx th
						JOIN ".TABLE_PREFIX."users u ON(th.adduid=u.uid)
						WHERE th.pid='$pid'
						ORDER BY th.time ASC");
	
	

	while($record = $db->fetch_array($query))
	{
		$is_thx+=$record['adduid']==$mybb->user['uid'];
		$date=my_date($mybb->settings['dateformat'].' '.$mybb->settings['timeformat'],$record['time']);
		$url=get_profile_link($record['adduid']);
		$name=format_name($record['username'], $record['usergroup'], $record['displaygroup']);
		$entries .= "".$r1comma."<a title=\".$date.\" href=\"$url\">$name</a>";
			
		$r1comma =", ";
	}
	
	return $entries;
}

function do_thank($pid)
{
	global $theme, $db,$mybb;
	
	$pid=intval($mybb->input['pid']);
	
	$query=$db->query("SELECT uid FROM ".TABLE_PREFIX."posts WHERE pid=".$pid);
			
	$user=$db->fetch_array($query);
		
	if ($user['uid']==$mybb->user['uid'])
		return;

	$check_query = $db->simple_select("thx","*" ,"adduid='{$mybb->user['uid']}' AND pid='$pid'");
	if($db->num_rows($check_query))
		return;
	$check_query = $db->simple_select("thx","*" ,"adduid='{$mybb->user['uid']}' AND pid='$pid'");
	if($db->num_rows($check_query))
		return;
	$check_query = $db->simple_select("posts","uid","pid='$pid'");
	if($db->num_rows($check_query)==1)//  post exsits
	{
		$tmp=$db->fetch_array($check_query);	
		$database = array (
			"uid" =>$tmp['uid'], 
			"adduid" => $mybb->user['uid'],
			"pid" => $pid,
			"time" => time()
		);
		$db->insert_query("thx", $database); //add thank to thx table
		
		
		//update user inforamtion
		$query=$db->simple_select("users","thx","uid={$mybb->user['uid']}");
		$tmp=$db->fetch_array($query);
		
		$update1['thx']=++$tmp['thx'];
		$db->update_query("users",$update1,"uid={$mybb->user['uid']}",1);
		
		$query=$db->simple_select("users","thxcount","uid={$database['uid']}");
		$tmp=$db->fetch_array($query);

		$update2['thxcount']=++$tmp['thxcount'];
		$db->update_query("users",$update2,"uid={$database['uid']}",1);
		
		//update post information
		$update3['thx']="1";
		$db->update_query("posts",$update3,"pid={$database['pid']}",1);
	}	
}

function del_thank($pid)
{
	global $theme, $mybb,$db;
	$check_query = $db->simple_select("thx","*" ,"adduid='{$mybb->user['uid']}' AND pid='$pid'");
	if($db->num_rows($check_query))
	{
		$data=$db->fetch_array($check_query);
		$query=$db->simple_select("users","thx","uid={$mybb->user['uid']}");
		$tmp=$db->fetch_array($query);

		$update1['thx']=--$tmp['thx'];
		$db->update_query("users",$update1,"uid={$mybb->user['uid']}",1);
				
		$query=$db->simple_select("users","thxcount","uid={$data['uid']}");
		$tmp=$db->fetch_array($query);
		--$tmp['thxcount'];
		$update2['thxcount']="{$tmp['thxcount']}";
		$db->update_query("users",$update2,"uid={$data['uid']}",1);

		
		$db->delete_query("thx","adduid='{$mybb->user['uid']}' AND pid='$pid'");
		
		
		/*$db->query("UPDATE ".TABLE_PREFIX."posts p SET p.thx=(SELECT count(*) FROM ".
				TABLE_PREFIX."thx t WHERE t.pid='{$pid}') WHERE p.pid='{$pid}'");*/

		$query=$db->simple_select("thx","count(*) as c","pid='{$pid}'");
		$tmp=$db->fetch_array($query);
		$update3['thx']=$temp['c'];
		$db->update_query("posts",$update3,"pid='{$pid}'",1);
	}
}

function do_search()
{
	global $mybb, $where_sql, $db, $searcharray;
	
	if ($mybb->input['sortby'] == "thanked") 
	{
		$where_sql .= " AND NOT p.thx='0'";
		$searcharray["querycache"] = $db->escape_string($where_sql);
		
	} elseif ($mybb->input['sortby'] == "thank") 
	{
	  // to be developed!
	}
	
	
}

function search_lng()
{
	global $mybb, $lang, $templates, $memprofile;
	
	if ($mybb->input['action'] == "profile") 
	{
		$lang->load("thx");
		$memprofile['find_thanked'] = $lang->thx_find_thanked;
	}
	
	
}

if(!function_exists("rebuild_settings"))
{
	function rebuild_settings()
	{
		global $theme, $db;
		$query = $db->query("SELECT * FROM ".TABLE_PREFIX."settings ORDER BY title ASC");
		while($setting = $db->fetch_array($query))
		{
			$setting['value'] = addslashes($setting['value']);
			$settings .= "\$settings['".$setting['name']."'] = \"".$setting['value']."\";\n";
		}
		$settings = "<?php\n/*********************************\ \n  DO NOT EDIT THIS FILE, PLEASE USE\n  THE SETTINGS EDITOR\n\*********************************/\n\n$settings\n?>";
		$file = fopen("../inc/settings.php", "w");
		fwrite($file, $settings);
		fclose($file);
	}
}
?>