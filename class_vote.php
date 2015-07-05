<?PHP

class class_vote {

	function votestatus($userline, $userid=0) {
		global $flouser;
		if (!$userid) $userid = $flouser->userid;
		
		if (preg_match("/^{$userid}-(1|0)-([0-9]+)/", $userline, $data) OR preg_match("/,{$userid}-(1|0)-([0-9]+)/", $userline, $data)) {
			return array('vote'=>intval($data[1]), 'dateline'=>intval($data[2]), 'userid'=>$userid);
		} else return false;
	}

	function vote($act, $userline, $userid=0) {
		global $flouser;
		if (!$userid) $userid = $flouser->userid;
		$status = $this->votestatus($userline, $userid);
		
		if ($status!==false && ($status['vote'] && $act=="thumpsup" OR !$status['vote'] && $act=="thumpsdown") OR $status===false && $act=="withdraw") {
				return array('changed'=>false, 'votestatus'=>$status, 'userline'=>$userline);
		}
		
		switch($act) {
			case "withdraw": {
				$userline = str_replace(",{$userid}-{$status['vote']}-{$status['dateline']}", '', $userline);
				$userline = str_replace("{$userid}-{$status['vote']}-{$status['dateline']},", '', $userline);
				$userline = str_replace("{$userid}-{$status['vote']}-{$status['dateline']}", '', $userline);
				return array('changed'=>true, 'votestatus'=>false, 'userline'=>$userline);
			}
			case "thumpsup": {
				//withdraw first
				$temp = $this->vote("withdraw", $userline, $userid);
				$userline = $temp['userline'];
				
				if (!strlen($userline)) $userline = "{$userid}-1-".date("U");
				else $userline = $userline.",{$userid}-1-".date("U");
				return array('changed'=>true, 'votestatus'=>$this->votestatus($userline, $userid), 'userline'=>$userline);
			}
			case "thumpsdown": {
				//withdraw first
				$temp = $this->vote("withdraw", $userline, $userid);
				$userline = $temp['userline'];
				
				if (!strlen($userline)) $userline = "{$userid}-0-".date("U");
				else $userline = $userline.",{$userid}-0-".date("U");
				return array('changed'=>true, 'votestatus'=>$this->votestatus($userline, $userid), 'userline'=>$userline);
			}
		}
	}
	
	function votestats($userline) {
		global $florensia, $flouserdata;
		
		$thumpsup = array();
		$thumpsdown = array();
		preg_match_all("/(?:^|,)(([1-9][0-9]*)-(1|0)-([0-9]+))/", $userline, $data);

		for ($i=0; $i<count($data[0]); $i++) {
			//echo "[$i]: $section ==> ".$data[1][$i]." - ".$data[2][$i]." - ".$data[3][$i]." - ".$data[4][$i]."<br />";
			$username = date("m.d.y", $data[4][$i]).": ".$flouserdata->get_username(intval($data[2][$i]));
	
			if ($data[3][$i]) $thumpsup[] = $username;
			else $thumpsdown[] = $username;
		}
		
		$display = "
			<table class='small' style='width:100%'>
				<tr><td style='text-align:center; width:50%;'><img src='{$florensia->layer_rel}/thumpsup.gif' alt='ThumpsUp' style='width:10px; height:13px;'></td><td style='text-align:center;'><img src='{$florensia->layer_rel}/thumpsdown.gif' alt='ThumpsDown' style='width:10px; height:13px;'></td></tr>
				<tr><td>".join("<br>", $thumpsup)."</td><td>".join("<br>", $thumpsdown)."</td></tr>
			</table>
		";
		return array('thumpsup'=>count($thumpsup), 'thumpsdown'=>count($thumpsdown), 'display'=>$display);
	}
}
?>