<?php

require_once("./init.php");
if (!defined('is_florensia')) die('Hacking attempt');

$splituri = explode("?", $_SERVER['REQUEST_URI']);
header("Location: {$florensia->forumurl}/member.php?{$splituri[1]}");
?>