<?
class eeNavigate{
  var $conf;
  function eeNavigate($conf)
  {
    $this->conf = $conf;
    //$this->install();
  }
  function printheader(){
    echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"><body>'
    //  .'<center><a href="?a=editactive&module=navigate" >Активные Разделы</a>&nbsp<a href="?a=editouter&module=navigate" >Обромление</a>&nbsp<a href="?a=editelement&module=navigate" >Элементы</a></center>'
    ;
  }
  function printRegionsList($i){
    $conf = $this->conf;
    static $level;
    $level++;
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$i ORDER BY `ORDER` ASC";
    $result=mysql_query($sql, $conf[DB]);
    echo "<table><tr><td>";

    while ($row=mysql_fetch_assoc($result)){
      $tmpID = $row['ID'];
      $tmp=0;
      for ($tmp=0; $tmp <= $level; $tmp++)
      echo "&nbsp;&nbsp;";
      $checked_active= "checked";
      $checked_aactive= "checked";
      $checked_top= "checked";

      $sql = "SELECT COUNT(REGION) as `count` FROM $conf[DB_PREFIX]navigate_active WHERE REGION=$row[ID]";
      $result2=mysql_query($sql, $conf[DB]);
      $che=mysql_fetch_assoc($result2);
      if ($che[count]==0)
      $checked_active= "";

      $sql = "SELECT COUNT(REGION) as `count` FROM $conf[DB_PREFIX]navigate_top WHERE REGION=$row[ID]";
      $result2=mysql_query($sql, $conf[DB]);
      $che=mysql_fetch_assoc($result2);
      if ($che[count]==0)
      $checked_top= "";

      $sql = "SELECT COUNT(REGION) as `count` FROM $conf[DB_PREFIX]navigate_aactive WHERE REGION=$row[ID]";
      $result2=mysql_query($sql, $conf[DB]);
      $che=mysql_fetch_assoc($result2);
      if ($che[count]==0)
      $checked_aactive= "";

      echo "1<INPUT type=\"checkbox\" name=\"region$row[ID]active\" $checked_active>"
      ."2<INPUT type=\"checkbox\" name=\"region$row[ID]top\" $checked_top>"
      ."3<INPUT type=\"checkbox\" name=\"region$row[ID]aactive\" $checked_aactive>"
      .$row['TITLE'], "<br>";
      $this->printRegionsList($tmpID);
      echo "</tr></td></table>";
    }
    mysql_free_result($result);
    $level--;
  }
  function install(){
    $conf = $this->conf;
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]navigate_active` ("
    ."`REGION` int(11) NOT NULL default '0'"
    .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]navigate_aactive` ("
    ."`REGION` int(11) NOT NULL default '0'"
    .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]navigate_top` ("
    ."`REGION` int(11) NOT NULL default '0'"
    .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);
    //@registerAccess("module_navigate_design", "Навигация - <b>дизайн</b>");
    //@registerAccess("module_navigate_admin", "Навигация - <b>разделы</b>");
    return 1;
  }
  function add()
  {
    return 0;
  }
  function del()
  {
    return 1;
  }
  function render($rID, $fID, &$template ){
    $conf = $this->conf;
    
    #var_dump ($template->Get("navigate"));
    $this->OUTER_MAIN   =& $template->Get("navigate.outer.main");
    $this->OUTER_SUB    =& $template->Get("navigate.outer.sub");
    $this->ELEMENT      =& $template->Get("navigate.element.main");
    $this->ELEMENT_SUB   =& $template->Get("navigate.element.sub");
    $this->ELEMENT_ACTIVE=& $template->Get("navigate.element.active");

    $subElements = "";
    //fill the chields of current region.
    $sql="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]navigate_active ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]navigate_active.REGION) WHERE `PARENT`=$rID ORDER BY `ORDER` ASC";
    //$sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `PARENT`=$rID ORDER BY `ORDER` ASC";
    if ($result=mysql_query($sql, $conf[DB])){
      while ($region=mysql_fetch_assoc($result))
      {
        $subnavigate = str_replace("%text%", $region[TITLE], $this->ELEMENT_SUB);
        if (($region[LINKTYPE]=="WWW")&&($region[SPECIAL]==2)){
          $subnavigate = str_replace("%link%",  "./".$region[WEBLINK], $subnavigate);
        }else{
          $subnavigate = str_replace("%link%",  "./".getFullPath($region[ID]), $subnavigate);
        };
        $subElements .= $subnavigate;
      };
    };
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$rID";
    $thisRegion=mysql_fetch_assoc(mysql_query($sql, $conf[DB]));
    $region = $thisRegion;

    $sql2="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]navigate_top ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]navigate_top.REGION) WHERE `ID`=$region[ID] ORDER BY `ORDER` ASC";
    @$res2=mysql_query($sql2, $conf[DB]);
    @$ans2=mysql_fetch_assoc($res2);
    $list=$subElements;

    $i=0;
    while (1&&$ans2[ID]==0){
      $tmpreg = $region;
      $sql="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]navigate_active ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]navigate_active.REGION) WHERE `PARENT`=$region[PARENT] ORDER BY `ORDER`";
      $result2=mysql_query($sql, $conf[DB]);
      $tmpbro="";
      $i++;
      $aatemp="";
      while($region2=mysql_fetch_assoc($result2)){

        if($region2[ID]==$tmpreg[ID]){
          $TEMP = str_replace("%text%", $region[TITLE], $this->ELEMENT_ACTIVE);
          if (($region[LINKTYPE]=="WWW")&&($region[SPECIAL]==2)){
            $TEMP = str_replace("%link%",  "./".$region[WEBLINK], $TEMP);
          }else{
            $TEMP = str_replace("%link%",  "./".getFullPath($region[ID]), $TEMP);
          }
          $tmpbro.=$TEMP."%SUB%";
        }else{
          $TEMP = str_replace("%text%", $region2[TITLE], $this->ELEMENT);

          if (($region2[LINKTYPE]=="WWW")&&($region2[SPECIAL]==2)){
            $TEMP = str_replace("%link%",  "./".$region2[WEBLINK], $TEMP);
          }else{
            $TEMP = str_replace("%link%",  "./".getFullPath($region2[ID]), $TEMP);
          };

          $tmpbro.=$TEMP;
        };

        $sql="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]navigate_aactive ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]navigate_aactive.REGION) WHERE `ID`=$region2[ID] ORDER BY `ORDER`";
        $result3=mysql_query($sql, $conf[DB]);
        @$region3 = mysql_fetch_assoc($result3);
        if (($region3[ID]!=0)&&($region3[ID]!=$region[ID])){
          $region3[ID];
          $aatemp="";
          //********************************
          $sql="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]navigate_active ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]navigate_active.REGION) WHERE `PARENT`=$region2[ID] ORDER BY `ORDER`";
          $result3=mysql_query($sql, $conf[DB]);
          $i++;
          while($region3=mysql_fetch_assoc($result3)){
            $TEMP = str_replace("%text%", $region3[TITLE], $this->ELEMENT_SUB);

            if (($region3[LINKTYPE]=="WWW")&&($region3[SPECIAL]==2)){
              $TEMP = str_replace("%link%",  "./".$region3[WEBLINK], $TEMP);
            }else{
              $TEMP = str_replace("%link%",  "./".getFullPath($region3[ID]), $TEMP);
            };
            $aatemp.=$TEMP;
          };
          if($aatemp!=""){
            $tmpbro.=str_replace("%main%",  $aatemp, $this->OUTER_SUB);
          };
          //********************************
        }
      };
      $list = str_replace("%SUB%", str_replace("%main%",  $list, $this->OUTER_SUB), $tmpbro);

      $sql="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]navigate_active ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]navigate_active.REGION) WHERE `ID`=$region[PARENT]";
      @$result=mysql_query($sql, $conf[DB]);
      @$region=mysql_fetch_assoc($result);

      $sql="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]navigate_top ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]navigate_top.REGION) WHERE `ID`=$region[ID] ORDER BY `ORDER` ASC";
      @$res=mysql_query($sql, $conf[DB]);
      @$ans=mysql_fetch_assoc($res);

      if ($region[ID]==0 || $ans[ID]!=0)
      break;
    };
    $list = str_replace('<ul class="menu">  </ul>',  "", $list);
    $list= str_replace("%main%",  $list, $this->OUTER_MAIN);
    return str_replace('//',  '/', $list);
  }

  function properties() {
    $conf = $this->conf;
    $action = $_GET[a];
    if ($action == "")
      $action="editactive";
    switch ($action)
    {
      case "editactive":
        $this->printheader();
        //if (CA("module_navigate_admin")){
        echo "1 - Показывать, 2 - Всегда наверху, 3 - Всегда раскрыта.";
        echo '<form method="POST" action="?module=navigate&a=saveactive">';
        $this->printRegionsList(0);
        ?>
<center><INPUT type="submit" value="Ok!" class="mainoption"></center>
        <?
        echo '</form>';
        //};
        break;
case "saveactive":
  $sql="SELECT * FROM $conf[DB_PREFIX]regions";
  $result=mysql_query($sql, $conf[DB]);
  while ($region=mysql_fetch_assoc($result)){
    if ($_POST["region$region[ID]active"]!="on")
    {
      $SQL = "DELETE FROM $conf[DB_PREFIX]navigate_active WHERE REGION=$region[ID]";
      mysql_query($SQL, $conf[DB]);
    }else{
      $SQL = "DELETE FROM $conf[DB_PREFIX]navigate_active WHERE REGION=$region[ID]";
      mysql_query($SQL, $conf[DB]);
      $SQL = "insert INTO $conf[DB_PREFIX]navigate_active VALUES($region[ID])";
      mysql_query($SQL, $conf[DB]);
    };

    if ($_POST["region$region[ID]aactive"]!="on")
    {
      $SQL = "DELETE FROM $conf[DB_PREFIX]navigate_aactive WHERE REGION=$region[ID]";
      mysql_query($SQL, $conf[DB]);
    }else{
      $SQL = "DELETE FROM $conf[DB_PREFIX]navigate_aactive WHERE REGION=$region[ID]";
      mysql_query($SQL, $conf[DB]);
      $SQL = "insert INTO $conf[DB_PREFIX]navigate_aactive VALUES($region[ID])";
      mysql_query($SQL, $conf[DB]);
    };

    if ($_POST["region$region[ID]top"]!="on")
    {
      $SQL = "DELETE FROM $conf[DB_PREFIX]navigate_top WHERE REGION=$region[ID]";
      mysql_query($SQL, $conf[DB]);
    }else{
      $SQL = "DELETE FROM $conf[DB_PREFIX]navigate_top WHERE REGION=$region[ID]";
      mysql_query($SQL, $conf[DB]);
      $SQL = "insert INTO $conf[DB_PREFIX]navigate_top VALUES($region[ID])";
      mysql_query($SQL, $conf[DB]);
    };
  }
  header("Location: ?module=navigate&a=editactive");
  break;
case "edit":
  $tID = $_GET[template];
  if (!$tID)
  die("template error");

  include_once ("core_template.php");
  $template = new Template($_GET[template]);

  $OUTER_MAIN     =& $template->Get("navigate.outer.main");
  $OUTER_SUB      =& $template->Get("navigate.outer.sub");
  $ELEMENT      =& $template->Get("navigate.element.main");
  $ELEMENT_SUB    =& $template->Get("navigate.element.sub");
  $ELEMENT_ACTIVE =& $template->Get("navigate.element.active");
  ?>
<form method="POST"
  action="?module=navigate&a=save&module=navigate&template=<?=$_GET[template]?>">
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Общее Окружение</h>
    
    </td>
  </tr>
  <tr>
    <td>%main% - содержание</td>
    <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"
      name="OUTER_MAIN"><?=$OUTER_MAIN;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Окружение Подуровня</h>
    
    </td>
  </tr>
  <tr>
    <td>%main% - содержание</td>
    <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"
      name="OUTER_SUB"><?=$OUTER_SUB;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Элемент</h>
    
    </td>
  </tr>
  <tr>
    <td>%link%</td>
    <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%" name="ELEMENT"><?=$ELEMENT;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Подчинённый Элемент</h>
    
    </td>
  </tr>
  <tr>
    <td></td>
    <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"
      name="ELEMENT_SUB"><?=$ELEMENT_SUB;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Активный Элемент</h>
    
    </td>
  </tr>
  <tr>
    <td></td>
    <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"
      name="ELEMENT_ACTIVE"><?=$ELEMENT_ACTIVE;?></TEXTAREA></td>
  </tr>
</TABLE>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
<FORM><?

break;
case "save":

  $tID = $_GET[template];
  if (!$tID)die("template error");

  include_once ("core_template.php");
  $template = new Template($_GET[template]);
  $template->Set("navigate.outer.main", $_POST[OUTER_MAIN]);
  $template->Set("navigate.outer.sub", $_POST[OUTER_SUB]);
  $template->Set("navigate.element.main", $_POST[ELEMENT]);
  $template->Set("navigate.element.sub", $_POST[ELEMENT_SUB]);
  $template->Set("navigate.element.active", $_POST[ELEMENT_ACTIVE]);
  $template->save();
  header("Location: ?module=navigate&a=edit&template=$_GET[template]");
  break;
}
}
};
$info = array(
  'plugin'      => "navigate",
  'cplugin'     => "eeNavigate",
  'pluginName'    => "Навигация",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>