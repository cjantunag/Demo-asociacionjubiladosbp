<?php

define("_CHARSET","windows-1251");
define("_SEARCH","�����");
define("_LOGIN","����");
define("_WRITES","����");
define("_POSTEDON","�����������");
define("_NICKNAME","����");
define("_PASSWORD","������");
define("_WELCOMETO","������� ������� ��");
define("_EDIT","����������");
define("_DELETE","�������");
define("_POSTEDBY","����������");
define("_READS","���������");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">�����</a> ]");
define("_COMMENTS","��������� ");
define("_PASTARTICLES","���� ���������");
define("_OLDERARTICLES","���� �����");
define("_BY","");
define("_ON","");
define("_LOGOUT","�����");
define("_WAITINGCONT","�����������");
define("_SUBMISSIONS","��� �����");
define("_WREVIEWS","��� ������");
define("_WLINKS","��� �������");
define("_EPHEMERIDS","�������");
define("_ONEDAY","� ����� ���� �� �������...");
define("_ASREGISTERED","<a href=\"index.php?V_dir=coremods&V_mod=feedback\">��������������</a>");
define("_MENUFOR","����");
define("_BIGSTORY","���� ��������� ������� ������: ");
define("_SURVEY","�����������");
define("_POLLS","�����������");
define("_PCOMMENTS","���������:");
define("_RESULTS","����������");
define("_HREADMORE","������ ���...");
define("_CURRENTLY","");
define("_GUESTS","������ �");
define("_MEMBERS","������.");
define("_YOUARELOGGED","�� ������ ��");
define("_YOUHAVE","� ���");
define("_PRIVATEMSG","����������.");
define("_YOUAREANON","�������, ������� ��� �� �������.");
define("_NOTE","�������:");
define("_ADMIN","����:");
define("_WERECEIVED","");
define("_PAGESVIEWS","������������ ������� ��������� �");
define("_TOPIC","����");
define("_UDOWNLOADS","���������");
define("_VOTE"," �����");
define("_VOTES","������");
define("_MVIEWADMIN","��������: ����� ��� ������������");
define("_MVIEWUSERS","��������: ����� ��� �������������");
define("_MVIEWANON","��������: ����� ��� ��������");
define("_MVIEWALL","��������: ��� ���");
define("_EXPIRELESSHOUR","Expiration: ���� �� �� ������");
define("_EXPIREIN","Expiration in");
define("_HTTPREFERERS","HTTP ���������");
define("_UNLIMITED","�����������");
define("_HOURS","�����");
define("_RSSPROBLEM","������� �������� � ����������� ����� �����");
define("_SELECTLANGUAGE","������� ����");
define("_SELECTGUILANG","������� ���� ����������:");
define("_NONE","����");
define("_BLOCKPROBLEM","<center>There is a problem right now with this block.</center>");
define("_BLOCKPROBLEM2","<center>There isn't content right now for this block.</center>");
define("_MODULENOTACTIVE","Sorry, this Module isn't active!");
define("_NOACTIVEMODULES","Inactive Modules");
define("_FORADMINTESTS","(for Admin tests)");
define("_BBFORUMS","Forums");
define("_ACCESSDENIED", "Access Denied");
define("_RESTRICTEDAREA", "You are trying to access to a restricted area.");
define("_MODULEUSERS", "We are Sorry but this section of our site is for <i>Registered Users Only</i><br>");
define("_MODULESADMINS", "We are Sorry but this section of our site is for <i>Administrators Only</i><br><br>");
define("_HOME","Home");
define("_HOMEPROBLEM","There is a big problem here: we have not a Homepage!!!");
define("_ADDAHOME","Add a Module in your Home");
define("_HOMEPROBLEMUSER","There is a problem right now on the Homepage. Please check it back later.");
define("_MORENEWS","More in News Section");
define("_ALLCATEGORIES","All Categories");
define("_DATESTRING","%d.%m.%Y");
define("_DATESTRING2","%d.%m.%Y");


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
