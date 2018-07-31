<?phpsession_start();if (!(int)$_SESSION[manager_id])  die("Asd");

include ("../db.php");  switch ($_GET[a]){
  case "":
     echo   "<html><head>"
                .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>'
                ."<META name=\"GENERATOR\" content=\"EasyEngine 3.0 www.lmm.ru\">"
                .'</head><body>'
                ;
    $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms order by NAME";
    $tmpres = mysql_query($tmpsql, $conf[DB]);
    while($fir = mysql_fetch_assoc($tmpres)){
     if ($fir[ID]==$_GET[firm])
       $fir[NAME] = "<b>$fir[NAME]</b>";
     echo "<a href=\"priceparcer.php?firm=$fir[ID]\">$fir[NAME] </a>";
    };
    if ((int)$_GET[firm])
         echo "<form method=post action=\"priceparcer.php?firm=$_GET[firm]&a=save\"><textarea name=\"names\" rows=30 cols=40></textarea><input type=submit></form>";
  break;
  case "save":
    $_GET[firm] = (int)$_GET[firm];
    if ($_GET[firm]){
      $SQL = "UPDATE $conf[DB_PREFIX]catalog SET inprice=2 WHERE FIRM=$_GET[firm]";
      $tmpres = mysql_query($SQL, $conf[DB]) or die (mysql_error());
     $names = explode("\n", $_POST[names]);//���᪠� ��ମ�� ���� ����-209
      echo   "<html><head>"
                .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>'
                ."<META name=\"GENERATOR\" content=\"EasyEngine 3.0 www.lmm.ru\">"
                .'</head><body>'
                ;
      foreach($names as $name){
        $name= str_replace("\n", "", $name);
        $name= str_replace("\r", "", $name);
        $name= str_replace("\t", "", $name);
        $SQL = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=0 WHERE FIRM=$_GET[firm] and TITLE='$name'";
        /*

        $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE STATE= '$_GET[f]' ORDER BY ID desc";
        $res = mysql_query($sql, $conf[DB]);
        while ($zak = @mysql_fetch_assoc($res)){
          echo  "<h3>$zak[ID]</h3>".
          "<a href=\"?id=$zak[ID]&module=cart&a=editorder\" target=_BALNK >Редактировать</a> ".
          "<a href=\"?id=$zak[ID]&module=cart&a=print\" target=_BALNK >Печать</a> ".
          " <a href=\"?id=$zak[ID]&module=cart&a=print2\" target=_BALNK >Платежка</a> ".
          " <a href=\"?id=$zak[ID]&module=cart&a=print3\" target=_BALNK >Счет</a> ".
          " <a href=\"?id=$zak[ID]&module=cart&a=mail\" target=_BALNK >Письмо</a><br>
          <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=commitmove&f=new\">Новые</a> 
          <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=commitmove&f=repeat\">Отложен</a> 
          <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=commitmove&f=process\">В обработке</a> 
          <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=commitmove&f=go\">Доставляется</a> 
          <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=commitmove&f=ok\">Доставлен</a> 
          <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=commitmove&f=del\">Удален</a>
          <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=commitmove&f=out\">Out of order</a>
          ";
          $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
          $res2= mysql_query($sql, $conf[DB]);
          $i=0;
          echo  "<br>";
          while ($goods = @mysql_fetch_assoc($res2)){
            $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
            $res3 = @mysql_query($sql, $conf[DB]) or die(mysql_error());
            $cat = @mysql_fetch_assoc($res3)or die(mysql_error());
            $i++;
            
            $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms where id=$cat[FIRM]";
            $res3= @mysql_query($SQL, $conf[DB]);
            $firm=@mysql_fetch_assoc($res3);

            $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $cat[PARENT]";
            $tmpres = @mysql_query($tmpsql, $conf[DB]);
            $tmpcat = @mysql_fetch_assoc($tmpres);

            echo "$i) <b>$firm[NAME]</b> <i>$tmpcat[TITLE]</i> $cat[TITLE] - $goods[COUNT](<b>$goods[PRICE]</b>)<br>";
          }
//          echo $zak[CONTENT];
          echo "<br>$zak[DATE_START] - (<i> <b>$zak[NAME]</b> - $zak[TEL] - $zak[ADDR])</i><hr>";

        */       $tmpres = mysql_query($SQL, $conf[DB]) or die (mysql_error());
        echo "$name - done<br>";
      }
    };
  break;
  };

?>