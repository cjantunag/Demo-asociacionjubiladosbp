<?php

define("_CHARSET","windows-874");
define("_POLLS","������繵�ҧ�");
define("_SEARCH","����");
define("_LOGIN","Login");
define("_WRITES","�ѹ�֡");
define("_POSTEDON","�Դ��С��");
define("_NICKNAME","�������¡");
define("_PASSWORD","���ʼ�ҹ");
define("_WELCOMETO","�͵�͹�Ѻ���");
define("_EDIT","���");
define("_DELETE","ź");
define("_POSTEDBY","���ѹ�֡");
define("_READS","����ҹ");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]");
define("_COMMENTS","��ͤԴ��繵�ҧ�");
define("_PASTARTICLES","����������ҹ��");
define("_OLDERARTICLES","����������");
define("_BY","��");
define("_ON","�����");
define("_LOGOUT","�͡�ҡ�����");
define("_WAITINGCONT","�������͡��͹��ѵ�");
define("_SUBMISSIONS","����ͧ�������");
define("_WREVIEWS","���Ԩ�ó�");
define("_WLINKS","Links �������");
define("_EPHEMERIDS","Ephemerids");
define("_ONEDAY","�ѹ����ʹյ...");
define("_ASREGISTERED","��ҷ�ҹ�ѧ���������Ҫԡ? ��ҹ����ö <a href=\"index.php?V_dir=admin&V_mod=nuevousuario&func=new\">��Ѥ�������</a>. 㹡������Ҫԡ ��ҹ�������ª��ҡ��õ�駤����ǹ��ǵ�ҧ� �� �ҡ���;������� �����ҹ�����Դ��� ��С���ʴ�������繴��ª��ͷ�ҹ�ͧ");
define("_MENUFOR","��������Ѻ");
define("_NOBIGSTORY","�ѧ����բ����˭�����Ѻ�ѹ���.");
define("_BIGSTORY","����ͧ��褹��ҹ�ҡ����ش��� :");
define("_SURVEY","���Ǩ�������");
define("_PCOMMENTS","���й�:");
define("_RESULTS","�����Ǩ");
define("_HREADMORE","read more...");
define("_CURRENTLY","��й����,");
define("_GUESTS","�ؤ�ŷ���� ���");
define("_MEMBERS","��Ҫԡ ��Ҫ� .");
define("_YOUARELOGGED","��ҹ�������Ҫԡ���");
define("_YOUHAVE","�س��");
define("_PRIVATEMSG","��ͤ�������");
define("_YOUAREANON","��ҹ�ѧ�����ŧ����¹����Ҫԡ �ҡ��ҹ��ͧ��� ��س���Ѥÿ����<a href=\"index.php?V_dir=admin&V_mod=nuevousuario&func=new\">�����</a>");
define("_NOTE","Note:");
define("_ADMIN","Admin:");
define("_WERECEIVED","We received");
define("_PAGESVIEWS","page views since");
define("_TOPIC","��Ǣ��");
define("_UDOWNLOADS","Downloads");
define("_VOTE","�ӹǹ�����¹�й�");
define("_VOTES","�ӹǹ���ŧ��ṹ");
define("_MVIEWADMIN","View: Administrators Only");
define("_MVIEWUSERS","View: Registered Users Only");
define("_MVIEWANON","View: Anonymous Users Only");
define("_MVIEWALL","View: All Visitors");
define("_EXPIRELESSHOUR","Expiration: Less than 1 hour");
define("_EXPIREIN","Expiration in");
define("_HTTPREFERERS","HTTP ����觼��������");
define("_HOURS","Hours");
define("_RSSPROBLEM","Currently there is a problem with headlines from this site");
define("_SELECTLANGUAGE","�ô���͡����");
define("_SELECTGUILANG","Select Interface Language:");
define("_NONE","None");
define("_BLOCKPROBLEM","<center>There is a problem right now with this block.</center>");
define("_BLOCKPROBLEM2","<center>There isn't content right now for this block.</center>");
define("_MODULENOTACTIVE","Sorry, this Module isn't active!");
define("_NOACTIVEMODULES","Inactive Modules");
define("_FORADMINTESTS","(for Admin tests)");
define("_BBFORUMS","Forums");
define("_ACCESSDENIED", "Access Denied");
define("_RESTRICTEDAREA", "You are trying to access to a restricted area.");
define("_MODULEUSERS", "We are Sorry but this section of our site is for <i>Registered Users Only</i><br><br>You can register for free by clicking <a href=\"index.php?V_dir=admin&V_mod=nuevousuario&func=new\">here</a>, then you can<br>access to this section without restrictions. Thanks.<br><br>");
define("_MODULESADMINS", "We are Sorry but this section of our site is for <i>Administrators Only</i><br><br>");
define("_HOME","Home");
define("_HOMEPROBLEM","There is a big problem here: we have not a Homepage!!!");
define("_ADDAHOME","Add a Module in your Home");
define("_HOMEPROBLEMUSER","There is a problem right now on the Homepage. Please check it back later.");
define("_MORENEWS","More in News Section");
define("_ALLCATEGORIES","All Categories");
define("_DATESTRING","%A %d %b %y@ %T %Z"); 
define("_DATESTRING2","%A %d %b ");
define("_DATE","Date");
define("_HOUR","Hour");
define("_UMONTH","Month");
define("_YEAR","Year");
define("_JANUARY","January");
define("_FEBRUARY","February");
define("_MARCH","March");
define("_APRIL","April");
define("_MAY","May");
define("_JUNE","June");
define("_JULY","July");
define("_AUGUST","August");
define("_SEPTEMBER","September");
define("_OCTOBER","October");
define("_NOVEMBER","November");
define("_DECEMBER","December");

/*****************************************************/
/* Function to translate Datestrings                 */
/*****************************************************/

function translate($phrase) {
    switch($phrase) {
	case "xdatestring":	$tmp = "%A, %B %d @ %T %Z"; break;
	case "linksdatestring":	$tmp = "%d-%b-%Y"; break;
	case "xdatestring2":	$tmp = "%A, %B %d"; break;
	default:		$tmp = "$phrase"; break;
    }
    return $tmp;
}

?>
