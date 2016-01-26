<?php

function post_url_info()
{
    return array(
        "name"        => "Post-URL Mod",
        "description" => "Outputs the Post-URL by clicking at the number in every post.",
        "website"     => "http://mods.mybboard.net/archive/10/forum-display",
        "author"      => "XxAnimusxX",
        "authorsite"  => "http://www.k-under.de",
        "version"     => "1.1",
        "guid"        => "27a2c114930fc9e8b38d46cc380f793c",
        "compatibility" => "14*"
        );
}

$plugins->add_hook("global_end", "addPosturlData");
function addPosturlData()
{
        global $headerinclude;

        $headerinclude .= "\n<style type=\"text/css\">
<!--
        .posturl_container
        {
                width: 500px;
                height: 300px;
                padding: 0px;
                margin-left: -390px;
                position: absolute;
                z-index: 9999;
        }
        .posturl_innertd
        {
                padding-left: 20px;
                padding-right: 20px;
                padding-top: 90px;
                padding-bottom: 15px;
        }

        .posturl_container input
        {
                width: 430px;
                border: 1px dashed black;
                font-size: 12px;
                font-family: tahoma;
                padding-left: 4px;
                padding-right: 4px;
        }

        .posturl_container input:hover
        {
                border: 1px solid gray;
                cursor: pointer;
        }

        .posturl_container b {color: black;}

        .posturl_container a:link {color:midnightblue;text-decoration:none;font-weight:bold;}
        .posturl_container a:hover {color:red;}
//-->
</style>";

}


$plugins->add_hook("postbit", "addPosturlViewer");
function addPosturlViewer($post)
{
        global $mybb;

        $posturl = $post['posturl'];
        $postlink = $post['postlink'];
        $pid = $post['pid'];
        $link = $mybb->settings[bburl]."/".$postlink."#pid".$pid;
        $subject = str_replace("RE: ", "", $post['subject']);
        $bb_code = "[url=$link]".$subject." - Post #".$pid."[/url]";
        $htm_code = htmlentities("<a href=\"").$link.htmlentities("\">").$subject." - Post #".$pid.htmlentities("</a>");


        $posturl = preg_replace("!<a href=\".*?\">(#\d+)!",
                                "<a href=\"javascript:document.getElementById('posturl_container_$pid').style.display = '';void(0);\" id=\"posturl".$pid."\">$1
                                <img src=\"images/posturl_icon.gif\" border=0 width=11 height=8 title=\"open the Post-URL window\">",
                                $posturl);

        $table = "<br/>
<div class=\"posturl_container\" id=\"posturl_container_".$pid."\" style=\"display: none;\">
 <table border=0 cellspacing=0 width=100% height=100% background=\"images/post_url.png\">
  <tr valign=middle>
   <td align=center class=\"posturl_innertd\">
    <table border=0 cellspacing=0 width=100% height=100% style=\"font-size:14px;\">
     <tr valign=middle>
      <td align=left>
      <b>Post-URL: Permalink</b><br>
      <input type=\"text\" value=\"".$link."\" onfocus=\"this.select();\" readonly>
      <br><br>
      <b>Post-URL: BB-Link</b><br>
      <input type=\"text\" onfocus=\"this.select();\" value=\"$bb_code\" readonly>
      <br><br>
      <b>Post-URL: HTML-Link</b><br>
      <input type=\"text\" onfocus=\"this.select();\" value=\"".$htm_code."\" readonly>
      </td>
     </tr>
     <tr valign=bottom>
      <td align=right><a href=\"javascript:document.getElementById('posturl_container_$pid').style.display = 'none';void(0);\">
      [x] close</a>
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
</div>";

        $post['posturl'] = preg_replace("!<div(.*?)>(.*?)</div>!is", "<div$1>$2".$table."</div>", $posturl);
}

?>