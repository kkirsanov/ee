<?php class eeHot_shop {
  var $conf;
  function eeHot_shop($conf) {
    $this->conf = $conf;    
    
  }
  function install(){
    $conf = $this->conf;
    $SQL ="CREATE TABLE `$conf[DB_PREFIX]hot_block` ( `COUNT` int(11) NOT NULL default 3, `ID` int(11) NOT NULL auto_increment, PRIMARY KEY (`ID`)) ENGINE=MyISAM;";
    mysql_query($SQL);
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
        $MAIN = &$template->Get("shop.hot.main");#CORE_LOAD("hot_shop", "main");
        $CAT = &$template->Get("shop.hot.cat");#CORE_LOAD("hot_shop", "cat");
        echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">';
        ?>
        <form method="POST" action="properties.php?a=save&module=hot_shop&template=<?echo $_GET[template]?>">
      <TABLE border="1" width="90%" bgcolor="#CCCCCC">
        <tr>
          <td colspan="2" align="center"><h2>Элемент Магазина</h></td>
        </tr>
        <tr>
          <td>Строка<br>
            <nobr><b>%title%</b> - Заголовок<br>
            <nobr><b>%description%</b>- Описание<br>
            <nobr><b>%parent%</b>- Вышестоящий раздел<br>
            <nobr><b>%parentAddr%</b>- Адрес вышестоящего раздела раздела<br>
            <nobr><b>%price%</b>- цена<br>
            <nobr><b>%imageaddr%</b>- Адрес кртинки<br>
            <nobr><b>%viewaddr%</b>- Адрес для добавления просмотра<br>
            <nobr><b>%cartaddr%</b>- Адрес для добавления в карзину<br>
            <nobr><b>%header%</b>- Краткое Описание<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="CAT"><?=$CAT;?></TEXTAREA></td>
        </tr>
        <tr>
          <td>Окружение<br>
            <nobr><b>%main%</b> - Заголовок<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="MAIN"><?=$MAIN;?></TEXTAREA></td>
        </tr>
        </table>
        <center><INPUT type="submit" value="Принять" class="mainoption"></center>
        </form>
        <?
      break;
      case "save" :
        $template->Set("shop.hot.main", $_POST[MAIN]);
        $template->Set("shop.hot.cat", $_POST[CAT]);
        $template->Save();

        header("Location: ?module=hot_shop&template=$_GET[template]");
      break;
    };
  }
  function add() {
    $conf = $this->conf;
    $SQL = "INSERT INTO $conf[DB_PREFIX]hot_block values()";
    mysql_query($SQL, $conf[DB]);
    $id = mysql_insert_id($conf[DB]);
    return $id;
  }
  function del($id) {
    $conf = $this->conf;
    $SQL = "DELETE $conf[DB_PREFIX]hot_block WHERE ID=$id";
    $sql = "SELECT * FROM `$conf[DB_PREFIX]blocks` WHERE `FID`=$id and TYPE='text'";
    $result = mysql_query($sql, $conf[DB]);
    $block = mysql_fetch_assoc($result);
    mysql_query($SQL, $conf[DB]);
  } 
  function parseprice($price)
  {
    return $price;
    $newPrice="<table cellpadding=0 cellspacing=0 border=0>";

    $price=explode(";", $price);

    foreach ($price as $pr)
    {
      $split = explode(":", $pr);

      if (isset($split[1]))
        {
        $newPrice.="<tr><td>$split[0]</td><td><font color='red'>$split[1]</font></td></tr>";
        }
      else
        {
        if (($pr != "") && (isset($pr)) && ($pr != 0)) 
        $newPrice.="<tr><td>&nbsp;</td><td><font color='red'>$pr</font></td></tr>";
        }
    };
    $newPrice.="</table>";
    return $newPrice;
  }
  function render($regionID = 0, $id, $template) {
    $conf = $this->conf;
    $MAIN = &$template->Get("shop.hot.main");#CORE_LOAD("hot_shop", "main");
    $ELEMENT = $template->Get("shop.hot.cat");#CORE_LOAD("hot_shop", "cat");    
    $VALUEK   = GLOBAL_LOAD("valuek.dat");

    $VALUE    = GLOBAL_LOAD("valuek.dat")* $VALUEK;
  
    $SQL = "SELECT * FROM $conf[DB_PREFIX]hot_block WHERE ID=$id";
    $res = mysql_query($SQL, $conf[DB]);
    $hot_block = mysql_fetch_assoc($res);


    $SQL="SELECT * FROM $conf[DB_PREFIX]catalog WHERE HOT=1 and INPRICE=0 ORDER BY RAND() LIMIT 0, $hot_block[COUNT]";
    $res=mysql_query($SQL, $conf[DB]);

    while (@$_catalog_item=mysql_fetch_assoc($res))
    {

      $_ret = $ELEMENT;
      $_ret = str_replace("%title%", stripslashes($_catalog_item[TITLE]), $_ret);
      $_ret = str_replace("%description%", stripslashes($_catalog_item[CONTENT]), $_ret);
      $_ret = str_replace("%header%", stripslashes($_catalog_item[HEADER]), $_ret);
      $_ret = str_replace("%price%", $this->parseprice($_catalog_item[PRICE]), $_ret);
      $_ret = str_replace("%price2%", $this->parseprice($_catalog_item[PRICE])*$VALUE, $_ret);
      
      $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog_item[PARENT]";
      $tmpres = mysql_query($tmpsql, $conf[DB]);
      $tmpcat = mysql_fetch_assoc($tmpres);
      $_ret = str_replace("%parent%", $tmpcat[TITLE], $_ret);
      $_ret = str_replace("%parentAddr%", "./shop/$tmpcat[ID]/", $_ret);

      $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog_item[FIRM]";
      $tmpres = mysql_query($tmpsql, $conf[DB]);
      $tmpfir = mysql_fetch_assoc($tmpres);
      $_ret = str_replace("%firm%", $tmpfir[NAME], $_ret);

      //get image addres
      $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$_catalog_item[ID] AND TYPE = 'catalog' ORDER BY `ID`";
      $_res=mysql_query($_sql, $conf[DB]);
      if (@$_image=mysql_fetch_assoc($_res)){
        $_IMAGE="./files/$_image[ID]";
      }else{
        $_IMAGE="./absent.jpg";
      };
      $_ret = str_replace("%imageaddr%", $_IMAGE, $_ret);

      $_ret = str_replace("%viewaddr%", "./shop/$_catalog_item[ID]", $_ret);
      $_ret = str_replace("%cartaddr%", "./cart/$_catalog_item[ID]", $_ret);
      $_ROWSET .= $_ret;
    };
    return $RETURN = str_replace("%main%",$_ROWSET, $MAIN);

  }
  function renderEx($regionID, &$template) {}
  function edit(){
    $conf = $this->conf;
    $id = $_GET[id];    
    switch($_GET[a]){
      case "update":
        $sql = "UPDATE $conf[DB_PREFIX]hot_block SET `COUNT`=$_POST[count] where ID=$id";
        mysql_query($sql, $conf[DB]);
        header("Location: edit.php?id=$id&module=hot_shop");
      break;  
      case "":
        $sql = "SELECT * FROM `$conf[DB_PREFIX]hot_block` WHERE ID=$id";            
        $result = mysql_query($sql, $conf[DB]);
        $hotblock = mysql_fetch_assoc($result);
        mysql_free_result($result);     
        ?>
        <html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css.css" rel="stylesheet" type="text/css"></head><body scroll="auto">
        <center>
        <form name="forma" action="edit.php?a=update&id=<?=$id?>&module=hot_shop" method="POST">      
          <INPUT type="text" maxlength="11" name="count" value="<?echo $hotblock[COUNT]?>">
        <input name="submit" type="submit" class="mainoption" value="Далее">
        </FORM>
        </center>
        <?
      break;
    } 
  
  }
};
$info = array ('plugin' => "hot_shop", 'cplugin' => "eeHot_shop", 'pluginName' => "Горячие Товары", 'ISMENU' => 0, 'ISENGINEMENU' => 0, 
'ISBLOCK' => 1, 'ISEXTRABLOCK' => 0, 'ISSPECIAL' => 0, 'ISLINKABLE' => 0, 'ISINTERFACE' => 0);
?>