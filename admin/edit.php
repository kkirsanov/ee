<?
@header ('Content-Type: text/html; charset="utf-8"');
include ("../db.php");
include_once ("protect.php");
$conf[path] ="../";
$conf[module_path] ="../modules/";

function LOGIT($region, $fid, $module, $text, $data=""){
		/*
		global $conf;
		$SQL = "insert into $conf[DB_PREFIX]log(USERID, DATE, REGION, MODULE, DATA, FID, DESCR) " 
				."VALUES ($_SESSION[manager_id], now(), $region, '$module', '$data', $fid ,'$text')";
		mysql_query($SQL, $conf[DB]);
        */
};
@session_start();

$tmpdata[GET]=$_GET;
$tmpdata[POST]=$_POST;
$tmpdata[SESSION]=$_SESSION;
$data= addslashes(serialize($tmpdata));

$SQL = "insert into $conf[DB_PREFIX]log(USERID, DATE, REGION, MODULE, DATA, FID, DESCR) " 
."VALUES ($_SESSION[manager_id], now(), 0, '$_GET[module]', '$data', 0 ,'')";
@mysql_query($SQL, $conf[DB]);

include_once ("core_module.php");
$mod = new Modules($conf);
$mod->modules["$_GET[module]"]->module->edit();
?>