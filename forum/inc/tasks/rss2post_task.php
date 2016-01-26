<?php
/*
	RSS To Post 1.2.2
	
	This plugin checks RSS feeds at a specified interval and posts new entries onto the forum.
	For MyBB 1.4.x.
	
	Copyright © 2006, 2008 Dennis Tsang (http://dennistt.net)
	
	==========
	LICENSE
	==========
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


function task_rss2post_task($task)
{
	global $db, $lang, $cache, $mybb;
	
	rss2post_load_language();

	// Create feed reader
	$feeder = new lastRSS;
	$feeder->CDATA = 'content';
	
	$query = $db->simple_select('rss2post');
	while($feed_info = $db->fetch_array($query))
	{
		$user = get_user($feed_info['uid']);
		$url = $feed_info['url'];
		$forum = $feed_info['forum'];
		$striphtml = $feed_info['striphtml'];
		
		$uid = $user['uid'];
		
		// Get the feed
		if($feed = $feeder->Get($url))
		{
			$items = $feed['items'];
			// Loop through each item
			foreach($items as $item_data)
			{
				// Make a title if none exists
				if(empty($item_data['title']))
				{
					$item_data['title'] = substr($item_data['description'], 0, 60);
					if(strlen($item_data['description']) > 60)
					{
						$item_data['title'] .= '...';
					}
				}
				// See if title exists in posting forum

				$encoding = mb_detect_encoding($item_data['description']);
				$item_data['title'] = mb_convert_encoding($item_data['title'], $encoding);
				$item_data['description'] = mb_convert_encoding($item_data['description'], $encoding);

				$subject = htmlspecialchars_decode($item_data['title'], ENT_QUOTES);
				$subject_sql = $db->escape_string($subject);
				$dupquery = $db->simple_select("threads", "tid", "subject='{$subject_sql}' AND fid='{$forum}'");
				if($db->num_rows($dupquery) == 0)
				{
					// Add thread to forum (code based on MyBB 1.2.1 newthread.php)
					// Set up posthandler.
					require_once MYBB_ROOT."inc/datahandlers/post.php";
					$posthandler = new PostDataHandler("insert");
					$posthandler->action = "thread";

					if($item_data['content:encoded'])
					{
						$message = htmlspecialchars_decode($item_data['content:encoded'], ENT_QUOTES);
					}
					else
					{
						$message = htmlspecialchars_decode($item_data['description'], ENT_QUOTES);
					}
					
					$meta_info = '';
					if(!empty($item_data['pubDate']))
					{
						$meta_info .= 'on '.$item_data['pubDate'].' ';
					}
					if(!empty($item_data['link']))
					{
						$meta_info .= 'at '.$item_data['link'];
					}
					if(!empty($meta_info))
					{
						$message .= "\n\nPosted ".$meta_info;
					}
					if(!empty($item_data['author']))
					{
						$message .= "\nAuthor: {$item_data['author']}";
					}
					if(!empty($item_data['comments']))
					{
						$message .= "\nComments: {$item_data['comments']}";
					}

					// Strip HTML tags if required
					if($striphtml)
					{
						$message = strip_tags($message);
					}

					// Set the thread data
					$new_thread = array(
						"fid" => $forum,
						"subject" => $subject,
						"icon" => -1,
						"uid" => $uid,
						"username" => $user['username'],
						"message" => $message,
						"ipaddress" => '127.0.0.1',
						"posthash" => '',
						"savedraft" => 0,
					);

					// Set up the thread options
					$new_thread['options'] = array(
						"signature" => 'yes',
						"emailnotify" => 'no',
						"disablesmilies" => 'no'
					);

					$posthandler->set_data($new_thread);
			
					// Now let the post handler do all the hard work.
					if($posthandler->validate_thread())
					{
						$thread_info = $posthandler->insert_thread();
					}
					else
					{
						$errors = $cache->read("rss2post_errors");	
						if($errors === false)
						{
							$errors = '';
						}					
						
						$errors .= 'Date: ' . gmdate('r') . "\n";
						$errors .= "URL: {$url}\n";
						$errors .= "Forum ID: {$forum}\n";
						$errors .= "User ID: {$uid}\n";
						$errors .= "Strip HTML: {$striphtml}\n";
						
						ob_start();
						var_dump($item_data);
						$errors .= ob_get_clean();
						
						$datahandler_errors = $posthandler->get_errors();
						ob_start();
						var_dump($datahandler_errors);
						$errors .= ob_get_clean();
						$errors .= "\n\n===========================================\n\n";
						
						$cache->update('rss2post_errors', $errors);
					}
				}
			}
		}
		else
		{
			$errors = $cache->read("rss2post_errors");	
			if($errors === false)
			{
				$errors = '';
			}					
			
			$errors .= 'Date: ' . gmdate('r') . "\n";
			$errors .= "URL: {$url}\n";
			$errors .= "Forum ID: {$forum}\n";
			$errors .= "User ID: {$uid}\n";
			$errors .= "Strip HTML: {$striphtml}\n";
			
			$errors .= "There was a problem accessing the URL.\n";
			$errors .= "Either the server is offline, or your server has\n";
			$errors .= "allow_url_fopen disabled.  Please see:\n";
			$errors .= "http://ca3.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen\n";
			$errors .= "and contact your host to resolve this issue.\n";
			$errors .= "\n===========================================\n\n";
			
			$cache->update('rss2post_errors', $errors);
		}
	}
	
	add_task_log($task, $lang->task_rss2post_task_ran);
}

if(!function_exists('htmlspecialchars_decode'))
{
	// Courtesy of people commenting @ php.net manual
	function htmlspecialchars_decode($text)
	{
		return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
	}
}

/*
 ======================================================================
 lastRSS 0.9.1
 
 Simple yet powerfull PHP class to parse RSS files.
 
 by Vojtech Semecky, webmaster @ webdot . cz
 
 Latest version, features, manual and examples:
 	http://lastrss.webdot.cz/

 ----------------------------------------------------------------------
 LICENSE

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License (GPL)
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 To read the license please visit http://www.gnu.org/copyleft/gpl.html
 ======================================================================
*/

/**
* lastRSS
* Simple yet powerfull PHP class to parse RSS files.
*/
class lastRSS {
	// -------------------------------------------------------------------
	// Public properties
	// -------------------------------------------------------------------
	var $default_cp = 'UTF-8';
	var $CDATA = 'nochange';
	var $cp = '';
	var $items_limit = 0;
	var $stripHTML = False;
	var $date_format = '';

	// -------------------------------------------------------------------
	// Private variables
	// -------------------------------------------------------------------
	var $channeltags = array ('title', 'link', 'description', 'language', 'copyright', 'managingEditor', 'webMaster', 'lastBuildDate', 'rating', 'docs');
	var $itemtags = array('title', 'link', 'description', 'author', 'category', 'comments', 'enclosure', 'guid', 'pubDate', 'source', 'content:encoded');
	var $imagetags = array('title', 'url', 'link', 'width', 'height');
	var $textinputtags = array('title', 'description', 'name', 'link');

	// -------------------------------------------------------------------
	// Parse RSS file and returns associative array.
	// -------------------------------------------------------------------
	function Get ($rss_url) {
		// If CACHE ENABLED
		if ($this->cache_dir != '') {
			$cache_file = $this->cache_dir . '/rsscache_' . md5($rss_url);
			$timedif = @(time() - filemtime($cache_file));
			if ($timedif < $this->cache_time) {
				// cached file is fresh enough, return cached array
				$result = unserialize(join('', file($cache_file)));
				// set 'cached' to 1 only if cached file is correct
				if ($result) $result['cached'] = 1;
			} else {
				// cached file is too old, create new
				$result = $this->Parse($rss_url);
				$serialized = serialize($result);
				if ($f = @fopen($cache_file, 'w')) {
					fwrite ($f, $serialized, strlen($serialized));
					fclose($f);
				}
				if ($result) $result['cached'] = 0;
			}
		}
		// If CACHE DISABLED >> load and parse the file directly
		else {
			$result = $this->Parse($rss_url);
			if ($result) $result['cached'] = 0;
		}
		// return result
		return $result;
	}
	
	// -------------------------------------------------------------------
	// Modification of preg_match(); return trimed field with index 1
	// from 'classic' preg_match() array output
	// -------------------------------------------------------------------
	function my_preg_match ($pattern, $subject) {
		// start regullar expression
		preg_match($pattern, $subject, $out);

		// if there is some result... process it and return it
		if(isset($out[1])) {
			// Process CDATA (if present)
			if ($this->CDATA == 'content') { // Get CDATA content (without CDATA tag)
				$out[1] = strtr($out[1], array('<![CDATA['=>'', ']]>'=>''));
			} elseif ($this->CDATA == 'strip') { // Strip CDATA
				$out[1] = strtr($out[1], array('<![CDATA['=>'', ']]>'=>''));
			}

			// If code page is set convert character encoding to required
			if ($this->cp != '')
				//$out[1] = $this->MyConvertEncoding($this->rsscp, $this->cp, $out[1]);
				$out[1] = iconv($this->rsscp, $this->cp.'//TRANSLIT', $out[1]);
			// Return result
			return trim($out[1]);
		} else {
		// if there is NO result, return empty string
			return '';
		}
	}

	// -------------------------------------------------------------------
	// Replace HTML entities &something; by real characters
	// -------------------------------------------------------------------
	function unhtmlentities ($string) {
		// Get HTML entities table
		$trans_tbl = get_html_translation_table (HTML_ENTITIES, ENT_QUOTES);
		// Flip keys<==>values
		$trans_tbl = array_flip ($trans_tbl);
		// Add support for &apos; entity (missing in HTML_ENTITIES)
		$trans_tbl += array('&apos;' => "'");
		// Replace entities by values
		return strtr ($string, $trans_tbl);
	}

	// -------------------------------------------------------------------
	// Parse() is private method used by Get() to load and parse RSS file.
	// Don't use Parse() in your scripts - use Get($rss_file) instead.
	// -------------------------------------------------------------------
	function Parse ($rss_url) {
		// Open and load RSS file
		if ($f = @fopen($rss_url, 'r')) {
			$rss_content = '';
			while (!feof($f)) {
				$rss_content .= fgets($f, 4096);
			}
			fclose($f);

			// Parse document encoding
			$result['encoding'] = $this->my_preg_match("'encoding=[\'\"](.*?)[\'\"]'si", $rss_content);
			// if document codepage is specified, use it
			if ($result['encoding'] != '')
				{ $this->rsscp = $result['encoding']; } // This is used in my_preg_match()
			// otherwise use the default codepage
			else
				{ $this->rsscp = $this->default_cp; } // This is used in my_preg_match()

			// Parse CHANNEL info
			preg_match("'<channel.*?>(.*?)</channel>'si", $rss_content, $out_channel);
			foreach($this->channeltags as $channeltag)
			{
				$temp = $this->my_preg_match("'<$channeltag.*?>(.*?)</$channeltag>'si", $out_channel[1]);
				if ($temp != '') $result[$channeltag] = $temp; // Set only if not empty
			}
			// If date_format is specified and lastBuildDate is valid
			if ($this->date_format != '' && ($timestamp = strtotime($result['lastBuildDate'])) !==-1) {
						// convert lastBuildDate to specified date format
						$result['lastBuildDate'] = date($this->date_format, $timestamp);
			}

			// Parse TEXTINPUT info
			preg_match("'<textinput(|[^>]*[^/])>(.*?)</textinput>'si", $rss_content, $out_textinfo);
				// This a little strange regexp means:
				// Look for tag <textinput> with or without any attributes, but skip truncated version <textinput /> (it's not beggining tag)
			if (isset($out_textinfo[2])) {
				foreach($this->textinputtags as $textinputtag) {
					$temp = $this->my_preg_match("'<$textinputtag.*?>(.*?)</$textinputtag>'si", $out_textinfo[2]);
					if ($temp != '') $result['textinput_'.$textinputtag] = $temp; // Set only if not empty
				}
			}
			// Parse IMAGE info
			preg_match("'<image.*?>(.*?)</image>'si", $rss_content, $out_imageinfo);
			if (isset($out_imageinfo[1])) {
				foreach($this->imagetags as $imagetag) {
					$temp = $this->my_preg_match("'<$imagetag.*?>(.*?)</$imagetag>'si", $out_imageinfo[1]);
					if ($temp != '') $result['image_'.$imagetag] = $temp; // Set only if not empty
				}
			}
			// Parse ITEMS
			preg_match_all("'<item(| .*?)>(.*?)</item>'si", $rss_content, $items);
			$rss_items = $items[2];
			$i = 0;
			$result['items'] = array(); // create array even if there are no items
			foreach($rss_items as $rss_item) {
				// If number of items is lower then limit: Parse one item
				if ($i < $this->items_limit || $this->items_limit == 0) {
					foreach($this->itemtags as $itemtag) {
						$temp = $this->my_preg_match("'<$itemtag.*?>(.*?)</$itemtag>'si", $rss_item);
						if ($temp != '') $result['items'][$i][$itemtag] = $temp; // Set only if not empty
					}
					// Strip HTML tags and other bullshit from DESCRIPTION
					if ($this->stripHTML && $result['items'][$i]['description'])
						$result['items'][$i]['description'] = strip_tags($this->unhtmlentities(strip_tags($result['items'][$i]['description'])));
					// Strip HTML tags and other bullshit from TITLE
					if ($this->stripHTML && $result['items'][$i]['title'])
						$result['items'][$i]['title'] = strip_tags($this->unhtmlentities(strip_tags($result['items'][$i]['title'])));
					// If date_format is specified and pubDate is valid
					if ($this->date_format != '' && ($timestamp = strtotime($result['items'][$i]['pubDate'])) !==-1) {
						// convert pubDate to specified date format
						$result['items'][$i]['pubDate'] = date($this->date_format, $timestamp);
					}
					// Item counter
					$i++;
				}
			}

			$result['items_count'] = $i;
			return $result;
		}
		else // Error in opening return False
		{
			return False;
		}
	}
}
?>
