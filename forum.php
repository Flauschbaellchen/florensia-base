<?PHP

if($mybb->input['action'] == "logout")
{
	$plugins->run_hooks("member_logout_start");

	if(!$mybb->user['uid'])
	{
		redirect("index.php", $lang->redirect_alreadyloggedout);
	}

	// Check session ID if we have one
	if($mybb->input['sid'] && $mybb->input['sid'] != $session->sid)
	{
		$florensia->output_page($florensia->notice($lang->error_notloggedout));
	}
	// Otherwise, check logoutkey
	else if(!$mybb->input['sid'] && $mybb->input['logoutkey'] != $mybb->user['logoutkey'])
	{
		$florensia->output_page($florensia->notice($lang->error_notloggedout, "warning"));
	}

	my_unsetcookie("mybbuser");
	my_unsetcookie("sid");
	if($mybb->user['uid'])
	{
		$time = TIME_NOW;
		$lastvisit = array(
			"lastactive" => $time-900,
			"lastvisit" => $time,
			);
		$db->update_query("users", $lastvisit, "uid='".$mybb->user['uid']."'");
		$db->delete_query("sessions", "sid='".$session->sid."'");
	}
	$plugins->run_hooks("member_logout_end");
	
	header("Location: {$florensia->root}");
	die;
	#redirect("index.php", $lang->redirect_loggedout);
}

if($mybb->input['action'] == "do_login" && $mybb->request_method == "post")
{
	$plugins->run_hooks("member_do_login_start");
	
	// Checks to make sure the user can login; they haven't had too many tries at logging in.
	// Is a fatal call if user has had too many tries
	$logins = login_attempt_check();
	$login_text = '';
	
	if(!username_exists($mybb->input['username']))
	{
		my_setcookie('loginattempts', $logins + 1);
		$florensia->output_page($florensia->notice($lang->error_invalidpworusername.$login_text, "warning"));
	}
	
	$query = $db->simple_select("users", "loginattempts", "LOWER(username)='".$db->escape_string(my_strtolower($mybb->input['username']))."'", array('limit' => 1));
	$loginattempts = $db->fetch_field($query, "loginattempts");
	
	$errors = array();
	
	$user = validate_password_from_username($mybb->input['username'], $mybb->input['password']);
	if(!$user['uid'])
	{
		my_setcookie('loginattempts', $logins + 1);
		$db->write_query("UPDATE ".TABLE_PREFIX."users SET loginattempts=loginattempts+1 WHERE LOWER(username) = '".$db->escape_string(my_strtolower($mybb->input['username']))."'");
		
		$mybb->input['action'] = "login";
		$mybb->input['request_method'] = "get";
		
		if($mybb->settings['failedlogintext'] == 1)
		{
			$login_text = $lang->sprintf($lang->failed_login_again, $mybb->settings['failedlogincount'] - $logins);
		}
		
		$errors[] = $lang->error_invalidpworusername.$login_text;
	}
	else
	{
		$correct = true;
	}
	
	if($loginattempts > 3 || intval($mybb->cookies['loginattempts']) > 3)
	{		
		// Show captcha image for guests if enabled
		if($mybb->settings['captchaimage'] == 1 && function_exists("imagepng") && !$mybb->user['uid'])
		{
			// If previewing a post - check their current captcha input - if correct, hide the captcha input area
			if($mybb->input['imagestring'])
			{
				$imagehash = $db->escape_string($mybb->input['imagehash']);
				$imagestring = $db->escape_string($mybb->input['imagestring']);
				$query = $db->simple_select("captcha", "*", "imagehash='{$imagehash}' AND imagestring='{$imagestring}'");
				$imgcheck = $db->fetch_array($query);
				if($imgcheck['dateline'] > 0)
				{		
					$correct = true;
				}
				else
				{
					$db->delete_query("captcha", "imagehash='{$imagehash}'");
					$errors[] = $lang->error_regimageinvalid;
				}
			}
			else if($mybb->input['quick_login'] == 1 && $mybb->input['quick_password'] && $mybb->input['quick_username'])
			{
				$errors[] = $lang->error_regimagerequired;
			}
			else
			{
				$errors[] = $lang->error_regimagerequired;
			}
		}
		
		$do_captcha = true;
	}
	
	if(!empty($errors))
	{
		$mybb->input['action'] = "login";
		$mybb->input['request_method'] = "get";
		
		foreach ($errors as $err) {
			$florensia->notice($err, "warning");
		}
		if ($do_captcha) {
				$captcha = "";
				// Show captcha image for guests if enabled
				if($mybb->settings['captchaimage'] == 1 && function_exists("imagepng") && $do_captcha == true)
				{
					if(!$correct)
					{	
						$randomstr = random_str(5);
						$imagehash = md5(random_str(12));
						$imagearray = array(
							"imagehash" => $imagehash,
							"imagestring" => $randomstr,
							"dateline" => TIME_NOW
						);
						$db->insert_query("captcha", $imagearray);
						$content = "
						<form action='".$florensia->escape($florensia->request_uri())."' method='post'>
							<div style='float:left; width:250px; padding-left:150px;'>
							<img src='{$florensia->forumurl}/captcha.php?imagehash={$imagehash}' alt='{$lang->image_verification}' title='{$lang->image_verification}' id='captcha_img' /><br />
							<input type='text' class='textbox' name='imagestring' value='' id='imagestring' style='width:200px' />
							</div>
							<div style='margin-left:250px;'>
							<input type='text' class='textbox' name='username' size='25' maxlength='{$mybb->settings['maxnamelength']}' style='width: 80px;' value='".$florensia->escape($_POST['username'])."' />
							<input type='password' class='textbox' name='password' size='25' style='width: 80px;' value='".$florensia->escape($_POST['password'])."' /><br /><br />
							<input type='submit' class='button' name='submit' value='{$lang->login}' />
							<input type='hidden' name='action' value='do_login' />
							<input type='hidden' name='imagehash' value='{$imagehash}' id='imagehash' />
							</div>
						</form>";
					}
				}
				
				$username = "";
				$password = "";
				if($mybb->input['username'] && $mybb->request_method == "post")
				{
					$username = htmlspecialchars_uni($mybb->input['username']);
				}
				
				if($mybb->input['password'] && $mybb->request_method == "post")
				{
					$password = htmlspecialchars_uni($mybb->input['password']);
				}
			
				eval("\$login = \"".$templates->get("member_login")."\";");
		}
		$florensia->output_page($content);
		//$inline_errors = inline_error($errors);
	}
	else if($correct)
	{		
		if($user['coppauser'])
		{
			$florensia->output_page($florensia->notice($lang->error_awaitingcoppa));
		}
		
		my_setcookie('loginattempts', 1);
		$db->delete_query("sessions", "ip='".$db->escape_string($session->ipaddress)."' AND sid != '".$session->sid."'");
		$newsession = array(
			"uid" => $user['uid'],
		);
		$db->update_query("sessions", $newsession, "sid='".$session->sid."'");
		
		$db->update_query("users", array("loginattempts" => 1), "uid='{$user['uid']}'");
	
		// Temporarily set the cookie remember option for the login cookies
		$mybb->user['remember'] = $user['remember'];
	
		my_setcookie("mybbuser", $user['uid']."_".$user['loginkey'], null, true);
		my_setcookie("sid", $session->sid, -1, true);
	
		$plugins->run_hooks("member_do_login_end");
	
		if($mybb->input['url'] != "" && my_strpos(basename($mybb->input['url']), 'member.php') === false)
		{
			if((my_strpos(basename($mybb->input['url']), 'newthread.php') !== false || my_strpos(basename($mybb->input['url']), 'newreply.php') !== false) && my_strpos($mybb->input['url'], '&processed=1') !== false)
			{
				$mybb->input['url'] = str_replace('&processed=1', '', $mybb->input['url']);
			}
			
			$mybb->input['url'] = str_replace('&amp;', '&', $mybb->input['url']);
			
			// Redirect to the URL if it is not member.php
			redirect(htmlentities($mybb->input['url']), $lang->redirect_loggedin);
		}
		else
		{
			redirect("index.php", $lang->redirect_loggedin);
		}
	}
	else
	{
		$mybb->input['action'] = "login";
		$mybb->input['request_method'] = "get";
	}
	
	$plugins->run_hooks("member_do_login_end");
}
?>
