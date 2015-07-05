// use browser sniffing to determine if IE or Opera (ugly, but required) --- basescript/idea by webbugtrack.blogspot.com, debugged by florensia-base.com
var isOpera, isIE = false;
if (typeof(window.opera) != 'undefined') { isOpera = true; }
if (navigator.userAgent.indexOf('Internet Explorer')) { isIE = true; }
//alert(navigator.userAgent + "--"+ isOpera+" - "+isIE);
if (isIE) {
	//  var document._getElementsByName = document.getElementsByName;
	document.getElementsByName = function(name) {
		var temp = document.all;
		var matches = [];
		if (temp) {
			for (var i=0; i<temp.length; i++) {
				if(temp[i].name == name){
					matches.push(temp[i]);
				}
			}
		}
		return matches;
	}
}