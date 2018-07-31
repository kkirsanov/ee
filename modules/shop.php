<?php
class eeShop{
  var $conf;
  function eeShop($conf){
    $this->conf = $conf;

  }
  function parseprice($price){
    return $price;
    $newPrice="<table cellpadding=0 cellspacing=0 border=0>";

    $price=explode(";", $price);

    foreach ($price as $pr)
    {
      $split = explode(":", $pr);
  
      if (isset($split[1])){
        $newPrice.="<tr><td>$split[0]</td><td><font color='red'>$split[1]</font></td></tr>";
      }else{
        if (($pr != "") && (isset($pr)) && ($pr != 0)) 
        $newPrice.="<tr><td>&nbsp;</td><td><font color='red'>$pr</font></td></tr>";
      }
    };
    $newPrice.="</table>";
    return $newPrice;
  }

  function printheader(){
    ?><html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"><body>
    <script>function DoConfirm(message, url){if (confirm(message))location.href = url;}
    function NW(adr, h, w){
          win=window.open(adr,"_blank","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,width="+ w + ",height="+h);
          win.parentw = window;
        }
    </script>
    <center>
      <a href="?module=shop&template=<?echo $_GET[template]?>">Просмотреть</a>&nbsp
      <!-- <a href="?action=editelement&module=shop&template=<?echo $_GET[template]?>" >Шаблоны</a> -->
      <a href="?action=firm&module=shop&template=<?echo $_GET[template]?>" >Редактировать Фирмы</a>
      <a href="?action=edit&task=byfirm&module=shop&template=<?echo $_GET[template]?>" > по Производителю</a>
      <a href="?action=edit&task=comments&module=shop&template=<?echo $_GET[template]?>" >Комментарии</a>
      <!--<a href="?action=edit&task=offers&module=shop&template=<?echo $_GET[template]?>" > Offers</a>-->
      <a href="../tools/priceparcer.php?template=<?echo $_GET[template]?>" >Загрузка прайсов</a>
      <a href="../tools/articulparcer.php?template=<?echo $_GET[template]?>" >Артикулы</a>
    </center>
    <?
  }


  function pricein($a, $b)
  {
    $pr_3=explode(";", $a);
    $pr_ext = explode(":", $pr_3[0]);
    
    if (!isset ($pr_ext[1]))
      return $pr_ext[0];
    $max = 0;
        
    foreach ($pr_3 as $pr){
      
      $pr_ext = explode(":", $pr);
      $pr_2=explode("-", $pr_ext[0]);
      $lpr=$pr_2[0];
      $hpr=$pr_2[1];
      $max=$pr_ext[1];
      
      if (($b <= $hpr) && (($b >= $lpr)))
      {
        return $pr_ext[1];
      }
    }
    return $max;
    
  }
  function install() {
    $conf = $this->conf;


$SQL = "CREATE TABLE IF NOT EXISTS `smspay` ("
  ."`number` varchar(40) NOT NULL default '',"
  ."`operator` varchar(50) NOT NULL default '',"
  ."`country` varchar(50) NOT NULL default '',"
  ."`price` decimal(10,2) NOT NULL default '0.00',"
  ."`key` varchar(10) NOT NULL default '',"
  ."`active` tinyint(1) NOT NULL default '1',"
  ."`phone` varchar(50) NOT NULL default '',"
  ."`userid` int(10) NOT NULL default '1',"
  ."`id` int(10) NOT NULL auto_increment,"
  ."`smsid` varchar(20) NOT NULL default '',"
  ."PRIMARY KEY  (`id`)"
.") ENGINE=MyISAM  DEFAULT CHARSET=utf8"
;

$r=mysql_query($SQL, $conf[DB]);

$SQL = "CREATE TABLE IF NOT EXISTS `smstarif` ("
  ."`access` varchar(20) NOT NULL default '',"
  ."`code` varchar(6) NOT NULL default '',"
  ."`number` decimal(10,0) NOT NULL default '0',"
  ."`operatorname` varchar(40) NOT NULL default '',"
  ."`operatorlatin` varchar(40) NOT NULL default '',"
  ."`abonentprice` varchar(40) NOT NULL default '',"
  ."`price` decimal(10,2) NOT NULL default '0.00',"
  ."`currency` varchar(10) NOT NULL default '',"
  ."`usdprice` decimal(10,2) NOT NULL default '0.00',"
  ."`clientprofit` decimal(10,2) NOT NULL default '0.00',"
  ."`clientprofitusd` decimal(10,2) NOT NULL default '0.00',"
  ."`prefixesallowed` varchar(50) NOT NULL default '',"
  ."`country` varchar(40) NOT NULL default '',"
  ."KEY `IDXOPERATOR` (`operatorname`),"
  ."KEY `IDXNUMBER` (`number`),"
  ."KEY `IDXCOUNTRY` (`country`)"
.") ENGINE=MyISAM DEFAULT CHARSET=utf8;"
;
$r=mysql_query($SQL, $conf[DB]);

$SQL = "CREATE TABLE `$conf[DB_PREFIX]tmpfile_rel_order` ("
  ."`order_id` int(11) NOT NULL default '0',"
  ."`goods_id` int(11) NOT NULL default '0',"
  ."`file` text NOT NULL,"
  ."`date` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."`id` int(12) NOT NULL auto_increment,"
  ."PRIMARY KEY  (`id`)"
.") ENGINE=MyISAM CHARACTER SET utf8";

$r=mysql_query($SQL, $conf[DB]);


$SQL = "CREATE TABLE `$conf[DB_PREFIX]userboard` ("
  ."`board_id` int(11) NOT NULL auto_increment,"
  ."`date` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."`message` text NOT NULL,"
  ."`author` int(11) NOT NULL default '0',"
  ."`target` int(11) NOT NULL default '0', "
  ."PRIMARY KEY  (`board_id`)"
.") ENGINE=MyISAM CHARACTER SET utf8;";

$r=mysql_query($SQL, $conf[DB]);

$SQL = "CREATE TABLE `$conf[DB_PREFIX]usermessages` ("
 ." `id` int(11) NOT NULL auto_increment,"
 ." `user_from` int(11) NOT NULL default '0',"
  ."`user_to` int(11) NOT NULL default '0',"
  ."`date` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."`message` text NOT NULL,"
 ." PRIMARY KEY  (`id`)"
.") ENGINE=MyISAM CHARACTER SET utf8;";
$r= mysql_query($SQL, $conf[DB]);


$SQL = "CREATE TABLE `$conf[DB_PREFIX]usernews` ("
 ." `id` int(11) NOT NULL auto_increment,"
 ." `user` int(11) NOT NULL default '0',"
  ."`name` varchar(200) NOT NULL default '',"
 ." `date` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."`header` text NOT NULL,"
  ."`text` text NOT NULL,"
  ."PRIMARY KEY  (`id`)"
.") ENGINE=MyISAM CHARACTER SET utf8;" ;

$r= mysql_query($SQL, $conf[DB]);

$SQL = "CREATE TABLE `$conf[DB_PREFIX]useroffer` ("
  ."`offer_id` int(10) NOT NULL auto_increment,"
  ."`user_id` int(10) NOT NULL default '0',"
  ."`catalog_id` int(10) NOT NULL default '0',"
  ."`description` text NOT NULL,"
  ."`price` decimal(10,2) NOT NULL default '0.00',"
  ."`date` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."PRIMARY KEY  (`offer_id`)"
.") ENGINE=MyISAM CHARACTER SET utf8;";

    $r=mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]subjects` ("
    ."`ID` int(10) unsigned NOT NULL auto_increment,"
    ."`NAME` varchar(145) NOT NULL default '',"
    ."PRIMARY KEY  (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8";
    $r= mysql_query($SQL, $conf[DB]);
    
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]catalog` ("
    ."`COUNT` int(11) NOT NULL default '0',"
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`ORDER` int(10) unsigned default '0',"
  ."`TITLE` varchar(200) default '',"
  ."`WEBTITLE` varchar(100) default '',"
  ."`TYPE` int(11) NOT NULL default '0',"
  ."`CONTENT` mediumtext,"
  ."`HEADER` mediumtext,"
  ."`DESC` mediumtext,"
  ."`KW` mediumtext,"
  ."`PARENT` int(11) NOT NULL default '0',"
  ."`ACTIVE` int(10) unsigned NOT NULL default '1',"
  ."`FIRM` int(10) unsigned NOT NULL default '0',"
  ."`HOT` int(10) unsigned default '0',"
  ."`INPRICE` int(11) NOT NULL default '0',"
  ."`PRICE` decimal(10,2) NOT NULL default '0.00',"
  ."`ARTICUL` varchar(20) default 'NONE',"
  ."`oldprice` decimal(10,2) NOT NULL default '0.00',"
  ."`file` text NOT NULL,"
  ."`TMP` int(11) NOT NULL default '0',"
  ."`TMP0` int(11) NOT NULL default '0',"
  ."`TMP1` int(11) NOT NULL default '0',"
  ."`TMP2` int(11) NOT NULL default '0',"
  ."`virtual` int(11) NOT NULL default '0',"
  ."`pdf` int(11) NOT NULL default '0',"
  ."`partner` int(11) NOT NULL default '0',"
  ."`referal` mediumtext,"
  ."`owner` int(11) NOT NULL default '0',"
  ."PRIMARY KEY  (`ID`),"
  ."KEY `Art` (`ARTICUL`),"
  ."KEY `NAMEIDX` (`TITLE`),"
  ."KEY `PARINDEX` (`PARENT`)) ENGINE=MyISAM CHARACTER SET utf8;";

    $r=mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]zakaz` ("
    ."`USER_ID` int(11) default '0',"
    ."`ID` int(11) NOT NULL auto_increment,"
  ."`DATE_START` datetime NOT NULL default '0000-00-00 00:00:00',"
  ."`DATE_FINISH` datetime default '0000-00-00 00:00:00',"
  ."`DATE_END` datetime default '0000-00-00 00:00:00',"
  ."`STATE` varchar(30) NOT NULL default 'new',"
  ."`CONTENT` mediumtext,"
  ."`MAIL` varchar(30) default NULL,"
  ."`DATA` mediumtext,"
  ."`NAME` varchar(200) NOT NULL default '',"
  ."`TEL` varchar(200) NOT NULL default '',"
  ."`ADDR` varchar(200) NOT NULL default '',"
  ."`manager` int(10) NOT NULL default '0',"
  ."`q1` text NOT NULL,"
  ."`q2` text NOT NULL,"
  ."`q3` text NOT NULL,"
  ."`q4` text NOT NULL,"
  ."`virtual` int(11) NOT NULL default '0',"
  ."`code` int(11) NOT NULL default '0',"
  ."`parthner` int(11) NOT NULL default '0',"
  ."`referal` text NOT NULL,"
  ."`commited` tinyint(4) NOT NULL default '0',"
    ."PRIMARY KEY (`ID`)) ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]zakaz_goods` ("
    ."`CATALOG_ID` int(11) NOT NULL default '0',"
      ."`ZAKAZ_ID` int(11) NOT NULL default '0',"
      ."`COUNT` int(11) NOT NULL default '0',"
      ."`PRICE` decimal(10,2) NOT NULL default '0.00',"
      ."`VALUE` decimal(10,2) NOT NULL default '0.00', "
      ."`VALUEK` decimal(10,2) default NULL,"
      ."`ID` int(11) NOT NULL auto_increment,"
     ." `VALUEE` decimal(10,2) NOT NULL default '0.00', "
     ."PRIMARY KEY ( `ID` )"
     .") TYPE = MYISAM CHARACTER SET utf8;"
      ;
    mysql_query($SQL, $conf[DB]);


    $SQL = "CREATE TABLE `$conf[DB_PREFIX]zakaz_goodsinfo` (".
        "`CATALOG_ID` INT NOT NULL ,"
        ."`NAME` varchar(200),".
        ") TYPE = MYISAM CHARACTER SET utf8;"
      ;
    mysql_query($SQL, $conf[DB]);


    $SQL ="CREATE TABLE `$conf[DB_PREFIX]catalog_properties` ("
      ."`ID` int(11) NOT NULL auto_increment,"
      ."`NAME` varchar(200) NOT NULL default '',"
      ."`TYPE` int(11) NOT NULL default '1',"
      ."`ORDER` int(11) default '0',"
      ."`CATALOG_ID` int(11) NOT NULL default '0',"
      ."PRIMARY KEY  (`ID`)"
    .") TYPE = MYISAM CHARACTER SET utf8;"
    ; 
    mysql_query($SQL, $conf[DB]);


        $SQL = "CREATE TABLE `$conf[DB_PREFIX]shop_mail` ("
          ."`ID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,"
        ."`EMAIL` VARCHAR(45) NOT NULL, "
        ."`MESSAGE` MEDIUMTEXT NOT NULL, "
        ."`DATE` TIMESTAMP NOT NULL DEFAULT 'now()', "
        ." PRIMARY KEY(`ID`)) TYPE = MYISAM CHARACTER SET utf8;"
        ;
    mysql_query($SQL, $conf[DB]);

    
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]catalog_values` ("
      ."`CATALOG_ID` INT NOT NULL ,"
      ."`PROPERTY_ID` INT NOT NULL ,"
      ."`VARCHAR` VARCHAR( 200 ) NOT NULL ,"
      ."`DOUBLE` DOUBLE NOT NULL ,"
      ."`DATETIME` DATETIME NOT NULL ,"
      ."`ID` INT UNSIGNED NOT NULL auto_increment,"
      ."PRIMARY KEY ( `ID` ) ,"
      ."INDEX ( `CATALOG_ID` , `PEROPERTY_ID` )"
      .") TYPE = MYISAM CHARACTER SET utf8;"
      ;
    mysql_query($SQL, $conf[DB]);
    
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]shopblock` ("
     ."`ID` int(11) NOT NULL auto_increment,"
     ."`GOODSID` varchar(20) NOT NULL default '0',"
     ."PRIMARY KEY (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);
    
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]catalog_firms` ("
      ."`ID` int(10) unsigned NOT NULL auto_increment,"
      ."`NAME` varchar(145) NOT NULL default '',"
      ."`CONTENT` MEDIUMTEXT NOT NULL, "
      ."PRIMARY KEY (`ID`)"
      .") ENGINE=MyISAM CHARACTER SET utf8"
      ;
    if (!mysql_query($SQL, $conf[DB]))
      return 0;

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]shop_comments` ("
      ."`ID` int(11) NOT NULL auto_increment,"
      ."`DATE` datetime NOT NULL default '0000-00-00 00:00:00',"
      ."`shopID` int(11) NOT NULL default '0',"
      ."`user` int(11) NOT NULL default '0',"
      ."`TEXT` text NOT NULL,"
      ."`NAME` varchar(100) NOT NULL default '',"
      ."`MAIL` varchar(100) NOT NULL default '',"

      ."PRIMARY KEY  (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);
      
    return 1;
  }
  function printsub($i){
    $conf = $this->conf;
    global $level;

    echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td>";
    $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE `PARENT` = $i AND `TYPE`= 0 ORDER by `ORDER`";
    $level++;
    $result = mysql_query($sql, $conf[DB]);
    while ($row = mysql_fetch_assoc($result)){
      $tmpID = $row['ID'];
      $tmpType = $row['TYPE'];
      $tmp=0;   
      for ($tmp=0;$tmp<=$level;$tmp++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";

        echo "<a href=\"JavaScript:DoConfirm('Удалить?','properties.php?action=Cdel&module=shop&id=$row[ID]')\"><img src=\"images/del.gif\" border=0></a>";
        echo"<a href=\"properties.php?action=Cdown&module=shop&id=$row[ID]\"><img src=\"images/d.gif\" border=0></a>";
        echo"<a href=\"properties.php?action=Cup&module=shop&id=$row[ID]\"><img src=\"images/u.gif\" border=0></a>";
        echo"<a href=\"JavaScript:NW('properties.php?action=Clistparent&module=shop&id=$row[ID]',370,470)\"><img src=\"images/parent.gif\" border=0></a>";
        echo"<a href=\"properties.php?action=edit&module=shop&id=$row[ID]\">$row[TITLE]</a>";

      $this->printsub($tmpID);
    }
    mysql_free_result($result);
    $level--;
    echo "</td></tr></table>";
  }
//*********************************************************************
  function printCatalogListParent($i, $exlude){
      $conf = $this->conf;
      global $level;
      $level++;
      $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE `PARENT`=$i and `TYPE` = 0 ORDER BY `ORDER` ASC";
      $result = mysql_query($sql, $conf[DB]);
      echo "<table><tr><td>";
      
      while ($row = mysql_fetch_assoc($result)) {
        $tmpID = $row['ID'];
        if ($tmpID !=$exlude){
          $tmp=0;
          for ($tmp=0;$tmp<=$level;$tmp++) echo "&nbsp;&nbsp;";
          echo "<a href=\"javascript:dosort('$tmpID')\">";
          echo $row['TITLE'], "</a><br>";
        }
      $this->printCatalogListParent($tmpID, $exlude);
      echo "</tr></td></table>";
    }
    mysql_free_result($result);
    $level--;
  }
  function properties(){
    $conf = $this->conf;
    $action = $_GET[action];
    $id = $_GET[id];
    $task = $_GET[task];
    
    if ($action==""){
      $action = "view";
    }
    switch ($action){
    case "reparent":
      ?><html>
      <head>
        <link href="css.css" rel="stylesheet" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script>
          function dosort(id){
      window.location = 'properties.php?action=commitreparent&module=shop&id=<?echo $_GET[id];?>&add='+id;
          }
        </script>
     <body><?
      $this->printCatalogListParent(0,"");
    break;
    case "commitreparent":
      $sql = "UPDATE $conf[DB_PREFIX]catalog SET PARENT=$_GET[add] WHERE ID=$_GET[id]";
      $result = mysql_query($sql, $conf[DB]);
      echo "<html><head><script>window.close();</script><body></body></html>";
    break;
    case "view":
      $this->printheader();
      $this->printsub(0);
      ?><center>
      Добавить новый Каталог.
      <form name=form method="POST" action="properties.php?action=edit&task=addsub&id=0&module=shop">
        <input type="text" value="Заголовок нового Каталога" class="mainoption" name ="title" size="80"><br>
        <input type="submit" value="Принять" class="mainoption">
        </form>
      </center>
      <hr>
      <?
    break;
    case "updatefirm":
      $text=addslashes($_POST['text']);
      $sql="UPDATE $conf[DB_PREFIX]catalog_firms SET `CONTENT`=\"$text\" where ID=$_GET[id]";
      mysql_query($sql, $conf[DB]);
      header ("Location: properties.php?action=editfirm&id=$id&module=shop");
    break;
    case "editfirm":    
      $sql="SELECT * FROM `$conf[DB_PREFIX]catalog_firms` WHERE ID=$id";
      $result=mysql_query($sql, $conf[DB]);
      $row=mysql_fetch_assoc($result);
      mysql_free_result ($result);
      ?><html><head><title></title>
      <link href = "css.css" rel = "stylesheet" type = "text/css">
      <meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">
      <link rel = 'stylesheet' type = 'text/css' href = 'normal.css'>
      <link href = "css.css" rel = "stylesheet" type = "text/css">
      </head>
      <body scroll = "auto">
        <center><form name = "form" method = "post" action = "properties.php?action=updatefirm&id=<?echo $id?>&module=shop">
          <table width = "100%" border = "1">
          <tr><td width = "100%">
          <?
          include("fckeditor.php");
          $sBasePath ="./";
          $oFCKeditor = new FCKeditor('text') ;
          $oFCKeditor->BasePath = $sBasePath ;
          $oFCKeditor->Value    = stripslashes($row[CONTENT]);
          $oFCKeditor->Create();
          ?>            
          </td>
          </tr></table><hr><center>
          <input name = "submit" type = "submit" class = "mainoption" value = "сохранить"></center>
          </form>
      </body></html>
    <?
    break;
    case "firm":
        $this->printheader();
        
        $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms ORDER by `NAME`";
        $res = mysql_query($SQL, $conf[DB]);
        echo "<form method=post action=\"?action=savefirms&module=shop\">";
        while ($firm=@mysql_fetch_assoc($res)){
          echo "$firm[ID].<input type=text name=firm$firm[ID] value=\"$firm[NAME]\" size=30> <a target=_blank href=\"properties.php?action=editfirm&id=$firm[ID]&module=shop\">Редактировать описание</a><br>";
        }
        echo "Добавить новую фирму: <input type=text name=\"newfirm\" size=30><br><input type=submit></form>";
      break;
      case "savefirms":
        $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms ORDER by `NAME`";
        $res = mysql_query($SQL, $conf[DB]);
        while ($firm=@mysql_fetch_assoc($res)){
          $nam = "firm$firm[ID]";
          $newname = $_POST["$nam"];
          if ($newname!=""){
            $SQL = "UPDATE $conf[DB_PREFIX]catalog_firms SET `NAME`='$newname' WHERE ID = $firm[ID]";
          }else{
            $SQL = "DELETE FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $firm[ID]";
          };
          mysql_query($SQL, $conf[DB]);
        }
        if ($_POST[newfirm]!=""){
          $SQL = "insert into $conf[DB_PREFIX]catalog_firms(`NAME`) values('$_POST[newfirm]')";
          mysql_query($SQL, $conf[DB]);
        }
        header("Location: ?action=firm&module=shop");
      break;
    case "editelement":
      $tID = $_GET[template];
      if (!$tID)
       die("template error");
    
      include_once ("core_template.php");
      $template = new Template($_GET[template]);
      $this->printheader();
      $ELEMENT    =& $template->Get("shop.element");#CORE_LOAD("shop", "element.dat");
      $ELEMENT_MORE =& $template->Get("shop.element_more");#CORE_LOAD("shop", "element_more.dat");
      $ELEMENT_MORE_PRINT =& $template->Get("shop.element_more_print");#CORE_LOAD("shop", "element_more_print.dat");
      
      $ELEMENTskidka    =& $template->Get("shop.elementskidka");#CORE_LOAD("shop", "elementskidka.dat");
      $ELEMENT_MOREskidka =& $template->Get("shop.element_moreskidka");#CORE_LOAD("shop", "element_moreskidka.dat");
      $ELEMENT_MORE_PRINTskidka =& $template->Get("shop.element_more_printskidka");#CORE_LOAD("shop", "element_more_printskidka.dat");

      $ELEMENT_MORE_ADDGOODS =& $template->Get("shop.element_more_addgoods");#CORE_LOAD("shop", "element_more_addgoods.dat");
      $ELEMENT_MORE_ADDGOODSELEMENT =& $template->Get("shop.element_more_addgoodselement");#CORE_LOAD("shop", "element_more_addgoodselement.dat");

      $OUTER      =& $template->Get("shop.outer");#CORE_LOAD("shop", "outer.dat");
      $FA       =& $template->Get("shop.fa");#CORE_LOAD("shop", "fa.dat");
      $F        =& $template->Get("shop.f");# CORE_LOAD("shop", "f.dat");

      $IP =& $template->Get("shop.inprice");# CORE_LOAD("shop","inprice.dat");
      $OP =& $template->Get("shop.offprice");# CORE_LOAD("shop","offprice.dat");
      $CALL =& $template->Get("shop.call");# CORE_LOAD("shop","call.dat");
      $NP = & $template->Get("shop.np");#CORE_LOAD("shop","np.dat");

      $VALUE      =GLOBAL_LOAD("value.dat"); 
      $VALUEE     =GLOBAL_LOAD("valuee.dat");# CORE_LOAD("shop", "valuee.dat"); //euro
      $EDIT     =& $template->Get("shop.edit");#CORE_LOAD("shop", "edit.dat");
      $VALUEK     =GLOBAL_LOAD("valuek.dat");
      
      $firmcatalogitem =& $template->Get("shop.firmcatalogitem");#= CORE_LOAD("shop","firmcatalogitem.dat");
      $firms      =& $template->Get("shop.firms");# CORE_LOAD("shop","firms.dat");
      $firmlink     =& $template->Get("shop.firmlink");# CORE_LOAD("shop","firmlink.dat");
      ?>
      <form method="POST" action="properties.php?action=saveelement&module=shop&template=<?=$_GET[template]?>">
      <TABLE border="1" width="90%" bgcolor="#CCCCCC">
        <tr>
          <td colspan="2" align="center"><h2>Элемент Магазина</h></td>
        </tr>
        <tr>
          <td>Курс</td>
          <td width="100%">
              * <input type=text value="<?=$VALUEK?>" name="VALUEK"><br>
            rub/$<input type=text value="<?=$VALUE?>" name="VALUE"><br>
            rub/euo <input type=text value="<?=$VALUEE?>" name="VALUEE"><br>
          </td>
        </tr>
        <tr>
          <td>Строка магазина<br>
          <nobr><b>%title%</b> - Заголовок<br>
          <nobr><b>%description%</b>- Описание<br>
          <nobr><b>%parent%</b>- Вышестоящий раздел<br>
          <nobr><b>%parentAddr%</b>- Адрес вышестоящего раздела раздел<br>
          <nobr><b>%price%</b>- цена<br>
          <nobr><b>%imageaddr%</b>- Адрес кртинки<br>
          <nobr><b>%viewaddr%</b>- Адрес для добавления просмотра<br>
          <nobr><b>%cartaddr%</b>- Адрес для добавления в карзину<br>
          <nobr><b>%header%</b>- Краткое Описание<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENT"><?=$ELEMENT;?></TEXTAREA></td>
        </tr>
        <tr>
          <td>Описание товара<br>
          <nobr><b>%title%</b> - Заголовок<br>
          <nobr><b>%description%</b>- Описание<br>
          <nobr><b>%price%</b>- цена<br>
          <nobr><b>%imageaddr%</b>- Адрес кртинки<br>
          <nobr><b>%cartaddr%</b>- Адрес для добавления в карзину<br>
          <nobr><b>%header%</b>- Краткое Описание<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENT_MORE"><?=$ELEMENT_MORE;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Описание товара<br>
          print
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENT_MORE_PRINT"><?=$ELEMENT_MORE_PRINT;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Строка магазина SKIDKA<br>
          <nobr><b>%title%</b> - Заголовок<br>
          <nobr><b>%description%</b>- Описание<br>
          <nobr><b>%parent%</b>- Вышестоящий раздел<br>
          <nobr><b>%parentAddr%</b>- Адрес вышестоящего раздела раздел<br>
          <nobr><b>%price%</b>- цена<br>
          <nobr><b>%imageaddr%</b>- Адрес кртинки<br>
          <nobr><b>%viewaddr%</b>- Адрес для добавления просмотра<br>
          <nobr><b>%cartaddr%</b>- Адрес для добавления в карзину<br>
          <nobr><b>%header%</b>- Краткое Описание<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENTskidka"><?=$ELEMENTskidka;?></TEXTAREA></td>
        </tr>
        <tr>
          <td>Описание товара SKIDKA<br>
          <nobr><b>%title%</b> - Заголовок<br>
          <nobr><b>%description%</b>- Описание<br>
          <nobr><b>%price%</b>- цена<br>
          <nobr><b>%imageaddr%</b>- Адрес кртинки<br>
          <nobr><b>%cartaddr%</b>- Адрес для добавления в карзину<br>
          <nobr><b>%header%</b>- Краткое Описание<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENT_MOREskidka"><?=$ELEMENT_MOREskidka;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Описание товара SKIDKA<br>
          print
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENT_MORE_PRINTskidka"><?=$ELEMENT_MORE_PRINTskidka;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Add goods form<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENT_MORE_ADDGOODS"><?=$ELEMENT_MORE_ADDGOODS;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Add goods element<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="ELEMENT_MORE_ADDGOODSELEMENT"><?=$ELEMENT_MORE_ADDGOODSELEMENT;?></TEXTAREA></td>       
        </tr>
        
        <tr>
          <td>Обрамление магазина<br>
          <nobr><b>%main%</b>- Список товаров<br>
          <nobr><b>%firm%</b>- Список фирм<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="OUTER"><?=$OUTER;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Активная фирма<br>
          <nobr><b>%title%</b>- Название фирма<br>
          <nobr><b>%link%</b>- ссылка на фирму<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="FA"><?=$FA;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Пассивная фирма<br>
          <nobr><b>%title%</b>- Название фирма<br>
          <nobr><b>%link%</b>- ссылка на фирму<br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="F"><?=$F;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Есть</td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="IP"><?=$IP;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Нет</td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="OP"><?=$OP;?></TEXTAREA></td>       
        </tr>
    <tr>
          <td>Снят с производства</td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="NP"><?=$NP;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Звоните</td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="CALL"><?=$CALL;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Элемент катлога в фирме <br>
          </td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="firmcatalogitem"><?=$firmcatalogitem;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Фирмы</td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="firms"><?=$firms;?></TEXTAREA></td>       
        </tr>
        <tr>
          <td>Ссылка на фирму</td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="firmlink"><?=$firmlink;?></TEXTAREA></td>       
        </tr>

        <tr>
          <td>Edit Description</td>
          <td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="EDIT"><?=$EDIT;?></TEXTAREA></td>       
        </tr>
      </TABLE>
      </TABLE>
      <center><INPUT type="submit" value="Принять" class="mainoption"></center>
      <FORM>
      <?
    break;
    case "saveelement":
    $tID = $_GET[template];
      if (!$tID)
       die("template error");
    
      include_once ("core_template.php");
      $template = new Template($_GET[template]);

      $template->Set("shop.element", $_POST[ELEMENT]);
      $template->Set("shop.element_more", $_POST[ELEMENT_MORE]);
      $template->Set("shop.element_more_print", $_POST[ELEMENT_MORE_PRINT]);
      $template->Set("shop.elementskidka", $_POST[ELEMENTskidka]);
      $template->Set("shop.element_moreskidka", $_POST[ELEMENT_MOREskidka]);
      $template->Set("shop.element_more_printskidka", $_POST[ELEMENT_MORE_PRINTskidka]);


      $template->Set("shop.element_more_addgoods", $_POST[ELEMENT_MORE_ADDGOODS]);
      $template->Set("shop.element_more_addgoodselement", $_POST[ELEMENT_MORE_ADDGOODSELEMENT]);

      $template->Set("shop.outer", $_POST[OUTER]);
      $template->Set("shop.fa", $_POST[FA]);
      $template->Set("shop.f", $_POST[F]);
      $template->Set("shop.inprice", $_POST[IP]);
      $template->Set("shop.offprice", $_POST[OP]);
      $template->Set("shop.np", $_POST[NP]);
      $template->Set("shop.call", $_POST[CALL]);

      
      GLOBAL_SAVE("value.dat", "$_POST[VALUE]");
      GLOBAL_SAVE("valuee.dat", "$_POST[VALUEE]");
      GLOBAL_SAVE("valuek.dat", "$_POST[VALUEK]");
                               
     $template->Set("shop.firmcatalogitem", $_POST[firmcatalogitem]);
       
      $template->Set("shop.firms", $_POST[firms]);
      $template->Set("shop.firmlink", $_POST[firmlink]);
      $template->Set("shop.edit", $_POST[EDIT]);
      $template->Save();


      header("Location: properties.php?action=editelement&module=shop&template=$_GET[template]");
    break;
    //********************************************************************************************
    case "addproperty":
      $SQL="SELECT max(`ORDER`) as `maximum` FROM `$conf[DB_PREFIX]catalog_properties` WHERE `CATALOG_ID`=$id";
      $res=@mysql_query($SQL, $conf[DB]);
      $ord=@mysql_fetch_assoc($res);
      $order=(int)$ord[maximum] + 1;
      $type=(int)+$_POST[pTYPE];


      $sql="INSERT INTO `$conf[DB_PREFIX]catalog_properties` (`NAME`, `ORDER`, `CATALOG_ID`, `TYPE`) VALUES ('$_POST[pNAME]', $order, $id, $type)";
      $r=mysql_query($sql, $conf[DB]) or die (mysql_error());
      header("Location: properties.php?action=edit&module=shop&id=82");
    break;
    case "editp":
      //TODO
      header("Location: properties.php?action=edit&module=shop&id=82");
    break;
    case "answer_message":
        if ($_GET[id]){
          echo "<form action=\"?module=shop&action=commitanswer&id=$_GET[id]\" method=POST>";
          echo "<input type=text name=\"user\" value=\"Admin\"><br><textarea cols=55 rows=6 name=answer></textarea><br><input type=submit value=\"Ответить\"></form>";
        };
    break;
    case "commitanswer":
        $sql = "select * from $conf[DB_PREFIX]shop_comments where ID=$_GET[id]";
        $res = mysql_query($sql, $conf[DB]) or die(mysql_error());
        $comm = mysql_fetch_assoc($res);
        //var_dump ($comm);

        $_SQL = "INSERT INTO `$conf[DB_PREFIX]shop_comments` (`DATE`, `shopID`, `TEXT`, `NAME`, `MAIL`)"
          ."VALUES (now(), $comm[shopID], '$_POST[answer]', '$_POST[user]', '')";
        $res = mysql_query($_SQL, $conf[DB]) or die(mysql_error()) or die (mysql_error());
        echo "<html><head><script>window.close();</script><body></body></html>";
    break;


    case "edit":
      include_once ("core_file_works.php");
      $filew = new Fileworks($_GET[id], 'catalog');
      switch ($task){
        case "userdescriptiondelete":
          
          $this->printheader();
          $SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog_description` WHERE ID=$_GET[id]";
          $result=mysql_query($SQL, $conf[DB]);// or die(mysql_error());
          $comm=mysql_fetch_assoc($result);//or die(mysql_error());

          $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[ID] AND TYPE = 'catalogD' order by ID";
          $_res=mysql_query($_sql, $conf[DB]);
          $image1=@mysql_fetch_assoc($_res);
          $image2=@mysql_fetch_assoc($_res);

          if($image1[ID])
            unlink("../files/$image1[ID].dat");
          if($image2[ID])
            unlink("../files/$image2[ID].dat");
          
          $_sql="DELETE FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[ID] AND TYPE = 'catalogD' order by ID";
          $_res=mysql_query($_sql, $conf[DB]);

          $SQL = "delete FROM `$conf[DB_PREFIX]catalog_description` WHERE ID=$_GET[id]";
          $_res=mysql_query($SQL, $conf[DB]);

          echo "<script>window.close();</script>";
          echo "<h3>Описание Товара удалено!</h3>";
        break;

        case "userdescriptionuse":
          
          $SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog_description` WHERE ID=$_GET[id]";
          $result=mysql_query($SQL, $conf[DB]);// or die(mysql_error());
          $comm=mysql_fetch_assoc($result);//or die(mysql_error());

          $SQL = "UPDATE `$conf[DB_PREFIX]catalog` SET `CONTENT`= '$comm[CONTENT]', `HEADER`= '$comm[HEADER]' WHERE ID=$comm[CATALOG_ID]";
          $result=mysql_query($SQL, $conf[DB]);// or die(mysql_error());


          $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[CATALOG_ID] AND TYPE = 'catalog' order by ID";
          $_res=mysql_query($_sql, $conf[DB]);
          $image1=@mysql_fetch_assoc($_res);
          $image2=@mysql_fetch_assoc($_res);


          $_sql="delete FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[CATALOG_ID] AND TYPE = 'catalog' order by ID";
          $_res=mysql_query($_sql, $conf[DB]);

          if($image1[ID])
            @unlink("../files/$image1[ID].dat");
          if($image2[ID])
            @unlink("../files/$image2[ID].dat");

          

          //copy files
          $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[ID] AND TYPE = 'catalogD' order by ID";
          $_res=mysql_query($_sql, $conf[DB]);
          $image12=@mysql_fetch_assoc($_res);
          $image22=@mysql_fetch_assoc($_res);

          $_sql="deltete FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[CATALOG_ID] AND TYPE = 'catalog' order by ID";
          $_res=mysql_query($_sql, $conf[DB]);

//          if($image12[ID])
//            unlink("../files/$image12[ID].dat");
//          if($image22[ID])  
//            unlink("../files/$image22[ID].dat");

          $query="INSERT INTO `$conf[DB_PREFIX]files` (`NAME`,`MIME`,`SIZE`,`DESC`,`COUNT`, `PARENT`, `TYPE`)" 
          ."values ('$image12[NAME]','$image12[MIME]','$image12[SIZE]','0', 1,  $comm[CATALOG_ID], 'catalog');";
          $r = mysql_query($query, $conf[DB]) or die(mysql_error());
          $id1=mysql_insert_id($conf[DB]);

          $query="INSERT INTO `$conf[DB_PREFIX]files` (`NAME`,`MIME`,`SIZE`,`DESC`,`COUNT`, `PARENT`, `TYPE`)" 
          ."values ('$image22[NAME]','$image22[MIME]','$image12[SIZE]','0', 1,  $comm[CATALOG_ID], 'catalog');";
          $r = mysql_query($query, $conf[DB]) or die(mysql_error());
          $id2=mysql_insert_id($conf[DB]);

          copy("../files/$image12[ID].dat", "../files/$id1.dat");
          copy("../files/$image22[ID].dat", "../files/$id2.dat");
          echo "<h3>Описание Товара измененео!</h3>";

        break;
        case "userdescriptionedit":
          $this->printheader();
          $SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog_description` WHERE ID=$_GET[id]";
          $result=mysql_query($SQL, $conf[DB]);// or die(mysql_error());
          $comm=mysql_fetch_assoc($result);//or die(mysql_error());
          $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[ID] AND TYPE = 'catalogD' order by ID";
          @$_res=mysql_query($_sql, $conf[DB]);
          @$image1=mysql_fetch_assoc($_res);
          //var_dump($comm);

          $SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$comm[CATALOG_ID]";
          $result=mysql_query($SQL, $conf[DB]);// or die(mysql_error());
          $cat = mysql_fetch_assoc($result);//or die(mysql_error());
          $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$comm[CATALOG_ID] AND TYPE = 'catalog' order by ID";
          @$_res=mysql_query($_sql, $conf[DB]);
          @$image2=mysql_fetch_assoc($_res);
          $_text="<table border=1 width=100%><tr>";


          $_text.= "<tr><td width=50%><div contenteditable style=\"width:100%;height:60px;overflow: auto;\">$comm[HEADER]</div></td>";
          $_text.= "<td><div contenteditable style=\"width:100%;height:60px;overflow: auto;\">$cat[HEADER]</div></td>";

          $_text.="</tr><tr>";

          $_text.= "<tr><td width=50%><div contenteditable style=\"width:100%;height:160px;overflow: auto;\">$comm[HEADER]</div><img src=\"../files/$image1[ID]\"></td>";
          $_text.= "<td><div contenteditable style=\"width:100%%;height:160px;overflow: auto;\">$cat[CONTENT]</div><img src=\"../files/$image2[ID]\"></td>";

          $_text.="</tr></table>";
          
          echo $_text;

          echo "<center><br><a href=\"?action=edit&task=userdescriptionuse&module=shop&id=$comm[ID]\"\">Принять</a></br></center>";
          echo "<center><br><a href=\"?action=edit&task=userdescriptiondelete&module=shop&id=$comm[ID]\">Удалить</a></br></center>";
          echo "<center><br><a href=\"JavaScript:window.close();\">Закрыть</a></br></center>";

        break;
        case "userdescription":
          $this->printheader();

          $page = $_GET[page];
          $page=(int) 20*$page;
          $sql = "SELECT count(`ID`) as `coun` FROM $conf[DB_PREFIX]catalog_description";
          $result=mysql_query($sql, $conf[DB]);
          $tmp = mysql_fetch_assoc($result);
          $coun = $tmp[coun];


          $sql = "SELECT * FROM $conf[DB_PREFIX]catalog_description ORDER BY `DATE` DESC LIMIT $page, 50 ";
          $result=mysql_query($sql, $conf[DB]);
          $coun = (int) $coun/20;
          for ($i=0;$i<=$coun; $i++){
            echo "<a href=\"?page=$i&action=edit&task=userdescription&module=shop\">$i</a> &nbsp;&nbsp;";
          }
          echo "<hr>";
          $_SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog_description` ORDER BY `DATE` DESC LIMIT $page, 50";
          $_result=@mysql_query($_SQL, $conf[DB]);
          while ($_comm=@mysql_fetch_assoc($_result)){
            $link="";
            $TEXT= stripslashes($_comm[HEADER]);
            $UID= $_comm[USER_ID];
            $_TEXT= stripslashes($_comm[TEXT]);
//            print_r($_comm);
            //$_TEXT = "<a target=blank href=\"../shop/$_comm[shopID]/\">$_TEXT</a>($_comm[DATE])";
            $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$_comm[ID] AND TYPE = 'catalogD' order by ID";

            $_res=mysql_query($_sql, $conf[DB]);
            @$_image=mysql_fetch_assoc($_res);
            
            $SQL = "select * from `$conf[DB_PREFIX]accounts` WHERE ID=$UID";
            $_result2=@mysql_query($SQL, $conf[DB]);
            $user=@mysql_fetch_assoc($_result2);
            

//            $link="<a target=blank href=\"http://muzbazar.ru/admin/properties.php?action=editarticle&module=shop&id=$_comm[CATALOG_ID]\">Edit</a>";
            $link.="<br><a target=blank href=\"?action=edit&task=userdescriptionedit&module=shop&id=$_comm[ID]\">Edit</a>";


            $_text.= "<tr><td><div contenteditable style=\"width:600px;height:70px;overflow: auto;\">$TEXT</div></td><td><img src=\"../files/$_image[ID]\"></td>";
            $_text.= "<td>$user[EMAIL]<br>$link</td>";
            $_text.="</tr>";

          };
          echo "<table border=1>$_text</table>";//"<form action=\"?action=edit&task=deletecomments&module=shop\" method=post><ul>$_comment</ul><input type= submit></form>";
        break;
        case "comments":
          $this->printheader();

          $page = $_GET[page];
          $page=(int) 40*$page;
          $sql = "SELECT count(`ID`) as `coun` FROM $conf[DB_PREFIX]shop_comments";
          $result=mysql_query($sql, $conf[DB]);
          $tmp = mysql_fetch_assoc($result);
          $coun = $tmp[coun];
    
  
          $sql = "SELECT * FROM $conf[DB_PREFIX]shop_comments ORDER BY `DATE` DESC LIMIT $page, 50 ";
          $result=mysql_query($sql, $conf[DB]);
          $coun = (int) $coun/40;
          for ($i=0;$i<=$coun; $i++){
            echo "<a href=\"?page=$i&action=edit&task=comments&module=shop\">$i</a> &nbsp;&nbsp;";
          }
          echo "<hr>";
          $_SQL = "SELECT * FROM `$conf[DB_PREFIX]shop_comments` ORDER BY `DATE` DESC LIMIT $page, 50";
          $_result=@mysql_query($_SQL, $conf[DB]);
          while ($_comm=@mysql_fetch_assoc($_result)){
            $_NAME= stripslashes($_comm[NAME]);
            $_MAIL= stripslashes($_comm[MAIL]);
            $_TEXT= stripslashes($_comm[TEXT]);
//            print_r($_comm);
            $_TEXT = "<a target=blank href=\"../shop/$_comm[shopID]/\">$_TEXT</a>($_comm[DATE]) <a href=\"JavaScript:NW('properties.php?module=shop&action=answer_message&id=$_comm[ID]', 400, 500)\">^^^^</a>";
            $_comment .= "<li><input type=checkbox name=\"comment$_comm[ID]\">$_TEXT</li>";
          };
          echo "<form action=\"?action=edit&task=deletecomments&module=shop\" method=post><ul>$_comment</ul><input type= submit></form>";
        break;
        case "deletecomments":
          $_SQL = "SELECT * FROM `$conf[DB_PREFIX]shop_comments`";
          $_result=@mysql_query($_SQL, $conf[DB]);
          while($_comm=@mysql_fetch_assoc($_result)){
            if ($_POST["comment$_comm[ID]"]=="on"){
              mysql_query("DELETE FROM `$conf[DB_PREFIX]shop_comments` WHERE ID=$_comm[ID]", $conf[DB]);
            };
          };
          header("Location: ?action=edit&task=comments&module=shop");

        break;
        case "offers":
          $this->printheader();

          $page = $_GET[page];
          $page=(int) 20*$page;
          $sql = "SELECT count(*) as `coun` FROM $conf[DB_PREFIX]useroffer";
          $result=mysql_query($sql, $conf[DB]);
          $tmp = mysql_fetch_assoc($result);
          $coun = $tmp[coun];
    
  
          $sql = "SELECT * FROM $conf[DB_PREFIX]useroffer ORDER BY `DATE` DESC LIMIT $page, 50 ";
          $result=mysql_query($sql, $conf[DB]);
          $coun = (int) $coun/20;
          for ($i=0;$i<=$coun; $i++){
            echo "<a href=\"?page=$i&action=edit&task=offers&module=shop\">$i</a> &nbsp;&nbsp;";
          }
          echo "<hr>";
          $_SQL = "SELECT * FROM `$conf[DB_PREFIX]useroffer` ORDER BY `DATE` DESC LIMIT $page, 50";
          $_result=@mysql_query($_SQL, $conf[DB]);
          while ($_comm=@mysql_fetch_assoc($_result)){
            $_TEXT= stripslashes($_comm['description']);
//            print_r($_comm);
            $_TEXT = "<a target=blank href=\"../shop/$_comm[catalog_id]/\">$_TEXT</a>($_comm[date])";
            $_comment .= "<li><input type=checkbox name=\"comment$_comm[offer_id]\">$_TEXT</li>";
          };
          echo "<form action=\"?action=edit&task=deleteoffers&module=shop\" method=post><ul>$_comment</ul><input type= submit value=\"Delete\"></form>";
        break;
        case "deleteoffers":
          $_SQL = "SELECT * FROM `$conf[DB_PREFIX]useroffer`";
          $_result=@mysql_query($_SQL, $conf[DB]);
          while($_comm=@mysql_fetch_assoc($_result)){
            if ($_POST["comment$_comm[offer_id]"]=="on"){
              mysql_query("DELETE FROM `$conf[DB_PREFIX]useroffer` WHERE offer_id=$_comm[offer_id]", $conf[DB]);
            };
          };
          header("Location: ?action=edit&task=offers&module=shop");

        break;
        case "addsub":
          $TITLE = explode(";", $_POST[title]);
          foreach ($TITLE as $title)
          {
            $SQL = "SELECT max(`ORDER`) as `maximum` FROM `$conf[DB_PREFIX]catalog` WHERE `PARENT`=$id";
            $res = mysql_query($SQL, $conf[DB]);
            $ord=mysql_fetch_assoc($res);
            $order = 0 + (int)$ord[maximum]+1;
            $sql = "INSERT INTO `$conf[DB_PREFIX]catalog` (`ORDER`, `ID`, `TITLE`, `TYPE`, `HEADER`, `CONTENT`, `PARENT`) VALUES ($order, NULL, '$title', 0, '', '', $id)";
            mysql_query($sql, $conf[DB]);
          };
          header("Location: properties.php?action=view&module=shop");
        break;
        case "byfirm":
          $this->printheader();
          
          $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms ORDER by `NAME`";
          $res = mysql_query($SQL, $conf[DB]);
          while ($firm=@mysql_fetch_assoc($res)){
            if ($firm[ID]==$_GET[firm]){
              echo "<a href=\"properties.php?action=edit&task=byfirm&module=shop&firm=$firm[ID]\"><b>$firm[NAME]</b></a> ";
            }else{
              echo "<a href=\"properties.php?action=edit&task=byfirm&module=shop&firm=$firm[ID]\">$firm[NAME]</a> ";
            };
          }
          
          switch ($_GET[order]){
            case "price":
              $order= 'PRICE';
            break;
            default:
              $order= 'TITLE';
            break;
          }

          
          if ($_GET[firm]!=''){
            $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE FIRM=$_GET[firm] ORDER BY `$order`";
            $_result = mysql_query($sql, $conf[DB]) or die(mysql_error());
//            echo "<hr><a href=\"?action=edit&task=byfirm&module=shop&firm=59&order=name\">Normal</a><a href=\"?action=edit&task=byfirm&module=shop&firm=$_GET[firm]&order=price\">Price</a><hr>";
            echo "<form action =\"properties.php?action=globalupdatefirmPrice&module=shop&firm=$_GET[firm]\" method=post>";
            echo "Change price <input type=text value name=percent><input type=submit>";
            echo "</form>";

            echo "<form action =\"properties.php?action=globalupdatefirm&module=shop&id=$id&firm=$_GET[firm]\" method=post>";
            echo "<table border=1 width=100%>";
            $i = 0;
            while (@$row=mysql_fetch_assoc($_result)){
              $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $row[PARENT]";
              $tmpres = mysql_query($tmpsql, $conf[DB]);
              $tmpcat = mysql_fetch_assoc($tmpres);
              $tmpcat = $tmpcat[TITLE];
              $tmpID = $row[ID];
              echo '<tr>';

              echo '<td>';
              echo "<input type=text name=\"articul$tmpID\" value =\"$row[ARTICUL]\" size=6><input type=text name=\"title$tmpID\" value =\"$row[TITLE]\" size=50>, <input name=\"price$tmpID\" type=text value =\"$row[PRICE]\" size=10>";
              echo "Hot:<input type = \"checkbox\" name = \"hot$tmpID\" value = \"1\" ";
                if ($row['HOT'] == 1)
                  echo " checked";
              echo '>| ';
              
              echo "Вкл<input type = \"checkbox\" name = \"active$tmpID\" value = \"1\" ";
                if ($row['ACTIVE'] == 1)
                  echo " checked";
              echo '>| ';

              if($row['INPRICE']=="0"){
                echo "<label for=\"_0inprice$tmpID\">Есть</label><input type=\"radio\" name=\"inprice$tmpID\" value=0 id=\"_0inprice$tmpID\" checked>|";
              }else{
                echo "<label for=\"_0inprice$tmpID\">Есть</label><input type=\"radio\" name=\"inprice$tmpID\" value=0 id=\"_0inprice$tmpID\" >|";
              }
              if($row['INPRICE']=="1"){
                echo "<label for=\"_1inprice$tmpID\">Звоните</label><input type=\"radio\" name=\"inprice$tmpID\" value=1 id=\"_1inprice$tmpID\" checked>|";
              }else{
                echo "<label for=\"_1inprice$tmpID\">Звоните</label><input type=\"radio\" name=\"inprice$tmpID\" value=1 id=\"_1inprice$tmpID\">|";
              }

              if($row['INPRICE']=="2"){
                echo "<label for=\"_2inprice$tmpID\">Нет</label><input type=\"radio\" name=\"inprice$tmpID\" value=2 id=\"_2inprice$tmpID\" checked>|";
              }else{
                echo "<label for=\"_2inprice$tmpID\">Нет</label><input type=\"radio\" name=\"inprice$tmpID\" value=2 id=\"_2inprice$tmpID\">|";
              }
        if($row['INPRICE']=="3"){
                echo "<label for=\"_3inprice$tmpID\">Снят с пр</label><input type=\"radio\" name=\"inprice$tmpID\" value=3 id=\"_3inprice$tmpID\" checked>";
              }else{
                echo "<label for=\"_3inprice$tmpID\">Снят с пр</label><input type=\"radio\" name=\"inprice$tmpID\" value=3 id=\"_3inprice$tmpID\">";
              }
 
              echo '<br>';
              echo stripslashes($row['HEADER']) . "<a href=\"properties.php?action=editarticle&module=shop&id=$tmpID\">Изменить</a><br>";
        echo '<br>';
              
              echo '<a href="JavaScript:NW(\'properties.php?action=reparent&module=shop&id='.$row[ID]."',400,470)\">Переподчиниить</a><br>";
              echo "</td>";
              echo '</tr>';
            }
            echo '</table><input type=submit></form>';
          }
        break;
        case "":
          $this->printheader();
          ?>
          <html> 
          <head>
            <title></title> 
            <link rel = 'stylesheet' type = 'text/css' href = 'normal.css'>
            <link href="css.css" rel="stylesheet" type="text/css">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <body>
          <?
          ?><center>
              Название
              <form name=form method="POST" action="properties.php?action=edit&module=shop&task=update&id=<?echo $id?>">
                <input type="text" value="<? echo $catItem['TITLE']?>" class="mainoption" name ="title" size="80"><br>
                <textarea id="text" name = "text" rows = "10" cols = "80"><? echo stripslashes($catItem['CONTENT'])?></textarea><br>
                <input type="submit" value="Принять" class="mainoption">
              </form>
            </center><br>
            <center>
            <table border=1>
            <?
            $sql="select * from `$conf[DB_PREFIX]catalog_properties` WHERE `CATALOG_ID` = $id";
            $result=mysql_query($sql, $conf[DB]);
            echo "<tr><td colspan=\"2\"><table>";
            echo "<tr><td>Add new Properties</td><td><form name=\"forma\" method = \"post\" action=\"?id=$id&module=shop&action=addproperty\">";
            echo "<select name=pTYPE><option value=\"1\">Text</op[tion><option value=\"2\">Number</op[tion><option value=\"3\">DATETIME</op[tion></select>";
            echo "<input type=text name=\"pNAME\"><input type=\"submit\" value = \"add!\"></form></td></tr>";
            echo "<form method = \"post\" name=\"form\" action=\"?a=updatep&id=$id&module=shop\">";
            ?><tr><td>NAME</td><td>Type</td></tr><?
            while ($property=mysql_fetch_assoc($result)){
              echo "<tr>";
//                echo "<td><a href=\"JavaScript:DoConfirm('Вы действительно зхотите удалить данный Ответ?','?a=Qdel&id=$id&add=$variant[ID]&module=vote')\"><img src=\"images/del.gif\" border=0></a></td>";
              echo "<td><input size=\"40\" name = \"pNAME$property[ID]\" type=text value=\"$property[NAME]\"></td>";
              echo "<td><input size=\"0\" name = \"pTYPE$property[ID]\" type=text value=\"$property[TYPE]\" ></td>";
              echo "</tr>";
            }
          echo "</tr></td></table>";
          echo "<input type=\"submit\" value = \"Принять\" class=\"mainoption\">";
          echo "</form>";
          echo "</table>";?>
          </center><center>
              Добавить новый подкаталог.
              <form name=form method="POST" action="properties.php?action=edit&module=shop&task=addsub&id=<?echo $id?>">
                <input type="text" value="Заголовок нового Каталога" class="mainoption" name ="title" size="80"><br>
                <input type="submit" value="Принять" class="mainoption">
              </form>
            </center>

            <center>
              Добавить новый товар.
                <form name=form method="POST" action="properties.php?action=editarticle&module=shop&task=add&id=<?echo $id?>">
                <input type="text" value="Заголовок нового товара" class="mainoption" name ="title" size="80"><br>
                <input type="submit" value="Принять" class="mainoption">
              </form>
            </center><?
          $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=$id";                    
          $result = mysql_query($sql, $conf[DB]); 

          if ($catItem = mysql_fetch_assoc($result)) {

            $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE `ID`=$catItem[PARENT]";
            $result = mysql_query($sql, $conf[DB]);
            $region = mysql_fetch_assoc($result);

            $tmpreg = $region;
            $tmp[] = array ("<font size=3>$tmpreg[TITLE]</strong>", $tmpreg[ID]);

            while ($tmpreg[PARENT] != 0) {
              $SQL = "SELECT TITLE, ID, PARENT FROM $conf[DB_PREFIX]catalog WHERE ID=$tmpreg[PARENT]";
              $result = mysql_query($SQL, $conf[DB]);
              $tmpreg = mysql_fetch_assoc($result);
              $tmp[] = array ($tmpreg[TITLE], $tmpreg[ID]);
            };

            $tmp = array_reverse($tmp);
            foreach ($tmp as $reg) {
              $data .= "-><a target=\"contentFrame\" href=\"properties.php?action=edit&module=shop&id=$reg[1]\">$reg[0]</a>";
            };
            $data .= "-><a target=\"contentFrame\" href=\"properties.php?action=edit&module=shop&id=$catItem[ID]\"><b>$catItem[TITLE]</b></a>";
            echo $data;

            $tmpID = $catItem['ID'];
            $text = $catItem[CONTENT];

            $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE TYPE=1 AND PARENT=$id ORDER by `ORDER` DESC";           

            $result = mysql_query($sql, $conf[DB]);
            echo "<form action =\"properties.php?action=globalupdate&module=shop&id=$id\" method=post>";
            echo "<table border=1 width=100%>";
            $i = 0;
            while ($row = mysql_fetch_assoc($result)) {
              if ($i==1)
                $i=0;
              $tmpID = $row['ID'];
              if ($i==0)
                echo '<tr>';
              echo '<td>';
              echo "<input type=text name=\"articul$tmpID\" value =\"$row[ARTICUL]\" size=6>, <input type=text name=\"title$tmpID\" value =\"$row[TITLE]\" size=50>, <input id=\"price$tmpID\" name=\"price$tmpID\" type=text value =\"$row[PRICE]\" size=10>";
              //$VALUEE     = CORE_LOAD("shop", "valuee.dat"); //euro
              echo "<input type = \"checkbox\" name = \"hot$tmpID\" value = \"1\" ";
                if ($row['HOT'] == 1)
                  echo " checked";
              echo '>Hot | ';
              
               echo "Вкл: <input type = \"checkbox\" name = \"active$tmpID\" value = \"1\" ";
                if ($row['ACTIVE'] == 1)
                  echo " checked";
              echo '>|';

              if($row['INPRICE']=="0"){
                echo " <label for=\"_0inprice$tmpID\">Есть</label><input type=\"radio\" name=\"inprice$tmpID\" value=0 id=\"_0inprice$tmpID\" checked>|";
              }else{
                echo " <label for=\"_0inprice$tmpID\">Есть</label><input type=\"radio\" name=\"inprice$tmpID\" value=0 id=\"_0inprice$tmpID\" >|";
              }
              if($row['INPRICE']=="1"){
                echo "<label for=\"_1inprice$tmpID\">Звоните</label><input type=\"radio\" name=\"inprice$tmpID\" value=1 id=\"_1inprice$tmpID\" checked>|";
              }else{
                echo "<label for=\"_1inprice$tmpID\">Звоните</label><input type=\"radio\" name=\"inprice$tmpID\" value=1 id=\"_1inprice$tmpID\">|";
              }

              if($row['INPRICE']=="2"){
                echo "<label for=\"_2inprice$tmpID\">Нет</label><input type=\"radio\" name=\"inprice$tmpID\" value=2 id=\"_2inprice$tmpID\" checked>|";
              }else{
                echo "<label for=\"_2inprice$tmpID\">Нет</label><input type=\"radio\" name=\"inprice$tmpID\" value=2 id=\"_2inprice$tmpID\">|";
              }
              
              if($row['INPRICE']=="3"){
                echo "<label for=\"_3inprice$tmpID\">Снято с пр</label><input type=\"radio\" name=\"inprice$tmpID\" value=3 id=\"_3inprice$tmpID\" checked>|";
              }else{
                echo "<label for=\"_3inprice$tmpID\">Снято с пр</label><input type=\"radio\" name=\"inprice$tmpID\" value=3 id=\"_3inprice$tmpID\">|";
              }

              echo '<p>'. stripslashes($row['HEADER']), "<br><a href=\"properties.php?action=editarticle&module=shop&id=$tmpID\">Изменить</a><br>";

              echo '</p>';
              
              echo '<a href="JavaScript:NW(\'properties.php?action=reparent&module=shop&id='.$row[ID]."',400,470)\">Переподчиниить</a><br>";
              //echo "<a href=\"properties.php?action=down&module=shop&id=$row[ID]\"><img src=\"images/d.gif\" border=0></a>";
              //echo "<a href=\"properties.php?action=up&module=shop&id=$row[ID]\"><img src=\"images/u.gif\" border=0></a>";
              echo "</td>";
              if ($i==0)
                echo '</tr>';
              $i++;
            }

            mysql_free_result($result);
            echo "</table><input type=submit value=\"Принять\">";
            echo "</form>";           
          }
        break;
        //****************************************************************************************************
        case "update":
          $title  = addslashes($_POST['title']);
          $text = addslashes($_POST['text']);

          $sql = "UPDATE $conf[DB_PREFIX]catalog SET TITLE='$title', `CONTENT` = '$text' where ID=$id";
          mysql_query($sql, $conf[DB]);
          header("Location:properties.php?action=edit&module=shop&id=$id");
        break;
        };
    break;
    case "globalupdate":
      $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `TYPE`=1 AND PARENT=$_GET[id]";           
      $result = mysql_query($sql, $conf[DB]);
      while ($cat = mysql_fetch_assoc($result)) {
        if ($_POST["title$cat[ID]"]!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET TITLE='".$_POST["title$cat[ID]"] ."' where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        if ($_POST["hot$cat[ID]"]!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET HOT=1 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }else{
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET HOT=0 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        if ($_POST["active$cat[ID]"]!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET ACTIVE=1 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }else{
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET ACTIVE=0 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        //if ($_POST["inprice$cat[ID]"]!=""){
        $tmpid = (int)$_POST["inprice$cat[ID]"];
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=$tmpid where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        //}else{
        //  $sql = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=0 where ID=$cat[ID]";
        //  mysql_query($sql, $conf[DB]);
        //}

        $pr =(int)$_POST["price$cat[ID]"];
        if ($pr!=0){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET PRICE=$pr where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }

        $art =$_POST["articul$cat[ID]"];
        if ($art!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET ARTICUL='$art' where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        
        header("Location:properties.php?action=edit&module=shop&id=$_GET[id]");
      };
      
    break;
    case "globalupdatefirmPrice":
        $percent = (int)$_POST[percent];
        if ($percent!=0){
            $k = $percent/100;
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET `PRICE` = `PRICE` + `PRICE` * $k WHERE `TYPE`=1 AND FIRM=$_GET[firm]";
          $result = mysql_query($sql, $conf[DB]) or die (mysql_error());
        }
        header("Location:properties.php?action=edit&task=byfirm&module=shop&firm=$_GET[firm]");
    break;
    case "globalupdatefirm":        
      $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE TYPE=1 AND FIRM=$_GET[firm]";           
      $result = mysql_query($sql, $conf[DB]);

      while ($cat = mysql_fetch_assoc($result)) {

        if ($_POST["title$cat[ID]"]!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET TITLE='".$_POST["title$cat[ID]"] ."' where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        if ($_POST["hot$cat[ID]"]!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET HOT=1 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }else{
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET HOT=0 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        if ($_POST["active$cat[ID]"]!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET ACTIVE=1 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }else{
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET ACTIVE=0 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        
        $tmpid = (int)$_POST["inprice$cat[ID]"];
        $sql = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=$tmpid where ID=$cat[ID]";
        mysql_query($sql, $conf[DB]);

        /*
        if ($_POST["inprice$cat[ID]"]!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=1 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }else{
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=0 where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        */
        
        $pr =(int)$_POST["price$cat[ID]"];
        if ($pr!=0){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET PRICE=$pr where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        
        $art =$_POST["articul$cat[ID]"];
        if ($art!=""){
          $sql = "UPDATE $conf[DB_PREFIX]catalog SET ARTICUL='$art' where ID=$cat[ID]";
          mysql_query($sql, $conf[DB]);
        }
        
        header("Location:properties.php?action=edit&task=byfirm&module=shop&firm=$_GET[firm]");
      };
      
    break;
    //*********************************************************************
    case "updatearticle":
      $title  = addslashes($_POST['title']);
      $header = addslashes($_POST['header']);
      $text = addslashes($_POST['text']);
      $price  = ($_POST['price']);
      $articul  = ($_POST['articul']);
      $firm = (int) $_POST['firm'];
      $owner= (int)$_POST[owner];
      $file =  addslashes($_POST['file']);
      $virtual =  (int)$_POST['virtual'];
      $pdf =  (int)($_POST['pdf']);
      $sql = "UPDATE $conf[DB_PREFIX]catalog SET `pdf`=$pdf, `virtual`= $virtual, `FIRM`=$firm, owner=$owner, TITLE=\"$title\", HEADER=\"$header\", PRICE=\"$price\" , CONTENT=\"$text\", `ARTICUL`='$articul', `file`= '$file' where ID=$id";

      mysql_query($sql, $conf[DB]);
      header("Location:properties.php?action=editarticleframe&module=shop&id=$id");
    break;
    //****************************************************************************************************    
    case "uploadarticlefiles":
      include_once ("core_file_works.php");
      $filew = new Fileworks($_GET[id], 'catalog');
      $filew->upload_file();
      header("Location: properties.php?action=editarticlefiles&module=shop&id=$id");
    break;
    //*********************************************************************
    case "clearticlefiles":
      include_once ("core_file_works.php");
      $filew = new Fileworks($_GET[id], 'catalog');
      $filew->clear_file();
      header("Location: properties.php?action=editarticlefiles&module=shop&id=$id");
      break;
    case "editarticlefiles":
      include_once ("core_file_works.php");
      $filew = new Fileworks($_GET[id], 'catalog');     
    ?>
      <html>
      <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel='stylesheet' type='text/css' href='normal.css'>
      </head>
      <body>
        <center>
        
        <div CONTENTEDITABLE>
        <?$filew->show_file();?>
        </div>
        
        <form method="post" action="properties.php?action=uploadarticlefiles&module=shop&id=<?echo $id?>" enctype="multipart/form-data">
          <input type="File" name="file" size="4"><br>            
          <input type="submit" value="Загрузить" class="mainoption">
        </form><br><br>
      <form method="post" action="properties.php?action=clearticlefiles&module=shop&id=<?echo $id?>">
          <input type="submit" value="Очистить" class="deloption">
      </form>
      </center>
    <?
    break;
    //*********************************************************************
    case "savevalues":
      $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE `ID`=$id";
      $result = mysql_query($sql, $conf[DB]);
      $region = mysql_fetch_assoc($result);
      $tmpreg = $region;
      while ($tmpreg[PARENT] != 0) {
        $SQL = "SELECT ID, PARENT FROM $conf[DB_PREFIX]catalog WHERE ID=$tmpreg[PARENT]";
        $result = mysql_query($SQL, $conf[DB]);
        $tmpreg = mysql_fetch_assoc($result);
        $tmp[] = $tmpreg[ID];
      };
      $tmp = array_reverse($tmp);
      
      foreach ($tmp as $tmpreg) {
        $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_properties WHERE CATALOG_ID=$tmpreg";
        $result = mysql_query($SQL, $conf[DB]) or die (mysql_error());;
        if ($result)
        while ($prop = mysql_fetch_assoc($result)){
          $SQL= "DELETE  FROM $conf[DB_PREFIX]catalog_values WHERE PROPERTY_ID=$prop[ID] and CATALOG_ID=$id";
          $result = mysql_query($SQL, $conf[DB]) or die (mysql_error());;

          $t = $_POST["prop$prop[ID]"];
          


          switch($prop[TYPE]){
          case 0:
            $SQL = "INSERT INTO $conf[DB_PREFIX]catalog_values(PROPERTY_ID, CATALOG_ID, `VARCHAR`) values ($prop[ID], $id, '$t')";
          break;
          case 1:           
              $t  = str_replace(',', '.', $t);            
              $t = (double)$t;
            $SQL = "INSERT INTO $conf[DB_PREFIX]catalog_values(PROPERTY_ID, CATALOG_ID, `DOUBLE`) values ($prop[ID], $id, $t)";
          break;
          case 2:
            $SQL = "INSERT INTO $conf[DB_PREFIX]catalog_values(PROPERTY_ID, CATALOG_ID, `DATETIME`) values ($prop[ID], $id, '$t')";

          break;

          };
          $result = mysql_query($SQL, $conf[DB]) or die (mysql_error());
        };
        
      };
      header("Location: ?action=editarticleframe&module=shop&id=$id");
    break;
    

    case "editarticleframe":
      $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=$id";
      $result = mysql_query($sql, $conf[DB]);
      echo "<center>";
      $row = mysql_fetch_assoc($result);
      mysql_free_result($result);
      include ("fckeditor.php");
    ?>
    <html> 
      <head> 
        <title></title> 
        <link href="css.css" rel="stylesheet" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel='stylesheet' type='text/css' href='normal.css'>
      <link href="css.css" rel="stylesheet" type="text/css">
      </head>

      <body scroll="auto"><?

          $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE `ID`=$row[PARENT]";
          
          $result = mysql_query($sql, $conf[DB]);
          $region = mysql_fetch_assoc($result);
          $tmpreg = $region;
          $tmp[] = array ("<font size=3>$tmpreg[TITLE]</strong>", $tmpreg[ID]);
      
          while ($tmpreg[PARENT] != 0) {
            $SQL = "SELECT TITLE, ID, PARENT FROM $conf[DB_PREFIX]catalog WHERE ID=$tmpreg[PARENT]";
            $result = mysql_query($SQL, $conf[DB]);
            $tmpreg = mysql_fetch_assoc($result);
            $tmp[] = array ($tmpreg[TITLE], $tmpreg[ID]);
          };
          
          $tmp = array_reverse($tmp);
          echo  "<form action=\"?action=savevalues&module=shop&id=$id\" method=POST><table>";
          foreach ($tmp as $reg) {
            $data .= "-><a target=\"contentFrame\" href=\"properties.php?action=edit&module=shop&id=$reg[1]\">$reg[0]</a>";
            $SQL = "SELECT * from $conf[DB_PREFIX]catalog_properties WHERE CATALOG_ID=$reg[1]";
            $result = mysql_query($SQL, $conf[DB]);
            while ($prop = mysql_fetch_assoc($result)){
            
              $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_values WHERE PROPERTY_ID=$prop[ID] and CATALOG_ID=$id";
              $result2 = mysql_query($SQL, $conf[DB]);
              $val= mysql_fetch_assoc($result2);

              switch($prop[TYPE]){
              case 0:
                $value=$val[VARCHAR];
              break;
              case 1:
                $value=$val[DOUBLE];
              break;
              case 2:
                $value=$val[DATETIME];
              break;
              };

              echo '<tr><td>' . $prop[NAME] ."</td><td> <input type=text value = \"$value\"name=\"prop$prop[ID]\"></td></tr>";
            };
          };
          echo  "</table><input type = submit></form>";
          echo $data;
      ?>
      <center>
      <form name="form" method="post" action="properties.php?action=updatearticle&module=shop&id=<?echo $id?>">
        <table width="100%" border="1">
      <tr>
       <td>Название</th>
       <td width="100%"><textarea name="title" style="WIDTH: 100%" rows="1"><?echo stripslashes($row['TITLE'])?></textarea></td>
      </tr>
      <tr>
      <td>Фирма</td>
      <td>
      <?
        echo "<select name=firm>";
          $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms ORDER by `NAME`";
          $res = mysql_query($SQL, $conf[DB]);
          while ($subject=@mysql_fetch_assoc($res)){
            $ch="";
            if ($subject[ID]==$row[FIRM])
            {
              $ch="selected";
            }
            echo "<option value =\"$subject[ID]\" $ch>$subject[NAME]</option>";
          };
        echo "</select>";
      ?>
      </td>
      </tr>
    <tr>
       <td>Заголовок</th>
       <td width="100%"><textarea name="webtitle" style="WIDTH: 100%" rows="1"><?echo stripslashes($row['TITLE'])?></textarea></td>
      </tr>
      <tr>
       <td>Краткое Описание</td>
       <td width="100%"><?
            $sBasePath ="./";
            $oFCKeditor = new FCKeditor('header') ;
            $oFCKeditor->BasePath = $sBasePath ;
            $oFCKeditor->Value    = stripslashes($row['HEADER']);
            $oFCKeditor->Create();
            ?></td>
           </tr>
           <tr>
             <td >Содержание</td>
             <td width="100%">
             <?
            $sBasePath ="./";
            $oFCKeditor = new FCKeditor('text') ;
            $oFCKeditor->BasePath = $sBasePath ;
            $oFCKeditor->Value    = stripslashes($row[CONTENT]);
            $oFCKeditor->Create();
            ?>             
             </td>
           </tr>
           <tr>
             <td>Цена - </td>           
            <?
            
            //newPrice
            $VALUE       =GLOBAL_LOAD("value.dat");#CORE_LOAD("shop", "value.dat"); //$
            $VALUEE     =GLOBAL_LOAD("valuee.dat");# CORE_LOAD("shop", "valuee.dat"); //euro
            $script="<script>vale=$VALUE;valee=$VALUEE;       
            function xget(id){
              if(document.getElementById) return document.getElementById(id);
              if(document.all) return document.all[id];
              return null;
            };


              function updateS(val){
                p2 = xget('price2');
                p3 = xget('price3');
                p2.value = val.value*vale;

                tmp=val.value*vale/valee;
                p3.value = tmp;
              };
              function updateR(val){
                p1 = xget('price');
                p3 = xget('price3');
                p1.value = val.value/vale;

                tmp=val.value/valee;
                p3.value = tmp;
              };
              function updateE(val){
                p1 = xget('price');
                p2 = xget('price2');
                p2.value = val.value*valee;

                tmp=(val.value*valee)/vale;
                p1.value = tmp;
              };
            </script>";                        
   ?>
             <td width="100%"><?echo $script?>
             
             <input onKeyDown="updateS(this)" onKeyUp="updateS(this)" size="34" type="text"  name="price" id= "price" value = "<?echo $row['PRICE']?>">
             RuR<input onKeyDown="updateR(this)" onKeyUp="updateR(this)" size="34" type="text" name="price2" id="price2" value = "<?echo $row['PRICE']*$VALUE?>">
             Euro<input onKeyDown="updateE(this)" onKeyUp="updateE(this)" size="34" type="text" name="price3" id="price3" value = "<?
             $tmp = $row['PRICE']*$VALUE / $VALUEE ;
             echo $tmp;             
             ?>"></td>
           </tr>
           <tr>
             <td>Articul - </td>
             <td width="100%"><input size="74" type="text" name="articul" value = "<?echo $row['ARTICUL']?>"></td>
           </tr>
           <tr>
             <td>File</td>
             <td width="100%"><input size="74" type="text" name="file" value = "<?echo stripslashes($row['file'])?>"></td>
           </tr>
           <tr>
             <td>virtual</td>
             <td width="100%">
             <?
             echo "<input type = \"checkbox\" name = \"virtual\" value = \"1\" ";
                if ($row['virtual'] == 1)
                  echo " checked";
             echo '> ';            
             ?>           
           </tr>
           <tr>
             <td>PDF</td>
             <td width="100%">
             <?
             echo "<input type = \"checkbox\" name = \"pdf\" value = \"1\" ";
                if ($row['pdf'] == 1)
                  echo " checked";
             echo '> ';            
             ?>           
           </tr>
           <tr>
             <td>Owner</td>
             <td width="100%"><input size="74" type="text" name="owner" value = "<?echo ($row['owner'])?>"><?
             
             if ($row['owner']){
              $SQL = "SELECT * from $conf[DB_PREFIX]accounts WHERE ID = $row[owner]";
              $r = mysql_query($SQL);
              $user=@mysql_fetch_assoc($r);

              echo "$user[LOGIN] - $user[NAME]";
             };
             
             ?></td>
           </tr>
     </table> <hr>
      <input name="submit" type="submit" class="mainoption" value="Далее">
      </form>
      <hr>
      <p align="left">
      <form name="formdd" method="post" action="properties.php?action=editarticle&module=shop&task=delete&id=<?echo $id?>">
        <input name="submit" type="submit" class="deloption" value="УДАЛ�?ТЬ" >
      </form> 
      </p>
      <?

      echo "<hr>";
      $_SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog_description` WHERE CATALOG_ID=$id  ORDER BY `DATE`";
      $_result=@mysql_query($_SQL, $conf[DB]);
      while ($_comm=@mysql_fetch_assoc($_result)){
        $TEXT= stripslashes($_comm[HEADER]);
        $UID= $_comm[USER_ID];
        $_TEXT= stripslashes($_comm[TEXT]);
        $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$_comm[ID] AND TYPE = 'catalogD' order by ID";

        $_res=mysql_query($_sql, $conf[DB]);
        @$_image=mysql_fetch_assoc($_res);
        
        $SQL = "select * from `$conf[DB_PREFIX]accounts` WHERE ID=$UID";
        $_result2=@mysql_query($SQL, $conf[DB]);
        $user=@mysql_fetch_assoc($_result2);
        $link.="<br><a target=blank href=\"?action=edit&task=userdescriptionedit&module=shop&id=$_comm[ID]\">Смотреть Комментарий</a>";

        $_text.= "<tr><td><div contenteditable style=\"width:600px;height:70px;overflow: auto;\">$TEXT</div></td><td><img src=\"../files/$_image[ID]\"></td>";
        $_text.= "<td>$user[EMAIL]<br>$link</td>";
        $_text.="</tr>";

      };
      echo "<table border=1>$_text</table>";

    break;
    //*********************************************************************
    case "editarticle":
      switch ($task){
        case "":
        ?>
        <html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">      
        </head>
        <frameset rows="*">
          <frameset cols="100%,170">
            <frame name="editframe" src="properties.php?action=editarticleframe&module=shop&id=<?echo $id?>">
            <frame name="editfilesFrame" src="properties.php?action=editarticlefiles&module=shop&id=<?echo $id?>">
          </frameset>   
        </frameset>
        </html>
        <?
        break;

        case "add":
          $order=0;
          $SQL="SELECT max(`ORDER`) as `maximum` FROM `$conf[DB_PREFIX]catalog` WHERE `PARENT`=$id and `TYPE`=1";
          @$res=mysql_query($SQL, $conf[DB]);
          @$ord=mysql_fetch_assoc($res);
          $order=0 + (int)$ord[maximum] + 1;

          $title = addslashes($_POST[title]);
          $sql = "INSERT INTO `$conf[DB_PREFIX]catalog`(`ORDER`, `ID`, `TITLE`, `TYPE`, `HEADER`, `CONTENT`, `PRICE`, `PARENT`) VALUES"
            ."($order, NULL, '$title', 1, '', '', '',$id)"
            ;
          mysql_query($sql, $conf[DB]);
          header("Location: properties.php?action=edit&module=shop&id=$id");
        break;

        case "delete":        
          $sqld = "DELETE FROM `$conf[DB_PREFIX]catalog` where ID=$id";
          $sqlc = "SELECT count(ID) AS 'count' FROM $conf[DB_PREFIX]catalog WHERE PARENT = $id";
          $result = mysql_query($sqlc, $conf[DB]);
          $row = mysql_fetch_assoc($result);
          if ($row['count']!=0){
            echo "Каталог не пуст!!!!";
          }else{
            mysql_query($sqld, $conf[DB]);
          }
          
          mysql_close($conf[DB]);
          header("Location: properties.php?action=view&module=shop");
        break;
      }
    break;
  case "on":
    $sql = "UPDATE $conf[DB_PREFIX]catalog SET `ACTIVE` = 1 WHERE `ID` = $id";
    $result = mysql_query($sql, $conf[DB]);
    
    $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID` = $id";
 $result = mysql_query($sql, $conf[DB]);
    $catalog = mysql_fetch_assoc($result);
    header("Location: properties.php?action=edit&module=shop&id=$catalog[PARENT]");
  break;
  case "off":
    $sql = "UPDATE $conf[DB_PREFIX]catalog SET `ACTIVE` = 0 WHERE `ID` = $id";
    $result = mysql_query($sql, $conf[DB]);
    
    $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID` = $id";
 $result = mysql_query($sql, $conf[DB]);
    $catalog = mysql_fetch_assoc($result);
    header("Location: properties.php?action=edit&module=shop&id=$catalog[PARENT]");
  break;
  case "up":
 $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID` = $id";
 $result = mysql_query($sql, $conf[DB]);
 $catalog = mysql_fetch_assoc($result);
 $sql = "SELECT max(`ORDER`) as `co` FROM `$conf[DB_PREFIX]catalog` where `ORDER`<$catalog[ORDER] and `PARENT`=$catalog[PARENT] and `TYPE`=1";
 if ($result5 = mysql_query($sql, $conf[DB])){
 $tmp = mysql_fetch_assoc($result5);
 $orderU = $tmp[co];
 $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` where `ORDER`=$orderU and `PARENT`=$catalog[PARENT] and `TYPE`=1";
 if ($result6 = mysql_query($sql, $conf[DB])){
 $up = mysql_fetch_assoc($result6);
 $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$catalog[ORDER] WHERE `ID`=$up[ID]";
 mysql_query($sql, $conf[DB]);
 $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$orderU WHERE `ID`=$id";
 mysql_query($sql, $conf[DB]);
 }
 }
 header("Location: properties.php?action=edit&module=shop&id=$catalog[PARENT]");
 break;
 
 case "down":
   $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID` = $id";
   $result = mysql_query($sql, $conf[DB]);
   $catalog= mysql_fetch_assoc($result);
   $sql = "SELECT min(`ORDER`) as `co` FROM `$conf[DB_PREFIX]catalog` where `ORDER`>$catalog[ORDER] and `PARENT`=$catalog[PARENT] and `TYPE`=1";
   if ($result5 = mysql_query($sql, $conf[DB])){
     $tmp = mysql_fetch_assoc($result5);
     $orderD = $tmp[co];        
     $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` where `ORDER`=$orderD and `PARENT`=$catalog[PARENT] and `TYPE`=1";
     if ($result6 = mysql_query($sql, $conf[DB])){
       $down = mysql_fetch_assoc($result6);
       $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$catalog[ORDER] WHERE `ID`=$down[ID]";
       mysql_query($sql, $conf[DB]);
       $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$orderD WHERE `ID`=$id";
       mysql_query($sql, $conf[DB]);
     };
   };
  
  header("Location: properties.php?action=edit&module=shop&id=$catalog[PARENT]");

break;

case "Cdel":
  $sql = "DELETE FROM $conf[DB_PREFIX]catalog WHERE ID=$id"; 
  $res = mysql_query($sql, $conf[DB]);
  header("Location: properties.php?module=shop");
 break;

case "Cup":
  $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID` = $id";
  $result = mysql_query($sql, $conf[DB]);
  $catalog = mysql_fetch_assoc($result);
  
  $sql = "SELECT max(`ORDER`) as `co` FROM `$conf[DB_PREFIX]catalog` where `ORDER`<$catalog[ORDER] and `PARENT`=$catalog[PARENT] and `TYPE`=0";
  if ($result = @mysql_query($sql, $conf[DB])){
     $tmp = mysql_fetch_assoc($result);
     $orderU = $tmp[co];
     $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` where `ORDER`=$orderU and `PARENT`=$catalog[PARENT] and `TYPE`=0";
     if ($result = mysql_query($sql, $conf[DB])){
       $up = mysql_fetch_assoc($result);
       
       $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$catalog[ORDER] WHERE `ID`=$up[ID]";
       mysql_query($sql, $conf[DB]);
       $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$catalog[ORDER]-1 WHERE `ID`=$id";
       mysql_query($sql, $conf[DB]);
    }
  }
  header("Location: properties.php?module=shop");
 break;
 
 case "Cdown":
   $sql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `ID` = $id";
   $result = mysql_query($sql, $conf[DB]);
   $catalog= mysql_fetch_assoc($result);
   $sql = "SELECT min(`ORDER`) as `co` FROM `$conf[DB_PREFIX]catalog` where `ORDER`>$catalog[ORDER] and `PARENT`=$catalog[PARENT] and `TYPE`=0";
   if ($result = @mysql_query($sql, $conf[DB])){
     $tmp = mysql_fetch_assoc($result);
     $orderD = $tmp[co];        
     $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` where `ORDER`=$orderD and `PARENT`=$catalog[PARENT] and `TYPE`=0";
     if ($result = mysql_query($sql, $conf[DB])){
       $down = mysql_fetch_assoc($result);
       $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$catalog[ORDER] WHERE `ID`=$down[ID]";
       mysql_query($sql, $conf[DB]);
       $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `ORDER`=$orderD WHERE `ID`=$id";
       mysql_query($sql, $conf[DB]);
     };
   };
  
   header("Location: properties.php?module=shop");

 break;
  case "Clistparent":
    ?><html>
      <head>
        <link href="css.css" rel="stylesheet" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script>
          function dosort(id){
            window.location = 'properties.php?action=Cdoparent&module=shop&module=shop&id=<?echo $id?>&add='+id;
          }
        </script>
     <body>
      <?
      echo "<a href=\"javascript:dosort('0')\">НУЛЕВОЙ УРОВЕНЬ</a><br>";
      $this->printCatalogListParent(0, $id);
      echo "</body></html>";
    break;

  case "Cdoparent":
      $add = $_GET[add];
      $sql = "SELECT max(`ORDER`) as `co` FROM `$conf[DB_PREFIX]catalog` where `PARENT`=$add";
       $max=1;
       if ($result5 = mysql_query($sql, $conf[DB])){
       if ($up = mysql_fetch_assoc($result5))
       {
        $max = $up[co]+1;
       };
      }

      $sql = "UPDATE `$conf[DB_PREFIX]catalog` SET `PARENT` = $add, `ORDER`=$max WHERE `ID`=$id";
      $result = mysql_query($sql, $conf[DB]);
      echo "<html><head><script>window.close();</script><body></body></html>";
  break;
    //********************************************************************************************
    };
  }
  function add() {
    $conf = $this->conf;
    $SQL = "INSERT INTO `$conf[DB_PREFIX]shopblock` values()";
    mysql_query($SQL, $conf[DB]);
    return mysql_insert_id($conf[DB]);
  }
  function del($id){
    return 1;
  }
  function renderEx($id, &$template){
    $IP = $template->Get("shop.inprice");#@CORE_LOAD("shop", "inprice.dat");
    $OP = $template->Get("shop.offprice");#@CORE_LOAD("shop", "offprice.dat");
    $NP = $template->Get("shop.np");#@CORE_LOAD("shop","np.dat");
    $CALL = $template->Get("shop.call");#@CORE_LOAD("shop","call.dat");
  

    $conf = $this->conf;
    $VALUE  = $template->Get("shop.value");#@CORE_LOAD("shop", "value.dat");
    $VALUE     =GLOBAL_LOAD("value.dat");
    //$VALUEK = $template->Get("shop.valuek");#@CORE_LOAD("shop", "valuek.dat");
    $VALUEK     =GLOBAL_LOAD("valuek.dat");

    if ($VALUE=="")
      $VALUE = 1;
    $path = split("/", $_GET[path]);
    if ($path[2]=="addgoods"){      
      if ($_POST['description']==""){        
        header("Location: $_SERVER[HTTP_REFERER]");
        exit();
      }
      if ((float)$_POST['price']<=0){        
        header("Location: $_SERVER[HTTP_REFERER]");
        exit();
      }
      $uid = $_SESSION[register][id];
      if (!uid){
        header("Location: $_SERVER[HTTP_REFERER]");
        exit();
      }        
      $_POST['description'] = addslashes($_POST[description]);
      $_POST[price]= str_replace(",", ".", $_POST[price]);

      $path[1] = (int) $path[1];
      $sql = "INSERT INTO `$conf[DB_PREFIX]useroffer` (`user_id` ,`catalog_id` ,`description` ,`price` ,`date`) ";
      $sql .= "VALUES ($uid, $path[1], '$_POST[description]', '$_POST[price]', NOW( ))";
      $z = mysql_query ($sql) or die (mysql_error());
      header("Location: $_SERVER[HTTP_REFERER]");
      //echo $sql;
      die();
    }

		if ($path[2]=="addcomment"){      
		  if ($_POST[COMMENT_TEXT]!=""){
		  $SQL = "CREATE TABLE `$conf[DB_PREFIX]zakaz_goodsinfo` (".
		    "`CATALOG_ID` INT NOT NULL ,"
		    ."`NAME` varchar(200)".
		    ")"
		  ;


        $_POST[COMMENT_TEXT] = htmlspecialchars($_POST[COMMENT_TEXT]);
        $_POST[COMMENT_NAME] = htmlspecialchars($_POST[COMMENT_NAME]);
        $_POST[COMMENT_EMAIL] = htmlspecialchars($_POST[COMMENT_EMAIL]);

        if (substr_count($_POST[COMMENT_TEXT], "http")!=0)
          if (substr_count($_POST[COMMENT_TEXT], "muzbazar.ru")==0)
            die();

        $_SQL = "INSERT INTO `$conf[DB_PREFIX]zakaz_goodsinfo` (`CATALOG_ID`, `NAME`)"
          . "VALUES ( $path[1], '$_POST[COMMENT_EMAIL]')";
        $res = mysql_query($_SQL, $conf[DB]) or die(mysql_error());

        $z = (int)$_SESSION[register][id];

        $_SQL = "INSERT INTO `$conf[DB_PREFIX]shop_comments` (`DATE`, `SHOPID`, `TEXT`, `NAME`, `MAIL`, `user`)"
          ."VALUES (now(), $path[1], '$_POST[COMMENT_TEXT]', '$_POST[COMMENT_NAME]', '$_POST[COMMENT_MAIL]', $z)";
        $res = mysql_query($_SQL, $conf[DB]) or die(mysql_error());
        
        $sql = "select * from $conf[DB_PREFIX]zakaz_goodsinfo where CATALOG_ID = $path[1]";        
        $res = mysql_query($sql, $conf[DB]) or die(mysql_error());
        while ($r = @mysql_fetch_assoc($res)){

          $_content.="$_POST[COMMENT_TEXT]";

          $_subject="MuzBazar";

          $_headers .= "From: MuzaBazar<sales@muzbazar.ru>\n";
          $_headers .= "X-Sender: <sales@muzbazar.ru>\n";
          $_headers .= "X-Mailer: PHP/mail()\n"; //mailer
          $_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

          $_headers .= "Return-Path: <sales@muzbazar.ru>\n";
          $_headers .= "Content-type: text/html; charset=UTF-8\r\n";    
          //echo $r[NAME];
          mail($r[NAME], "New comment on muzbazar", $_content/*, $_headers*/);
        };
        header ("Location: shop/$path[1]");
        die();
      };
      header ("Location: /shop/$path[1]");
      die();
    };
    
    if ($path[1]=="print"){
        
        $ELEMENT_MORE =& $template->Get("shop.element_more_print");#CORE_LOAD("shop","element_more_print.dat");
        
        $SQL="SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=" . (int)$path[2];

        $r= mysql_query($SQL, $conf[DB]);
        $_catalog= mysql_fetch_assoc($r);


        $_ret = $ELEMENT_MORE;
        if ($_catalog[PRICE]==0)
      $_catalog[INPRICE]=1;
          //$_ret = str_replace("%inprice%", $CALL, $_ret);
          
        $_ret = str_replace("%title%", stripslashes($_catalog[TITLE]), $_ret);
        $_ret = str_replace("%description%", stripslashes($_catalog[CONTENT]), $_ret);
        $_ret = str_replace("%price%", $this->parseprice($_catalog[PRICE])*$VALUEK, $_ret);
        $_ret = str_replace("%price2%", $this->parseprice($_catalog[PRICE])*$VALUE*$VALUEK, $_ret);
        $_ret = str_replace("%header%", stripslashes($_catalog[HEADER]), $_ret);

        if ($_catalog[PRICE]==0)
          str_replace("%inprice%", $CALL, $_ret);
        switch($_catalog[INPRICE]){
          case 0:
            $_ret = str_replace("%inprice%", $IP, $_ret);
          break;
          case 1:
            $_ret = str_replace("%inprice%", $CALL, $_ret);
          break;
      case 2:
            $_ret = str_replace("%inprice%", $OP, $_ret);
          break;
      case 3:
            $_ret = str_replace("%inprice%", $NP, $_ret);
          break;

        }
        
        $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog[PARENT]";
        $tmpres = mysql_query($tmpsql, $conf[DB]);
        $tmpcat = mysql_fetch_assoc($tmpres);
        $_ret = str_replace("%parent%", $tmpfir[TITLE], $_ret);
        $_ret = str_replace("%parentAddr%", "./shop/$tmpfir[TITLE]/", $_ret);

        $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog[FIRM]";
        $tmpres = mysql_query($tmpsql, $conf[DB]);
        $tmpfir = mysql_fetch_assoc($tmpres);
        $_ret = str_replace("%firm%", $tmpfir[NAME], $_ret);
        $_ret = str_replace("%firmid%", $tmpfir[ID], $_ret);
        $_ret = str_replace("%nameUTF%",RuEncodeUTF($tmpfir[NAME]), $_ret);

        $DATA[TITLE] ="$Gfirm[NAME] $tmpfir[NAME] $_catalog[TITLE] - $DATA[TITLE]";
        //get image addres
        $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$_catalog[ID] AND TYPE = 'catalog' order by ID";
        $_res=mysql_query($_sql, $conf[DB]);
        if (@$_image=mysql_fetch_assoc($_res))
        {
          $_IMAGE="/files/$_image[ID]";
        }else{
          $_IMAGE="/absent.jpg";
        };
        if (@$_image=mysql_fetch_assoc($_res))
        {
          $_IMAGE="/files/$_image[ID]";
        };
        
        $_ret = str_replace("%imageaddr%", $_IMAGE, $_ret);

        echo "<html>"
        ."<head><title>$DATA[TITLE]</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/></head>"
        ."<body OnLoad='javascript:print()'>$_ret</body></html>"
        ;
        die();
    };
    if ($path[1]=="firms"){
      $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms";
          
          $result=mysql_query($SQL, $conf[DB]) or die(mysql_error());
          while($reg = mysql_fetch_assoc($result)){
              if (strtolower (RuEncodeUTF($reg[NAME]))==strtolower($path[2])){
              $path[2]=$reg[ID];
                 break;
            }
      }
      if((int)$path[2]){
        $ret="";
        $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms where id=$path[2]";
        $res = mysql_query($SQL, $conf[DB]);
        $firm=@mysql_fetch_assoc($res);
        
        global $DATA;
        $DATA[TITLE] = $firm[NAME];
        $FIRM =& $template->Get("shop.firms");#CORE_LOAD("shop", "firms.dat");

        $return= str_replace("%name%",$firm[NAME], $FIRM);
        $return= str_replace("%id%",$firm[ID], $return);
        $return= str_replace("%text%",stripcslashes($firm[CONTENT]), $return);

        $CATALOG =& $template->Get("shop.firmcatalogitem");#CORE_LOAD("shop", "firmcatalogitem.dat");

        $SQL = "SELECT DISTINCT PARENT FROM muzbazar3_catalog WHERE FIRM=$path[2] and ACTIVE=1 ORDER by TITLE";
        $res = mysql_query($SQL, $conf[DB]);
        while ($cat=@mysql_fetch_assoc($res)){
          $SQL = "SELECT * FROM muzbazar3_catalog WHERE ID=$cat[PARENT] and ACTIVE=1";
          $res2 = mysql_query($SQL, $conf[DB]);
          $catalog=mysql_fetch_assoc($res2);
          
          $t = str_replace("%name%",$catalog[TITLE], $CATALOG);
          $t = str_replace("%id%",$catalog[ID], $t);
          $t = str_replace("%firm%",$path[2], $t);
          $ret.=$t;
        }
        $res = mysql_query($SQL, $conf[DB]);
        $return = str_replace("%catalogs%",$ret, $return);
        return $return;
      }else{
        $ret="";
        $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms ORDER by `NAME`";
        $res = mysql_query($SQL, $conf[DB]);
        $FIRM = "<li><a href=\"shop/firms/%nameUTF%\"> %name%</a>";
        $FIRM =&$template->Get("shop.firmlink");CORE_LOAD("shop", "firmlink.dat");
        while ($firm=@mysql_fetch_assoc($res)){
          $t = str_replace("%name%",$firm[NAME], $FIRM);
          $t = str_replace("%id%",$firm[ID], $t);
          $t = str_replace("%nameUTF%",RuEncodeUTF($firm[NAME]), $t);
          $ret.=$t;
        }
        return $ret;        
      };
    };
    if ((int)$path[1]!=0){
      //goods
      $_SQL="SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=$path[1]";
      $add = $path[1];
      $_result=mysql_query($_SQL, $conf[DB]);
      $_catalog=mysql_fetch_assoc($_result);
      
      $ELEMENT  =& $template->Get("shop.element");# CORE_LOAD("shop", "element.dat");
      $OUTER    =& $template->Get("shop.outer");# CORE_LOAD("shop", "outer.dat");
                                                   
      if ($_catalog[TYPE] == 0){
        //List Of goods
        $_pagesize= 20;
        $_page= $path[2];
        //Where conclusion
        $wherestring = " `PARENT`=$add ";
        $SQL="SELECT * from `$conf[DB_PREFIX]catalog` WHERE `PARENT`=$add and `TYPE` = 0";
        $result4=mysql_query($SQL, $conf[DB])or die(mysql_error());
        while ($catalog=mysql_fetch_assoc($result4))
        {
          $wherestring .= " OR `PARENT`= $catalog[ID] ";
          
          if ($catalog[TYPE]==0){
            $SQL="SELECT * from `$conf[DB_PREFIX]catalog` WHERE `PARENT`=$catalog[ID] and `TYPE` = 0";
            $result5=mysql_query($SQL, $conf[DB])or die(mysql_error());
            while ($catalog=mysql_fetch_assoc($result5))
            {
              $wherestring .= " OR `PARENT`= $catalog[ID] ";
              if ($catalog[TYPE]==0){
                $SQL="SELECT * from `$conf[DB_PREFIX]catalog` WHERE `PARENT`=$catalog[ID] and `TYPE` = 0";
                $result6=mysql_query($SQL, $conf[DB])or die(mysql_error());
                while ($catalog=mysql_fetch_assoc($result6)){
                  $wherestring .= " OR `PARENT`= $catalog[ID]";
                };
              };
            };
          };
        };
        //echo "$wherestring";
        if ($path[3]!=""){
        };
        $firmwhere = "";
        if ((int)$path[3]!=0){
          $firmwhere = "and `FIRM`= $path[3]";

          $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID=$path[3]";
          $res = mysql_query($SQL, $conf[DB]);
          $Gfirm=mysql_fetch_assoc($res) or die (mysql_error());
        }
        global $DATA;
        $DATA[TITLE] = "$Gfirm[NAME] $_catalog[TITLE]  $DATA[TITLE]";


        $_csql="SELECT DISTINCT `FIRM` FROM $conf[DB_PREFIX]catalog WHERE ($wherestring) AND `TYPE`=1 and `ACTIVE`=1";
        $_cresult=mysql_query($_csql, $conf[DB]);

        $firmsstr = "in(";
        while ($firm=mysql_fetch_assoc($_cresult)){
          $firmsstr .="$firm[FIRM],";
        };
        $firmsstr.=")";

        $firmsstr = str_replace(",)", ")",$firmsstr);

        $_csql="SELECT count(`ID`) as `coun` FROM $conf[DB_PREFIX]catalog WHERE ($wherestring) AND `TYPE`=1 and `ACTIVE`=1 $firmwhere";       
        $_cresult=mysql_query($_csql, $conf[DB]);
        $_ctmp=mysql_fetch_assoc($_cresult);
        $_count = $_ctmp[coun];
        $_pagescount = $_coun=(int)( $_count/ $_pagesize);
        $_atmp = "<center>";
        for ($i=0; $i<=$_pagescount;$i++)
        {
          $_t = " " . 1 + (int)$i . " ";
          if ($i==(int)$_page)
          {
            $_t = "<b>$_t</b>";
          }
          $_atmp .= "<a href=\"./shop/$add/$i/$path[3]\">$_t</a>";
        };
        $_atmp .= "</center>";
        $_start = ($_page)*$_pagesize;
        $_SQL="SELECT * FROM $conf[DB_PREFIX]catalog WHERE ($wherestring) and `TYPE`=1 and `ACTIVE`=1 $firmwhere ORDER BY INPRICE, `PRICE` asc LIMIT $_start, $_pagesize";

        $_result1=mysql_query($_SQL, $conf[DB]);
        $_ROWSET = "";
        $RETURN = str_replace("%description%",stripslashes($_catalog[CONTENT]), $RETURN);
        $_TTMP=0;
        if ($_SESSION[register][id]){
            $userid = $_SESSION[register][id];
            $SQL = "select * from $conf[DB_PREFIX]accounts where ID = $userid";
            $_userRes = mysql_query($SQL);
            $account = mysql_fetch_assoc($_userRes);
            $ELEMENT = & $template->Get("shop.elementskidka");#CORE_LOAD("shop", "elementskidka.dat");
        }

        while (@$_catalog_item=mysql_fetch_assoc($_result1))
        {
          $_TTMP=1;
          $_ret = $ELEMENT;
          $_ret = str_replace("%title%", stripslashes($_catalog_item[TITLE]), $_ret);
          $_ret = str_replace("%description%", stripslashes($_catalog_item[CONTENT]), $_ret);
          $_ret = str_replace("%header%", stripslashes($_catalog_item[HEADER]), $_ret);
          
          $priceval = $this->parseprice($_catalog_item[PRICE]);
          
          $_ret = str_replace("%price%", $priceval*$VALUEK, $_ret);
          $_ret = str_replace("%price2%", $priceval*$VALUE*$VALUEK, $_ret);

          if ($userid){
            if($account[discount_admin]!=-1){
              $skidka = $account[discount_admin];
            }else{
              $skidka = $account[discount];
            }
            $percent = (float)$priceval / 100;
            $priceval-=$percent*$skidka;


            $_ret = str_replace("%price2skidka%", $priceval*$VALUE*$VALUEK, $_ret);
            $_ret = str_replace("%discount%", $skidka, $_ret);
          }
          
          
          if($_catalog_item[PRICE]==0)
      $_catalog_item[INPRICE]=2;
            //$_ret = str_replace("%inprice%", $CALL, $_ret);
            
          switch($_catalog_item[INPRICE]){
        case 0:
        $_ret = str_replace("%inprice%", $IP, $_ret);
        break;
        case 1:
        $_ret = str_replace("%inprice%", $CALL, $_ret);
        break;
        case 2:
        $_ret = str_replace("%inprice%", $OP, $_ret);
        break;
        case 3:
        $_ret = str_replace("%inprice%", $NP, $_ret);
        break;
          }

          $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog_item[PARENT]";
          $tmpres = mysql_query($tmpsql, $conf[DB]);
          $tmpcat = mysql_fetch_assoc($tmpres);
          $_ret = str_replace("%parent%", $tmpcat[TITLE], $_ret);
          $_ret = str_replace("%parentAddr%", "./shop/$tmpcat[ID]/", $_ret);
          $_ret = str_replace("%id%",$_catalog_item[ID], $_ret);

          $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog_item[FIRM]";
          $tmpres = mysql_query($tmpsql, $conf[DB]);
          $tmpfir = mysql_fetch_assoc($tmpres);
          $_ret = str_replace("%firm%", $tmpfir[NAME], $_ret);
          $_ret = str_replace("%firmid%", $tmpfir[ID], $_ret);
          $_ret = str_replace("%nameUTF%",RuEncodeUTF($tmpfir[NAME]), $_ret);

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
        $RETURN = str_replace("%main%",$_ROWSET, $OUTER);

        //user goods list
        $ELEMENT_MORE_ADDGOODSELEMENT = & $template->Get("shop.element_more_addgoodselement");#CORE_LOAD("shop", "element_more_addgoodselement.dat");

        $selllist = "";
        $sql = "SELECT * from $conf[DB_PREFIX]useroffer where catalog_id = $_catalog[ID] order by `date`";
        $_result=@mysql_query($sql, $conf[DB]);
        
        while ($_off=@mysql_fetch_assoc($_result)){
          $offertemplate = $ELEMENT_MORE_ADDGOODSELEMENT;
          $sql = "SELECT * from $conf[DB_PREFIX]accounts where ID=$_off[user_id]";
          $_resultu=@mysql_query($sql);
          $offuser = @mysql_fetch_assoc($_resultu);

          $offertemplate = str_replace("%name%", $offuser[NAME] , $offertemplate);
          $offertemplate = str_replace("%price%", $_off[price], $offertemplate);
          $offertemplate = str_replace("%description%", stripslashes($_off[description]), $offertemplate);
          $offertemplate = str_replace("%date%", stripslashes($_off['date']), $offertemplate);

          $selllist.=$offertemplate;
        
        }
        $RETURN = str_replace("%goodslist%", $selllist, $RETURN);


        $RETURN = str_replace("%description%", stripslashes($_catalog[CONTENT]), $RETURN);
        $RETURN = $_atmp . $RETURN . $_atmp;
      }else{
        $ELEMENT_MORE =&$template->Get("shop.element_more");#CORE_LOAD("shop","element_more.dat");
        $COMMENT    = & $template->Get("shop.comment");#CORE_LOAD("shop", "comment.dat");
        $_SQL2="SELECT * FROM catalog WHERE ID=$path[1]";
        
        if ($_SESSION[register][id]){
            $userid = $_SESSION[register][id];
            $ELEMENT_MORE = & $template->Get("shop.element_moreskidka");#CORE_LOAD("shop","element_moreskidka.dat");
            $SQL = "select * from $conf[DB_PREFIX]accounts where ID = $userid";
            $_userRes = mysql_query($SQL);
            $account = mysql_fetch_assoc($_userRes);
            //$//ELEMENT = $ELEMENT  = CORE_LOAD("shop", "elementskidka.dat");
        }
        
        
        $_ret = $ELEMENT_MORE;

        global $DATA;
        $DATA[KW] .=", $_catalog[TITLE]";
        

        $_ret = str_replace("%title%", stripslashes($_catalog[TITLE]), $_ret);
        $_ret = str_replace("%description%", stripslashes($_catalog[CONTENT]), $_ret);
        $_ret = str_replace("%id%",$_catalog[ID], $_ret);
        
        $ELEMENT_MORE_ADDGOODS = & $template->Get("shop.element_more_addgoods");#CORE_LOAD("shop", "element_more_addgoods.dat");
        $ELEMENT_MORE_ADDGOODSELEMENT = & $template->Get("shop.element_more_addgoodselement");#CORE_LOAD("shop", "element_more_addgoodselement.dat");

        $ELEMENT_MORE_ADDGOODS = str_replace("%id%", $_catalog[ID], $ELEMENT_MORE_ADDGOODS);

        $_ret = str_replace("%addgoodsform%", $ELEMENT_MORE_ADDGOODS, $_ret);
        $_ret = str_replace("%user%", $account[NAME], $_ret);

        
        
        
        $selllist = "";
        $sql = "SELECT * from $conf[DB_PREFIX]useroffer where catalog_id = $_catalog[ID] order by `date`";
        $_result=@mysql_query($sql, $conf[DB]);
        
        while ($_off=@mysql_fetch_assoc($_result)){
          $offertemplate = $ELEMENT_MORE_ADDGOODSELEMENT;
          $sql = "SELECT * from $conf[DB_PREFIX]accounts where ID=$_off[user_id]";
          $_resultu=@mysql_query($sql);
          $offuser = @mysql_fetch_assoc($_resultu);

          $offertemplate = str_replace("%name%", $offuser[NAME] , $offertemplate);
          $offertemplate = str_replace("%price%", $_off[price], $offertemplate);
          $offertemplate = str_replace("%description%", stripslashes($_off[description]), $offertemplate);
          $offertemplate = str_replace("%date%", stripslashes($_off['date']), $offertemplate);

          $selllist.=$offertemplate;
        
        }
        $_ret = str_replace("%goodslist%", $selllist, $_ret);


//        $sql = "INSERT INTO `$conf[DB_PREFIX]useroffer` (`user_id` ,`catalog_id` ,`description` ,`price` ,`date`) ";
        


        $priceval = $this->parseprice($_catalog[PRICE]);
        $_ret = str_replace("%price%", $priceval*$VALUEK, $_ret);
        $_ret = str_replace("%price2%", $priceval*$VALUE*$VALUEK, $_ret);        

        if ($userid){
            if($account[discount_admin]!=-1){
              $skidka = $account[discount_admin];
            }else{
              $skidka = $account[discount];
            }
            $percent = (float)$priceval / 100;
            $priceval-=$percent*$skidka;


            $_ret = str_replace("%price2skidka%", $priceval*$VALUE*$VALUEK, $_ret);
            $_ret = str_replace("%discount%", $skidka, $_ret);
          }
          

        $_ret = str_replace("%header%", stripslashes($_catalog[HEADER]), $_ret);

        if ($priceval==0)
          $_ret = str_replace("%inprice%", $CALL, $_ret);
        switch($_catalog[INPRICE]){
      case 0:
            $_ret = str_replace("%inprice%", $IP, $_ret);
          break;
          case 1:
            $_ret = str_replace("%inprice%", $CALL, $_ret);
          break;
          case 2:
            $_ret = str_replace("%inprice%", $OP, $_ret);
          break;
          default:
            $_ret = str_replace("%inprice%", $NP, $_ret); 

        }
        
        $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog[PARENT]";
        $tmpres = mysql_query($tmpsql, $conf[DB]);
        $tmpcat = mysql_fetch_assoc($tmpres);
        $_ret = str_replace("%parent%", $tmpfir[TITLE], $_ret);
        $_ret = str_replace("%parentAddr%", "./shop/$tmpfir[TITLE]/", $_ret);

        $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog[FIRM]";
        $tmpres = mysql_query($tmpsql, $conf[DB]);
        $tmpfir = mysql_fetch_assoc($tmpres);
        $_ret = str_replace("%firm%", $tmpfir[NAME], $_ret);
        $_ret = str_replace("%firmid%", $tmpfir[ID], $_ret);
        $_ret = str_replace("%nameUTF%",RuEncodeUTF($tmpfir[NAME]), $_ret);

        $DATA[TITLE] ="$Gfirm[NAME] $tmpfir[NAME] $_catalog[TITLE] - $DATA[TITLE]";
        //get image addres
        $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$_catalog[ID] AND TYPE = 'catalog' order by ID";
        $_res=mysql_query($_sql, $conf[DB]);
        if (@$_image=mysql_fetch_assoc($_res))
        {
          $_IMAGE="./files/$_image[ID]";
        }else{
          $_IMAGE="./absent.jpg";
        };
        if (@$_image=mysql_fetch_assoc($_res))
        {
          $_IMAGE="./files/$_image[ID]";
        };
        $_ret = str_replace("%imageaddr%", $_IMAGE, $_ret);
        $_ret = str_replace("%cartaddr%", "./cart/$_catalog[ID]", $_ret);
        $_ROWSET .= $_ret;
        $RETURN = str_replace("%main%",$_ret, $OUTER);
        $_SQL = "SELECT * FROM `$conf[DB_PREFIX]shop_comments` WHERE SHOPID=$path[1] ORDER BY ID";
        $_result=@mysql_query($_SQL, $conf[DB]);

        while ($_comm=@mysql_fetch_assoc($_result)){
          $_NAME= stripslashes($_comm[NAME]);
          $_MAIL= stripslashes($_comm[MAIL]);
          $_TEXT= stripslashes($_comm[TEXT]);
          $_tmp=" ";

          if ($_comm[user]){
              $_SQL = "SELECT * FROM `$conf[DB_PREFIX]accounts` WHERE ID=$_comm[user]";
              $_resultu=mysql_query($_SQL);
              $u = mysql_fetch_assoc($_resultu);
              $_NAME = "<a href=\"/resume/$u[ID]\">$u[NAME]</a>";
              

          };
          $_tmp = str_replace("%NAME%", $_NAME, $COMMENT);
          $_tmp = str_replace("%MAIL%", $_MAIL, $_tmp);
          $_tmp = str_replace("%TEXT%", $_TEXT, $_tmp);
          $_tmp = str_replace("%DATE%", $_comm[DATE], $_tmp);
          $_comment .= $_tmp;
        };
        $RETURN = str_replace("%COMMENT%", $_comment, $RETURN);
        $RETURN = str_replace("%action%", "shop/$path[1]/addcomment" , $RETURN);
        
        $EDIT=& $template->Get("shop.edit");#CORE_LOAD("shop", "edit.dat");
        
        /*
        if($_SESSION[register][id]){
          include("fckeditor.php");
          $z = $_SESSION[register][id];

          $SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog_description` WHERE USER_ID=$z and CATALOG_ID=$path[1]";
          $result=@mysql_query($SQL, $conf[DB]);// or die(mysql_error());
          $comm=@mysql_fetch_assoc($result);//or die(mysql_error());
          
          $sBasePath ="./";
          $oFCKeditor = new FCKeditor('header') ;
          $oFCKeditor->BasePath = $sBasePath ;
          $oFCKeditor->Height="200";
          $oFCKeditor->Value    = stripslashes($comm[HEADER]);
          $EDIT2.= $oFCKeditor->Create();

          $oFCKeditor = new FCKeditor('description') ;
          $oFCKeditor->BasePath = $sBasePath ;
          $oFCKeditor->Height="500";
          $oFCKeditor->Value    = stripslashes($comm[CONTENT]);
          $EDIT2.=$oFCKeditor->Create();


          $EDIT= str_replace("%forms%", $EDIT2 , $EDIT);

          $RETURN = str_replace("%edit%", $EDIT , $RETURN);
          $RETURN = str_replace("%action%", "shop/$path[1]/adddescription" , $RETURN);
        }
        */
        $RETURN = str_replace("%edit%", "", $RETURN);

      }
    }

    $FA = & $template->Get("shop.fa");#CORE_LOAD("shop", "fa.dat");
    $F  = & $template->Get("shop.f");#CORE_LOAD("shop", "f.dat");
    
    $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms ORDER by `NAME`";
    if ($firmsstr!=""){
      $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE `ID` $firmsstr ORDER by `NAME`";
    };

    $res = mysql_query($SQL, $conf[DB]);
    $firm = "";
    $page = (int)$path[2];
    while ($fir=@mysql_fetch_assoc($res)){

      if ($path[3]==$fir[ID]){
        $fir_str = str_replace("%link%", "/shop/$path[1]/$page/$fir[ID]", $FA);
      }else{
        $fir_str = str_replace("%link%", "/shop/$path[1]/$page/$fir[ID]", $F);
      };
      $firm.= $fir_str = str_replace("%title%",$fir[NAME], $fir_str);
    }
    return str_replace("%firm%",$firm, $RETURN);
  }
  function render($regionID = 0, $id, &$template) {
    $conf = $this->conf;
    
    return ;  
  }
  function edit() {
    $action = $_GET[a];
    $conf = $this->conf;

    if ($action == "")
    $action="edit";

    switch ($action){
    case "update":
      $sql = "UPDATE $conf[DB_PREFIX]shopblock SET `GOODSID`='$_POST[nid]' where ID=$id";
      mysql_query($sql, $conf[DB]);
      mysql_close($conf[DB]);
      header("Location: edit.php?action=edit&id=$id&module=shop");
    break;

    case "edit":
      $id = $_GET[ID];
      $sql = "SELECT * FROM `$conf[DB_PREFIX]shopblock` WHERE ID=$id";
      $result = mysql_query($sql, $conf[DB]);
      $shopblock= mysql_fetch_assoc($result);
      ?>
      <html>
      <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css.css" rel="stylesheet" type="text/css">
        </head>
      <body scroll="auto">
      <center>
      <form name="forma" action="edit.php?action=update&id=<?echo $id?>&module=shop" method="POST">
      <table width="200" border="1"> 
       <tr><td><INPUT type="text" maxlength="11" name="nid" value="<?echo $shopblock[GOODSID]?>"></td>
         <td>Код Товара</td></tr>
      </table>
      <center>
      <input name="submit" type="submit" class="mainoption" value="Принять">
      </FORM>
      </center>
      <?
    break;
    //**************************************
    };
  }
};
$info = array(
  'plugin'      => "shop",
  'cplugin'     => "eeShop",
  'pluginName'    => "Магазин",
  'ISMENU'      =>1,
  'ISENGINEMENU'    =>1,
  'ISBLOCK'     =>0,
  'ISEXTRABLOCK'    =>1,
  'ISSPECIAL'     =>1,
  'ISLINKABLE'    =>1,
  'ISINTERFACE'   =>1,
);
?>
