/**
  * Thank you 3.0.2
  * written by Arash.j13@gmail.com
  * website  WWW.CodeCorona.com
  * **************************
  * Thank you 3.0.6
  * Upgrade for MyBB 1.4 : AmirH Hassaneini,Hamed Arfaee
  * w w w . i r a n v i g . c o m
*/
var pid=-1;
var spinner='';
var ThankYou = {

thx_action: function(response) {

	xml=response.responseXML;

	table=document.getElementById("thx"+pid);
	list=document.getElementById("thx_list"+pid);

	table.style.display=xml.getElementsByTagName("display").item(0).firstChild.data!=0 ?
		'' : 'none';
	
	list.innerHTML= xml.getElementsByTagName('list').item(0).firstChild.data;
	
	lin=document.getElementById('a'+pid);
	lin.onclick= new Function("","return ThankYou.rthx("+pid+");");
		
	lin.href='showthread.php?action=remove_thank&pid='+pid;
	
	img=document.getElementById('i'+pid);
	
	img.src=xml.getElementsByTagName('image').item(0).firstChild.data;
	
  document.body.style.cursor = 'default';
	spinner.destroy();
	spinner='';
},

rthx_action: function (response) {

	xml=response.responseXML;
	table=document.getElementById("thx"+pid);
	list=document.getElementById("thx_list"+pid);
	table.style.display=xml.getElementsByTagName("display").item(0).firstChild.data!=0 ?
		'' : 'none';
	
	list.innerHTML=xml.getElementsByTagName("list").item(0).firstChild.data;
	
	lin=document.getElementById("a"+pid);
	lin.onclick= new Function("","return ThankYou.thx("+pid+");");
	lin.href='showthread.php?action=thank&pid='+pid;
	
	img=document.getElementById('i'+pid);
	
	img.src=xml.getElementsByTagName('image').item(0).firstChild.data;
	
	document.body.style.cursor = 'default';
	spinner.destroy();
	spinner='';
},

thx: function (id) {

	spinner = new ActivityIndicator("body", {image: "images/spinner_big.gif"});
	document.body.style.cursor = 'wait';
	pid=id;
	b="pid="+pid;
	new Ajax.Request('xmlhttp.php?action=thankyou',{method: 'post', postBody:b ,onComplete: function(request) { ThankYou.thx_action(request); }});
	return false;
},

rthx: function rthx(id) {

	spinner = new ActivityIndicator("body", {image: "images/spinner_big.gif"});
	document.body.style.cursor = 'wait';
	pid=id;
	b="pid="+pid;
	new Ajax.Request('xmlhttp.php?action=remove_thankyou',{method: 'post', postBody:b ,onComplete: function(request) { ThankYou.rthx_action(request); }});
	return false;
}
};