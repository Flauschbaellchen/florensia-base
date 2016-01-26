<?php

/*
FancyZoom Plugin for MyBB
Copyright (C) 2009 Sebastian Wunderlich

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined("IN_MYBB"))
{
	die();
}

$plugins->add_hook("pre_output_page","fancyzoom");

function fancyzoom_info()
{
	return array
	(
		"name"=>"FancyZoom",
		"description"=>"Open images and thumbnails with FancyZoom.",
		"website"=>"http://mods.mybboard.net/view/fancyzoom",
		"author"=>"Sebastian Wunderlich",
		"version"=>"1.2",
		"guid"=>"b67008c6447fe5f3b27ee7bcb8e1fc6e",
		"compatibility"=>"14*"
	);

}

function fancyzoom($page)
{
	global $mybb,$db;
	if(THIS_SCRIPT=="showthread.php")
	{
		$result=$db->simple_select("threads","fid","tid='".intval($mybb->input["tid"])."'",array("limit"=>1));
		$thread=$db->fetch_array($result);
		$permissions=forum_permissions($thread["fid"]);
		if(!empty($thread)&&$permissions["candlattachments"]==1)
		{
			$page=str_replace("</head>",'<script type="text/javascript" src="'.$mybb->settings["bburl"].'/jscripts/fancyzoom/FancyZoom.js"></script>
<script type="text/javascript" src="'.$mybb->settings["bburl"].'/jscripts/fancyzoom/FancyZoomHTML.js"></script>
</head>',$page);
			$page=preg_replace('/\<body(.*)\>/Usi','<body$1 onload="setupZoom()">',$page);
			$page=preg_replace('/\<a href="attachment.php\?aid=([0-9]+)" target="_blank"\>\<img/Usi','<a href="attachment.php?aid=$1" rel="fancyzoom"><img',$page);
			return $page;
		}
	}
}

?>