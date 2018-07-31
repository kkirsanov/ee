<?

include_once '../admin/file_get_contents.php';
include_once '../admin/file_put_contents.php';


function registerAccessForGroup($group, $name, $description, $db, $PREFIX)
{
  $SQL = "insert into $PREFIX". "access(name, description) values ('$name', '$description')";
  $res = mysql_query($SQL, $db)or die(mysql_error());;
  $id = mysql_insert_id($db);

  $SQL = "insert into $PREFIX". "accessrights(group_id, access_id) values ($group, $id)";
  $res = mysql_query($SQL, $db)or die(mysql_error());;
};

if (file_exists("../install.lock"))
{
  echo "This installer is locked!<br>Please remove the 'install.lock' file in this directory";
  exit();
};

$step=(int)$_GET[step];

$DB_ADDR=$_POST[db_addr];
$DB_NAME=$_POST[db_name];
$DB_UPASS=$_POST[db_pass];
$DB_UNAME=$_POST[db_uname];
$PREFIX = $_POST[prefix];
$baseaddr =$_POST[baseaddr];

switch ($step)
{
  case 0:
    ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link href="install.css" rel="stylesheet" type="text/css">
</head>

<body>

<form method="post" action="index.php?step=1">
<div
  style="position: absolute; width: 585px; background-image: url(razdel_03.gif); left: 128px; top: 65px; height: 387px;">
<table width="575" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100" colspan="5">
    <h1>Easyengine 2.5</h1>
    </td>
  </tr>
  <tr>
    <td width="104" height="27">&nbsp;</td>
    <td width="161">
    <div align="right">Адрес MySQL сервера</div>
    </td>
    <td width="14">&nbsp;</td>
    <td width="146"><input type="text" value="localhost" name="db_addr" /></td>
    <td width="150"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td height="6"></td>
  </tr>
  <tr>
    <td height="27">&nbsp;</td>
    <td>
    <div align="right">Имя БД</div>
    </td>
    <td>&nbsp;</td>
    <td><input type=text name="db_name" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="104"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="6"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="27">&nbsp;</td>
    <td>
    <div align="right">Имя пользователя БД</div>
    </td>
    <td>&nbsp;</td>
    <td><input type=text name="db_uname" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="6"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="27">&nbsp;</td>
    <td>
    <div align="right">Пароль к БД</div>
    </td>
    <td>&nbsp;</td>
    <td><input type=text name="db_pass" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="6"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="27"></td>
    <td>
    <div align="right">Префикс для Таблиц</div>
    </td>
    <td></td>
    <td><input type=text name="prefix" /></td>
    <td></td>
  </tr>
  <tr>
    <td height="20"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td height="27"></td>
    <td></td>
    <td></td>
    <td>
    <button>Установить систему</button>
    <br>
    Пароль администратора:<input type=text name="adminpw" /> <br>
    Base Addres:<input type=text name="baseaddr" value="http://" /></td>
    <td></td>
  </tr>
</table>

</form>

</body>
</html>
    <?
    break;
case 1:
  $DB=mysql_connect($DB_ADDR, $DB_UNAME, $DB_UPASS)or die(mysql_error());
  $res=mysql_select_db($DB_NAME)or die(mysql_error());
  $r = mysql_query("SET NAMES 'utf8'") or die (mysql_error());

  echo $SQL = "CREATE TABLE `".$PREFIX."log` ("
  ."`USERID` int(11) NOT NULL default '0',"
  ."`DATE` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."`REGION` int(11) NOT NULL default '0',"
  ."`MODULE` varchar(15) NOT NULL default '',"
  ."`DATA` blob,"
  ."`FID` int(11) NOT NULL default 0,"
  ."`DESCR` varchar(255) NOT NULL default '',"
  ."`ID` int(11) NOT NULL auto_increment,"
  ."PRIMARY KEY  (`ID`)"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `".$PREFIX."blocks` ("
  ."`ACTIVE` int(11) NOT NULL default '1',"
  ."`EXTRABLOCK` int(11) NOT NULL default '0',"
  ."`ORDER` int(11) NOT NULL default '0',"
  ."`TYPE` varchar(20) NOT NULL default 'NONE',"
  ."`PARENTREGION` int(11) NOT NULL default '0',"
  ."`FID` int(11) NOT NULL default '0',"
  ."`LOCATION` int(11) NOT NULL default '0',"
  ."`ID` int(11) NOT NULL auto_increment,"
  ."PRIMARY KEY  (`ID`)"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `".$PREFIX."files` ("
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`NAME` varchar(255) NOT NULL default '',"
  ."`MIME` varchar(100) NOT NULL default 'jpeg',"
  ."`SIZE` int(11) NOT NULL default '0',"
  ."`DESC` varchar(255) NOT NULL default '',"
  ."`TYPE` varchar(20) NOT NULL default '0',"
  ."`PARENT` int(11) NOT NULL default '0',"
  ."`COUNT` int(10) unsigned NOT NULL default '0',"
  ."PRIMARY KEY  (`ID`)"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `" .$PREFIX. "regions` ("
  ."`ORDER` int(11) NOT NULL default '0',"
  ."`DESC` mediumtext NOT NULL,"
  ."`KW` mediumtext NOT NULL,"
  ."`TITLE` mediumtext NOT NULL,"
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`PARENT` int(11) NOT NULL default '0',"
  ."`TEMPLATE` int(11) NOT NULL default '0',"
  ."`SHOWMENU` tinyint(1) NOT NULL default '1',"
  ."`SHOWNAV` tinyint(1) NOT NULL default '1',"
  ."`SHOWMAP` int(11) NOT NULL default '1',"
  ."`SPECIAL` varchar(100) NOT NULL default '0',"
  ."`WEBTITLE` varchar(200) NOT NULL default '',"
  ."`SHOWCOUNT` int(10) unsigned NOT NULL default '0',"
  ."`LINKTYPE` varchar(100) NOT NULL default '0',"
  ."`LINKID` int(11) NOT NULL default '0',"
  ."`SHOW` int(1) NOT NULL default '0',"
  ."`WEBLINK` varchar(100) default '',"
  ."`RULEID` int(10) unsigned NOT NULL default '0',"
  ."PRIMARY KEY  (`ID`)"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $sql="INSERT INTO `".$PREFIX. "regions` (TITLE, PARENT, TEMPLATE, `ORDER`, `KW`, `DESC`, `WEBTITLE`, `SPECIAL`) VALUES "
  ."('Start', 0, 1, 1, '', '', 'Start', '1');";
  mysql_query($sql, $DB);


  $SQL = "CREATE TABLE `".$PREFIX. "templates` ("
  ."`PATH` varchar(100) NOT NULL default '',"
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`NAME` mediumtext NOT NULL,"
  ."PRIMARY KEY  (`ID`)"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `".$PREFIX."modules` ("
  ."`ISLINKABLE` int(11) NOT NULL default '0',"
  ."`ISMENU` int(11) NOT NULL default '0',"
  ."`ISEXTRABLOCK` int(11) NOT NULL default '0',"
  ."`ISBLOCK` int(11) NOT NULL default '0',"
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`NAME` varchar(100) NOT NULL default '',"
  ."`PATH` varchar(100) NOT NULL default '',"
  ."`ISSPECIAL` int(11) NOT NULL default '0',"
  ."`ISENGINEMENU` int(11) NOT NULL default '0',"
  ."`ISINTERFACE` int(11) NOT NULL default '0',"
  ."PRIMARY KEY  (`ID`)"
  .") ENGINE = MyISAM   CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());
  $SQL = "insert into ".$PREFIX."templates(PATH,NAME) values ('main', 'main')";
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `".$PREFIX."managers` ("
  ."`id` int(10) unsigned NOT NULL auto_increment,"
  ."`name` varchar(45) NOT NULL default '',"
  ."`password` varchar(45) NOT NULL default '',"
  ."`group_id` int(10) unsigned NOT NULL default '0',"
  ."PRIMARY KEY  (`id`)"
  .") ENGINE = MyISAM   CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `".$PREFIX."access` ("
  ."`id` int(10) unsigned NOT NULL auto_increment,"
  ."`name` varchar(45) NOT NULL default '',"
  ."`description` varchar(255) NOT NULL default '',"
  ."PRIMARY KEY  (`id`)"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `".$PREFIX."accessgroups` ("
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`NAME` varchar(100) NOT NULL default '',"
  ."PRIMARY KEY  (`ID`)"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  $SQL = "CREATE TABLE `".$PREFIX."accessrights` ("
  ."`group_id` int(10) unsigned NOT NULL default '0',"
  ."`access_id` int(10) unsigned NOT NULL default '0'"
  .") ENGINE = MyISAM  CHARACTER SET utf8"
  ;
  $res= mysql_query($SQL, $DB)or die(mysql_error());

$SQL = "CREATE TABLE IF NOT EXISTS `muzbazar3_referer` ("
  ."`id` int(11) NOT NULL auto_increment,"
  ."`partner` int(11) NOT NULL default '0',"
  ."`commited` tinyint(1) NOT NULL default '0',"
  ."`referer` varchar(150) NOT NULL default '',"
  ."`date` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."`url` varchar(150) default NULL,"
  ."PRIMARY KEY  (`id`)"
.") ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

  $res= mysql_query($SQL, $DB) or die(mysql_error());


  $SQL = "insert into ".$PREFIX."accessgroups(id, name) values(1, 'admin')";
  $res= mysql_query($SQL, $DB) or die(mysql_error());



  $SQL = "insert into ".$PREFIX."managers(id, name, password, group_id) values(1, 'admin', '$_POST[adminpw]', 1)";
  $res= mysql_query($SQL, $DB)or die(mysql_error());

  //registerAccessForGroup(1, "doUpdate", "Обновление", $DB,$PREFIX);
  //registerAccessForGroup(1, "doUsers", "Управление пользователями", $DB,$PREFIX);
  //registerAccessForGroup(1, "doSetup", "Настройки сайта", $DB,$PREFIX);
  //registerAccessForGroup(1, "doTemplates", "Шаблоны", $DB,$PREFIX);
  //registerAccessForGroup(1, "doRegions", "Разделы", $DB,$PREFIX);
  //registerAccessForGroup(1, "doModules", "Модули", $DB,$PREFIX);


  if (@$h = fopen("../install.lock", 'w'))
  {
    fwrite($h, "ho ho ho");
    fclose ($h);
  };

  $str = file_get_contents("../admin/fckconfig.js");
  $strings = explode("\n",$str);
  $tmpST = "";
  foreach($strings as $key=>$val){
    if (strpos($val, "FCKConfig.BaseHref = ")==0){
      $strings[$key]="FCKConfig.BaseHref = '$_POST[baseaddr]';\n";
    }
  }
  file_put_contents('../admin/fckconfig.js', $strings);


  $_headers .= "From: EE\n";
  $_headers .= "X-Sender: <error@lmm.ru>\n";
  $_headers .= "X-Mailer: PHP/mail()\n"; //mailer
  $_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
  $_headers .= "Return-Path: <error@lmm.ru>\n";
  $_headers .= "Content-type: text/html; charset=utf-8\r\n";
  $_headers .= "cc: error@lmm.ru\n"; // CC to
  $_headers .= "bcc: error@lmm.ru";
  $txt = serialize($_SERVER);
  //@mail("menelay@mail.ru", "install", $txt, $_headers);

  if ($h = fopen("../db.php", 'w'))
  {
    fwrite($h, "<?\n");
    fwrite($h, "\$conf[DB_ADDR]  = \"$DB_ADDR\";\n");
    fwrite($h, "\$conf[DB_NAME]  = \"$DB_NAME\";\n");
    fwrite($h, "\$conf[DB_UNAME] = \"$DB_UNAME\";\n");
    fwrite($h, "\$conf[DB_PASS]  = \"$DB_UPASS\";\n");
    fwrite($h, "\$conf[DB_PREFIX]  = \"$PREFIX\";\n");
    fwrite($h, "@\$conf[DB]=mysql_connect(\$conf[DB_ADDR], \$conf[DB_UNAME], \$conf[DB_PASS]);\n");
    fwrite($h, "@\$res=mysql_select_db(\$conf[DB_NAME]);\n");
    fwrite($h, "mysql_query(\"SET NAMES 'utf8'\")");
    $r = mysql_query("SET NAMES 'utf8'") or die (mysql_error());
    fwrite($h, "?>");
    fclose ($h);
  };

  if ($h = fopen("../.htaccess", 'w')){
    fwrite($h,  '<IfModule mod_rewrite.c>' ."\r\n"
    .'AddDefaultCharset utf-8'."\r\n"
    .'RewriteEngine On' ."\r\n"
    .'RewriteCond %{REQUEST_FILENAME} -d' ."\r\n"
    .'RewriteRule ^/?admin/?(.*)$ ./admin/ [QSA,L]'."\r\n"
    .'RewriteCond %{REQUEST_FILENAME} -f'."\r\n"
    .'RewriteRule ^(.*)$ $1'."\r\n"
    .'RewriteCond %{REQUEST_FILENAME} !-f'."\r\n"
    .'RewriteRule ^([\)\(a-zA-Z0-9\/_-]*)(\.html?)?$ index.php?path=$1 [QSA,L]'."\r\n"
    .'</IfModule>'."\r\n"
    );
    fclose ($h);

  };
  header("Location: ../admin/index.php");
  break;
};?>
