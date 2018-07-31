<?
class eePath{
  var $conf;
  function printheader(){
    ?>
<html>
<head>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<body>
    <?
}
function eePath($conf)
{
  $this->conf = $conf;
}
function add()
{
  return 0;
}
function del($id)
{
  return 1;
}

function render($id, $fid, &$template)
{
  $conf = $this->conf;
  $OUTER  = & $template->Get("path.outer");
  $COMMON =& $template->Get("path.common");
  $ACTIVE =& $template->Get("path.active");
  $_sql="SELECT * FROM `$conf[DB_PREFIX]regions` WHERE `ID`=$id";
  $_result=mysql_query($_sql, $conf[DB]);
  $_row=mysql_fetch_assoc($_result);
  $_names[$_tmp1] = $_row[TITLE];
  $_namesID[$_tmp1] = $_row[ID];
  $_tmp1=0;
  while ($_row[PARENT] != 0)
  {
    $_tmp_id = $_row[PARENT];
    mysql_free_result($_result);
    $_sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE ID=$_tmp_id";
    $_result=mysql_query($_sql, $conf[DB]);
    $_row=mysql_fetch_assoc($_result);
    $_names[$_tmp1] = $_row[TITLE];
    $_namesID[$_tmp1] = $_row[ID];
    $_tmp1++;
  };
  $_names   = array_reverse($_names);
  $_namesID = array_reverse($_namesID);

  $_ret="";
  $_tmp2=0;
  foreach ($_names as $_NAME){
    if ($_tmp1==0){
      //Active Element
      $_tmp = str_replace("%text%", $_NAME, $ACTIVE);
    }else{
      $_tmp = str_replace("%text%", $_NAME, $COMMON);
    }
    $_sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE ID=". $_namesID[$_tmp2];
    $_result=mysql_query($_sql, $conf[DB]);
    $_row=@mysql_fetch_assoc($_result);

    if ($_row)
    if ($_row[SPECIAL]==2 && $_row[LINKTYPE]=="WWW"){
      $_tmp = str_replace("%link%", $_row[WEBLINK], $_tmp);

    }else{
      if ($_namesID[$_tmp2])
      $_tmp = str_replace("%link%", "./" . getFullPath($_namesID[$_tmp2]), $_tmp);
    };

    $_tmp1--;
    $_ret = $_ret . $_tmp;
    $_tmp2++;
  };
  $_ret = str_replace("%link%", getFullPath($id), $_ret);//If the starting region is not processed.
  return str_replace("%main%", $_ret, $OUTER);

}
function install(){
  //@registerAccess("module_gallery_design", "Крошка/Дизайн");
  return 1;
}
function properties(){

  $tID = $_GET[template];
  if (!$tID)  die("template error");

  include_once ("core_template.php");
  $template = new Template($_GET[template]);

  switch ($_GET[a]){
    case "":
      $this->printheader();
      $OUTER    =& $template->Get("path.outer");
      $COMMON   =& $template->Get("path.common"); 
      $ACTIVE   =& $template->Get("path.active"); 
      ?>
<form method="POST"
  action="?a=save&module=path&template=<?=$_GET[template]?>">
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td>Обрамление Блока<br>
    <b>%main%</b> - Содержание</td>
    <td width="100%"><TEXTAREA rows="3" style="WIDTH: 100%" name="OUTER"><?=$OUTER;?></TEXTAREA></td>
  </tr>
  <tr>
    <td>Обрамление Элемента <br>
    <b>%link%</b> - ссылка на раздел <br>
    <b>%text%</b> - называние раздела</td>
    <td width="100%"><TEXTAREA rows="8" style="WIDTH: 100%" name="COMMON"><?=$COMMON;?></TEXTAREA></td>
  </tr>
  <tr>
    <td>Обрамление Активного Элемента <br>
    <b>%text%</b> - называние раздела</td>
    <td width="100%"><TEXTAREA rows="8" style="WIDTH: 100%" name="ACTIVE"><?=$ACTIVE;?></TEXTAREA></td>
  </tr>
</TABLE>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
<FORM><?
break;
case "save":
  $template->Set("path.outer", $_POST[OUTER]);
  $template->Set("path.common", $_POST[COMMON]);
  $template->Set("path.active", $_POST[ACTIVE]);
  $template->Save();

  header("Location: ?module=path&template=$_GET[template]");
  break;
}
}
};
$info = array(
  'plugin'      => "path",
  'cplugin'     => "eePath",
  'pluginName'    => "Крошка",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>