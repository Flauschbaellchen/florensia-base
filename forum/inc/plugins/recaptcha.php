<?php
// Original Recaptcha Plugin
// By Nitemare http://www.Nitemare.ca/
// Version 1.0
// For myBB 1.2.x

// Updated for myBB V 1.4.x
// Added Style & Language settings
// By Jim Booth http://www.morkeye.co.uk/
// Version 1.1

// Tell MyBB when to run the hooks
// $plugins->add_hook("hook name", "function name");
$plugins->add_hook("member_register_start", "recaptcha_display");
$plugins->add_hook('datahandler_user_validate', 'recaptcha_verify');

// The information that shows up on the plugin manager
// Note that the name of the function before _info, _activate, _deactivate must be the same as the filename before the extension.
function recaptcha_info()
{
	return array(
		"name"			=> "reCAPTCHA Plugin",
		"description"	=> "Allows image verification provided by ReCAPTCHA",
		"website"		=> "http://www.morkeye.co.uk",
		"author"		=> "Jim Booth",
		"authorsite"	=> "http://www.morkeye.co.uk",
		"version"		=> "1.1",
		"guid"			=> "01febd72387a02ede5930b5091641098"
	);
}

// This function runs when the plugin is activated.
function recaptcha_activate()
{
	
	global $db, $mybb, $lang;

	$lang->load("member");
	
	$new_template = array(
		"tid"		=> NULL,
		"title"		=> 'register_captcha',
		"template"	=> $db->escape_string('<br /> 
		{$captcha_options}
		<fieldset class="trow2">
		<legend><strong>'.$lang->image_verification.'<strong></legend>
		<table cellspacing="0" cellpadding="{$theme[\'tablespace\']}"><tr>
		<td><span class="smalltext">'.$lang->verification_note.'</span><br/><br/>{$captcha_image}</td>
		</tr>
		</table></fieldset>'),
		"sid"		=> "-1",
		"version"	=> "1.0",
		"dateline"	=> "1148741714",
	);

	$db->insert_query("templates", $new_template);
	
	
	require MYBB_ROOT.'/inc/adminfunctions_templates.php';
	// MEMBERPROFILE
	find_replace_templatesets("member_register", '#{\$regimage}#', "{\$captcha}{\$regimage}");
	
	
		$captcha_group = array(
		"name"			=> "captcha_group",
		"title"			=> "reCAPTCHA Settings.",
		"description"	=> "New users need to verify a reCAPTCHA image before completing the registration.",
		"disporder"		=> "25",
		"isdefault"		=> "no",
	);

	$db->insert_query("settinggroups", $captcha_group);
	$gid = $db->insert_id();

		$new_setting = array(
		'name'			=> 'captcha_status',
		'title'			=> 'ReCAPTCHA status',
		'description'	=> 'Show reCAPTCHA While New User Tries To Register?',
		'optionscode'	=> 'yesno',
		'value'			=> 'no',
		'disporder'		=> '1',
		'gid'			=> intval($gid)
	);

	$db->insert_query('settings', $new_setting);
		
	$new_setting2 = array(
		'name'			=> 'captcha_public',
		'title'			=> 'ReCAPTCHA public key',
		'description'	=> 'Enter your reCAPTCHA public key here, you can obtain this by loging in to your account here: http://recaptcha.net/',
		'optionscode'	=> 'text',
		'value'			=> '',
		'disporder'		=> '2',
		'gid'			=> intval($gid)
	);

	$db->insert_query('settings', $new_setting2);

		$new_setting3 = array(
		'name'			=> 'captcha_private',
		'title'			=> 'ReCAPTCHA private key',
		'description'	=> 'Enter your reCAPTCHA private key here, you can obtain this by loging in to your account here: http://recaptcha.net/',
		'optionscode'	=> 'text',
		'value'			=> '',
		'disporder'		=> '3',
		'gid'			=> intval($gid)
	);

	$db->insert_query('settings', $new_setting3);
	
		$new_setting4 = array(
		'name'			=> 'captcha_colour',
		'title'			=> 'ReCAPTCHA style/colour',
		'description'	=> 'Select the reCAPTCHA style/colour that best suits your theme.',
        "optionscode" => "select
red= Red
white= White
blackglass= Black Glass
clean= Clean",
		'value'			=> 'red',
		'disporder'		=> '4',
		'gid'			=> intval($gid),
	);

	$db->insert_query('settings', $new_setting4);

		$new_setting5 = array(
		'name'			=> 'captcha_language',
		'title'			=> 'ReCAPTCHA Language',
		'description'	=> 'Which language is used in the interface for the pre-defined themes above.',
        "optionscode" => "select
en= English
nl= Dutch
fr= French
de= German
pt= Portugese
ru= Russian
es= Spanish
tr= Turkish",
		'value'			=> 'en',
		'disporder'		=> '5',
		'gid'			=> intval($gid),
	);

	$db->insert_query('settings', $new_setting5);
	
	rebuildsettings();
}

// This function runs when the plugin is deactivated.
function recaptcha_deactivate()
{
	global $mybb, $db;
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title = 'register_captcha'");

	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='captcha_colour'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='captcha_status'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='captcha_public'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='captcha_private'");
	$db->delete_query("settinggroups","name='captcha_group'");

	require MYBB_ROOT.'/inc/adminfunctions_templates.php';
	//REMOVING tags
	find_replace_templatesets("member_register", '#'.preg_quote('{$captcha}').'#', '',0);

	rebuildsettings();

	
}

// This is the function that is run when the hook is called.
// It must match the function name you placed when you called add_hook.
// You are not just limited to 1 hook per page.  You can add as many as you want.
function recaptcha_display()
{
	global $db, $mybb, $templates, $captcha, $theme;
	require_once('recaptchalib.php');
	if($mybb->settings['captcha_status'] != "no" && $mybb->settings['captcha_public']!= "" && $mybb->settings['captcha_private'] != ""){
		$publickey = $mybb->settings['captcha_public'];
		$captcha_options = "<script type='text/javascript'>
					var RecaptchaOptions = {
					theme : '".$mybb->settings['captcha_colour']."',
					lang : '".$mybb->settings['captcha_language']."'
					};
					</script>
					";
		$captcha_image = recaptcha_get_html($publickey);
		eval("\$captcha = \"".$templates->get("register_captcha")."\";");
	}
}

function recaptcha_verify($reg){

	global $db, $mybb, $templates, $captcha, $theme;
	
	if($mybb->settings['captcha_status'] != "no" && $mybb->settings['captcha_public']!= "" && $mybb->settings['captcha_private'] != ""){
		if(strpos($_SERVER['REQUEST_URI'], 'member.php')){
			
			require_once('recaptchalib.php');
			$privatekey = $mybb->settings['captcha_private'];
			$resp = recaptcha_check_answer ($privatekey,
			                                $_SERVER["REMOTE_ADDR"],
			                                $_POST["recaptcha_challenge_field"],
			                                $_POST["recaptcha_response_field"]);
			
			if (!$resp->is_valid) {
				$reg->set_error($db->escape_string("reCAPTCHA Verification failed. (".$resp->error.")"));
				return $reg;
			}
		}
	}
}


// End of plugin.
?>