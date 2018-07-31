<?
header ('Content-Type: text/html; charset="utf-8"');
error_reporting (E_ALL ^ E_NOTICE);
include_once ("protect.php");
include_once ("../db.php");
$conf[path] ="../";
$conf[module_path] ="../modules/";

function GLOBAL_LOAD($filename){
  global $ADMINPATH;
  return stripslashes(file_get_contents("./data/$filename"));
};


function LOGIT($region, $module, $text, $data=""){
  global $conf;
  $SQL = "insert into $conf[DB_PREFIX]log(USERID, DATE, REGION, MODULE, DATA, DESCR) VALUES ($_SESSION[manager_id], now(), $region, '$data', $module)";

	$z = @GLOBAL_LOAD('log');
	if ($z=='1')
			mysql_query($SQL, $conf[DB]);
};


function GLOBAL_SAVE($filename, $data){
  global $ADMINPATH;
  if ($h = fopen("./data/$module$filename", 'w'))
    {
      fwrite($h, $data);
      fclose ($h);
    };
};

$tmpdata[GET]=$_GET;
$tmpdata[POST]=$_POST;
$tmpdata[SESSION]=$_SESSION;
$data= addslashes(serialize($tmpdata));

$SQL = "insert into $conf[DB_PREFIX]log(USERID, DATE, REGION, MODULE, DATA, FID, DESCR) "
."VALUES ($_SESSION[manager_id], now(), 0, '$_GET[module]', '$data', 0 ,'')";

$z = @GLOBAL_LOAD('log');

if ($z=='1')
		mysql_query($SQL, $conf[DB]);

include_once ("core_module.php");
$mod = new Modules($conf);
$mod->modules["$_GET[module]"]->module->properties();
?>
