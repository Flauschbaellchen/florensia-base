<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$flolang->load("donate");

//get current goal
list($dcurrentgoal) = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT goal FROM flobase_donate_goals WHERE starttime<='".date("U")."' ORDER BY starttime DESC LIMIT 1"));

$querydonator = MYSQL_QUERY("SELECT name, SUM(amount) as amount, COUNT(name) as donations FROM flobase_donate_donators GROUP BY name ORDER BY amount DESC, name");
while ($donator = MYSQL_FETCH_ARRAY($querydonator)) {
    if (!strlen($donator['name'])) $donator['name'] = $flolang->donate_anonym;
    $donatorlist .= "<tr>
        <td style='padding-right:10px; text-align:right;'>{$donator['amount']}&euro;</td>
        <td style='padding-right:10px;'>".$florensia->escape($donator['name'])."</td>
        <td style='font-weight:normal; text-align:right;'>".$flolang->sprintf($flolang->donate_donatorlist_donationamount, $donator['donations'])."</td>
        </tr>";
}
$content .= "
<div class='subtitle small' style='margin-bottom:5px;'>".$flolang->sprintf($flolang->donate_notice, $dcurrentgoal."&euro;")."</div>
<div class='subtitle small' style='margin-bottom:30px; font-weight:normal'>{$flolang->donate_anonym_donatenotice}</div>

<div style='text-align:center;'>
	<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
            <input type='hidden' name='cmd' value='_s-xclick'>
            <input type='hidden' name='hosted_button_id' value='1907547'>
            <input type='image' src='{$florensia->layer_rel}/donate.png' name='submit' alt='' style='background-color:#396087;'>
            <img alt='' border='0' src='https://www.paypal.com/de_DE/i/scr/pixel.gif' width='1' height='1'>
	</form>
</div>
<div style='margin-bottom:20px;'></div>
<table class='small subtitle' style='margin:auto;'>
    <tr><td colspan='3' style='border-bottom:1px solid;'>{$flolang->donate_donatorlist_title}</td></tr>
    {$donatorlist}
</table>";
$florensia->sitetitle("Donate");
$florensia->output_page($content);

?>