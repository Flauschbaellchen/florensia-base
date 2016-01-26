<?php

/**************************************************************************
* Add more spiders to your database											*
***************************************************************************/
/*MyBB Plugin: Video Link v 0.1
---------------------------------------------------------------------------------------------------------
http://www.edidesign.ca
---------------------------------------------------------------------------------------------------------
Author : Thor2705
http://www.edidesign.ca
Please do not remove the links from this file. Please report any sites that are not working on mybb comunity forum
*/

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("admin_config_spiders_add", "addbot");

function addbot_info() {
	return array(
		"name" 			=> "Add Spiders",
		"description"	=> "Add more spiders to your data base",

		"website"		=> "http://www.edidesign.ca",

		"author"		=> "Thor2705",

		"authorsite"	=> "http://www.edidesign.ca",

		"version"		=> "0.1",

    	"guid"			=> "66cc039962c0412fadbb48be13e96e37",

		"compatibility"	=> "14*",
	);
}





function addbot_activate()
{

// Globals
global $db;

$addSpider = array(
	// The big spiders detected by MyBB by default 				 		Website							Description
/*	array(
'name' => 'Google',
'useragent' => 'Googlebot'),										//http://www.google.com					The main spider used by Google
	array(
'name' => 'Msn',
'useragent' => 'MsnBot'
),	*/										//http://www.msn.com					The main spider used by MSN
/*	array(
'name' => 'Yahoo!',
'useragent' => 'Slurp'),	*/										//http://www.yahoo.com					Worlds most aggressive spider

	// MAJOR spiders							 				Website							Description	
	array(
	'name' => 'Ask', 
	'useragent' => 'Teoma'),									// http://www.ask.com					Spider for Ask Search Engine
	array(
	'name' => 'Baidu',
	'useragent' => 'Baiduspider'),							// http://www.baidu.com				Spider for Chinese search engine
	array(
	'name' => 'GigaBot',
	'useragent' => 'Gigabot'),							// http://www.gigablast.com				Another heavily travelled spider
	array(
	'name' => 'Google-AdSense',
	'useragent' => 'Mediapartners-Google'),		// http://www.google.com				Spider related to Adsense/Adwords
	array(
	'name' => 'Google-Adwords',
	'useragent' => 'AdsBot-Google'),				// http://www.google.com				Spider related to Adwords
	array(
	'name' => 'Google-SA',
	'useragent' => 'gsa-crawler'),						// http://www.google.com				Google Search Appliance Spider
	array(
	'name' => 'Google-Image',
	'useragent' => 'Googlebot-Image'),				// http://www.google.com				Spider for google image search
	array(
	'name' => 'InternetArchive',
	'useragent' => 'ia_archiver-web.archive.org'),// http://www.archive.org				Way back When machine Spider
	array(
	'name' => 'Alexa',
	'useragent' => 'ia_archiver'),							// http://www.alexa.com				*Must be detected after Internet Archive
	array(
	'name' => 'Omgili',
	'useragent' => 'omgilibot'),							// http://www.omgili.com				Extremely aggressive Messageboard/forum Spider
	array(
	'name' => 'Speedy Spider',
	'useragent' => 'Speedy Spider'),				// http://www.entireweb.com				Entire web spider
	array(
	'name' => 'Yahoo',
	'useragent' => 'yahoo'),								// http://www.yahoo.com				For Yahoo Publisher Network  (a variety in use)
	array(
	'name' => 'Yahoo JP',
	'useragent' => 'Y!J'),								// http://www.yahoo.co.jp				Spider for Yahoo Japan

	// Checkers/Testers/Robots						 				Website							Description
	array(
	'name' => 'DeadLinksChecker',
	'useragent' => 'link validator'),			// http://www.dead-links.com/			Checks your site for dead/bad links
	array(
	'name' => 'W3C Validator',
	'useragent' => 'W3C_Validator'),				// http://validator.w3.org				Checks standards validity of any html/xhtml page
	array(
	'name' => 'W3C CSSValidator',
	'useragent' => 'W3C_CSS_Validator'),			// http://jigsaw.w3.org/css-validator/		Checks standards validity of css stylesheets
	array(
	'name' => 'W3C FeedValidator',
	'useragent' => 'FeedValidator'),			// http://validator.w3.org/feed/ 			Checks standards validity of atom/rss feeds
	array(
	'name' => 'W3C LinkChecker',
	'useragent' => 'W3C-checklink'),				// http://validator.w3.org/checklink			Checks links on any html/xhtml page are valid
	array(
	'name' => 'W3C mobileOK',
	'useragent' => 'W3C-mobileOK'),					// http://www.w3.org/2006/07/mobileok-ddc	Checks page for how good it is for mobiles
	array(
	'name' => 'W3C P3PValidator',
	'useragent' => 'P3P Validator'),				// http://www.w3.org/P3P/validator.html		Checks something??
	
	// Feed readers								 				Website							Description	
	array(
	'name' => 'Bloglines',
	'useragent' => 'Bloglines'),						// http://www.bloglines.com				Spider for blog/rich web content (owned by Ask)
	array(
	'name' => 'Feedburner',
	'useragent' => 'Feedburner'),						// http://www.feedburner.com			Another RSS feed reader

	// Website Thumbnail/Snapshot/Thumbshot takers		 				Website							Description
	array(
	'name' => 'SnapBot',
	'useragent' => 'Snapbot'),							// http://www.snap.com					Shapshots provider
	array(
	'name' => 'Picsearch',
	'useragent' => 'psbot'),							// http://www.picsearch.com				Picture/Image Search Engine
	array(
	'name' => 'Websnapr',
	'useragent' => 'Websnapr'),							// http://www.websnapr.com				Snapshot/site screenshot taker
	
	// More MINOR Spiders/Robots					 				Website							Description
	array(
	'name' => 'AllTheWeb',
	'useragent' => 'FAST-WebCrawler'), 					// http://www.alltheweb.com				Spider for alltheweb (now owned by Yahoo)
	array(
	'name' => 'Altavista',
	'useragent' => 'Scooter'),							// http://www.altavista.com				Another Major Search Engine spider
	array(
	'name' => 'Asterias',
	'useragent' => 'asterias'),							// http://www.aol.com					Media Spider
	array(
	'name' => '192bot',
	'useragent' => '192.comAgent'),						// http://www.192.com					Spider to index for 192.com
	array(
	'name' => 'AbachoBot',
	'useragent' => 'ABACHOBot'),						// http://www.abacho.com				Spider for multi language search engine/translator
	array(
	'name' => 'Abdcatos',
	'useragent' => 'abcdatos'),							// http://www.abcdatos.com/botlink/		Spider for Italian Search Engine
	array(
	'name' => 'Acoon',
	'useragent' => 'Acoon'),								// http://www.acoon.de					Spider for small search engine
	array(
	'name' => 'Accoona',
	'useragent' => 'Accoona'),							// http://www.accoona.com				Spider for Accoona
	array(
	'name' => 'BecomeBot',
	'useragent' => 'BecomeBot'),						// http://www.become.com				Shopping/Products type search engine
	array(
	'name' => 'BlogRefsBot',
	'useragent' => 'BlogRefsBot'),					// http://www.blogrefs.com/about/bloggers	Blogs related spider
	array(
	'name' => 'Daumoa',
	'useragent' => 'Daumoa'),								// http://ws.daum.net/aboutkr.html			South Korean Search Engine Spider
	array(
	'name' => 'DuckDuckBot',
	'useragent' => 'DuckDuckBot'),					// http://duckduckgo.com/duckduckbot.html	Spider for small search engine
	array(
	'name' => 'Exabot',
	'useragent' => 'Exabot'),								// http://www.exalead.com				Spider for small search engine
	array(
	'name' => 'Furl',
	'useragent' => 'Furlbot'),								// http://www.furl.net					Spider for Furl social bookmarking site
	array(
	'name' => 'FyperSpider',
	'useragent' => 'FyberSpider'),					// http://www.fybersearch.com			Spider for Small Search Engine
	array(
	'name' => 'Geona',
	'useragent' => 'GeonaBot'),								// http://www.geona.com				Spider for another small search engine
	array(
	'name' => 'GirafaBot',
	'useragent' => 'Girafabot'),						// http://www.girafa.com/				Thumbshot provider
	array(
	'name' => 'GoSeeBot',
	'useragent' => 'GoSeeBot'),							// http://www.gosee.com/bot.html			Spider for small search engine
	array(
	'name' => 'Ichiro',
	'useragent' => 'ichiro'),								// http://help.goo.ne.jp/door/crawler.html		Spider for Japanese search engine
	array(
	'name' => 'LapozzBot',
	'useragent' => 'LapozzBot'),						// http://www.lapozz.hu				 	Spider for Hungarian search engine
	array(
	'name' => 'Looksmart',
	'useragent' => 'WISENutbot'),						// http://www.looksmart.com				Spider related to advertising
	array(
	'name' => 'Lycos',
	'useragent' => 'Lycos_Spider'),							// http://www.lycos.com				Spider for search engine
	array(
	'name' => 'Majestic12',
	'useragent' => 'MJ12bot/v2'),						// http://www.majestic12.co.uk/			Distributed Search Engine Project
	array(
	'name' => 'MLBot',
	'useragent' => 'MLBot'),								// http://www.metadatalabs.com/			Media indexing spider
	array(
	'name' => 'MSRBOT',
	'useragent' => 'msrbot'),								// http://research.microsoft.com/research/sv/msrbot/  	Microsoft Research bot
	array(
	'name' => 'MSR-ISRCCrawler',
	'useragent' => 'MSR-ISRCCrawler'),			// http://www.microsoft.com/research/	  	Another Microsoft Research bot
	array(
	'name' => 'Naver',
	'useragent' => 'NaverBot'),								// http://www.naver.com				South Korean Search Engine Spider
	array(
	'name' => 'Naver',
	'useragent' => 'Yeti'),									// http://www.naver.com				Another NaverBot for the South Korean Search Engine
	array(
	'name' => 'NoxTrumBot',
	'useragent' => 'noxtrumbot'),						// http://www.noxtrum.com				Spider for Spanish search engine
	array(
	'name' => 'OmniExplorer',
	'useragent' => 'OmniExplorer_Bot'),				// http://www.omni-explorer.com/			Spider
	array(
	'name' => 'OnetSzukaj',
	'useragent' => 'OnetSzukaj'),						// http://szukaj.onet.pl					Polish Search Engine Spider
	array(
	'name' => 'ScrubTheWeb',
	'useragent' => 'Scrubby'),						// http://www.scrubtheweb.com			Spider for Scrub the web
	array(
	'name' => 'SearchSight',
	'useragent' => 'SearchSight'),					// http://www.searchsite.com				Another search engine
	array(
	'name' => 'Seeqpod',
	'useragent' => 'Seeqpod'),							// http://www.seeqpod.com		 		Spider for search engine (the google for mp3 files)
	array(
	'name' => 'Shablast',
	'useragent' => 'ShablastBot'),						// http://www.shablast.com				Spider for a small search engine
	array(
	'name' => 'SitiDiBot',
	'useragent' => 'SitiDiBot'),						// http://www.sitidi.net					Spider for italian Sitidi search engine
	array(
	'name' => 'Slider',
	'useragent' => 'silk/1.0'),							// http://www.slider.com				Spider for Slider, but it only spiders DMOZ entries
	array(
	'name' => 'Sogou',
	'useragent' => 'Sogou'),								// http://www.sogou.com				Spider for Chinese search engine
	array(
	'name' => 'Sosospider',
	'useragent' => 'Sosospider'),						// http://help.soso.com/webspider.htm		Non-english search engine
	array(
	'name' => 'StackRambler',
	'useragent' => 'StackRambler'),					// http://www.rambler.ru/doc/robots.shtml		Spider for Russian portal/search engine
	array(
	'name' => 'SurveyBot',
	'useragent' => 'SurveyBot'),						// http://www.domaintools.com			Probe for website statistics (WhoIs  Source)
	array(
	'name' => 'Touche',
	'useragent' => 'Touche'),								// http://www.touche.com.ve				Another small search engine
	array(
	'name' => 'Walhello',
	'useragent' => 'appie'),								// http://www.wahello.com/				Spider for wahello
	array(
	'name' => 'WebAlta',
	'useragent' => 'WebAlta'), 							// http://www.webalta.net				Russian Search Engine
	array(
	'name' => 'Wisponbot',
	'useragent' => 'wisponbot'), 						// http://www.wispon.com				Korean Search Engine
	array(
	'name' => 'YacyBot',
	'useragent' => 'yacybot'),							// http://www.yacy.com			 		Crawler for distributed search engine
	array(
	'name' => 'YodaoBot',
	'useragent' => 'YodaoBot'),							// http://www.yodao.com				Spider for Chinese Search Engine
	
	// Google-Wanna-Be's - Spiders/Robots for Startups		 				Website							Description
	array(
	'name' => 'Charlotte',
	'useragent' => 'Charlotte'),						// http://www.searchme.com/support/ 		Spider for new search engine (in beta)
	array(
	'name' => 'DiscoBot',
	'useragent' => 'DiscoBot'),							// http://discoveryengine.com/discobot.html	Spider for new search engine startup
	array(
	'name' => 'EnaBot',
	'useragent' => 'EnaBot'),								// http://www.enaball.com/crawler.html		Experimental new spider
	array(
	'name' => 'Gaisbot',
	'useragent' => 'Gaisbot'),							// http://gais.cs.ccu.edu.tw/robot.php		Spider for search engine startup
	array(
	'name' => 'Kalooga',
	'useragent' => 'kalooga'),							// http://www.kalooga.com				Spider for new media search engine (in beta)
	array(
	'name' => 'ScoutJet',
	'useragent' => 'ScoutJet'),							// http://www.scoutjet.com/				Spider for new search engine (by the DMOZ founders)
	array(
	'name' => 'TinEye',
	'useragent' => 'TinEye'),								// http://tineye.com/crawler.html			Spider for search engine startup
	array(
	'name' => 'Twiceler',
	'useragent' => 'twiceler'),							// http://www.cuill.com/twiceler/robot.html	Experimental Spider, (aggressive)

	// Software								 				Website							Description
	array(
	'name' => 'GSiteCrawler',
	'useragent' => 'GSiteCrawler'),					// http://www.gsitecrawler.com/			Windows Based Sitemap Generator Software
	array(
	'name' => 'HTTrack',
	'useragent' => 'HTTrack'),							// http://www.httrack.com				HTTrack Website Copier - Offline Browser
	array(
	'name' => 'Wget',
	'useragent' => 'Wget'),									// http://www.gnu.org/software/wget/		GNU software to retrieve files
	// Reason for detecting these: They can be very intensive. So seeing them in use, enables you to block if necessary.

);

	
	// Grab all the existing spiders to match against
	$query = $db->query("SELECT useragent
	FROM ".TABLE_PREFIX."spiders");
/*	$db->query('', '
		SELECT useragent
		FROM mybb_spiders',
		array()
	);
*/
	$knownspiders = $db->fetch_array($query);
//	$a = mysql_num_rows($request);
/*	if (db_num_rows($request) != "false")
	{	
		// Store all found spiders in an array
		while ($row = $db->fetch_array($request))
			$knownspiders[] = $row['useragent'];
	}
*/	
// Now go through spider in the db
foreach($addSpider as $spider)
{
	// If doesn't already exist in the table, then add it
	if(!in_array($spider['useragent'], $knownspiders))
	{
		// Now add each spider
	$spiders_group = array(
		'name'		=> addslashes($spider['name']),
		'useragent'		=> addslashes($spider['useragent']),
	);
		$db->insert_query("spiders", $spiders_group);
	}
}

//Unset everything
unset($addSpider, $spider, $knownspiders);

}
function addbot()
{
}
function addbot_deactivate()
{
	global $db;
$addSpider = array(
	// The big spiders detected by MyBB by default
//	array('Google', 'Googlebot'),
//	array('Msn', 'MsnBot'),
//	array('Yahoo!', 'Slurp'),
	// MAJOR spiders
	array(
	'name' => 'Ask', 
	'useragent' => 'Teoma'),
	array(
	'name' => 'Baidu',
	'useragent' => 'Baiduspider'),
	array(
	'name' => 'GigaBot',
	'useragent' => 'Gigabot'),
	array(
	'name' => 'Google-AdSense',
	'useragent' => 'Mediapartners-Google'),
	array(
	'name' => 'Google-Adwords',
	'useragent' => 'AdsBot-Google'),
	array(
	'name' => 'Google-SA',
	'useragent' => 'gsa-crawler'),
	array(
	'name' => 'Google-Image',
	'useragent' => 'Googlebot-Image'),
	array(
	'name' => 'InternetArchive',
	'useragent' => 'ia_archiver-web.archive.org'),
	array(
	'name' => 'Alexa',
	'useragent' => 'ia_archiver'),
	array(
	'name' => 'Omgili',
	'useragent' => 'omgilibot'),
	array(
	'name' => 'Speedy Spider',
	'useragent' => 'Speedy Spider'),
	array(
	'name' => 'Yahoo',
	'useragent' => 'yahoo'),
	array(
	'name' => 'Yahoo JP',
	'useragent' => 'Y!J'),
	// Checkers/Testers/Robots
	array(
	'name' => 'DeadLinksChecker',
	'useragent' => 'link validator'),
	array(
	'name' => 'W3C Validator',
	'useragent' => 'W3C_Validator'),
	array(
	'name' => 'W3C CSSValidator',
	'useragent' => 'W3C_CSS_Validator'),
	array(
	'name' => 'W3C FeedValidator',
	'useragent' => 'FeedValidator'),
	array(
	'name' => 'W3C LinkChecker',
	'useragent' => 'W3C-checklink'),
	array(
	'name' => 'W3C mobileOK',
	'useragent' => 'W3C-mobileOK'),
	array(
	'name' => 'W3C P3PValidator',
	'useragent' => 'P3P Validator'),
	
	// Feed readers
	array(
	'name' => 'Bloglines',
	'useragent' => 'Bloglines'),
	array(
	'name' => 'Feedburner',
	'useragent' => 'Feedburner'),
	// Website Thumbnail/Snapshot/Thumbshot takers
	array(
	'name' => 'SnapBot',
	'useragent' => 'Snapbot'),
	array(
	'name' => 'Picsearch',
	'useragent' => 'psbot'),
	array(
	'name' => 'Websnapr',
	'useragent' => 'Websnapr'),	
	// More MINOR Spiders/Robots
	array(
	'name' => 'AllTheWeb',
	'useragent' => 'FAST-WebCrawler'),
	array(
	'name' => 'Altavista',
	'useragent' => 'Scooter'),
	array(
	'name' => 'Asterias',
	'useragent' => 'asterias'),
	array(
	'name' => '192bot',
	'useragent' => '192.comAgent'),
	array(
	'name' => 'AbachoBot',
	'useragent' => 'ABACHOBot'),
	array(
	'name' => 'Abdcatos',
	'useragent' => 'abcdatos'),
	array(
	'name' => 'Acoon',
	'useragent' => 'Acoon'),
	array(
	'name' => 'Accoona',
	'useragent' => 'Accoona'),
	array(
	'name' => 'BecomeBot',
	'useragent' => 'BecomeBot'),
	array(
	'name' => 'BlogRefsBot',
	'useragent' => 'BlogRefsBot'),
	array(
	'name' => 'Daumoa',
	'useragent' => 'Daumoa'),
	array(
	'name' => 'DuckDuckBot',
	'useragent' => 'DuckDuckBot'),
	array(
	'name' => 'Exabot',
	'useragent' => 'Exabot'),
	array(
	'name' => 'Furl',
	'useragent' => 'Furlbot'),
	array(
	'name' => 'FyperSpider',
	'useragent' => 'FyberSpider'),
	array(
	'name' => 'Geona',
	'useragent' => 'GeonaBot'),
	array(
	'name' => 'GirafaBot',
	'useragent' => 'Girafabot'),
	array(
	'name' => 'GoSeeBot',
	'useragent' => 'GoSeeBot'),
	array(
	'name' => 'Ichiro',
	'useragent' => 'ichiro'),
	array(
	'name' => 'LapozzBot',
	'useragent' => 'LapozzBot'),
	array(
	'name' => 'Looksmart',
	'useragent' => 'WISENutbot'),
	array(
	'name' => 'Lycos',
	'useragent' => 'Lycos_Spider'),
	array(
	'name' => 'Majestic12',
	'useragent' => 'MJ12bot/v2'),
	array(
	'name' => 'MLBot',
	'useragent' => 'MLBot'),
	array(
	'name' => 'MSRBOT',
	'useragent' => 'msrbot'),
	array(
	'name' => 'MSR-ISRCCrawler',
	'useragent' => 'MSR-ISRCCrawler'),
	array(
	'name' => 'Naver',
	'useragent' => 'NaverBot'),
	array(
	'name' => 'Naver',
	'useragent' => 'Yeti'),
	array(
	'name' => 'NoxTrumBot',
	'useragent' => 'noxtrumbot'),
	array(
	'name' => 'OmniExplorer',
	'useragent' => 'OmniExplorer_Bot'),
	array(
	'name' => 'OnetSzukaj',
	'useragent' => 'OnetSzukaj'),
	array(
	'name' => 'ScrubTheWeb',
	'useragent' => 'Scrubby'),
	array(
	'name' => 'SearchSight',
	'useragent' => 'SearchSight'),
	array(
	'name' => 'Seeqpod',
	'useragent' => 'Seeqpod'),
	array(
	'name' => 'Shablast',
	'useragent' => 'ShablastBot'),
	array(
	'name' => 'SitiDiBot',
	'useragent' => 'SitiDiBot'),
	array(
	'name' => 'Slider',
	'useragent' => 'silk/1.0'),
	array(
	'name' => 'Sogou',
	'useragent' => 'Sogou'),
	array(
	'name' => 'Sosospider',
	'useragent' => 'Sosospider'),
	array(
	'name' => 'StackRambler',
	'useragent' => 'StackRambler'),
	array(
	'name' => 'SurveyBot',
	'useragent' => 'SurveyBot'),
	array(
	'name' => 'Touche',
	'useragent' => 'Touche'),
	array(
	'name' => 'Walhello',
	'useragent' => 'appie'),
	array(
	'name' => 'WebAlta',
	'useragent' => 'WebAlta'),
	array(
	'name' => 'Wisponbot',
	'useragent' => 'wisponbot'),
	array(
	'name' => 'YacyBot',
	'useragent' => 'yacybot'),
	array(
	'name' => 'YodaoBot',
	'useragent' => 'YodaoBot'),	
	// Google-Wanna-Be's - Spiders/Robots for Startups
	array(
	'name' => 'Charlotte',
	'useragent' => 'Charlotte'),
	array(
	'name' => 'DiscoBot',
	'useragent' => 'DiscoBot'),
	array(
	'name' => 'EnaBot',
	'useragent' => 'EnaBot'),
	array(
	'name' => 'Gaisbot',
	'useragent' => 'Gaisbot'),
	array(
	'name' => 'Kalooga',
	'useragent' => 'kalooga'),
	array(
	'name' => 'ScoutJet',
	'useragent' => 'ScoutJet'),
	array(
	'name' => 'TinEye',
	'useragent' => 'TinEye'),
	array(
	'name' => 'Twiceler',
	'useragent' => 'twiceler'),
	// Software
	array(
	'name' => 'GSiteCrawler',
	'useragent' => 'GSiteCrawler'),
	array(
	'name' => 'HTTrack',
	'useragent' => 'HTTrack'),
	array(
	'name' => 'Wget',
	'useragent' => 'Wget'),
	// Reason for detecting these: They can be very intensive. So seeing them in use, enables you to block if necessary.

);

	
// Now go through spider in the mo
foreach($addSpider as $spider)
{


		// Now delete each spider
	$spiders_group = array(

		'name'		=> addslashes($spider['name']),

	);
		$db->write_query("DELETE FROM ".TABLE_PREFIX."spiders WHERE name='".$spiders_group['name']."'");
			

}	
}

?>