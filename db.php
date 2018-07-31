<?
$conf[DB_ADDR]  = "localhost";
$conf[DB_NAME]  = "muzbazardb";
$conf[DB_UNAME] = "muzbazardb";
$conf[DB_PASS]  = "ma6iantingesh";
$conf[DB_PREFIX]  = "muzbazar3_";
$conf[DB]=mysql_connect($conf[DB_ADDR], $conf[DB_UNAME], $conf[DB_PASS]) or die(mysql_error());
$res=mysql_select_db($conf[DB_NAME]) or die(mysql_error());

//$res=mysql_query("SET NAMES UTF8") or die(mysql_error());

mysql_query("SET character_set_client = cp1251");
$res=mysql_query ("set character_set_client=cp1251")or die(mysql_error());
$res=mysql_query ("set character_set_results=cp1251")or die(mysql_error());
$res=mysql_query ("set collation_connection=cp1251_general_ci")or die(mysql_error());
?>
