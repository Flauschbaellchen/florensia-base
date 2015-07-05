/* Copyright by florensia-base.com */

function switchlayer(layernames, showlayer) {
	var layerarray = layernames.split(",");
	for (var layer in layerarray) {
		if (!document.getElementById(layerarray[layer])) continue;
		document.getElementById(layerarray[layer]).style.display= (layerarray[layer] == showlayer) ? "block" : "none";
	}
}


skill = Array();
function skilltree(skillbookid, do_action, maxlevel, skillpicture) {
	if (!skill[skillbookid]) {
		skill[skillbookid] = 0;
	}
	
	document.getElementById(skillbookid+"_"+skill[skillbookid]).style.display="none";

	if (skill[skillbookid]<maxlevel && do_action==1) {
		skill[skillbookid]++;
	} else if (skill[skillbookid]>0 && do_action==-1) {
		skill[skillbookid]--;
	}

	document.getElementById(skillbookid+"_"+skill[skillbookid]).style.display="inline";

	document.getElementById(skillbookid+"_plus").src= (skill[skillbookid]==maxlevel) ? "http://en.florensia-base.com/layer/skill_plus_inactive.gif" : "http://en.florensia-base.com/layer/skill_plus_active.gif";
	document.getElementById(skillbookid+"_minus").src= (skill[skillbookid]==0) ? "http://en.florensia-base.com/layer/skill_minus_inactive.gif" : "http://en.florensia-base.com/layer/skill_minus_active.gif";
	document.getElementsByName(skillbookid+"_level")[0].innerHTML=skill[skillbookid];
	if (document.getElementsByName(skillbookid+"_level")[1]) document.getElementsByName(skillbookid+"_level")[1].innerHTML=skill[skillbookid];
	
	if (!document.getElementById('permlink')) return;
	permlink = Array();
	for (bookid in skill) {
		if (skill[bookid] == 0) continue;
		permlink.push(bookid+"l"+skill[bookid]);
	}
	if (permlink.length>0) {
		document.getElementById('permlink').value=document.URL.split("?")[0] + "?s=" + permlink.join("b");
	} else {
		document.getElementById('permlink').value=document.URL.split("?")[0];
	}
}

function skilltree_rescue(skillbookid, maxlevel) {
	if (!skill[skillbookid]) {
		skill[skillbookid] = 0;
		return;
	}
	document.getElementById(skillbookid+"_0").style.display="none";
	document.getElementById(skillbookid+"_"+skill[skillbookid]).style.display="inline";
	document.getElementById(skillbookid+"_plus").src= (skill[skillbookid]==maxlevel) ? "http://en.florensia-base.com/layer/skill_plus_inactive.gif" : "http://en.florensia-base.com/layer/skill_plus_active.gif";
	document.getElementById(skillbookid+"_minus").src= (skill[skillbookid]==0) ? "http://en.florensia-base.com/layer/skill_minus_inactive.gif" : "http://en.florensia-base.com/layer/skill_minus_active.gif";
	document.getElementsByName(skillbookid+"_level")[0].innerHTML=skill[skillbookid];
	if (document.getElementsByName(skillbookid+"_level")[1]) document.getElementsByName(skillbookid+"_level")[1].innerHTML=skill[skillbookid];
}

function npc_mapprotection_change(divid, imageid) {
	document.getElementById(divid).style.backgroundImage = document.getElementById(divid).style.backgroundImage.replace(/mapid=[^&]+/i, "mapid=" + imageid);
}

function tabbar(activetab, alltabs) {
	alltabs = alltabs.split(",");
	if (!in_array(activetab, alltabs)) activetab = alltabs[0];
	else if (document.getElementsByName(activetab)) {
			var hiddenanchor = document.createElement('div');
			hiddenanchor.setAttribute("style", "position:absolute; visibility:normal; top:"+f_scrollTop()+"px;");
			hiddenanchor.setAttribute("id", activetab);
			document.getElementById("body").appendChild(hiddenanchor);
			window.location.hash = "#"+activetab;
			document.getElementById("body").removeChild(hiddenanchor);
	}
		
	
	for (tab in alltabs) {
		if (tab==activetab) continue;
		for (id in document.getElementsByName(alltabs[tab])) {
			if (!isInt(id)) continue;
			document.getElementsByName(alltabs[tab])[id].style.display = "none";
		}
		if (document.getElementById("tab_"+alltabs[tab])) {
			document.getElementById("tab_"+alltabs[tab]).style.background = "#396087";
			document.getElementById("tab_"+alltabs[tab]).style.color = "#8CE0FF";
			document.getElementById("tab_"+alltabs[tab]).style.fontWeight = "normal";
			document.getElementById("tab_"+alltabs[tab]).href = document.getElementById("tab_"+alltabs[tab]).href = "javascript:tabbar(\""+alltabs[tab]+"\", \""+alltabs.join(",")+"\")";
		}
	}
	
	for (id in document.getElementsByName(activetab)) {
		if (!isInt(id)) continue;		
		document.getElementsByName(activetab)[id].style.display = "inline";
	}
	if (document.getElementById("tab_"+activetab)) {
		document.getElementById("tab_"+activetab).style.fontWeight = "bold";
		document.getElementById("tab_"+activetab).style.background = "#75B2F0";
		document.getElementById("tab_"+activetab).style.color = "#396087";
	}
}


function isInt(x) {
	var y=parseInt(x);
	if (isNaN(y)) return false;
	return x==y && x.toString()==y.toString();
}


// http://www.softcomplex.com/docs/get_window_size_and_scrollbar_position.html
function f_scrollTop() {
	return f_filterResults (
		window.pageYOffset ? window.pageYOffset : 0,
		document.documentElement ? document.documentElement.scrollTop : 0,
		document.body ? document.body.scrollTop : 0
	);
}
function f_filterResults(n_win, n_docel, n_body) {
	var n_result = n_win ? n_win : 0;
	if (n_docel && (!n_result || (n_result > n_docel)))
		n_result = n_docel;
	return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}


// http://phpjs.org
function in_array(needle, haystack, argStrict) {
   var key = '', strict = !!argStrict; 
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
	    }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {                return true;
            }
        }
    }
    return false;
}