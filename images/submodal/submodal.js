/**
 * SUBMODAL v1.6
 * Used for displaying DHTML only popups instead of using buggy modal windows.
 *
 * By Subimage LLC
 * http://www.subimage.com
 *
 * Contributions by:
 * 	Eric Angel - tab index code
 * 	Scott - hiding/showing selects for IE users
 *	Todd Huss - inserting modal dynamically and anchor classes
 *
 * Up to date code can be found at http://submodal.googlecode.com
 */

// Popup code
var gPopupMask = null;
var gPopupContainer = null;
var gPopFrame = null;
var gReturnFunc;
var gPopupIsShown = false;
var gDefaultPage = "images/submodal/loading.html";
var gHideSelects = false;
var gReturnVal = null;
var stringSTOP = null;
var imageMAX = 'images/submodal/maximize.gif';
var imageRES = 'images/submodal/restore.gif';

var gTabIndexes = new Array();
// Pre-defined list of tags we want to disable/enable tabbing into
var gTabbableTags = new Array("A","BUTTON","TEXTAREA","INPUT","IFRAME");

// If using Mozilla or Firefox, use Tab-key trap.
if (!document.all) {
	document.onkeypress = keyDownHandler;
}

/**
 * Initializes popup code on load.
 */
function initPopUp() {
	// Add the HTML to the body
	theBody = document.getElementsByTagName('BODY')[0];
	popmask = document.createElement('div');
	popmask.id = 'popupMask';
	popcont = document.createElement('div');
	popcont.id = 'popupContainer';
	popcont.innerHTML = '' +
		'<div id="popupInner">' +
			'<div id="popupTitleBar">' +
				'<div id="popupTitle"></div>' +
				'<div id="popupControls">' +
					'<img src="' + imageMAX + '" onclick="maximizePopWin();" id="popMaximize" />&nbsp;' +
					'<img src="images/submodal/close.gif" onclick="if (window.frames[\'popupFrame\'].closePop()) doThreshold();" id="popCloseBox" />' +
				'</div>' +
			'</div>' +
			'<iframe src="'+ gDefaultPage +'" style="width:100%;height:100%;background-color:transparent;" scrolling="auto" frameborder="0" allowtransparency="true" id="popupFrame" name="popupFrame" width="100%" height="100%"></iframe>' +
		'</div>';
	theBody.appendChild(popmask);
	theBody.appendChild(popcont);

	gPopupMask = document.getElementById("popupMask");
	gPopupContainer = document.getElementById("popupContainer");
	gPopFrame = document.getElementById("popupFrame");

	// check to see if this is IE version 6 or lower. hide select boxes if so
	// maybe they'll fix this in version 7?
	var brsVersion = parseInt(window.navigator.appVersion.charAt(0), 10);
	if (brsVersion <= 6 && window.navigator.userAgent.indexOf("MSIE") > -1) {
		gHideSelects = true;
	}

	// Add onclick handlers to 'a' elements of class submodal or submodal-width-height
	var elms = document.getElementsByTagName('a');
	for (i = 0; i < elms.length; i++) {
		if (elms[i].className.indexOf("submodal") == 0) {
			// var onclick = 'function (){showPopWin(\''+elms[i].href+'\','+width+', '+height+', null,true,false);return false;};';
			// elms[i].onclick = eval(onclick);
			elms[i].onclick = function(){
				// default width and height
				var width = 400;
				var height = 200;
				// Parse out optional width and height from className
				params = this.className.split('-');
				if (params.length == 3) {
					width = parseInt(params[1]);
					height = parseInt(params[2]);
				}
				showPopWin(this.href,width,height,null,true,false); return false;
			}
		}
	}
}
addEvent(window, "load", initPopUp);

 /**
	* @argument width - int in pixels
	* @argument height - int in pixels
	* @argument url - url to display
	* @argument returnFunc - function to call when returning true from the window.
	* @argument showCloseBox - show the close box - default true
	*/
var defwidth=0;
var defheight=0;
var defmaximized=false;
var defurl="";
function showPopWin(url, width, height, returnFunc, showCloseBox, maximized) {
	if (defwidth==0) defwidth=width;
	if (defheight==0) defheight=height;
	if (defwidth==1) defwidth=800;
	if (defheight==1) defheight=500;
	defurl=url;
	if (maximized == null) maximized=false;
	defmaximized=maximized;
	if (maximized===true) {
		height = getViewportHeight();
		width = getViewportWidth();
	}

	// show or hide the window close widget

	if (maximized) document.getElementById("popMaximize").src=imageRES;
	
	if (showCloseBox == null || showCloseBox == true) {
		document.getElementById("popCloseBox").style.display = "inline";
		document.getElementById("popMaximize").style.display = "inline";
	} else {
		document.getElementById("popCloseBox").style.display = "none";
		document.getElementById("popMaximize").style.display = "none";
	}
	gPopupIsShown = true;
	disableTabIndexes();
	gPopupMask.style.display = "block";
	gPopupContainer.style.display = "block";
        theBody.style.overflow = "hidden";
	// calculate where to place the window on screen
	centerPopWin(width, height);

	var titleBarHeight = parseInt(document.getElementById("popupTitleBar").offsetHeight, 10);

	if (maximized===true) {
            gPopupContainer.style.left = 0 + "px";
            gPopupContainer.style.top = 10 + "px";
            gPopupContainer.style.width = width + "px";
        }else{
            gPopupContainer.style.width = width + "px";
	    gPopupContainer.style.height = (height+titleBarHeight-50) + "px";
        }

	setMaskSize();

	// need to set the width of the iframe to the title bar width because of the dropshadow
	// some oddness was occuring and causing the frame to poke outside the border in IE6
	gPopFrame.style.width = parseInt(document.getElementById("popupTitleBar").offsetWidth, 10) + "px";
	gPopFrame.style.height = (height-50) + "px";

	// set the url
	gPopFrame.src = url;

	gReturnFunc = returnFunc;
	// for IE
	if (gHideSelects == true) {
		hideSelectBoxes();
	}

	window.setTimeout("setPopTitle();", 600);
}

//
var gi = 0;
function centerPopWin(width, height) {
	if (gPopupIsShown == true) {
		if (width == null || isNaN(width)) {
			width = gPopupContainer.offsetWidth;
		}
		if (height == null) {
			height = gPopupContainer.offsetHeight;
		}

		//var theBody = document.documentElement;
		var theBody = document.getElementsByTagName("BODY")[0];
		//theBody.style.overflow = "hidden";
		var scTop = parseInt(getScrollTop(),10);
		var scLeft = parseInt(theBody.scrollLeft,10);
                var scWidth = theBody.offsetWidth - theBody.clientWidth;
                var scHeight = theBody.offsetHeight - theBody.clientHeight;
                var scHeight = 0; // se situa arriba el popup

		setMaskSize();

		//window.status = gPopupMask.style.top + " " + gPopupMask.style.left + " " + gi++;

		var titleBarHeight = parseInt(document.getElementById("popupTitleBar").offsetHeight, 10);

		var fullHeight = getViewportHeight();
		var fullWidth = getViewportWidth();
                var styleTop=((scTop + ((fullHeight - (height + 20)) + scHeight) / 2));
                var styleTop=(scTop + ((fullHeight - (height+titleBarHeight)) / 2));
		if (styleTop<0) {
                    gPopupContainer.style.top = "0px";
                } else {
                    gPopupContainer.style.top = styleTop + "px";
                }
		gPopupContainer.style.left =  (scLeft + (fullWidth - width) / 2) + "px";
		//alert(fullWidth+".w="+width+".l="+gPopupContainer.style.left+".t="+gPopupContainer.style.top);
	}
}
addEvent(window, "resize", centerPopWin);
addEvent(window, "scroll", centerPopWin);
window.onscroll = centerPopWin;


function maximizePopWin() {
	var fullHeight = getViewportHeight();
	var fullWidth = getViewportWidth();
        var scTop = parseInt(getScrollTop(),10);
        var scLeft = parseInt(theBody.scrollLeft,10);

	if (defmaximized==false) {
		defmaximized=true;
		//showPopWin(defurl,fullWidth,fullHeight,null,true,true); return false;
                theBody.style.overflow = "hidden";
                gPopupContainer.style.left = scLeft + "px";
                gPopupContainer.style.top = (scTop + 5) + "px";
                gPopupContainer.style.width = fullWidth + "px";
                gPopupContainer.style.height = (fullHeight - 50) + "px";
                gPopFrame.style.width = fullWidth + "px";
                gPopFrame.style.height = (fullHeight - 50) + "px";
		document.getElementById("popMaximize").src=imageRES;

	} else {
		defmaximized=false;
		//hidePopWin(false);
		//showPopWin('',defwidth,defheight,null,true,false); return false;
                gPopupContainer.style.width = defwidth + "px";
                gPopupContainer.style.height = defheight + "px";
                gPopFrame.style.width = defwidth + "px";
                gPopFrame.style.height = defheight + "px";
                centerPopWin(defwidth,defheight);
		document.getElementById("popMaximize").src=imageMAX;
	}
}
/**
 * Sets the size of the popup mask.
 *
 */
function setMaskSize() {
	var theBody = document.getElementsByTagName("BODY")[0];

	var fullHeight = getViewportHeight();
	var fullWidth = getViewportWidth();

	// Determine what's bigger, scrollHeight or fullHeight / width
	if (fullHeight > theBody.scrollHeight) {
		popHeight = fullHeight;
	} else {
		popHeight = theBody.scrollHeight;
	}

	if (fullWidth > theBody.scrollWidth) {
		popWidth = fullWidth;
	} else {
		popWidth = theBody.scrollWidth;
	}
	if (window.navigator.userAgent.indexOf("MSIE") > -1) popHeight+=parseInt(getScrollTop(),10); // IE mask Size FIX!
	gPopupMask.style.height = popHeight + "px";
	gPopupMask.style.width = popWidth + "px";
}

/**
 * @argument callReturnFunc - bool - determines if we call the return function specified
 * @argument returnVal - anything - return value
 */

function hidePopWin(callReturnFunc) {
	gPopupIsShown = false;
	var theBody = document.getElementsByTagName("BODY")[0];
	theBody.style.overflow = "auto";
	restoreTabIndexes();
	if (gPopupMask == null) {
		return;
	}
	gPopupMask.style.display = "none";
	gPopupContainer.style.display = "none";
	if (callReturnFunc == true && gReturnFunc != null) {
		// Set the return code to run in a timeout.
		// Was having issues using with an Ajax.Request();
		gReturnVal = window.frames["popupFrame"].returnVal;
		window.setTimeout('gReturnFunc(gReturnVal);', 1);
	}
	gPopFrame.src = gDefaultPage;
	// display all select boxes
	if (gHideSelects == true) {
		displaySelectBoxes();
	}
}

/**
 * Sets the popup title based on the title of the html document it contains.
 * Uses a timeout to keep checking until the title is valid.
 */
function setPopTitle() {
	//return;
	if (window.frames["popupFrame"].document.title == null) {
		window.setTimeout("setPopTitle();", 10);
	} else {
		document.getElementById("popupFrame").focus();
		document.getElementById("popupTitle").innerHTML = window.frames["popupFrame"].document.title;
	}
}

// Tab key trap. iff popup is shown and key was [TAB], suppress it.
// @argument e - event - keyboard event that caused this function to be called.

function keyDownHandler(e) {
    if (e == null) keycode = event.keyCode; // ie
    else keycode = e.which; // mozilla
    if (gPopupIsShown && keycode == 9) return false;
    if(keycode == 27) hidePopWin(false); // close
}

// For IE.  Go through predefined tags and disable tabbing into them.
function disableTabIndexes() {
	if (document.all) {
		var i = 0;
		for (var j = 0; j < gTabbableTags.length; j++) {
			var tagElements = document.getElementsByTagName(gTabbableTags[j]);
			for (var k = 0 ; k < tagElements.length; k++) {
				gTabIndexes[i] = tagElements[k].tabIndex;
				tagElements[k].tabIndex="-1";
				i++;
			}
		}
	}
}

// For IE. Restore tab-indexes.
function restoreTabIndexes() {
	if (document.all) {
		var i = 0;
		for (var j = 0; j < gTabbableTags.length; j++) {
			var tagElements = document.getElementsByTagName(gTabbableTags[j]);
			for (var k = 0 ; k < tagElements.length; k++) {
				tagElements[k].tabIndex = gTabIndexes[i];
				tagElements[k].tabEnabled = true;
				i++;
			}
		}
	}
}


/**
 * Hides all drop down form select boxes on the screen so they do not appear above the mask layer.
 * IE has a problem with wanted select form tags to always be the topmost z-index or layer
 *
 * Thanks for the code Scott!
 */
function hideSelectBoxes() {
  var x = document.getElementsByTagName("SELECT");

  for (i=0;x && i < x.length; i++) {
    x[i].style.visibility = "hidden";
  }
}

/**
 * Makes all drop down form select boxes on the screen visible so they do not
 * reappear after the dialog is closed.
 *
 * IE has a problem with wanting select form tags to always be the
 * topmost z-index or layer.
 */
function displaySelectBoxes() {
  var x = document.getElementsByTagName("SELECT");

  for (i=0;x && i < x.length; i++){
    x[i].style.visibility = "visible";
  }
}
