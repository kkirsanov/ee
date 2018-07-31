<?
include ('../db.php');
mysql_query("SET NAMES 'utf8'");

switch ($_GET[a]){

case "":
case "list":
    $sql = "select * from $conf[DB_PREFIX]zakaz where partner>0 and (not commited) and state='ok' limit 20";
    $r = mysql_query($sql) or die (mysql_error());

    $arr=Array();
    while ($z = mysql_fetch_assoc($r)){
      $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$z[ID]";
      $res2= mysql_query($sql, $conf[DB]) or die(mysql_error());
      $i=0;
      $goodsList="";
      $isOk=false;
      $sum=0.0;
      while ($goods = mysql_fetch_assoc($res2)){
        $isOk=true;
        $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
        $res3 = @mysql_query($sql, $conf[DB]);
        $cat = @mysql_fetch_assoc($res3);
        if ($goods[COUNT]>=1){
           $sum+=(float)($goods[PRICE]*$goods[COUNT]);
        }
      }
      $arr[] = Array("money"=>$sum, "partner"=>$z[partner], "order"=>$z[ID], "date"=>$z[DATE_START], "referal"=>($z[referal]));
    }

    include('spyc.php4');
    $yaml = Spyc::YAMLDump($arr,4,60);

    print_r($yaml);
break;
case "ref":
    $sql = "select * from $conf[DB_PREFIX]referer limit 20";
    $r = mysql_query($sql) or die (mysql_error());

    $arr=Array();
    while ($z = mysql_fetch_assoc($r))
      $arr[] = Array("partner"=>$z[partner], "id"=>$z[id], "date"=>$z['date'], "referer"=>($z['referer']), "url"=>($z[url]));

    include('spyc.php4');
    $yaml = Spyc::YAMLDump($arr,4,60);

    print_r($yaml);
break;

case "commitorder":
  $order = (int)$_GET[order];
  if ($order){
    $sql = "UPDATE $conf[DB_PREFIX]zakaz set commited = true where ID=$order";
    $r = mysql_query($sql) or die (mysql_error());
    echo "ok";
  }
break;
case "commitref":
  $ref = (int)$_GET[ref];
  if ($ref){
    $sql = "delete from $conf[DB_PREFIX]referer where id=$ref";
    $r = mysql_query($sql) or die (mysql_error());
    echo "ok";
  }
break;
}
?>