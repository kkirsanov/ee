<? class eeText {
  var $conf;
  function eeText($conf) {
    $this->conf = $conf;
  }
  function install(){
    $conf = $this->conf;
    //@registerAccess("module_text_design", "Текс/Дизайн");
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]texts` ("."`ID` int(11) NOT NULL auto_increment,"."`CONTENT` mediumtext NOT NULL,"."PRIMARY KEY  (`ID`)".") ENGINE=MyISAM CHARACTER SET utf8";
    if (mysql_query($SQL, $conf[DB]))
      return 1;
    return 1;
  }
  function properties() {
    $tID = $_GET[template];
    if (!$tID)
      die("template error");
    
    include_once ("core_template.php");
    $template = new Template($_GET[template]);

    switch ($_GET[a]) {
      case "" :
        $TEXT =& $template->Get("text.main");
        ?>
          <html>
          <head>
          <link href="css.css" rel="stylesheet" type="text/css">
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <body>
          <form method="POST" action="properties.php?module=text&a=save&template=<?echo $_GET[template]?>">
          <TABLE border="1" width="90%" bgcolor="#CCCCCC">
            <tr>
              <td colspan="2" align="center">
              <h2>Обрамление Блока</h2>              
              </td>
            </tr>
            <tr>
              <td>%text%</td>
              <td width="100%"><TEXTAREA rows="5" style="WIDTH: 100%" name="TEXT"><?=$TEXT;?></TEXTAREA></td>
            </tr>
          </TABLE>
          <center><INPUT type="submit" value="Принять" class="mainoption"></center>
          </FORM>
        <? break;
      case "save" :
        $template->Set("text.main", $_POST[TEXT]);
        $template->Save();      
        header("Location: ?module=text&template=$_GET[template]");
        break;
      }
}
function add() {
  $conf = $this->conf;
  $SQL = "INSERT INTO $conf[DB_PREFIX]texts values()";
  mysql_query($SQL, $conf[DB]);
  $id = mysql_insert_id($conf[DB]);
  return $id;
}
function del($id) {
  $conf = $this->conf;
  $SQL = "DELETE $conf[DB_PREFIX]texts WHERE ID=$id";
  $sql = "SELECT * FROM `$conf[DB_PREFIX]blocks` WHERE `FID`=$id and TYPE='text'";
  $result = mysql_query($sql, $conf[DB]);
  $block = mysql_fetch_assoc($result);
  LOGIT($block[PARENTREGION], $id, "text", "Text Deleted!");
  mysql_query($SQL, $conf[DB]);
}
function render($regionID = 0, $id, &$template ) {
  
  $conf = $this->conf;
  $SQL = "SELECT * FROM $conf[DB_PREFIX]texts WHERE ID=$id";
  $res = mysql_query($SQL, $conf[DB]);
  $text = mysql_fetch_assoc($res);
  
  
  $TEXT =& $template->Get("text.main");

  return str_replace("%text%", stripslashes($text[CONTENT]), $TEXT);
}
function edit() {
  $conf = $this->conf;
  include_once ("core_file_works.php");
  $filew = new Fileworks($_GET[id], 'text');
  if ($_GET[a] == "")
  $_GET[a] = "editframe";

  switch ($_GET[a]) {
    case "update" :
      $text = addslashes($_POST['text']);
      $sql = "UPDATE $conf[DB_PREFIX]texts SET CONTENT=\"$text\" where ID=$_GET[id]";
      mysql_query($sql, $conf[DB]);

      header("Location: edit.php?module=text&a=edit&id=$_GET[id]");
      break;
    case "editframe" :
      ?>
<html>
<head>
<meta http-equiv="Content-Type"
  content="text/html; charset=windows-1251">
</head>
<frameset rows="*">
  <frame name="editframe"
    src="edit.php?module=text&a=edit&id=<?echo $_GET[id]?>">

</html>
      <? break;
      //<frame name = "editfilesframe" src = "edit.php?module=text&a=editfiles&id=<?echo $_GET[id]? >">

case "edit" :
  $sql = "SELECT * FROM `$conf[DB_PREFIX]texts` WHERE ID=$_GET[id]";
  $result = mysql_query($sql, $conf[DB]);
  $row = mysql_fetch_assoc($result);
  mysql_free_result($result);
  ?>
<html>
<head>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body scroll="auto">
<center><?
$sql = "SELECT * FROM `$conf[DB_PREFIX]blocks` WHERE `FID`=$row[ID]";
$result = mysql_query($sql, $conf[DB]);
$block = mysql_fetch_assoc($result);

$sql = "SELECT * FROM `$conf[DB_PREFIX]regions` WHERE `ID`=$block[PARENTREGION]";
$result = mysql_query($sql, $conf[DB]);
$region = mysql_fetch_assoc($result);

$tmpreg = $region;
$tmp[] = array ("<font size=3>$tmpreg[TITLE]</strong>", $tmpreg[ID]);

while ($tmpreg[PARENT] != 0) {
  $SQL = "SELECT TITLE, ID, PARENT FROM $conf[DB_PREFIX]regions WHERE ID=$tmpreg[PARENT]";
  $result = mysql_query($SQL, $conf[DB]);
  $tmpreg = mysql_fetch_assoc($result);
  $tmp[] = array ($tmpreg[TITLE], $tmpreg[ID]);
};

$tmp = array_reverse($tmp);
foreach ($tmp as $reg) {
  $data .= "-><a target=\"contentFrame\" href=\"index.php?a=regions&s=edit&id=$reg[1]\">$reg[0]</a>";
};
echo $data;
?>
<hr>
<form name="form" method="post"
  action="edit.php?module=text&a=update&id=<?echo $_GET[id];?>">
<table width="100%" border="1">
  <tr>
    <td width="100%"><?
    include("fckeditor.php");
    $sBasePath ="./";
    $oFCKeditor = new FCKeditor('text') ;
    $oFCKeditor->BasePath = $sBasePath ;
    $oFCKeditor->Height="500";
    $oFCKeditor->Value    = stripslashes($row[CONTENT]);
    $oFCKeditor->Create();
    ?></td>
  </tr>
</table>
<hr>
<center><input name="submit" type="submit" class="mainoption"
  value="Сохранить изменения"></center>
</form>
<hr>

</body>
</html>
    <? break;
case "uploadfiles" :
  $filew->upload_file();
  header("Location: edit.php?module=text&a=editfiles&id=$_GET[id]");
  break;
case "clearfiles" :
  $filew->clear_file();
  header("Location: edit.php?module=text&a=editfiles&id=$_GET[id]");
  break;
case "editfiles" :
  ?>
<html>
<head>
<title></title>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<center>
<div CONTENTEDITABLE><?$filew->show_file();?></div>
<form method="post"
  action="edit.php?module=text&a=uploadfiles&id=<?echo $_GET[id]?>"
  enctype="multipart/form-data"><input type="file" name="file" size="5">
<br>
<input type="submit" value="Загрузить" class="mainoption"></form>
<form method="post"
  action="edit.php?module=text&a=clearfiles&id=<?echo $_GET[id]?>"><input
  type="submit" value="ОЧИCТИТЬ" class="deloption"></form>
  <?
  break;
}
}
};
$info = array ('plugin' => "text", 'cplugin' => "eeText", 'pluginName' => "Текст", 'ISMENU' => 0, 'ISENGINEMENU' => 0, 'ISBLOCK' => 1, 'ISEXTRABLOCK' => 0, 'ISSPECIAL' => 0, 'ISLINKABLE' => 0, 'ISINTERFACE' => 0);
?>