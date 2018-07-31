<?
class eePath_shop{
  var $conf;
  function printheader(){
  ?><html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"><body><?
  }
  function eePath_shop($conf)
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
  function getFullPath_shop($id){
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
  function render($id, $fid, $template)
  {
    $conf = $this->conf;
    #print_r ($template);
    $OUTER  = $template->Get("shop.path.outer");
    $COMMON = $template->Get("shop.path.common");;
    $ACTIVE = $template->Get("shop.path.active");
    $path = split("/", $_GET[path]);
    $id =  (int)$path[1];
    if ($id==0)
      return;
      $_sql="SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE `ID`=$id";
      $_result=mysql_query($_sql, $conf[DB]);
      $_row=mysql_fetch_assoc($_result);
        $_names[$_tmp1] = $_row[TITLE];
      $_namesID[$_tmp1] = $_row[ID];
        $_tmp1=0;
        while ($_row[PARENT] != 0)
    {
          $_tmp_id = $_row[PARENT];
          mysql_free_result($_result);
          $_sql="SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=$_tmp_id";
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
      $_sql="SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=". $_namesID[$_tmp2];
          $_result=mysql_query($_sql, $conf[DB]);
          $_row=@mysql_fetch_assoc($_result);

      if ($_row)
        if ($_namesID[$_tmp2])
          $_tmp = str_replace("%link%", "./shop/". $_namesID[$_tmp2]."", $_tmp);

        $_tmp1--;
      $_ret = $_ret . $_tmp;
      $_tmp2++;
    };
    $_ret = str_replace("%link%", "./shop/$id", $_ret);//If the starting region is not processed.
    return str_replace("%main%", $_ret, $OUTER);
    
  }
  function install(){
    @registerAccess("module_path_shop_design", "Крошка/Дизайн");
    return 1;
  }
  function properties(){
    $tID = $_GET[template];
    if (!$tID)
      die("template error");
    
    include_once ("core_template.php");
    $template = new Template($_GET[template]);
    switch ($_GET[a]){  
      case "":
        $this->printheader();
        $OUTER    = &$template->Get("shop.path.outer");#CORE_LOAD("Path_shop", "outer.dat");
        $COMMON   = &$template->Get("shop.path.common");#CORE_LOAD("Path_shop", "common.dat");
        $ACTIVE   = &$template->Get("shop.path.active");#CORE_LOAD("Path_shop", "active.dat"); 
        ?><form method="POST" action="?a=save&module=Path_shop&template=<?echo $_GET[template]?>">
        <TABLE border="1" width="90%" bgcolor="#CCCCCC">
          <tr>
            <td>Обрамление Блока<br><b>%main%</b> - Содержание</td>
            <td width="100%"><TEXTAREA rows="3" style="WIDTH: 100%"  name="OUTER"><?=$OUTER;?></TEXTAREA></td>
          </tr>
          <tr>
            <td>Обрамление Элемента
            <br><b>%link%</b> - ссылка на раздел
            <br><b>%text%</b> - называние раздела
            </td>
            <td width="100%"><TEXTAREA rows="8" style="WIDTH: 100%"  name="COMMON"><?=$COMMON;?></TEXTAREA></td>
          </tr>
          <tr>
            <td>Обрамление Активного Элемента
            <br><b>%text%</b> - называние раздела       
            </td>
            <td width="100%"><TEXTAREA rows="8" style="WIDTH: 100%"  name="ACTIVE"><?=$ACTIVE;?></TEXTAREA></td>
          </tr>
        </TABLE>
        <center><INPUT type="submit" value="Принять" class="mainoption"></center>
        <FORM>
        <?
      break;
      case "save":
        $template->Set("shop.path.outer", $_POST[OUTER]);
        $template->Set("shop.path.common", $_POST[COMMON]);
        $template->Set("shop.path.active", $_POST[ACTIVE]);
        $template->Save(); 
        header("Location: ?module=Path_shop&template=$_GET[template]");
      break;
    }
  }
};
$info = array(
  'plugin'      => "Path_shop",
  'cplugin'     => "eePath_shop",
  'pluginName'    => "Крошка Магазин",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>