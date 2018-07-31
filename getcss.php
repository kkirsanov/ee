<?
header("Content-Type: text/css");
include_once("db.php");
include_once 'admin/core_template.php';
$id = (int)$_GET[template];

$sql="SELECT * FROM $conf[DB_PREFIX]templates WHERE `ID`=$id";
$result=@mysql_query($sql, $conf[DB]) or die (mysql_error());
$template=@mysql_fetch_assoc($result) or die (mysql_error());;
$templ = new Template($template[PATH]);
echo $templ->get("css");
?>