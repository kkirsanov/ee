<?
class eeSearch_shop{
  var $conf;
  function eeSearch_shop($conf)
  {
    $this->conf = $conf;
  }
  function pricein($a, $b)
  {
    $pr_3=explode(";", $a);
    $pr_ext = explode(":", $pr_3[0]);
    
    if (!isset ($pr_ext[1]))
      return $pr_ext[0];
    $max = 0;
        
    foreach ($pr_3 as $pr)
    {
      
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
  function getFullPath($id){
    $conf = $this->conf;
    
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$id";
    $result=mysql_query($sql, $conf[DB]);   
    $ret = "";
    
    while($region=mysql_fetch_assoc($result)){      
      $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$region[PARENT]";
      $result=mysql_query($sql, $conf[DB]);
      $ret="$region[TITLE]/$ret";
    };
    $ret = RuEncodeUTF($ret);
    return substr($ret,0,-1);//strip the last "/"
  }
  function printheader(){
    ?><html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">
  <center>
    <a href="?module=search_shop&action=view" >Шаблоны</a>
  </center> 
  <?
  echo '<script>function DoConfirm(message, url){if (confirm(message))location.href = url;}</script>';
  }
  function install(){
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
  function render($rID, $fid, $template){
    $conf = $this->conf;
    global $RETURN;
    $RETURN = "";     
  
    $SEARCH= &$template->get("shop.search.input");#CORE_LOAD("search_shop","input.dat");
    
    $firmlist = '<select name="FIRM"><option value="0">Выберите производителя</option>';
    $sql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms order by NAME";
    $res = mysql_query($tmpsql, $conf[DB]);
    while ($firm = @mysql_fetch_assoc($res)){
      $firmlist.="<option value='$firm[ID]'>$firm[NAME]</option>";
    };

    $firmlist.= "</select>";


    $SEARCH  = str_replace("%action%", "./search_shop/search", $SEARCH);
    $SEARCH  = str_replace("%firmlist%", $firmlist, $SEARCH);
    return $RETURN = $SEARCH;

  }
  function makelike($term, $field){
    $termlist = split('[- /.]',$term);
//    print_r($termlist);
    $where="";
    foreach ($termlist as $t){
     if  ($where==""){
        $where .= "`$field` like '%$t%'";
      }else{
        $where .= " and `$field` like '%$t%'" ;
      }
    }
    return $where;
  }
  function renderEx($id, &$template){
    $conf = $this->conf;
    //********
    $_T = $_POST[TERM];
    $_F = (int)$_POST[FIRM];
    $len = ($_T);
    if($_T[$len]==" ")
      array_pop($_T);

    if ($_T=="")
      $_T = $_GET[TERM];
    if ($_F=="")
      $_F = $_GET[FIRM];
    $ELEMENT  = &$template->get("shop.element");#CORE_LOAD("shop", "element.dat");
    $OUTER    = &$template->get("shop.outer");#CORE_LOAD("shop", "outer.dat");
    $VALUEK   = &$template->get("shop.valuek");#CORE_LOAD("shop", "valuek.dat");
    $VALUE    = &$template->get("shop.value")*$VALUEK;#CORE_LOAD("shop", "value.dat")*$VALUEK;

    $IP = &$template->get("shop.inprice");#CORE_LOAD("shop","inprice.dat");
    $OP = &$template->get("shop.offprice");#CORE_LOAD("shop","offprice.dat");
    $CALL=&$template->get("shop.call");#CORE_LOAD("shop", "call.dat");
    $NP=&$template->get("shop.np");#CORE_LOAD("shop", "np.dat");

    if ($VALUE=="")
      $VALUE = 1;

    @$kurs= &$template->Get("shop.kurs");#CORE_LOAD("shop", "kurs.dat");
    //$_T= str_replace(" ", "%", $_T);

    
  
    $firm="";
    if ($_F!=0)
      $firm= " AND (`FIRM`=$_F) ";
    
//    $this->makelike($_T, 'TITLE');

    //$_SQL = "SELECT count(*) as `count` FROM $conf[DB_PREFIX]catalog WHERE (`TITLE` LIKE '%$_T%' OR `CONTENT` LIKE '%$_T%' OR `HEADER` LIKE '%$_T%') AND(`ACTIVE`=1) $firm order by INPRICE DESC";
    $_SQL = "SELECT count(*) as `count` FROM $conf[DB_PREFIX]catalog WHERE ((" . $this->makelike($_T, 'TITLE').") OR (" . $this->makelike($_T, 'CONTENT').") OR (" . $this->makelike($_T, 'HEADER').")) AND(`ACTIVE`=1) $firm order by INPRICE DESC";
    
    $_result=mysql_query($_SQL, $conf[DB]);
    $_co=mysql_fetch_assoc($_result);

    $total = $_co[count];
    $page = $_GET[page];
    $pagesize=10;
    $page=(int) $pagesize*$page;
    
    $pageTXT = '';
    $pagecount = $total/$pagesize;
    for ($i=0;$i<=$pagecount; $i++){
            $pageTXT.= "<a href=\"http://www.muzbazar.ru/search_shop/search?TERM=$_GET[TERM]&FIRM=$_GET[FIRM]&page=$i\">$i</a> &nbsp;";
    }
    
    
    //$_SQL = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE (`TITLE` LIKE '%$_T%' OR `CONTENT` LIKE '%$_T%' OR `HEADER` LIKE '%$_T%') AND(`ACTIVE`=1) $firm order by INPRICE, PRICE LIMIT $page, $pagesize";
    $_SQL = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ((" . $this->makelike($_T, 'TITLE').") OR (" . $this->makelike($_T, 'CONTENT').") OR (" . $this->makelike($_T, 'HEADER').")) AND(`ACTIVE`=1) $firm order by INPRICE DESC";
  
    $_result=mysql_query($_SQL, $conf[DB]);
    while (@$_catalog_item=mysql_fetch_assoc($_result))         
    {
      $_TTMP=1;
      $_ret = $ELEMENT;
      $_ret = str_replace("%title%", stripslashes($_catalog_item[TITLE]), $_ret);
      $_ret = str_replace("%description%", stripslashes($_catalog_item[CONTENT]), $_ret);
      $_ret = str_replace("%header%", stripslashes($_catalog_item[HEADER]), $_ret);
      $_ret = str_replace("%price%", $this->parseprice($_catalog_item[PRICE])*$VALUEK, $_ret);
      $_ret = str_replace("%id%", $_catalog_item[ID], $_ret);
      $_ret = str_replace("%price2%", $VALUE * (float)$_catalog_item[PRICE], $_ret);

      //get image addres
      $_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$_catalog_item[ID] AND TYPE = 'catalog' order by ID";
      $_res=mysql_query($_sql, $conf[DB]);                    
      if (@$_image=mysql_fetch_assoc($_res))
      {
        $_IMAGE="../files/$_image[ID]";               
      }else{                        
          $_IMAGE="absent.jpg";
      };
      $_ret = str_replace("%imageaddr%", $_IMAGE, $_ret);

      $_ret = str_replace("%viewaddr%", "../shop/$_catalog_item[ID]", $_ret);
      $_ret = str_replace("%cartaddr%", "../cart/$_catalog_item[ID]", $_ret);

      $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog_item[FIRM]";
      $tmpres = mysql_query($tmpsql, $conf[DB]);
      $tmpfir = mysql_fetch_assoc($tmpres);

      $_ret = str_replace("%firm%", $tmpfir[NAME], $_ret);

      $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog_item[PARENT]";
      $tmpres = mysql_query($tmpsql, $conf[DB]);
      $tmpcat = mysql_fetch_assoc($tmpres);
            $_ret = str_replace("%parent%", $tmpcat[TITLE], $_ret);
      $_ret = str_replace("%parentAddr%", "./shop/$tmpcat[ID]/", $_ret);

      switch($_catalog_item[INPRICE]){
          case "0":
            $_ret = str_replace("%inprice%", $IP, $_ret);
          break;
          case "1":
            $_ret = str_replace("%inprice%", $CALL, $_ret);
          break;
          case "2":
            $_ret = str_replace("%inprice%", $OP, $_ret);
          break;
          case "3":
            $_ret = str_replace("%inprice%", $NP, $_ret); 
          break;

        }

      $_ROWSET .= $_ret;
    };
    $RETURN = str_replace("%main%",$_ROWSET, $OUTER);
    $RETURN = str_replace("%pages%",$pageTXT, $RETURN);
    $RETURN = str_replace("%description%", stripslashes($_catalog[CONTENT]), $RETURN);    
    $RETURN = "Результаты поиска: <br>" . str_replace("%description%", "", $RETURN);

    return $pageTXT.'<hr>'.$RETURN.'<hr>'.$pageTXT;
  }
  
  function properties(){
    $tID = $_GET[template];
    if (!$tID)
      die("template error");
    
    include_once ("core_template.php");
    $template = new Template($_GET[template]);

    switch ($_GET[a]){
      case "":
      case "view":
      $this->printheader();
      $INPUT= &$template->Get("shop.search.input");#CORE_LOAD("search_shop", "input.dat");
      ?>
      <form method="POST" action="?a=save&module=search_shop&template=<?echo $_GET[template]?>">
      <TABLE border="1" width="90%" bgcolor="#ECECEC">
        <tr>
          <td colspan="2" align="center"><h2>Строка поиска</h2></td>
        </tr>
        <tr>
          <td>
            <br><nobr><b>%action%</b> - Адрес Формы</nobr>
            <br><nobr>TERM - Имя поля</nobr>
          </td>
          <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="INPUT"><?=$INPUT;?></TEXTAREA></td>
        </tr>
      </TABLE>
      <center><INPUT type="submit" value="Принять" class="mainoption"></center>
      </FORM>
      <?
    break;

    case "save":
      $template->Set("shop.search.input", $_POST[INPUT]);
      $template->Save();      
      header("Location: ?action=view&module=search_shop&template=$_GET[template]");
    break;
    };
  }
};
$info = array(
  'plugin'      => "search_shop",
  'cplugin'     => "eeSearch_shop",
  'pluginName'    => "Поиск в магазине",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>1,
  'ISSPECIAL'     =>1,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>