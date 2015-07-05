<?PHP

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

	$content = "
	<div class='subtitle' style='text-align:center'>
		xxx<br />
		xxx<br />
		xxx<br />
		<br />
		xxx
	</div>
	";

	$florensia->sitetitle("Legal Notice");
	$florensia->output_page($content);

?>