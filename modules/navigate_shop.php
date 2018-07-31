<?
class eeNavigateShop{
  var $conf;
  function eeNavigateShop($conf)
  {
    $this->conf = $conf;
  }
  function printheader(){

    echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"><body>'
      ."<a href=\"?a=editouter&module=NavigateShop&template=$_GET[template]\" >Обрамление</a>&nbsp<a href=\"?a=editelement&module=NavigateShop&template=$_GET[template]\" >Элементы</a></center>"
    ;
  }
  function getFullPath($id){
    $conf = $this->conf;
    
    $sql="SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID`=$id";
    $result=mysql_query($sql, $conf[DB]);   
    $ret = "";
    
    while($region=mysql_fetch_assoc($result)){      
      $sql="SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID`=$region[PARENT]";
      $result=mysql_query($sql, $conf[DB]);
      $ret="$region[TITLE]/$ret";
    };
    $ret = RuEncodeUTF($ret);
    return substr($ret,0,-1);//strip the last "/"
  }
  function printCatalogList($i){
    $conf = $this->conf;
    static $level;
    $level++;
    $sql="SELECT * FROM $conf[DB_PREFIX]catalog WHERE PARENT=$i AND `TYPE`=0 ORDER BY `ORDER` ASC";
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

      $sql = "SELECT COUNT(REGION) as `count` FROM $conf[DB_PREFIX]navigateShop_active WHERE REGION=$row[ID]";
      $result2=mysql_query($sql, $conf[DB]);
      $che=mysql_fetch_assoc($result2);
      if ($che[count]==0)
        $checked_active= "";

      $sql = "SELECT COUNT(REGION) as `count` FROM $conf[DB_PREFIX]navigateShop_top WHERE REGION=$row[ID]";
      $result2=mysql_query($sql, $conf[DB]);
      $che=mysql_fetch_assoc($result2);
      if ($che[count]==0)
        $checked_top= "";

      $sql = "SELECT COUNT(REGION) as `count` FROM $conf[DB_PREFIX]navigateShop_aactive WHERE REGION=$row[ID]";
      $result2=mysql_query($sql, $conf[DB]);
      $che=mysql_fetch_assoc($result2);
      if ($che[count]==0)
        $checked_aactive= "";

      echo "1<INPUT type=\"checkbox\" name=\"region$row[ID]active\" $checked_active>"
        ."2<INPUT type=\"checkbox\" name=\"region$row[ID]top\" $checked_top>"
        ."3<INPUT type=\"checkbox\" name=\"region$row[ID]aactive\" $checked_aactive>"
      .$row['TITLE'], "<br>";
      $this->printCatalogList($tmpID);
      echo "</tr></td></table>";
    }
    mysql_free_result($result);
    $level--;
  }
  function install(){   
    $conf = $this->conf;
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]navigateShop_active` ("
      ."`REGION` int(11) NOT NULL default '0'"
      .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]navigateShop_aactive` ("
      ."`REGION` int(11) NOT NULL default '0'"
      .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]navigateShop_top` ("
      ."`REGION` int(11) NOT NULL default '0'"
      .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);
    @registerAccess("module_navigateShop_design", "Навигация Магазин- <b>дизайн</b>");
    @registerAccess("module_navigateShop_admin", "Навигация Магазин- <b>разделы</b>");
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
  function render($rID, $fID, $template){
    
    $conf = $this->conf;
    $this->OUTER_MAIN   = &$template->Get("shop.navigate.outer.main");#CORE_LOAD("navigateShop", "outer_main.dat");
    $this->OUTER_SUB    = &$template->Get("shop.navigate.outer.sub");#CORE_LOAD("navigateShop", "outer_sub.dat");
    $this->ELEMENT      = &$template->Get("shop.navigate.element.main");#CORE_LOAD("navigateShop", "element.dat");
    $this->ELEMENT_SUB    = &$template->Get("shop.navigate.element.sub");#CORE_LOAD("navigateShop", "element_sub.dat");
    $this->ELEMENT_ACTIVE = &$template->Get("shop.navigate.element.active");#CORE_LOAD("navigateShop", "element_active.dat");

    $subElements = "";
    //fill the chields of current region.
    
    $path = split("/", $_GET[path]);
    $rID = (int)$path[1];

    $sql="SELECT * FROM $conf[DB_PREFIX]catalog INNER JOIN $conf[DB_PREFIX]navigateShop_active ON ($conf[DB_PREFIX]catalog.ID = $conf[DB_PREFIX]navigateShop_active.REGION) WHERE `PARENT`=$rID ORDER BY `ORDER` ASC";
    
    if ($result=mysql_query($sql, $conf[DB])){      
      while ($region=mysql_fetch_assoc($result))
      {
        $subnavigateShop = str_replace("%text%", $region[TITLE], $this->ELEMENT_SUB);
        $subnavigateShop = str_replace("%link%",  "./shop/$region[ID]", $subnavigateShop);
        $subnavigateShop= str_replace("/index.html", "", $subnavigateShop);
        $subElements .= $subnavigateShop;
      };
    };
    if (($path[0]!="shop") || ($path[1]=="firms"))
      return str_replace("%main%", $subElements,$this->OUTER_SUB);

    $sql="SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID`=$rID";
    @$thisRegion=mysql_fetch_assoc(mysql_query($sql, $conf[DB]));
    @$region = $thisRegion;

    $sql2="SELECT * FROM $conf[DB_PREFIX]catalog INNER JOIN $conf[DB_PREFIX]navigateShop_top ON ($conf[DB_PREFIX]catalog.ID = $conf[DB_PREFIX]navigateShop_top.REGION) WHERE `ID`=$region[ID] ORDER BY `ORDER` ASC";
    @$res2=mysql_query($sql2, $conf[DB]);
    @$ans2=mysql_fetch_assoc($res2);
    $list=$subElements;

    $i=0;
    while (1&&$ans2[ID]==0){
      $tmpreg = $region;
      $sql="SELECT * FROM $conf[DB_PREFIX]catalog INNER JOIN $conf[DB_PREFIX]navigateShop_active ON ($conf[DB_PREFIX]catalog.ID = $conf[DB_PREFIX]navigateShop_active.REGION) WHERE `PARENT`=$region[PARENT] ORDER BY `ORDER` ASC";
      $result2=mysql_query($sql, $conf[DB]);
      $tmpbro="";
      $i++;
      $aatemp="";
      while($region2=@mysql_fetch_assoc($result2)){
        
        if($region2[ID]==$tmpreg[ID]){          
          $TEMP = str_replace("%text%", $region[TITLE], $this->ELEMENT_ACTIVE);
            $TEMP = str_replace("%link%",  "./shop/$region[ID]", $TEMP);
          
          $tmpbro.=$TEMP."%SUB%";
        }else{
          $TEMP = str_replace("%text%", $region2[TITLE], $this->ELEMENT);
          
          $TEMP = str_replace("%link%",  "./shop/$region2[ID]", $TEMP);
          $TEMP = str_replace("/index.html", "", $TEMP);
          $tmpbro.=$TEMP;
        };

        $sql="SELECT * FROM $conf[DB_PREFIX]catalog INNER JOIN $conf[DB_PREFIX]navigateShop_aactive ON ($conf[DB_PREFIX]catalog.ID = $conf[DB_PREFIX]navigateShop_aactive.REGION) WHERE `ID`=$region2[ID] ORDER BY `ORDER` ASC";
        $result3=mysql_query($sql, $conf[DB]);
        @$region3 = mysql_fetch_assoc($result3);
        if (($region3[ID]!=0)&&($region3[ID]!=$region[ID])){
          $region3[ID];
          $aatemp="";
          //********************************
          $sql="SELECT * FROM $conf[DB_PREFIX]catalog INNER JOIN $conf[DB_PREFIX]navigateShop_active ON ($conf[DB_PREFIX]catalog.ID = $conf[DB_PREFIX]navigateShop_active.REGION) WHERE `PARENT`=$region2[ID] ORDER BY `ORDER` ASC";
          $result3=mysql_query($sql, $conf[DB]);
          $i++;
          while($region3=mysql_fetch_assoc($result3)){              
              $TEMP = str_replace("%text%", $region3[TITLE], $this->ELEMENT_SUB);
              $TEMP = str_replace("%link%",  "./shop/$region3[ID]", $TEMP);
              $TEMP = str_replace("/index.html", "", $TEMP);
              $aatemp.=$TEMP;
          };
          if($aatemp!=""){
            $tmpbro.=str_replace("%main%",  $aatemp, $this->OUTER_SUB);
          };
          //********************************
        }
      };
      $list = str_replace("%SUB%", str_replace("%main%",  $list, $this->OUTER_SUB), $tmpbro);

      $sql="SELECT * FROM $conf[DB_PREFIX]catalog INNER JOIN $conf[DB_PREFIX]navigateShop_active ON ($conf[DB_PREFIX]catalog.ID = $conf[DB_PREFIX]navigateShop_active.REGION) WHERE `ID`=$region[PARENT] ORDER BY `ORDER` ASC";
      @$result=mysql_query($sql, $conf[DB]);
      @$region=mysql_fetch_assoc($result);

      $sql="SELECT * FROM $conf[DB_PREFIX]catalog INNER JOIN $conf[DB_PREFIX]navigateShop_top ON ($conf[DB_PREFIX]catalog.ID = $conf[DB_PREFIX]navigateShop_top.REGION) WHERE `ID`=$region[ID] ORDER BY `ORDER` ASC";
      @$res=mysql_query($sql, $conf[DB]);
      @$ans=mysql_fetch_assoc($res);
      
      if ($region[ID]==0 || $ans[ID]!=0)
        break;
    };
    return str_replace("%main%",  $list, $this->OUTER_MAIN);
  }

  function properties() {
    $conf = $this->conf;
    $action = $_GET[a];
    if ($action == "")
      $action="view";
    if ($action == "view")
      $action="editouter";
    
   switch ($action)
    { 
      case "editactive":
      $this->printheader();
      //if (CA("module_navigateShop_admin")){
        echo "1 - Показывать, 2 - Всегда наверху, 3 - Всегда раскрыта.";
        echo '<form method="POST" action="?module=NavigateShop&a=saveactive&module=NavigateShop">';
        $this->printCatalogList(0);     
        ?><center><INPUT type="submit" value="Ok!" class="mainoption"></center><?
        echo '</form>';
    //  };
    break;
    case "saveactive":
      $sql="SELECT * FROM $conf[DB_PREFIX]catalog";
      $result=mysql_query($sql, $conf[DB]); 
      while ($region=mysql_fetch_assoc($result)){
        if ($_POST["region$region[ID]active"]!="on")
        {
          $SQL = "DELETE FROM $conf[DB_PREFIX]navigateShop_active WHERE REGION=$region[ID]";
          mysql_query($SQL, $conf[DB]);
        }else{  
          $SQL = "DELETE FROM $conf[DB_PREFIX]navigateShop_active WHERE REGION=$region[ID]";
          mysql_query($SQL, $conf[DB]);
          $SQL = "insert INTO $conf[DB_PREFIX]navigateShop_active VALUES($region[ID])";
          $r = mysql_query($SQL) or die (mysql_error());
        };

        if ($_POST["region$region[ID]aactive"]!="on")
        {
          $SQL = "DELETE FROM $conf[DB_PREFIX]navigateShop_aactive WHERE REGION=$region[ID]";
          mysql_query($SQL, $conf[DB]);
        }else{  
          $SQL = "DELETE FROM $conf[DB_PREFIX]navigateShop_aactive WHERE REGION=$region[ID]";
          mysql_query($SQL, $conf[DB]);
          $SQL = "insert INTO $conf[DB_PREFIX]navigateShop_aactive VALUES($region[ID])";
          $r = mysql_query($SQL) or die (mysql_error());
        };
        if ($_POST["region$region[ID]top"]!="on")
        {
          $SQL = "DELETE FROM $conf[DB_PREFIX]navigateShop_top WHERE REGION=$region[ID]";
          mysql_query($SQL, $conf[DB]);
        }else{  
          $SQL = "DELETE FROM $conf[DB_PREFIX]navigateShop_top WHERE REGION=$region[ID]";
          mysql_query($SQL, $conf[DB]);
          $SQL = "insert INTO $conf[DB_PREFIX]navigateShop_top VALUES($region[ID])";
          $r = mysql_query($SQL) or die (mysql_error());
        };
      }
      header("Location: ?module=NavigateShop&a=editactive");
    break;
    case "editouter":     
      $tID = $_GET[template];
      if (!$tID)
        die("template error");
    
        include_once ("core_template.php");
        $template = new Template($_GET[template]);
        $this->printheader();
      //if (CA("module_navigateShop_design")){
      global $OUTER_MAIN;
      global $OUTER_SUB;
      $OUTER_MAIN   = &$template->Get("shop.navigate.outer.main");#CORE_LOAD("navigateShop", "outer_main.dat");
      $OUTER_SUB    = &$template->Get("shop.navigate.outer.sub");#CORE_LOAD("navigateShop", "outer_sub.dat");
      ?><form method="POST" action="?module=NavigateShop&a=saveouter&module=NavigateShop&template=<?echo $_GET[template]?>">
        <TABLE border="1" width="90%" bgcolor="#CCCCCC">
          <tr>
            <td colspan="2" align="center"><h2>Общее Окружение</h></td>
          </tr><tr><td>%main% - содержание</td>
            <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"  name="OUTER_MAIN"><?=$OUTER_MAIN;?></TEXTAREA></td>
          </tr>
        </TABLE>
        <TABLE border="1" width="90%" bgcolor="#ECECEC">
          <tr>
            <td colspan="2" align="center"><h2>Окружение Подуровня</h></td>
          </tr> 
          <tr>
            <td>%main% - содержание</td>
            <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%" name="OUTER_SUB"><?=$OUTER_SUB;?></TEXTAREA></td>       
          </tr>
        </TABLE>
        <center><INPUT type="submit" value="Принять" class="mainoption"></center>
        <FORM>
        <?
        //};
      break;
      case "saveouter":
        $tID = $_GET[template];
      if (!$tID)
        die("template error");
    
        include_once ("core_template.php");
        $template = new Template($_GET[template]);
        $template->Set("shop.navigate.outer.main", $_POST[OUTER_MAIN]);
        $template->Set("shop.navigate.outer.sub", $_POST[OUTER_SUB]);
        $template->Save();      
         header("Location: ?module=NavigateShop&a=editouter&template=$_GET[template]");
      break;
    case "editelement":
      $this->printheader();   
      $tID = $_GET[template];
      if (!$tID)
      die("template error");
    
      include_once ("core_template.php");
      $template = new Template($_GET[template]);
     // if (CA("module_navigateShop_design")){
      $ELEMENT    = &$template->Get("shop.navigate.element.main");#CORE_LOAD("navigateShop", "element.dat");
      $ELEMENT_SUB  = &$template->Get("shop.navigate.element.sub");#CORE_LOAD("navigateShop", "element_sub.dat");
      $ELEMENT_ACTIVE = &$template->Get("shop.navigate.element.active");#CORE_LOAD("navigateShop", "element_active.dat");
      ?>
      <form method="POST" action="?module=NavigateShop&a=saveelement&module=NavigateShop&template=<?echo $_GET[template]?>">
      <TABLE border="1" width="90%" bgcolor="#CCCCCC">
        <tr>
          <td colspan="2" align="center"><h2>Элемент</h></td>
        </tr><tr><td>%link%</td>
          <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"  name="ELEMENT"><?=$ELEMENT;?></TEXTAREA></td>
        </tr> 
      </TABLE>
      <TABLE border="1" width="90%" bgcolor="#ECECEC">
        <tr>
          <td colspan="2" align="center"><h2>Подчинённый Элемент</h></td>
        </tr><tr><td></td>
          <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"  name="ELEMENT_SUB"><?=$ELEMENT_SUB;?></TEXTAREA></td>
        </tr>   
      </TABLE>
      <TABLE border="1" width="90%" bgcolor="#CCCCCC">
        <tr>
          <td colspan="2" align="center"><h2>Активный Элемент</h></td>
        </tr><tr><td></td>
          <td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"  name="ELEMENT_ACTIVE"><?=$ELEMENT_ACTIVE;?></TEXTAREA></td>
        </tr> 
      </TABLE>
      <center><INPUT type="submit" value="Принять" class="mainoption"></center>
      <FORM>
      <?
     // };
    break;
    case "saveelement": 
      $tID = $_GET[template];
      if (!$tID)
        die("template error");
    
      include_once ("core_template.php");
      $template = new Template($_GET[template]);
        $template->Set("shop.navigate.element.main", $_POST[ELEMENT]);
        $template->Set("shop.navigate.element.sub", $_POST[ELEMENT_SUB]);
        $template->Set("shop.navigate.element.active", $_POST[ELEMENT_ACTIVE]);
        $template->Save();      
      header("Location: ?module=NavigateShop&a=editelement&template=$_GET[template]");
    break;
    }
  }
};
$info = array(
  'plugin'      => "NavigateShop",
  'cplugin'     => "eeNavigateShop",
  'pluginName'    => "Навигация Магазина",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>