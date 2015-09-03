//	written	by Tan Ling	Wee	on 2 Dec 2001
//	email :	fuushikaden@yahoo.com
//	last updated 27 Aug 2004 by Peter Mikula

var fixedX = -1;			// x position (-1 if to appear below control)
var fixedY = -1;			// y position (-1 if to appear below control)
var startAt = 1;			// 0 - sunday ; 1 - monday
var showWeekNumber = 1;	// 0 - don't show; 1 - show
var showToday = 0;		// 0 - don't show; 1 - show
var imgDir = "images/calendar/";
var gotoString = "Ir a mes actual";
var todayString = "Hoy es";
var weekString = "S.";
var scrollLeftMessage = "Click to scroll to previous month. Hold mouse button to scroll automatically.";
var scrollRightMessage = "Click to scroll to next month. Hold mouse button to scroll automatically.";
var selectMonthMessage = "Click to select a month.";
var selectYearMessage = "Click to select a year.";
var selectDateMessage = "Select [date] as date."; // do not replace [date], it will be replaced by date.
var crossobj, crossMonthObj, crossYearObj, monthSelected, yearSelected, dateSelected, omonthSelected, oyearSelected, odateSelected, monthConstructed, yearConstructed, intervalID1, intervalID2, timeoutID1, timeoutID2, ctlToPlaceValue, ctlToPlaceValueD, ctlToPlaceValueM, ctlToPlaceValueY, ctlNow, dateFormat, nStartingYear;
var bPageLoaded=false;
var ie=document.all;
var dom=document.getElementById;
var ns4=document.layers;
var today =	new	Date();
var dateNow	 = today.getDate();
var monthNow = today.getMonth();
var yearNow	 = today.getYear();
var imgsrc = new Array("drop1.gif","drop2.gif","left1.gif","left2.gif","right1.gif","right2.gif");
var img	= new Array();
var bShow = false;

/* hides <select> and <applet> objects (for IE only) */
function hideElement( elmID, overDiv ) {
      if(ie) {
        for( i = 0; i < document.all.tags( elmID ).length; i++ ) {
          obj = document.all.tags( elmID )[i];
          if( !obj || !obj.offsetParent ) { continue; }
          // Find the element's offsetTop and offsetLeft relative to the BODY tag.
          objLeft   = obj.offsetLeft;
          objTop    = obj.offsetTop;
          objParent = obj.offsetParent;
          while( objParent.tagName.toUpperCase() != "BODY" ) {
            objLeft  += objParent.offsetLeft;
            objTop   += objParent.offsetTop;
            objParent = objParent.offsetParent;
          }
          objHeight = obj.offsetHeight;
          objWidth = obj.offsetWidth;
          if(( overDiv.offsetLeft + overDiv.offsetWidth ) <= objLeft );
          else if(( overDiv.offsetTop + overDiv.offsetHeight ) <= objTop );
          else if( overDiv.offsetTop >= ( objTop + objHeight ));
          else if( overDiv.offsetLeft >= ( objLeft + objWidth ));
          else {
            obj.style.visibility = "hidden";
          }
        }
      }
}
// unhides <select> and <applet> objects (for IE only)
function showElement(elmID) {
      if(ie) {
        for( i = 0; i < document.all.tags( elmID ).length; i++ ) {
          obj = document.all.tags( elmID )[i];
          if(!obj || !obj.offsetParent2) { continue; }
          obj.style.visibility = "";
        }
      }
}
function HolidayRec (d, m, y, desc) {
		this.d = d;
		this.m = m;
		this.y = y;
		this.desc = desc;
}
var HolidaysCounter = 0;
var Holidays = new Array();
function addHoliday (d, m, y, desc) {
    Holidays[HolidaysCounter++] = new HolidayRec ( d, m, y, desc )
}



	if (dom) {
		for (i=0; i<imgsrc.length; i++) {
			img[i] = new Image;
			img[i].src = imgDir + imgsrc[i];
		}
		document.write ("<div onclick='bShow=true' id='calendar' style='z-index:+999;position:absolute;visibility:hidden;top:0px;left:0px;'><table	width="+((showWeekNumber==1)?250:220)+" style='font-family:arial;font-size:11px;border-width:1px;border-style:solid;border-color:#a0a0a0;font-family:arial; font-size:11px;' bgcolor='#ffffff'><tr class=calendar bgcolor='#0000aa'><td><table width='"+((showWeekNumber==1)?248:218)+"'><tr><td style='padding:2px;font-family:arial; font-size:11px;'><font color='#ffffff'><B><span id='caption'></span></B></font></td><td align=right><a href='javascript:hideCalendar()'><IMG SRC='"+imgDir+"close.gif' WIDTH='15' HEIGHT='13' BORDER='0' ALT='Close the Calendar'></a></td></tr></table></td></tr><tr><td style='padding:5px' bgcolor=#ffffff><span id='content'></span></td></tr>");
		if (showToday==1) {
			document.write ("<tr bgcolor=#f0f0f0><td style='padding:5px' align=center><span id='lblToday'></span></td></tr>");
		}
		document.write ("</table></div><div id='selectMonth' style='z-index:+999;position:absolute;visibility:hidden;'></div><div id='selectYear' style='z-index:+999;position:absolute;visibility:hidden;'></div>");
	}
	var monthName =	new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	var monthName2 = new Array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
	if (startAt==0) {
		//dayName = new Array ("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
      dayName = new Array ("Dom","Lun","Mar","Mie","Jue","Vie","Sab");
	} else {
		//dayName = new Array ("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
      dayName = new Array ("Lun","Mar","Mie","Jue","Vie","Sab","Dom");
	}
	var styleAnchor="text-decoration:none;color:black;";
	var styleLightBorder="border-style:solid;border-width:1px;border-color:#a0a0a0;";

	function swapImage(srcImg, destImg){
		if (ie)	{ document.getElementById(srcImg).setAttribute("src",imgDir + destImg); }
	}
	function init()	{
		if (yearNow<200 && yearNow>0) yearNow += 1900;
		if (!ns4) {
			crossobj=(dom)?document.getElementById("calendar").style : ie? document.all.calendar : document.calendar;
			hideCalendar();
			crossMonthObj=(dom)?document.getElementById("selectMonth").style : ie? document.all.selectMonth	: document.selectMonth;
			crossYearObj=(dom)?document.getElementById("selectYear").style : ie? document.all.selectYear : document.selectYear;
			monthConstructed=false;
			yearConstructed=false;
			if (showToday==1) {
				document.getElementById("lblToday").innerHTML =	todayString + " <a onmousemove='window.status=\""+gotoString+"\"' onmouseout='window.status=\"\"' title='"+gotoString+"' style='"+styleAnchor+"' href='javascript:monthSelected=monthNow;yearSelected=yearNow;constructCalendar();'>"+dayName[(today.getDay()-startAt==-1)?6:(today.getDay()-startAt)]+", " + dateNow + " " + monthName[monthNow].substring(0,3)	+ "	" +	yearNow	+ "</a>";
			}
			sHTML1="<span id='spanLeft' style='border-style:solid;border-width:1px;border-color:#3366FF;cursor:pointer' onmouseover='swapImage(\"changeLeft\",\"left2.gif\");this.style.borderColor=\"#88AAFF\";window.status=\""+scrollLeftMessage+"\"' onclick='javascript:decMonth()' onmouseout='clearInterval(intervalID1);swapImage(\"changeLeft\",\"left1.gif\");this.style.borderColor=\"#3366FF\";window.status=\"\"' onmousedown='clearTimeout(timeoutID1);timeoutID1=setTimeout(\"StartDecMonth()\",500)'	onmouseup='clearTimeout(timeoutID1);clearInterval(intervalID1)'>&nbsp<IMG id='changeLeft' SRC='"+imgDir+"left1.gif' width=10 height=11 BORDER=0>&nbsp</span>&nbsp;";
			sHTML1+="<span id='spanRight' style='border-style:solid;border-width:1px;border-color:#3366FF;cursor:pointer'	onmouseover='swapImage(\"changeRight\",\"right2.gif\");this.style.borderColor=\"#88AAFF\";window.status=\""+scrollRightMessage+"\"' onmouseout='clearInterval(intervalID1);swapImage(\"changeRight\",\"right1.gif\");this.style.borderColor=\"#3366FF\";window.status=\"\"' onclick='incMonth()' onmousedown='clearTimeout(timeoutID1);timeoutID1=setTimeout(\"StartIncMonth()\",500)'	onmouseup='clearTimeout(timeoutID1);clearInterval(intervalID1)'>&nbsp<IMG id='changeRight' SRC='"+imgDir+"right1.gif'	width=10 height=11 BORDER=0>&nbsp</span>&nbsp";
			sHTML1+="<span id='spanMonth' style='border-style:solid;border-width:1px;border-color:#3366FF;cursor:pointer'	onmouseover='swapImage(\"changeMonth\",\"drop2.gif\");this.style.borderColor=\"#88AAFF\";window.status=\""+selectMonthMessage+"\"' onmouseout='swapImage(\"changeMonth\",\"drop1.gif\");this.style.borderColor=\"#3366FF\";window.status=\"\"' onclick='popUpMonth()'></span>&nbsp;";
			sHTML1+="<span id='spanYear' style='border-style:solid;border-width:1px;border-color:#3366FF;cursor:pointer' onmouseover='swapImage(\"changeYear\",\"drop2.gif\");this.style.borderColor=\"#88AAFF\";window.status=\""+selectYearMessage+"\"'	onmouseout='swapImage(\"changeYear\",\"drop1.gif\");this.style.borderColor=\"#3366FF\";window.status=\"\"'	onclick='popUpYear()'></span>&nbsp;";
			document.getElementById("caption").innerHTML  =	sHTML1;
			bPageLoaded=true;
		}
	}
	function hideCalendar()	{
	    crossobj.visibility="hidden";
	    if (crossMonthObj != null){crossMonthObj.visibility="hidden"};
	    if (crossYearObj !=	null){crossYearObj.visibility="hidden"};
	    showElement( 'SELECT' );
	    showElement( 'APPLET' );
	}
	function padZero(num) {
		return (num	< 10)? '0' + num : num ;
	}
	function constructDate(d,m,y) {
		sTmp = dateFormat;
		sTmp = sTmp.replace	("dd","<e>");
		sTmp = sTmp.replace	("d","<d>");
		sTmp = sTmp.replace	("<e>",padZero(d));
		sTmp = sTmp.replace	("<d>",d);
		sTmp = sTmp.replace	("mmmm","<p>");
		sTmp = sTmp.replace	("mmm","<o>");
		sTmp = sTmp.replace	("mm","<n>");
		sTmp = sTmp.replace	("m","<m>");
		sTmp = sTmp.replace	("<m>",m+1);
		sTmp = sTmp.replace	("<n>",padZero(m+1));
		sTmp = sTmp.replace	("<o>",monthName[m]);
		sTmp = sTmp.replace	("<p>",monthName2[m]);
		sTmp = sTmp.replace	("yyyy",y);
		return sTmp.replace ("yy",padZero(y%100));
	}
	function closeCalendar() {
		var	sTmp

		hideCalendar();
		if ( ctlToPlaceValue == undefined ) {
			ctlToPlaceValueD.value	= dateSelected
			ctlToPlaceValueM.value	= monthSelected+1
			if (yearSelected<200 && yearSelected>0) ctlToPlaceValueY.value	= yearSelected+1900
			else ctlToPlaceValueY.value	= yearSelected
		}else{
			ctlToPlaceValue.value =	constructDate(dateSelected,monthSelected,yearSelected)
		}
	}
	// Month Pulldown
	function StartDecMonth() {
		intervalID1=setInterval("decMonth()",80);
	}
	function StartIncMonth() {
		intervalID1=setInterval("incMonth()",80);
	}
	function incMonth () {
		monthSelected++;
		if (monthSelected>11) {
			monthSelected=0;
			yearSelected++;
		}
		constructCalendar();
	}
	function decMonth () {
		monthSelected--;
		if (monthSelected<0) {;
			monthSelected=11;
			yearSelected--;
		}
		constructCalendar();
	}
	function constructMonth() {
		popDownYear();
		if (!monthConstructed) {
			sHTML =	"";
			for (i=0; i<12;	i++) {
				sName =	monthName[i];
				if (i==monthSelected) { sName =	"<B>" +	sName +	"</B>"; }
				sHTML += "<tr><td id='m" + i + "' onmouseover='this.style.backgroundColor=\"#FFCC99\"' onmouseout='this.style.backgroundColor=\"\"' style='cursor:pointer' onclick='monthConstructed=false;monthSelected=" + i + ";constructCalendar();popDownMonth();event.cancelBubble=true'>&nbsp;" + sName + "&nbsp;</td></tr>";
			}
			document.getElementById("selectMonth").innerHTML = "<table width=70	style='font-family:arial; font-size:11px; border-width:1px; border-style:solid; border-color:#a0a0a0;' bgcolor='#FFFFDD' cellspacing=0 onmouseover='clearTimeout(timeoutID1)'	onmouseout='clearTimeout(timeoutID1);timeoutID1=setTimeout(\"popDownMonth()\",100);event.cancelBubble=true'>" +	sHTML +	"</table>";
			monthConstructed=true;
		}
	}
	function popUpMonth() {
		constructMonth();
		crossMonthObj.visibility = (dom||ie)? "visible"	: "show";
		crossMonthObj.left = parseInt(crossobj.left) + 50 + 'px';
		crossMonthObj.top =	parseInt(crossobj.top) + 26 + 'px';
		hideElement( 'SELECT', document.getElementById("selectMonth") );
		hideElement( 'APPLET', document.getElementById("selectMonth") );
	}
	function popDownMonth()	{
		crossMonthObj.visibility= "hidden";
	}
	//*** Year Pulldown
	function incYear() {
		for (i=0; i<7; i++){
			newYear	= (i+nStartingYear)+1;
			if (newYear==yearSelected) { txtYear = "&nbsp;<B>" + newYear +	"</B>&nbsp;"; }
			else { txtYear = "&nbsp;" + newYear + "&nbsp;"; }
			document.getElementById("y"+i).innerHTML = txtYear;
		}
		nStartingYear ++;
		bShow=true;
	}
	function decYear() {
		for (i=0; i<7; i++){
			newYear	= (i+nStartingYear)-1;
			if (newYear==yearSelected) { txtYear = "&nbsp;<B>" + newYear +	"</B>&nbsp;"; }
			else { txtYear = "&nbsp;" + newYear + "&nbsp;"; }
			document.getElementById("y"+i).innerHTML = txtYear;
		}
		nStartingYear --;
		bShow=true;
	}
	function selectYear(nYear) {
		yearSelected=parseInt(nYear+nStartingYear);
		yearConstructed=false;
		constructCalendar();
		popDownYear();
	}
	function constructYear() {
		popDownMonth();
		sHTML =	"";
		if (!yearConstructed) {
			sHTML =	"<tr><td align='center'	onmouseover='this.style.backgroundColor=\"#FFCC99\"' onmouseout='clearInterval(intervalID1);this.style.backgroundColor=\"\"' style='cursor:pointer'	onmousedown='clearInterval(intervalID1);intervalID1=setInterval(\"decYear()\",30)' onmouseup='clearInterval(intervalID1)'>-</td></tr>";
			j = 0;
			nStartingYear =	yearSelected-3;
			for (i=(yearSelected-3); i<=(yearSelected+3); i++) {
				sName =	i;
				if (i==yearSelected) { sName =	"<B>" +	sName +	"</B>"; }
				sHTML += "<tr><td id='y" + j + "' onmouseover='this.style.backgroundColor=\"#FFCC99\"' onmouseout='this.style.backgroundColor=\"\"' style='cursor:pointer' onclick='selectYear("+j+");event.cancelBubble=true'>&nbsp;" + sName + "&nbsp;</td></tr>";
				j ++;
			}
			sHTML += "<tr><td align='center' onmouseover='this.style.backgroundColor=\"#FFCC99\"' onmouseout='clearInterval(intervalID2);this.style.backgroundColor=\"\"' style='cursor:pointer' onmousedown='clearInterval(intervalID2);intervalID2=setInterval(\"incYear()\",30)'	onmouseup='clearInterval(intervalID2)'>+</td></tr>";
			document.getElementById("selectYear").innerHTML	= "<table width=44 style='font-family:arial; font-size:11px; border-width:1px; border-style:solid; border-color:#a0a0a0;'	bgcolor='#FFFFDD' onmouseover='clearTimeout(timeoutID2)' onmouseout='clearTimeout(timeoutID2);timeoutID2=setTimeout(\"popDownYear()\",100)' cellspacing=0>" + sHTML + "</table>";
			yearConstructed	= true;
		}
	}
	function popDownYear() {
		clearInterval(intervalID1);
		clearTimeout(timeoutID1);
		clearInterval(intervalID2);
		clearTimeout(timeoutID2);
		crossYearObj.visibility= "hidden";
	}
	function popUpYear() {
		var	leftOffset;
		constructYear();
		crossYearObj.visibility	= (dom||ie)? "visible" : "show";
		leftOffset = parseInt(crossobj.left) + document.getElementById("spanYear").offsetLeft;
		if (ie) { leftOffset += 6; }
		crossYearObj.left = leftOffset + 'px';
		crossYearObj.top = parseInt(crossobj.top) + 26 + 'px';
	}


   function WeekNbr(n) {
      // Algorithm used: From Klaus Tondering's Calendar document (The Authority/Guru)
      // hhtp://www.tondering.dk/claus/calendar.html
      // a = (14-month) / 12
      // y = year + 4800 - a
      // m = month + 12a - 3
      // J = day + (153m + 2) / 5 + 365y + y / 4 - y / 100 + y / 400 - 32045
      // d4 = (J + 31741 - (J mod 7)) mod 146097 mod 36524 mod 1461
      // L = d4 / 1460
      // d1 = ((d4 - L) mod 365) + L
      // WeekNumber = d1 / 7 + 1
      year = n.getFullYear();
      month = n.getMonth() + 1;
      if (startAt == 0) { day = n.getDate() + 1; }
      else { day = n.getDate(); }
      a = Math.floor((14-month) / 12);
      y = year + 4800 - a;
      m = month + 12 * a - 3;
      b = Math.floor(y/4) - Math.floor(y/100) + Math.floor(y/400);
      J = day + Math.floor((153 * m + 2) / 5) + 365 * y + b - 32045;
      d4 = (((J + 31741 - (J % 7)) % 146097) % 36524) % 1461;
      L = Math.floor(d4 / 1460);
      d1 = ((d4 - L) % 365) + L;
      week = Math.floor(d1/7) + 1;
      return week;
   }

	function constructCalendar () {
		var aNumDays = Array (31,0,31,30,31,30,31,31,30,31,30,31);
		var dateMessage;
		var startDate =	new Date (yearSelected,monthSelected,1);
		var endDate;
		if (monthSelected==1) {
			endDate	= new Date (yearSelected,monthSelected+1,1);
			endDate	= new Date (endDate	- (24*60*60*1000));
			numDaysInMonth = endDate.getDate();
		} else { numDaysInMonth = aNumDays[monthSelected]; }
		datePointer = 0;
		dayPointer = startDate.getDay() - startAt;
		if (dayPointer<0) { dayPointer = 6; }
		sHTML =	"<table	 border=0 style='font-family:verdana;font-size:10px;'><tr>";
		if (showWeekNumber==1) {
			sHTML += "<td width=27><b>" + weekString + "</b></td><td width=1 rowspan=7 bgcolor='#d0d0d0' style='padding:0px'><img src='"+imgDir+"divider.gif' width=1></td>";
		}
		for (i=0; i<7; i++) { sHTML += "<td width='27' align='right'><B>"+ dayName[i]+"</B></td>"; }
		sHTML +="</tr><tr>";
		if (showWeekNumber==1) { sHTML += "<td align=right>" + WeekNbr(startDate) + "&nbsp;</td>"; }
		for ( var i=1; i<=dayPointer;i++ ) { sHTML += "<td>&nbsp;</td>"; }
		for ( datePointer=1; datePointer<=numDaysInMonth; datePointer++ ) {
			dayPointer++;
			sHTML += "<td align=right>";
			sStyle=styleAnchor;
			if ((datePointer==odateSelected) && (monthSelected==omonthSelected) && (yearSelected==oyearSelected)) { sStyle+=styleLightBorder; }
			sHint = "";
			for (k=0;k<HolidaysCounter;k++) {
				if ((parseInt(Holidays[k].d)==datePointer)&&(parseInt(Holidays[k].m)==(monthSelected+1))) {
					if ((parseInt(Holidays[k].y)==0)||((parseInt(Holidays[k].y)==yearSelected)&&(parseInt(Holidays[k].y)!=0))) {
						sStyle+="background-color:#FFDDDD;";
						sHint+=sHint==""?Holidays[k].desc:"\n"+Holidays[k].desc;
					}
				}
			}
			var regexp= /\"/g;
			sHint=sHint.replace(regexp,"&quot;");
			dateMessage = "onmousemove='window.status=\""+selectDateMessage.replace("[date]",constructDate(datePointer,monthSelected,yearSelected))+"\"' onmouseout='window.status=\"\"' ";
			if ((datePointer==dateNow)&&(monthSelected==monthNow)&&(yearSelected==yearNow)) {
			    sHTML += "<b><a "+dateMessage+" title=\"" + sHint + "\" style='"+sStyle+"' href='javascript:dateSelected="+datePointer+";closeCalendar();'><font color=#ff0000>&nbsp;" + datePointer + "</font>&nbsp;</a></b>";
			} else if (dayPointer % 7 == (startAt * -1)+1) {
			    sHTML += "<a "+dateMessage+" title=\"" + sHint + "\" style='"+sStyle+"' href='javascript:dateSelected="+datePointer + ";closeCalendar();'>&nbsp;<font color=#909090>" + datePointer + "</font>&nbsp;</a>";
			} else {
			    sHTML += "<a "+dateMessage+" title=\"" + sHint + "\" style='"+sStyle+"' href='javascript:dateSelected="+datePointer + ";closeCalendar();'>&nbsp;" + datePointer + "&nbsp;</a>";
			}
			sHTML += "";
			if ((dayPointer+startAt) % 7 == startAt) {
				sHTML += "</tr><tr>";
				if ((showWeekNumber==1)&&(datePointer<numDaysInMonth)) {
					sHTML += "<td align=right>" + (WeekNbr(new Date(yearSelected,monthSelected,datePointer+1))) + "&nbsp;</td>";
				}
			}
		}
		document.getElementById("content").innerHTML   = sHTML;
      document.getElementById("spanMonth").innerHTML = "&nbsp;<span style='color:white'>" +	monthName[monthSelected] + "</span>&nbsp;<IMG id='changeMonth' SRC='"+imgDir+"drop1.gif' WIDTH='12' HEIGHT='10' BORDER=0>";
		document.getElementById("spanYear").innerHTML =	"&nbsp;<span style='color:white'>" + yearSelected	+ "</span>&nbsp;<IMG id='changeYear' SRC='"+imgDir+"drop1.gif' WIDTH='12' HEIGHT='10' BORDER=0>";
	}
	function popUpCalendar(ctl,	ctl2,	ctl3,	ctl4,	ctl5, format) {

		var leftpos=0;
		var toppos=0;
		if (bPageLoaded) {
			if ( crossobj.visibility ==	"hidden" ) {
				ctlToPlaceValue	= ctl2
				if ( ctlToPlaceValue == undefined ) {
					ctlToPlaceValueD	= ctl3
					ctlToPlaceValueM	= ctl4
					ctlToPlaceValueY	= ctl5
				}
				dateFormat=format;
				formatChar = " ";
				aFormat	= dateFormat.split(formatChar);
				if (aFormat.length<3) {
					formatChar = "/";
					aFormat	= dateFormat.split(formatChar);
					if (aFormat.length<3) {
						formatChar = ".";
						aFormat	= dateFormat.split(formatChar);
						if (aFormat.length<3) {
							formatChar = "-";
							aFormat	= dateFormat.split(formatChar);
							if (aFormat.length<3) {
								// invalid date	format
								formatChar="";
							}
						}
					}
				}
				tokensChanged =	0;
				if ( formatChar	!= "" ) {
					aData =	ctl2.value.split(formatChar);
					for	(i=0;i<3;i++) {
						if ((aFormat[i]=="d") || (aFormat[i]=="dd")) {
							dateSelected = parseInt(aData[i], 10);
							tokensChanged ++;
						} else if ((aFormat[i]=="m") || (aFormat[i]=="mm")) {
							monthSelected =	parseInt(aData[i], 10) - 1;
							tokensChanged ++;
						} else if (aFormat[i]=="yyyy") {
							yearSelected = parseInt(aData[i], 10);
							tokensChanged ++;
						} else if (aFormat[i]=="mmm") {
							for (j=0; j<12; j++) {
								if (aData[i]==monthName[j]) {
									monthSelected=j;
									tokensChanged ++;
								}
							}
						} else if (aFormat[i]=="mmmm") {
							for (j=0; j<12; j++) {
								if (aData[i]==monthName2[j]) {
									monthSelected=j;
									tokensChanged ++;
								}
							}
						}
					}
				}
				if ((tokensChanged!=3)||isNaN(dateSelected)||isNaN(monthSelected)||isNaN(yearSelected)) {
					dateSelected = dateNow;
					monthSelected =	monthNow;
					yearSelected = yearNow;
				}
				odateSelected=dateSelected;
				omonthSelected=monthSelected;
				oyearSelected=yearSelected;
				aTag = ctl;
				do {
					aTag = aTag.offsetParent;
					leftpos	+= aTag.offsetLeft;
					toppos += aTag.offsetTop;
				} while(aTag.tagName!="BODY");
                                constructCalendar (1, monthSelected, yearSelected);
                                crossobj.left = fixedX==-1 ? ctl.offsetLeft     + leftpos + 'px' :     fixedX + 'px';
                                crossobj.top = fixedY==-1 ?     ctl.offsetTop + toppos + ctl.offsetHeight +     2 + 'px':     fixedY + 'px';
                                crossobj.visibility=(dom||ie)? "visible" : "show";
				hideElement( 'SELECT', document.getElementById("calendar") );
				hideElement( 'APPLET', document.getElementById("calendar") );
				bShow = true;
			} else {
				hideCalendar();
				if (ctlNow!=ctl) {popUpCalendar(ctl, ctl2, ctl3, ctl4, ctl5,format); }
			}
			ctlNow = ctl;
		}
	}
	document.onkeypress = function hidecal1 () {
		if (event.keyCode==27) { hideCalendar(); }
	}
	document.onclick = function hidecal2 () {
		if (!bShow) { hideCalendar(); }
		bShow = false;
	}
	if(ie) { init(); }
	else { window.onload=init; }
//////////////////////////////////////////////////////////////////////////////////
var layerQueue=new Array()
var layerIndex=-1

// hides <select> and <applet> objects (for IE only)
function hideElement( elmID, overDiv ) {
  if( ie ) {
	for( i = 0; i < document.getElementsByTagName( elmID ).length; i++ ) {
	  obj = document.getElementsByTagName( elmID )[i];
	  if( !obj || !obj.offsetParent ) { continue; }
	  // Find the element's offsetTop and offsetLeft relative to the BODY tag.
	  objLeft   = obj.offsetLeft;
	  objTop    = obj.offsetTop;
	  objParent = obj.offsetParent;
	  while( objParent.tagName.toUpperCase() != "BODY" ) {
		objLeft  += objParent.offsetLeft;
		objTop   += objParent.offsetTop;
		objParent = objParent.offsetParent;
	  }
	  objHeight = obj.offsetHeight;
	  objWidth = obj.offsetWidth;
	  if(( overDiv.offsetLeft + overDiv.offsetWidth ) <= objLeft );
	  else if(( overDiv.offsetTop + overDiv.offsetHeight ) <= objTop );
	  else if( overDiv.offsetTop >= ( objTop + objHeight ));
	  else if( overDiv.offsetLeft >= ( objLeft + objWidth ));
	  else { obj.style.visibility = "hidden"; }
	}
  }
}
// unhides <select> and <applet> objects (for IE only)
function showElement( elmID ) {
  if( ie ) {
	for( i = 0; i < document.getElementsByTagName( elmID ).length; i++ ) {
	  obj = document.getElementsByTagName( elmID )[i];
	  if( !obj || !obj.offsetParent ) { continue; }
	  obj.style.visibility = "";
	}
  }
}
function lw_createLayer (layerName, top_pos, left_pos, width, height, bgcolor, bordercolor, z_index) {
	document.write("<div ONCLICK='event.cancelBubble=true' id='"+layerName+"' style='z-index:" + z_index + ";position:absolute;top:"+top_pos+";left:"+left_pos+";visibility:hidden;'><table bgcolor='"+bgcolor+"' style='border-width:1px;border-style:solid;border-color:" + bordercolor + "' cellpadding=2 cellspacing=0 width=0><tr><td valign=top width='"+width+"' height='"+height+"'><span id='"+layerName+"_content'></span></td></tr></table></div>");
}
function lw_getObj (objName) {
	return (dom)?document.getElementById(objName).style:ie?eval("document.all."+objName) :eval("document."+objName);
}
function lw_showLayer (layerName) {
	found=false;
	for (i=0;i<=layerIndex;i++) {
		if (layerQueue[i]==layerName) { found=true; }
	}
	if ((lw_getObj(layerName).visibility!="visible")&&(lw_getObj(layerName).visibility!="show")) {
		lw_getObj(layerName).visibility = (dom||ie)?"visible":"show";
		layerQueue[++layerIndex] = layerName;
		hideElement( 'SELECT', document.getElementById(layerName) );
		hideElement( 'APPLET', document.getElementById(layerName) );
	}
}
function lw_hideLayer () {
	showElement( 'SELECT', document.getElementById(layerQueue[layerIndex]) );
	showElement( 'APPLET', document.getElementById(layerQueue[layerIndex]) );
	lw_getObj(layerQueue[layerIndex--]).visibility = "hidden";
}
function lw_hideLayerName (layerName) {
	var i;
	var tmpQueue=new Array();
	var newIndex=-1;
	showElement( 'SELECT', document.getElementById(layerName) );
	showElement( 'APPLET', document.getElementById(layerName) );
	lw_getObj(layerName).visibility = "hidden";
	for (i=0;i<=layerIndex;i++) {
		if ((layerQueue[i]!="")&&(layerQueue[i]!=layerName)) {
			tmpQueue [++newIndex] = layerQueue[i];
			hideElement( 'SELECT', document.getElementById(layerQueue[i]) );
			hideElement( 'APPLET', document.getElementById(layerQueue[i]) );
		}
	}
	layerQueue = tmpQueue;
	layerIndex = newIndex;
}
function lw_closeAllLayers() {
	while (layerIndex >= 0) { lw_hideLayer(); }
}
function lw_closeLastLayer() {
	if (layerIndex >= 0) {
		while ((lw_getObj(layerQueue[layerIndex]).visibility!="visible") && (layerIndex>0)) { layerIndex--; }
		lw_hideLayer();
	}
}
function lw_escLayer (e) {
	if (navigator.appName=="Netscape") {
		var keyCode = e.keyCode?e.keyCode:e.which?e.which:e.charCode;
		if ((keyCode==27)||(keyCode==1)) { lw_closeLastLayer(); }
	}
	else if ((event.keyCode==0)||(event.keyCode==27)) { lw_closeLastLayer(); }
}
var lw_leftpos = 0;
var lw_toppos = 0;
var lw_width = 0;
var lw_height = 0;
function lw_calcpos(obj) {
	lw_leftpos=0;
	lw_toppos=0;
	lw_width = obj.offsetWidth;
	lw_height = obj.offsetHeight;
	var aTag = obj
	do {
		lw_leftpos += aTag.offsetLeft;
		lw_toppos += aTag.offsetTop;
		aTag = aTag.offsetParent;
	} while(aTag.tagName!="BODY");
}
document.onkeypress = lw_escLayer;
document.onclick = lw_closeAllLayers;

var orientation=0;	// 0-horizontal 1-vertical
var imgPath = "images/calendar/";
var mainItemForeground = "#000000";
var mainItemBorder = "#f0f0f0";
var mainItemBackground = "#f0f0f0";
var mainItemHoverForeground = "#000000";
var mainItemHoverBorder = "#a0a0a0";
var mainItemHoverBackground = "#d0d0d0";
var subItemForeground = "#000000";
var subItemBorder = "#ffffff";
var subItemBackground = "#ffffff";
var subItemHoverForeground = "#000000";
var subItemHoverBorder = "#a0a0a0";
var subItemHoverBackground = "#d0d0d0";
var menuFont = "verdana";
var menuSize = "11px";
/////////////////////////////////////////////////////////////////////////////
var ie=(navigator.appName=='Microsoft Internet Explorer');
var ns=(navigator.appName=='Netscape');
var dom=document.getElementById;
var lw_menuId, lw_trigger, to1;
var s1;
/////////////////////////////////////////////////////////////////////////////
function Tmenu (id, parentId, url, description, img) {
	this.id = id;
	this.parentId = parentId;
	this.url = url;
	this.description = description;
	this.numChild = 0;
	this.levelId = 0;
	this.img = img;
}
/////////////////////////////////////////////////////////////////////////////
var menu = new Array();
var menuCounter = 0;
var numLevel = 0;
var prevMenuId = -1;
var nNowAt = 0;
function displayMenuItem(menuId) {
	var sHTML;
	if (menu[menuId].parentId>0) {
		ItemForeground = subItemForeground;
		Border = subItemBorder;
		Background = subItemBackground;
		HoverForeground = subItemHoverForeground;
		HoverBorder = subItemHoverBorder;
		HoverBackground = subItemHoverBackground;
	} else {
		ItemForeground = mainItemForeground;
		Border = mainItemBorder;
		Background = mainItemBackground;
		HoverForeground = mainItemHoverForeground;
		HoverBorder = mainItemHoverBorder;
		HoverBackground = mainItemHoverBackground;
	}
	if ((orientation==1)||(menu[menuId].levelId>0)) { sHTML += "<tr>"; }
	if (menu[menuId].description!='-') {
		sHTML = "<td id='menu_"+menuId+"' style='cursor:pointer;cursor:hand;border-style:solid;border-width:1px;background-color:"+ Background +";color=\""+ ItemForeground +"\";border-color:"+ Border +"' onmouseover='clearInterval(s1);this.style.backgroundColor=\"" + HoverBackground + "\";this.style.color=\"" + HoverForeground + "\";this.style.borderColor=\"" + HoverBorder + "\";";
		if (menu[menuId].numChild!=0) {
			sHTML += "showSubmenu("+menu[menuId].id+",this);hideSubmenu(menu["+menuId+"].levelId+1);";
		} else {
			sHTML += "prevMenuId = "+menuId+";hideSubmenu(menu["+menuId+"].levelId);";
		}
		sHTML += ";nNowAt="+menuId+";clearAll("+menu[menuId].levelId+","+menu[menuId].parentId+")' onclick='document.location.href=\""+menu[menuId].url+"\"' onmouseout='s1=setInterval(\"resetMenu();hideSubmenu(0)\",1000);'><table cellpadding=0 cellspacing=0 border=0 width='100%'><tr>";

		if (menu[menuId].levelId>0) {
			sHTML += "<td style='padding-left:3px' width=20><img src='";
			if (menu[menuId].img=="") { sHTML += imgPath + "trans.gif"; }
			else { sHTML += imgPath + menu[menuId].img; }
			sHTML += "' width=16 height=16 border=0></td>";
		}
		if ((orientation==0) && (menu[menuId].levelId==0)) { nArrowWidth = 0; }
		else { nArrowWidth = 25; }
		sHTML += "<td style='padding-left:5px;padding-right:5px' align=left>" + menu[menuId].description.replace(" ","&nbsp;") + "</td><td style='padding-right:2px' align=right width="+nArrowWidth+">";
		if (menu[menuId].numChild>0) {
			if ((orientation==0)&&(menu[menuId].levelId==0)) {
				sHTML += "<img src='" + imgPath + "arrow_down.gif'>";
			} else {
				sHTML += "<img src='" + imgPath + "arrow_right.gif'>";
			}
		} else {
			sHTML += "&nbsp;";
		}
		sHTML += "</td></tr></table></td>"
	} else {
	    sHTML = "<td><img src='" + imgPath + "trans.gif' height=2></td></tr><tr><td onmouseover='' bgcolor='#d0d0d0'><img src='" + imgPath + "trans.gif' height=1></td></tr><tr><td><img src='" + imgPath + "trans.gif' height=2></td>";
	}
	if ((orientation==1)||(menu[menuId].levelId>0)) { sHTML += "</tr>"; }
	return sHTML;
}
function clearAll(levelId, parentId) {
	if (levelId>0) {
		Border = subItemBorder;
		Background = subItemBackground;
	} else {
		Border = mainItemBorder;
		Background = mainItemBackground;
	}
	for (i=0;i<menuCounter;i++) {
		if (menu[i].levelId==levelId) {
			if ((i!=nNowAt) && (menu[i].parentId==parentId) && (menu[i].description!="-")) {
				lw_getObj("menu_"+i).backgroundColor=Background;
				lw_getObj("menu_"+i).borderColor=Border;
			}
		}
	}
}
function mapID (id) {
	for (var i=0;i<menuCounter;i++) {
		if (menu[i].id==id) { return i; }
	}
	return -1;
}
function showSubmenu(menuId,trigger) {
	lw_menuId = menuId;
	lw_trigger = trigger;
	if (ns) { to1 = setTimeout("showActualSubmenu(lw_menuId,lw_trigger)",50); }
	else { showActualSubmenu(lw_menuId,lw_trigger); }
}
function showActualSubmenu(menuId,trigger) {
	var nLevel = 0;
	var leftpos = 0;
	var nIndex=0;
	if (menuId>0) {
		lw_calcpos(trigger);
		for (var i=0;i<menuCounter;i++) {
			if (menu[i].id==menuId) {
				nLevel = menu[i].levelId;
				nIndex = i;
			}
		}
		if ((orientation==1)||(menu[nIndex].parentId>0)) {
			lw_getObj("menu_level_"+nLevel).top=lw_toppos;
			leftpos = lw_leftpos + lw_width + 5;
			if (nLevel==0) {
				leftpos -= 3;
			}
		} else {
			lw_getObj("menu_level_"+nLevel).top = lw_toppos + lw_height + 2;
			leftpos = lw_leftpos;
		}
		lw_getObj("menu_level_"+nLevel).left=leftpos;
		sHTML = "<table cellpadding=0 cellspacing=0 border=0>";
		for (var i=0;i<menuCounter;i++) {
			if (menu[i].parentId==menuId) { sHTML += displayMenuItem (i); }
		}
		sHTML += "</table>";
		document.getElementById("menu_level_"+nLevel+"_content").innerHTML=sHTML;
		lw_showLayer('menu_level_'+nLevel);
	}
}
function hideSubmenu(levelId) {
	for (var cnt=levelId; cnt<numLevel; cnt++) { lw_hideLayerName("menu_level_"+cnt); }
}
function DrawMenu ()  {
	for (var i=0; i<numLevel ; i++) { lw_createLayer("menu_level_"+i,0,0,0,0,"#ffffff","#d0d0d0",100); }
	sHTML="<table width=100% cellpadding=2 cellspacing=0>";
	for (var i=0; i<menuCounter; i++) {
		if (menu[i].parentId==0) { sHTML += displayMenuItem (i); }
		else if (menu[i].parentId==-1) {
			sHTML += "<tr><td><img src='trans' height=2></td></tr><tr bgcolor='"+mainItemBackground+"'><td style='padding:5px'><b>" + menu[i].description + "</b></td></tr>";
		}
	}
	sHTML += "</table>";
	document.writeln(sHTML);
}
function getLevel (menuId) {
	var pId=menuId;
	var nLevel=0;
	while (pId!=0) {
		nLevel++;
		for (var i=0;i<menuCounter;i++) {
			if (menu[i].id==pId) {
				pId = menu[i].parentId;
			}
		}
	}
	return nLevel;
}
function AddMenuItem (id, parentId, url, description, img) {
	menu[menuCounter++] = new Tmenu (id, parentId, url, description, img);
	if (parentId>0) {
		for (i=0;i<menuCounter;i++) {
			if (menu[i].id==parentId) { menu[i].numChild++; }
		}
		menu[menuCounter-1].levelId = getLevel(parentId);
		if ( numLevel < menu[menuCounter-1].levelId) {
			numLevel = menu[menuCounter-1].levelId;
		}
	}
	else if (parentId==0) { menu[menuCounter-1].levelId = 0; }
	else { menu[menuCounter-1].levelId = -1; }
}
function handleonclick() {
	if (ns) { lw_closeAllLayers(); }
	else { lw_closeAllLayers(event); }
	resetMenu();
}
function handlekeypress(e) {
	if (ns) {
		var keyCode = e.keyCode?e.keyCode:e.which?e.which:e.charCode;
		if ((keyCode==27)||(keyCode==1)) { handleonclick(); }
	} else if ((event.keyCode==0)||(event.keyCode==27)) { handleonclick(); }
	resetMenu();
}
function resetMenu () {
	for (i=0;i<menuCounter;i++) {
		if (menu[i].levelId==0) {
			lw_getObj("menu_"+i).backgroundColor=mainItemBackground;
			lw_getObj("menu_"+i).borderColor=mainItemBorder;
		}
	}
}

document.onkeypress = handlekeypress;
document.onclick = handleonclick;
