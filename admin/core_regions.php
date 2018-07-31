<?

function RuEncode ($ruString) {
  $re_ar= array(" "=>"_", "�"=>"a", "�"=>"A", "�"=>"b", "�"=>"B", "�"=>"v", "�"=>"V", "�"=>"g", "�"=>"G", "�"=>"d", "�"=>"D", "�"=>"e", "�"=>"E", "�"=>"e", "�"=>"E", "�"=>"j", "�"=>"J", "�"=>"z", "�"=>"Z", "�"=>"i", "�"=>"I", "�"=>"i", "�"=>"I", "�"=>"k", "�"=>"K", "�"=>"l", "�"=>"L", "�"=>"m", "�"=>"M", "�"=>"n", "�"=>"N", "�"=>"o", "�"=>"O", "�"=>"p", "�"=>"P", "�"=>"r", "�"=>"R", "�"=>"s", "�"=>"S", "�"=>"t", "�"=>"T", "�"=>"y", "�"=>"Y", "�"=>"f", "�"=>"F", "�"=>"h", "�"=>"H", "�"=>"c", "�"=>"C", "�"=>"ch", "�"=>"CH", "�"=>"sh", "�"=>"SH", "�"=>"sh", "�"=>"SH", "�"=>"'", "�"=>"'", "�"=>"y", "�"=>"Y", "�"=>"'", "�"=>"'", "�"=>"e", "�"=>"E", "�"=>"u", "�"=>"U", "�"=>"ia", "�"=>"IA");
  foreach ($re_ar as $key=>$val){
    $ruString = preg_replace ("/{$key}/", "{$val}", $ruString);
  }
  $ruString = str_replace("!", "_", $ruString);
  $ruString = str_replace("№", "_", $ruString);
  $ruString = str_replace(":", "_", $ruString);
  $ruString = str_replace("<", "_", $ruString);
  $ruString = str_replace(">", "_", $ruString);
  $ruString = str_replace("?", "_", $ruString);

  $ruString = str_replace(",", "_", $ruString);
  $ruString = str_replace(".", "_", $ruString);
  $ruString = str_replace("[", "_", $ruString);
  $ruString = str_replace("]", "_", $ruString);
  $ruString = str_replace("{", "_", $ruString);
  $ruString = str_replace("}", "_", $ruString);
  $ruString = str_replace("~", "_", $ruString);
  $ruString = str_replace("`", "_", $ruString);
  $ruString = str_replace(";", "_", $ruString);
  $ruString = str_replace("=", "_", $ruString);

  $ruString = str_replace("@", "_", $ruString);
  $ruString = str_replace("#", "_", $ruString);
  $ruString = str_replace("$", "_", $ruString);
  $ruString = str_replace("%", "_", $ruString);
  $ruString = str_replace("^", "_", $ruString);
  $ruString = str_replace("&", "_", $ruString);
  $ruString = str_replace("*", "_", $ruString);
  $ruString = str_replace("*", "_", $ruString);
  $ruString = str_replace("+", "_", $ruString);
  $ruString = str_replace("-", "_", $ruString);
  $ruString = str_replace("|", "_", $ruString);
  $ruString = str_replace("/", "_", $ruString);
  $ruString = str_replace("\\", "_", $ruString);
  $ruString = str_replace('"', "_", $ruString);
  $ruString = str_replace("'", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);

  return $ruString;
};
function RuEncodeUTF ($ruString) {
  $re_ar= array(" "=>"_", "а"=>"a", "А"=>"A", "б"=>"b", "Б"=>"B", "в"=>"v", "В"=>"V", "г"=>"g", "Г"=>"G", "д"=>"d", "Д"=>"D", "е"=>"e", "Е"=>"E", "ё"=>"e", "Ё"=>"E", "ж"=>"j", "Ж"=>"J", "з"=>"z", "З"=>"Z", "и"=>"i", "И"=>"I", "й"=>"i", "Й"=>"I", "к"=>"k", "К"=>"K", "л"=>"l", "Л"=>"L", "м"=>"m", "М"=>"M", "н"=>"n", "Н"=>"N", "о"=>"o", "О"=>"O", "п"=>"p", "П"=>"P", "р"=>"r", "Р"=>"R", "с"=>"s", "С"=>"S", "т"=>"t", "Т"=>"T", "у"=>"y", "У"=>"Y", "ф"=>"f", "Ф"=>"F", "х"=>"h", "Х"=>"H", "ц"=>"c", "Ц"=>"C", "ч"=>"ch", "Ч"=>"CH", "ш"=>"sh", "Ш"=>"SH", "щ"=>"sh", "Щ"=>"SH", "ъ"=>"", "Ъ"=>"", "ы"=>"y", "Ы"=>"Y", "ь"=>"", "Ь"=>"", "э"=>"e", "Э"=>"E", "ю"=>"u", "Ю"=>"U", "я"=>"ia", "Я"=>"IA");
  foreach ($re_ar as $key=>$val){
    $ruString = preg_replace ("/{$key}/", "{$val}", $ruString);
  }
  $ruString = str_replace("!", "_", $ruString);
  $ruString = str_replace("№", "_", $ruString);
  $ruString = str_replace(":", "_", $ruString);
  $ruString = str_replace("<", "_", $ruString);
  $ruString = str_replace(">", "_", $ruString);
  $ruString = str_replace("?", "_", $ruString);

  $ruString = str_replace(",", "_", $ruString);
  $ruString = str_replace(".", "_", $ruString);
  $ruString = str_replace("[", "_", $ruString);
  $ruString = str_replace("]", "_", $ruString);
  $ruString = str_replace("{", "_", $ruString);
  $ruString = str_replace("}", "_", $ruString);
  $ruString = str_replace("~", "_", $ruString);
  $ruString = str_replace("`", "_", $ruString);
  $ruString = str_replace(";", "_", $ruString);
  $ruString = str_replace("=", "_", $ruString);

  $ruString = str_replace("@", "_", $ruString);
  $ruString = str_replace("#", "_", $ruString);
  $ruString = str_replace("$", "_", $ruString);
  $ruString = str_replace("%", "_", $ruString);
  $ruString = str_replace("^", "_", $ruString);
  $ruString = str_replace("&", "_", $ruString);
  $ruString = str_replace("*", "_", $ruString);
  $ruString = str_replace("*", "_", $ruString);
  $ruString = str_replace("+", "_", $ruString);
  $ruString = str_replace("-", "_", $ruString);
  $ruString = str_replace("|", "_", $ruString);
  $ruString = str_replace("/", "_", $ruString);
  $ruString = str_replace("\\", "_", $ruString);
  $ruString = str_replace('"', "_", $ruString);
  $ruString = str_replace("'", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);
  $ruString = str_replace("__", "_", $ruString);

  return $ruString;
};


function getFullPath($id){
  global $conf;
  $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$id";
  $result=mysql_query($sql, $conf[DB]);
  $ret = "";

  while($region=mysql_fetch_assoc($result)){
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$region[PARENT]";
    $result=mysql_query($sql, $conf[DB]);
    $ret=RuEncodeUTF($region[TITLE])."/$ret";
  };
  $ret=RuEncodeUTF($region[TITLE])."/$ret";
  return substr($ret,0,-1);//strip the last "/"
}
class Regions{
  var $data;
  var $export_tmp;
  var $xml;
  //export an tree of regions

  function import($id, $data){
    return  $data;
  }
  function export_templates($id=0)
  {
    global $conf;
    $SQL = "SELECT * FROM $conf[DB_PREFIX]templates";
    $res = mysql_query($SQL, $conf[DB]);
    while($template = mysql_fetch_assoc($res))
    {
      $templ= new Template($template[PATH]);
      $templ->read();
      $tmp .='<template>'
      ."<id>$template[ID]</id>"
      ."<locations>$templ->count</locations>"
      ."<name>$templ->name</name>"
      ."<description>$templ->description</description>"
      .'<content><![CDATA['.$templ->content.']]></content>'
      ."</template>\r\n"
      ;
    };
    return $tmp;
  }
  function getFullPath($id){
    global $conf;


    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$id";
    $result=mysql_query($sql, $conf[DB]);
    $ret = "";

    while($region=mysql_fetch_assoc($result)){
      $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$region[PARENT]";
      $result=mysql_query($sql, $conf[DB]);
      $ret=RuEncodeUTF($region[TITLE])."/$ret";
    };
    return substr($ret,0,-1);//strip the last "/"
  }
  function reorder($id=0)
  {
    global $conf;
    $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `PARENT` = $id ORDER by `ORDER`";
    $order = 1;
    if (@$result = mysql_query($SQL, $conf[DB]))
    {
      while($region= mysql_fetch_assoc($result)){
        $SQL2 = "UPDATE $conf[DB_PREFIX]regions SET `ORDER`=$order WHERE ID=$region[ID]";
        mysql_query($SQL2, $conf[DB]);
        $this->reorder($region[ID]);
        $order++;
      };
    };
  }
  function reorders($id=0)
  {
    global $conf;
    $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE `PARENT` = $id ORDER by `ORDER`";
    $order = 1;
    if (@$result = mysql_query($SQL, $conf[DB]))
    {
      while($region= mysql_fetch_assoc($result)){
        $SQL2 = "UPDATE $conf[DB_PREFIX]catalog SET `ORDER`=$order WHERE ID=$region[ID]";
        mysql_query($SQL2, $conf[DB]);
        $this->reorders($region[ID]);
        $order++;
      };
    };
  }
  function export($id=0)
  {
    $id = (int)$id;
    $this->xml ="<?xml version=\"1.0\" encoding=\"WINDOWS-1251\"?" .">\r\n";
    $this->xml .="<export>\r\n";
    $this->xml .="<regions>\r\n".$this->export_regions($id)."\r\n</regions>\r\n";
    $this->xml .="<templates>\r\n".$this->export_templates($id)."\r\n</templates>\r\n";
    $this->xml .='</export>';
    $handle = fopen("export.xml", 'w');
    fwrite($handle, $this->xml);
    fclose  ($handle);
  }
  function export_regions($id=0)
  {
    $tmp = "";
    global $conf;
    //**************
    $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID` = $id";
    if (@$result = mysql_query($SQL, $conf[DB]))
    if ($region= mysql_fetch_assoc($result)){
      $region[DESC]     = urlencode($region[DESC]);
      $region[KW]     = urlencode($region[KW]);
      $region[TITLE]      = urlencode($region[TITLE]);
      $region[WEBLINK]    = urlencode($region[WEBLINK]);
      $region[WEBTITLE]   = urlencode($region[WEBTITLE]);
      $tmp = '<region>'
      ."<template>$region[TEMPLATE]</template>"
      ."<kw>$region[KW]</kw>"
      ."<desc>$region[DESC]</desc>"
      ."<title>$region[TITLE]</title>"
      ."<showmenu>$region[SHOWMENU]</showmenu>"
      ."<special>$region[SPECIAL]</special>"
      ."<webtitle>$region[WEBTITLE]</webtitle>"
      ."<linktype>$region[LINKTYPE]</linktype>"
      ."<linkid>$region[LINKID]</linkid>"
      ."<show>$region[SHOW]</show>"
      ."<weblink>$region[WEBLINK]</weblink>"
      ."\r\n"
      ;
      $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `PARENT`=$id ORDER BY `ORDER`";
      $res = @mysql_query($SQL, $conf[DB]);
      while (@$region= mysql_fetch_assoc($res))
      $tmp .= $this->export_regions($region[ID]);


      $SQL = "SELECT * FROM $conf[DB_PREFIX]blocks WHERE `PARENTREGION`=$id ORDER BY `ORDER`";
      $res = mysql_query($SQL, $conf[DB]);
      while ($block= mysql_fetch_assoc($res))
      {
        $tmp.='<block>'
        ."<active>$block[ACTIVE]</active>"
        ."<type>$block[TYPE]</type>"
        ."<location>$block[LOCATION]</location>"
        //            ."<fid>$block[FID]</fid>"
        ."\r\n"
        ;
        $RETURN="";
        global $FID;
        $FID = $block[FID];
        @include ("../modules/$block[TYPE]/exportxml.php");
        $tmp.="$RETURN \r\n </block>\r\n";
      };
      $tmp.="\r\n</region>\r\n";
    }
    return $tmp;
  }

  //Retirn the MENU element in Li tag
  function doLi($engName, $rusName, $link=0, $linkaddr="")
  {
    if ($linkaddr!="")
    $rusName = "<a href=\"$linkaddr\" target=\"contentFrame\">$rusName</a>";

    if ($link==0)
    return "<li id=$engName xid=$xid><a href=\"javascript:expand(\'$engName\')\"><img border=0 src=\"./images/tree_expand.png\"></a>$rusName</li>";

    return "<li id=$engName xid=$xid><a href=\"javascript:collaps(\'$engName\')\"><img border=0 src=\"./images/tree_collapse_corner.png\"></a>$rusName</li>";
  }

  //Retirn the MENU Region in Li tag
  function generateLi($region, $state=1, $li=1)
  {
    global $conf;
    if (is_int($region))
    {
      //get an region from database;
      $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID` = $region";
      $result = mysql_query($SQL, $conf[DB]);
      $region= mysql_fetch_assoc($result);
      mysql_free_result($result);
    }

    $SQL = "SELECT count(*) as `count` FROM $conf[DB_PREFIX]regions WHERE `PARENT` = $region[ID]";
    $result = mysql_query($SQL, $conf[DB]);
    $tmp = mysql_fetch_assoc($result);
    mysql_free_result($result);
    $rc = $tmp[count];

    $addr="?a=regions&s=edit&id=$region[ID]";
    $name = "regions" ."_$region[ID]";

    if($state)
    {
      $command="expand";
      $Ltext = "<img border=0 src=\"./images/tree_expand.png\">";
    }else{
      $command="collaps";
      $Ltext = "<img border=0 src=\"./images/tree_collapse_corner.png\">";
    };

    $tmp2 = "";
    if ($li)
    $tmp2 .="<li id=\"$name\">";

    if ($rc)
    $tmp2 .= "<a href=\"javascript:$command(\'$name\')\">$Ltext</a>";

    $tmp2 .="<a href=\"JavaScript:region_down(\'$region[ID]\')\"><img src=\"images/d.gif\" border=0></a>"
    ."<a href=\"JavaScript:region_up(\'$region[ID]\')\"><img src=\"images/u.gif\" border=0></a>"
    ."<a target=\"contentFrame\" href=\"?a=regions&s=showreparent&id=$region[ID]\"><img src=\"images/parent.gif\" border=0></a>"
    ."<a href=\"$addr\" target=\"contentFrame\">$region[TITLE]</a>"
    ;

    if ($li)
    $tmp2 .= "</li>";

    return $tmp2;
  }

  // return code do expand REGION
  function expandspecail()
  {
    global $conf;

    $sql="SELECT * FROM $conf[DB_PREFIX]modules WHERE ISEXTRABLOCK=1";
    $result=mysql_query($sql, $conf[DB]);
    while ($module=mysql_fetch_assoc($result))
    {
      $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE SPECIAL='$module[PATH]'";
      $result2=@mysql_query($SQL, $conf[DB]);
      if (@$region = mysql_fetch_assoc($result2))
      {
        //        $tmp2 .="<a href=\"menu.php?action=Rdown&id=$region[ID]\"><img src=\"images/d.gif\" border=0></a>";
        //        $tmp2 .="<a href=\"menu.php?action=Rup&id=$region[ID]\"><img src=\"images/u.gif\" border=0></a>";
        //        $tmp2 .="<a href=\"JavaScript:NW('menu.php?action=Rlistparent&id=$region[ID]',370,470)\"><img src=\"images/parent.gif\" border=0></a>";
        $tmp2 .= $this->generateLi($region);
      }else{
        //        $this->data .="<a class=\"important\" href=\"?a=regions&s=addspecial&module=$module[PATH]\">Добавить раздел для &#xab;$module[NAME]&#xbb; </a><br>";
      };

    };
    //*****************

    $tmp .= "var temp = xget('special');";
    return "$tmp;temp.innerHTML = '".doNoLi("special", "Специальные Разделы", 1, "?a=regions&s=viewspecial")
    ."<ul>".$tmp2."</ul>';";
  }
  function expand($id)
  {
    global $conf;
    $id=(int)$id;
    $special="";

    if($id){
      $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID` = $id";
      $result = mysql_query($SQL, $conf[DB]);
      $region= mysql_fetch_assoc($result);

      $tmp .= "var temp = xget('regions_$id');";
      $tmp .= "temp.innerHTML = '".$this->generateLi($region, 0,0). "';";
    }else{
      //expand specifed regions list
      $tmp .= "var temp = xget('regions');";
      $tmp .= "temp.innerHTML = '". doNoLi("regions", "РАЗДЕЛЫ", 1,"index.php?a=regions&s=viewtotal")."';";
      $special = $this->doLi("special", "Специальные Разделы", 0, "?a=regions&s=viewspecial") . "<hr>";
      //$special .= '<li><a href="?a=regions&s=viewbookmarks" target="contentFrame">Избранное</a></li>';
      //$special .= '<li><a href="?a=regions&s=viewtotal" target="contentFrame">Полный просмотр</a></li>';
    };

    $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `PARENT` = $id and (SPECIAL='0' OR SPECIAL='1' OR SPECIAL='2') ORDER by `ORDER` ";
    $result = mysql_query($SQL, $conf[DB]);
    $tmp2 ="";
    while ($region= mysql_fetch_assoc($result)){
      $tmp2 .= $this->generateLi($region);
    };
    return "$tmp;temp.innerHTML += '<ul>".$special.$tmp2."</ul>';";
  }

  function delblock($blockID){
    global $conf;
    include ("path.php");

    $SQL="SELECT * FROM `$conf[DB_PREFIX]blocks` WHERE ID=$blockID";
    $res  = mysql_query($SQL, $conf[DB]);
    $block = mysql_fetch_assoc ($res);

    //*************$module = new Module ($block[TYPE]);
    global $modules;
    $modules->modules["$block[TYPE]"]->del($block[FID]);
    $SQL="DELETE FROM `$conf[DB_PREFIX]blocks` WHERE ID=$block[ID]";
    mysql_query($SQL, $conf[DB]);
  }
  function upblock($id)
  {
    global $conf;
    $sql = "SELECT * FROM $conf[DB_PREFIX]blocks WHERE `ID` = $id";

    $result = mysql_query($sql, $conf[DB]);
    $block= mysql_fetch_assoc($result);
    $order = (int)$block[ORDER];
    $order2 = $order -1;
    if ($order>1)
    {
      $sql = "UPDATE `$conf[DB_PREFIX]blocks` SET `ORDER`=$order WHERE `ORDER`=$order2 and `PARENTREGION`=$block[PARENTREGION] and `LOCATION` = $block[LOCATION]";
      mysql_query($sql, $conf[DB]);
      $sql = "UPDATE `$conf[DB_PREFIX]blocks` SET `ORDER`=$order2 WHERE `ID`=$id";
      mysql_query($sql, $conf[DB]);
    }
  }
  function downblock($id)
  {
    global $conf;
    $sql = "SELECT * FROM $conf[DB_PREFIX]blocks WHERE `ID` = $id";
    $result = mysql_query($sql, $conf[DB]);
    $block= mysql_fetch_assoc($result);

    $sql = "SELECT max(`ORDER`) as `max` FROM `$conf[DB_PREFIX]blocks` WHERE `PARENTREGION`=$block[PARENTREGION] and `LOCATION` = $block[LOCATION]";
    $result = mysql_query($sql, $conf[DB]);
    $tmp = mysql_fetch_assoc($result);

    $orderMax = (int)$tmp[max];
    $order = (int)$block[ORDER];
    $order2 = $order +1;

    if ($order<=$orderMax)
    {
      $sql = "UPDATE `$conf[DB_PREFIX]blocks` SET `ORDER`=$order WHERE `ORDER`=$order2 and `PARENTREGION`=$block[PARENTREGION] and `LOCATION` = $block[LOCATION]";
      mysql_query($sql, $conf[DB]);
      $sql = "UPDATE `$conf[DB_PREFIX]blocks` SET `ORDER`=$order2 WHERE `ID`=$id";
      mysql_query($sql, $conf[DB]);
    };
  }
  function addblock($id, $location, $block)
  {
    global $conf;
    $order=1;
    $SQL="SELECT max(`ORDER`) as `maximum` FROM `$conf[DB_PREFIX]blocks` WHERE `PARENTREGION`=$id and `LOCATION`=$location";
    $res=mysql_query($SQL, $conf[DB]);
    $ord=mysql_fetch_assoc($res);
    $order=0 + (int)$ord[maximum] + 1;

    $bl = (int)$block;
    $SQL= "SELECT * FROM $conf[DB_PREFIX]modules WHERE `ID`=$bl";
    $temp=mysql_fetch_assoc(mysql_query($SQL, $conf[DB]));

    global $modules;

    $fid = $modules->modules["$temp[PATH]"]->addnew();
    $extra=0;
    if (strpos($block, "_EXTRA")!= false){
      $extra=1;   }
      $SQL="INSERT INTO `$conf[DB_PREFIX]blocks`(`ORDER`, `TYPE`, `PARENTREGION`, `FID`, `LOCATION`, `EXTRABLOCK`) VALUES ($order, '$temp[PATH]', $id, $fid, $location, $extra)";
      mysql_query($SQL, $conf[DB]);
  }

  function setkw($id)
  {
    global $conf;
    $_POST[WEBTITLE] = str_replace("!", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("№", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace(":", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("<", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace(">", "_", $_POST[WEBTITLE]);

    $_POST[WEBTITLE] = str_replace(",", "_", $_POST[WEBTITLE]);
//    $_POST[WEBTITLE] = str_replace(".", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("[", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("]", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("{", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("}", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("~", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("`", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace(";", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("=", "_", $_POST[WEBTITLE]);

    $_POST[WEBTITLE] = str_replace("@", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("#", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("$", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("%", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("^", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("&", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("*", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("*", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("+", "_", $_POST[WEBTITLE]);
//    $_POST[WEBTITLE] = str_replace("-", "_", $_POST[WEBTITLE]);
//    $_POST[WEBTITLE] = str_replace("|", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("/", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("\\", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace('"', "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("'", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("__", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("__", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("__", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("__", "_", $_POST[WEBTITLE]);
    $_POST[WEBTITLE] = str_replace("__", "_", $_POST[WEBTITLE]);

    $_POST[KW] = str_replace("!", "_", $_POST[KW]);
    $_POST[KW] = str_replace("№", "_", $_POST[KW]);
    $_POST[KW] = str_replace(":", "_", $_POST[KW]);
    $_POST[KW] = str_replace("<", "_", $_POST[KW]);
    $_POST[KW] = str_replace(">", "_", $_POST[KW]);

    $_POST[KW] = str_replace(",", "_", $_POST[KW]);
    $_POST[KW] = str_replace(".", "_", $_POST[KW]);
    $_POST[KW] = str_replace("[", "_", $_POST[KW]);
    $_POST[KW] = str_replace("]", "_", $_POST[KW]);
    $_POST[KW] = str_replace("{", "_", $_POST[KW]);
    $_POST[KW] = str_replace("}", "_", $_POST[KW]);
    $_POST[KW] = str_replace("~", "_", $_POST[KW]);
    $_POST[KW] = str_replace("`", "_", $_POST[KW]);
    $_POST[KW] = str_replace(";", "_", $_POST[KW]);
    $_POST[KW] = str_replace("=", "_", $_POST[KW]);

    $_POST[KW] = str_replace("@", "_", $_POST[KW]);
    $_POST[KW] = str_replace("#", "_", $_POST[KW]);
    $_POST[KW] = str_replace("$", "_", $_POST[KW]);
    $_POST[KW] = str_replace("%", "_", $_POST[KW]);
    $_POST[KW] = str_replace("^", "_", $_POST[KW]);
    $_POST[KW] = str_replace("&", "_", $_POST[KW]);
    $_POST[KW] = str_replace("*", "_", $_POST[KW]);
    $_POST[KW] = str_replace("*", "_", $_POST[KW]);
    $_POST[KW] = str_replace("+", "_", $_POST[KW]);
    $_POST[KW] = str_replace("-", "_", $_POST[KW]);
    $_POST[KW] = str_replace("|", "_", $_POST[KW]);
    $_POST[KW] = str_replace("/", "_", $_POST[KW]);
    $_POST[KW] = str_replace("\\", "_", $_POST[KW]);
    $_POST[KW] = str_replace('"', "_", $_POST[KW]);
    $_POST[KW] = str_replace("'", "_", $_POST[KW]);
    $_POST[KW] = str_replace("__", "_", $_POST[KW]);
    $_POST[KW] = str_replace("__", "_", $_POST[KW]);
    $_POST[KW] = str_replace("__", "_", $_POST[KW]);
    $_POST[KW] = str_replace("__", "_", $_POST[KW]);
    $_POST[KW] = str_replace("__", "_", $_POST[KW]);
    $sql="UPDATE `$conf[DB_PREFIX]regions` SET `KW`= '$_POST[KW]', `WEBTITLE`= '$_POST[WEBTITLE]', `DESC`= '$_POST[DESС]' WHERE `ID`=$id";
    mysql_query($sql, $conf[DB]);
  }
  function settitle($id)
  {
    global $conf;
    $_POST[TITLE] = str_replace("!", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("№", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace(":", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("<", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace(">", "_", $_POST[TITLE]);

    $_POST[TITLE] = str_replace(",", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace(".", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("[", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("]", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("{", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("}", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("~", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("`", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace(";", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("=", "_", $_POST[TITLE]);

    $_POST[TITLE] = str_replace("@", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("#", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("$", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("%", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("^", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("&", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("*", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("*", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("+", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("-", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("|", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("/", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("\\", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace('"', "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("'", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("__", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("__", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("__", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("__", "_", $_POST[TITLE]);
    $_POST[TITLE] = str_replace("__", "_", $_POST[TITLE]);

    $sql="UPDATE `$conf[DB_PREFIX]regions` SET `TITLE`= '$_POST[TITLE]' WHERE `ID`=$id";
    mysql_query($sql, $conf[DB]);
  }

  function up($id)
  {
    global $conf;
    $sql = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID` = $id";

    $result = mysql_query($sql, $conf[DB]);
    $region = mysql_fetch_assoc($result);

    $order = (int)$region[ORDER];
    $order2 = $order -1;

    if ($order>1)
    {
      $sql = "UPDATE `$conf[DB_PREFIX]regions` SET `ORDER`=$order WHERE `ORDER`=$order2";
      mysql_query($sql, $conf[DB]);

      $sql = "UPDATE `$conf[DB_PREFIX]regions` SET `ORDER`=$order2 WHERE `ID`=$id";
      mysql_query($sql, $conf[DB]);
    }
  }
  function down($id)
  {
    global $conf;
    $sql = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID` = $id";
    $result = mysql_query($sql, $conf[DB]);
    $region = mysql_fetch_assoc($result);

    $sql = "SELECT max(`ORDER`) as `max` FROM `$conf[DB_PREFIX]regions` where `PARENT`=$region[PARENT]";
    $result = mysql_query($sql, $conf[DB]);
    $tmp = mysql_fetch_assoc($result);

    $orderMax = (int)$tmp[max];
    $order = (int)$region[ORDER];
    $order2 = $order +1;

    if ($order<$orderMax)
    {
      $sql = "UPDATE `$conf[DB_PREFIX]regions` SET `ORDER`=$order WHERE `ORDER`=$order2";
      mysql_query($sql, $conf[DB]);
      $sql = "UPDATE `$conf[DB_PREFIX]regions` SET `ORDER`=$order2 WHERE `ID`=$id";
      mysql_query($sql, $conf[DB]);
    }
  }

  function _printRegionsList($i)
  {
    global $conf;
    global $level;

    $level++;
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$i ORDER BY `ORDER` ASC";
    $result=mysql_query($sql, $conf[DB]);
    $this->data .= "<table><tr><td>";

    while ($row=mysql_fetch_assoc($result))
    {
      $tmpID = $row['ID'];
      $tmp=0;
      for ($tmp=0; $tmp <= $level; $tmp++)
      $this->data .="&nbsp;&nbsp;";
      $this->data .="<a href=\"?a=regions&s=down&id=$row[ID]\"><img src=\"images/d.gif\" border=0></a>";
      $this->data .="<a href=\"?a=regions&s=up&id=$row[ID]\"><img src=\"images/u.gif\" border=0></a>";
      //      $this->data .="<a href=\"?a=regions&s=listparent&id=$row[ID]\"><img src=\"images/parent.gif\" border=0></a>";
      if ($row['SPECIAL'] == 0)
      {
        $this->data .="<a href=\"?a=regions&s=edit&id=$row[ID]\"><b><i>" .$row['TITLE']. '</i></b></a><br>';
      }else{
        $this->data .="<a href=\"?a=regions&s=edit&id=$row[ID]\">" .$row['TITLE']. '</a><br>';
      }

      $this->_printRegionsList($tmpID);
    }
    $this->data .="</tr></td></table>";

    mysql_free_result($result);
    $level--;
  }

  function _printRegionsListTotal($i)
  {
    global $conf;
    global $level;
    $level++;

    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$i ORDER BY `ORDER` ASC";
    $result=mysql_query($sql, $conf[DB]);
    if ($result)
    while ($region=mysql_fetch_assoc($result))
    {
      $tmpID = $region['ID'];

      $this->data .= '<tr><td align=left>';
      $tmp=0;
      for ($tmp=0; $tmp <= $level; $tmp++)
      $this->data .="&nbsp;&nbsp;";

      $this->data .="<a href=\"?a=regions&s=down&id=$region[ID]\"><img src=\"images/d.gif\" border=0></a>";
      $this->data .="<a href=\"?a=regions&s=up&id=$region[ID]\"><img src=\"images/u.gif\" border=0></a>";
      $this->data .="<a target=\"contentFrame\" href=\"?a=regions&s=showreparent&id=$region[ID]\"><img src=\"images/parent.gif\" border=0></a>";

      if ($region[SPECIAL] !=0)
      $this->data .= "<b>";

      $this->data .="<a href=\"?a=regions&s=edit&id=$tmpID\">" .$region['TITLE']. "</a>";
      if ($row[SPECIAL] !=0)
      $this->data .="</b>";
      $this->data .='</td>';
      $this->data .='<td align="CENTER">';

      if ($region['SHOWMENU'] == 1)
      {
        $this->data .="<INPUT type=\"checkbox\" CHECKED name=\"rMENU$tmpID\">";
      }else{
        $this->data .="<INPUT type=\"checkbox\" name=\"rMENU$tmpID\">";
      }

      $this->data .='</td>';

      $sql2="SELECT * FROM $conf[DB_PREFIX]templates";
      $result2=mysql_query($sql2, $conf[DB]);
      $this->data .="<td  align=center><select name=\"rTEMPLATE$tmpID\">";

      while ($template=mysql_fetch_assoc($result2))
      {
        $tplID = $template['ID'];
        $tplTITLE=$template['NAME'];
        if ($tplID == $region['TEMPLATE']){
          $this->data .="<option value = \"$tplID\" selected>$tplTITLE</option>";
        }else{
          $this->data .="<option value = \"$tplID\">$tplTITLE</option>";
        };
      }

      mysql_free_result($result2);
      $this->data .='</td>';
      $this->data .='<td  align=center>';
      unset($spec);

      $spec[$region[SPECIAL]]="selected";

      $this->data .="<select name=\"rSPECIAL$tmpID\">";
      $tmp = $region[SPECIAL];
      if ($tmp != "0" && $tmp!="1" && $tmp!="2")
      {
        $SQL = "SELECT * FROM $conf[DB_PREFIX]modules WHERE `PATH`='$tmp'";
        $result2=mysql_query($SQL, $conf[DB]);
        $module = mysql_fetch_assoc($result2);

        $this->data .="<option value = \"$module[PATH]\" $spec[0]>$module[NAME]</option>";
      }else{
        $this->data .="<option value = \"0\" $spec[0]>Нет</option>";
      };

      $this->data .="<option value = \"1\" $spec[1]>Стартовый</option>";
      $this->data .="<option value = \"2\" $spec[2]>Ссылка</option>";
      $this->data .='</td></tr>';
      $this->_printRegionsListTotal($tmpID);
    }
    @mysql_free_result($result);
    $level--;
  }


  function printshort()
  {
    $this->data="";
    $this->_printRegionsList(0);
    return "<table>". $this->data . "</table>";
  }
  function printtotal()
  {
    $this->data="";
    $this->_printRegionsListTotal(0);
    return "<form name=\"form\" method=\"POST\" action=\"?a=regions&s=savetotal\">"
    ."<table><tr><td align=center>Разделы</td><td align=center>Меню</td><td align=center>Шаблон</td><td align=center>Спец.</td></tr>" .$this->data ."</table>"
    ."<P align = \"center\"><input type=\"submit\" value=\"Принять\"></form>";

  }

  function printspecial()
  {
    global $conf;

    $this->data="";
    $sql="SELECT * FROM $conf[DB_PREFIX]modules WHERE ISEXTRABLOCK=1";
    $result=mysql_query($sql, $conf[DB]);
    while ($module=mysql_fetch_assoc($result))
    {
      $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE SPECIAL='$module[PATH]'";
      $result2=@mysql_query($SQL, $conf[DB]);
      if (@$region = mysql_fetch_assoc($result2))
      {
        $this->data .="<a href=\"?a=regions&s=edit&id=$region[ID]\"><b>" .$region['TITLE']. '</b></a><br>';
      }else{
        $this->data .="<a class=\"important\" href=\"?a=regions&s=addspecial&module=$module[PATH]\">Добавить раздел для &#xab;$module[NAME]&#xbb; </a><br>";
      };

    };
    return $this->data;
  }

  function _printlistparent($id, $target)
  {
    global $conf;
    global $level;
    $level++;
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$id ORDER BY `ORDER` ASC";
    $result=mysql_query($sql, $conf[DB]);
    $this->data .= "<table><tr><td>";

    while ($row=mysql_fetch_assoc($result))
    {
      if ($target != $row[ID])
      {
        $tmp=0;
        for ($tmp=0; $tmp <= $level; $tmp++)
        $this->data .="&nbsp;&nbsp;";
        $this->data .="<a href=\"?a=regions&s=setparent&id=$target&parent=$row[ID]\"><b><i>" .$row['TITLE']. '</i></b></a><br>';
        $this->_printlistparent($row[ID], $target);
      };
    }
    $this->data .="</tr></td></table>";

    mysql_free_result($result);
    $level--;
  }
  function _printlistdelete($id)
  {
    global $conf;
    global $level;

    $level++;
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$id ORDER BY `ORDER` ASC";
    $result=mysql_query($sql, $conf[DB]);
    $this->data .= "<table><tr><td>";

    while ($row=mysql_fetch_assoc($result))
    {
      if ($target != $row[ID])
      {
        $tmp=0;
        for ($tmp=0; $tmp <= $level; $tmp++)
        $this->data .="&nbsp;&nbsp;";
        $this->data .="<a href=\"?a=regions&s=commitdelete&id=$row[ID]\"><b><i>" .$row['TITLE']. '</a><br>';
        $this->_printlistdelete($row[ID]);
      };
    }
    $this->data .="</tr></td></table>";
    mysql_free_result($result);
    $level--;
  }
  function printlistdelete()
  {
    $this->data="";
    $this->_printlistdelete(0);
    return "<table>". $this->data . "</table>";
  }

  function printlistparent($target)
  {
    $this->data="";
    $this->_printlistparent(0, $target);
    return "<table>". $this->data . "</table>";
  }

  function setparent($id, $parent)
  {
    global $conf;
    $sql = "SELECT max(`ORDER`) as `co` FROM `$conf[DB_PREFIX]regions` where `PARENT`=$parent";
    $max=1;
    if ($result5 = mysql_query($sql, $conf[DB])){
      if ($up = mysql_fetch_assoc($result5))
      {
        $max = $up[co]+1;
      };
    };
    $sql = "UPDATE `$conf[DB_PREFIX]regions` SET `PARENT` = $parent, `ORDER`=$max WHERE `ID`=$id";
    $result = mysql_query($sql, $conf[DB]);
  }

  function editlink($id){
    global $conf;

    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$id";
    $result=mysql_query($sql, $conf[DB]);
    $region=mysql_fetch_assoc($result);
    $type = $region[LINKTYPE];
    $terget = $region[LINKID];
    $text = $region[WEBLINK];
    $ret = "<form method=\"post\" action =\"?a=regions&s=fixlink&id=$id&add=1&type=WWW\">"
    ."<input type=\"text\" size=50 name=\"term\" value=$text>"
    ."<input type=\"submit\" value=\"Принять\" class=\"mainoption\">"
    ."</form>"
    ."<hr>"
    ."</center>"
    ;
    return  $ret;
  }

  function fixlink($id){
    global $conf;
    $id= $_GET[id];
    $add= $_GET[add];
    if ($_POST[term]!="")
    {
      $sql = "UPDATE `$conf[DB_PREFIX]regions` SET `LINKID`=$add, `LINKTYPE`='$_GET[type]', `WEBLINK`='$_POST[term]' WHERE `ID` = $id";
    }else{
      $sql = "UPDATE `$conf[DB_PREFIX]regions` SET `LINKID`=$add, `LINKTYPE`='$_GET[type]',  WHERE `ID` = $id";
    };

    mysql_query($sql, $conf[DB]);
    header("Location: ?a=regions&s=editlink&id=$id");
  }
  function edit($id){
    $this->data = "";

    global $conf;

    if ((int)$id ==0)
    return 0;
    $sql = "SELECT * FROM `$conf[DB_PREFIX]regions` WHERE `ID`=$id";
    $result=mysql_query($sql, $conf[DB]);

    if ($region = mysql_fetch_assoc($result))
    {
      if ($region[SPECIAL]==2)
      {
        header("Location: ?a=regions&s=editlink&id=$id");
        die();
      };

      $tmpreg = $region;
      $tmp[] = array("<font size=3>$tmpreg[TITLE]</strong>", $tmpreg[ID]);

      while ($tmpreg[PARENT]!=0)
      {
        $SQL = "SELECT TITLE, ID, PARENT FROM $conf[DB_PREFIX]regions WHERE ID=$tmpreg[PARENT]";
        $result=mysql_query($SQL, $conf[DB]);
        $tmpreg= mysql_fetch_assoc($result);
        $tmp[] = array($tmpreg[TITLE], $tmpreg[ID]);
      };

      $tmp = array_reverse($tmp);
      foreach ($tmp as $reg)
      {
        $this->data .="-><a href=\"?a=regions&s=edit&id=$reg[1]\">$reg[0]</a>";
      };

      $sql="SELECT * FROM `$conf[DB_PREFIX]templates` WHERE `ID`=$region[TEMPLATE]";
      $result3=mysql_query($sql, $conf[DB]);
      $tpl=mysql_fetch_assoc($result3);
      mysql_free_result($result3);
      include ("path.php");
      $template= new Template($tpl[PATH]);
      $this->data.='<center>'
      .'<form action = "?a=regions&s=settitle&id='. $_GET[id].'" method = "POST">'
      .'Название: <input type = "text" value = "' . $region[TITLE] .'" name = "TITLE" size = 40>'
      .'<input type = "submit" value = "Принять" >'
      .'</form>'
      .'<form action = "?a=regions&s=setkw&id='.$_GET[id]. '" method = "POST">'
      .'Заголовок<br>'
      .'<textarea rows="3" cols="40" name="WEBTITLE">' .$region[WEBTITLE].'</textarea><br>'
      .'Ключевые Слова \ Описание<br>'
      .'<textarea name="KW" rows="5" cols="30">'.$region[KW].'</textarea>'
      .'<textarea name="DESС" rows="5" cols="30">' . $region[DESC] .'</textarea><br>'
      .'<input type = "submit" value = "Принять">'
      .'</form>'
      ;
      $this->data.='<table border=1>'
      .'<tr>'
      ;

      for ($i=1; $i<=$template->count;$i++)
      {
        $this->data.='<td valign=top>'
        .$template->Get("main.locations.$i")
        .'<br><form method = "POST" action = "?a=regions&s=addblock&id='. $_GET[id] .'&location='. $i .'">'
        .'<select name = "type" class = "mainoption">'
        ;
        $SQL="SELECT * FROM $conf[DB_PREFIX]modules WHERE ISBLOCK = 1";
        $result=mysql_query($SQL, $conf[DB]);
        while ($module=mysql_fetch_assoc($result))
        {
          $this->data.="<option value=\"$module[ID]\">$module[NAME]</option>";
        }
        $SQL="SELECT * FROM $conf[DB_PREFIX]modules WHERE ISEXTRABLOCK = 1 AND `PATH`='$region[SPECIAL]'";
        @$result=mysql_query($SQL, $conf[DB]);
        @$module=mysql_fetch_assoc($result);
        if ($module)
        $this->data.="<option value=\"$module[ID]_EXTRA\">*$module[NAME] </option>";

        $this->data.='</select>'
        .'<br><input type = "submit" value = "Добавить" class = "mainoption"><br>'
        .'</form>'
        ;
        $sql="SELECT * FROM `$conf[DB_PREFIX]blocks` WHERE `PARENTREGION`=$id and `LOCATION`=$i ORDER BY `ORDER` ASC";
        $result3=mysql_query($sql, $conf[DB])or die(mysql_error());

        $this->data.="<form action=\"?a=regions&s=setactive&location=$i&id=$id\" method=\"POST\">";
        $this->data.='<table border=0 heigh= "100%" width="100%" valign=top>';
        $this->data.= "<tr><td>";
        while ($block=mysql_fetch_assoc($result3))
        {
          global $modules;
          $tmp = $modules->modules["$block[TYPE]"]->info[pluginName];;
          if ($block['ACTIVE'] == 1)
          {
            $this->data.="<INPUT type=\"checkbox\" CHECKED name=\"block$block[ID]\">";
          }else{
            $this->data.="<INPUT type=\"checkbox\" name=\"block$block[ID]\">";
          };
          $this->data.="<a href=\"JavaScript:DoConfirm('Вы действительно зхотите удалить данный Блок?','?a=regions&s=delblock&id=$_GET[id]&add=$block[ID]')\"><img src=\"images/del.gif\" border=0></a>"."<a href=\"?a=regions&s=downblock&id=$id&add=$block[ID]\"><img src=\"images/d.gif\" border=0></a>"."<a href=\"?a=regions&s=upblock&id=$id&add=$block[ID]\"><img src=\"images/u.gif\" border=0></a>";
          if (!$block[EXTRABLOCK])
          {
            if ($block[FID]!=0){ // is Editable module
              $this->data.="<a target=blank href=\"edit.php?id=$block[FID]&module=$block[TYPE]\">^</a>";
              $this->data.="<a href=\"edit.php?id=$block[FID]&module=$block[TYPE]\">$tmp($block[FID])</a>";
            }else{
              $this->data.=$tmp;
            }

          }else{
            $this->data.="*$tmp";
          };
          $this->data.= "<br>";
        };
        $this->data.="</td></tr>";
        $this->data.='<tr><td><input type="submit" value="Принять"></td></tr>'
        .'</table></form>'
        ;
      };
    };
    return $this->data;
  }
  function updatetotal()
  {
    global $conf;
    $sql="SELECT * FROM `$conf[DB_PREFIX]regions`";
    $result=mysql_query($sql, $conf[DB]);
    while ($row=mysql_fetch_assoc($result))
    {
      if ($_POST["rMENU$row[ID]"]=="on"){
        $rMENU=1;
      }else{
        $rMENU=0;
      };
      $rTEMPLATE=$_POST["rTEMPLATE$row[ID]"];
      $rSPECIAL=$_POST["rSPECIAL$row[ID]"];
      $sql ="UPDATE `$conf[DB_PREFIX]regions` SET `SHOWMENU`= $rMENU, `TEMPLATE`=$rTEMPLATE, `SPECIAL`='$rSPECIAL' WHERE `ID`=$row[ID]";
      mysql_query($sql, $conf[DB]);
    }
  }
  function _getreparentlist($id, $exlude, $level)
  {
    global $conf;
    $level++;
    $sql = "SELECT * FROM $conf[DB_PREFIX]regions WHERE `PARENT`=$id ORDER BY `ORDER` ASC";

    $result = mysql_query($sql, $conf[DB]);
    while ($region = mysql_fetch_assoc($result))
    {
      if ($region[ID]!=$exlude)
      {
        $tmp=0;
        for ($tmp=0;$tmp<=$level;$tmp++)
        $ret .= "&nbsp;&nbsp;";

        $ret .= "<a href=\"?a=regions&s=reparent&id=$_GET[id]&parent=$region[ID]\">$region[TITLE]</a><br>";
        $ret .="</td></tr>";
        $ret .= $this->_getreparentlist($region[ID], $exlude, $level);
      };
    }
    mysql_free_result($result);
    $level--;
    return $ret;
  }
  function reparentlist($id)
  {
    return "<a href=\"?a=regions&s=reparent&id=$_GET[id]&parent=0\">Нулевой уровень</a><br>".$this->_getreparentlist(0, $id, 1);
  }
};