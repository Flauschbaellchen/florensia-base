<?php
class class_addition {

	function get_additionmenu($class, $classid) {
		global $florensia, $flouser, $flolog, $flolang;
		
                if ($flouser->userid) {
                    if ($flouser->get_permission("mod_flags")) {
                        
                        $addition = $this->get_addition($class, $classid, "entrystatus");
                        if ($addition) {
                            //there was already something checked
                            list($status, $userid, $timestamp) = explode(",", $addition);
                            if ($_POST['do_addition'] && $status != $_POST['entrystatus'] && in_array($_POST['entrystatus'], array('normal', 'removed', 'notimplemented', 'event'))) {
                                //user changed
                                if ($_POST['entrystatus']!="normal") {
                                    $this->set_addition($class, $classid, "entrystatus", "{$_POST['entrystatus']},{$flouser->userid},".date("U"));
                                    $flolog->add("{$class}:flag", "{user:{$flouser->userid}} changed entrystatus from {$status} to {$_POST['entrystatus']} on {{$class}:{$classid}}");
                                }
                                else {
                                    $this->set_addition($class, $classid, "entrystatus", 0);
                                    $flolog->add("{$class}:flag", "{user:{$flouser->userid}} removed entrystatus {$status} on {{$class}:{$classid}}");
                                }
                                $status = $_POST['entrystatus'];
                            }
                        } else {
                            //nothing was set
                            $status = "normal";
                            if ($_POST['do_addition'] && in_array($_POST['entrystatus'], array('removed', 'notimplemented', 'event'))) {
                                //user changed
                                $this->set_addition($class, $classid, "entrystatus", "{$_POST['entrystatus']},{$flouser->userid},".date("U"));
                                $flolog->add("{$class}:flag", "{user:{$flouser->userid}} set new entrystatus {$_POST['entrystatus']} on {{$class}:{$classid}}");
                                $status = $_POST['entrystatus'];
                            }
                        }
                        
                        $statuschecked[$status] = "checked='checked'";

                        $formcontent = "
                            <form action='".$florensia->escape($florensia->request_uri())."' method='post'>
                                <div style='font-weight:bold;'>Status:</div>
                                <div style='margin-left:10px;'>
                                    <input type='radio' name='entrystatus' value='normal' {$statuschecked['normal']}>{$flolang->addition_menu_normal}<br />
                                    <input type='radio' name='entrystatus' value='removed' {$statuschecked['removed']}>{$flolang->addition_menu_removed}<br />
                                    <input type='radio' name='entrystatus' value='notimplemented' {$statuschecked['notimplemented']}>{$flolang->addition_menu_notimplemented}<br />
                                    <input type='radio' name='entrystatus' value='event' {$statuschecked['event']}>{$flolang->addition_menu_event}<br />
                                </div>
                                <div style='text-align:right;'><input type='submit' name='do_addition' value='{$flolang->addition_menu_submit}'></div>
                            </form>
                        ";
                        
                        if ($flouser->get_permission("watch_log")) $adminloglink = " [<a href='{$florensia->root}/adminlog?section=".urlencode($class.":flag")."&amp;logvalue=".urlencode($classid)."' target='_blank'>L</a>]";
                    } else { return; }
                } else {
                    $formcontent = "<div style='text-align:center;'>{$flolang->addition_menu_error_notloggedin}</div>";
                }
		
		return "
			<div class='subtitle small' style='font-weight:normal;'>
                                <div id='titlebar_{$class}_{$classid}' style='text-align:center; font-weight:bold;'><a href='javascript:switchlayer(\"form_{$class}_{$classid},titlebar_{$class}_{$classid}\", \"form_{$class}_{$classid}\")'>{$flolang->addition_menu_title}</a>{$adminloglink}</div>
                                <div id='form_{$class}_{$classid}' style='display:none;'>
                                    <div style='text-align:center; font-weight:bold;'><a href='javascript:switchlayer(\"form_{$class}_{$classid},titlebar_{$class}_{$classid}\", \"titlebar_{$class}_{$classid}\")'>{$flolang->addition_menu_title}</a>{$adminloglink}</div>
                                    <div style='border-top:1px solid;'>
                                        {$formcontent}
                                    </div>
                                </div>
			</div>
		";
	}
        
        function get_additionlist($class, $classid) {
                global $flolang, $flouserdata, $florensia;
                $notices = array();
                $flagimg = "<img src='{$florensia->layer_rel}/flag.gif' boder='0' style='height:12px; margin-right:3px;'>";
		$addition = $this->get_addition($class, $classid);
		if ($addition['entrystatus']) {
			list($mstatus, $muserid, $mtimestamp) = explode(",", $addition['entrystatus']);
                        switch($mstatus) {
                            case "notimplemented": {
                                $notices['notimplemented'] = $flagimg."<span class='small' style='font-weight:normal;'>".$flolang->sprintf($flolang->addition_notice_notimplemented, $flouserdata->get_username($muserid), date("m.d.y", $mtimestamp))."</span>";
                                break;
                            }
                            case "removed": {
                                $notices['removed'] = $flagimg."<span class='small' style='font-weight:normal;'>".$flolang->sprintf($flolang->addition_notice_removed, $flouserdata->get_username($muserid), date("m.d.y", $mtimestamp))."</span>";
                                break;
                            }
                            case "event": {
                                $notices['event'] = $flagimg."<span class='small' style='font-weight:normal;'>".$flolang->sprintf($flolang->addition_notice_event, $flouserdata->get_username($muserid), date("m.d.y", $mtimestamp))."</span>";
                                break;
                            }
                        }	
		}
                return $notices;
        }
	
	
	function get_addition($class, $classid, $column="*") {
		$queryaddition = MYSQL_QUERY("SELECT $column FROM flobase_addition WHERE class='{$class}' AND classid='".mysql_real_escape_string($classid)."'");
		if (!($addition = MYSQL_FETCH_ARRAY($queryaddition))) return false;
		if ($column=="*") return $addition;
		return $addition[$column];
	}
	
	function set_addition($class, $classid, $column, $value) {
		$queryaddition = MYSQL_QUERY("SELECT classid FROM flobase_addition WHERE class='{$class}' AND classid='".mysql_real_escape_string($classid)."'");
		if (!($addition = MYSQL_FETCH_ARRAY($queryaddition))) {
			return MYSQL_QUERY("INSERT INTO flobase_addition (class, classid, $column) VALUES('{$class}', '".mysql_real_escape_string($classid)."', '".mysql_real_escape_string($value)."')");
		}
		return MYSQL_QUERY("UPDATE flobase_addition SET $column='".mysql_real_escape_string($value)."' WHERE class='{$class}' AND classid='".mysql_real_escape_string($classid)."'");
	}
}
?>