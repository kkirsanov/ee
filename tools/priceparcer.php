<?phpsession_start();if (!(int)$_SESSION[manager_id])  die();

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
     $names = explode("\n", $_POST[names]);//’γ«μαª ο ƒ ΰ¬®­μ € ’“‹€-209
      echo   "<html><head>"
                .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>'
                ."<META name=\"GENERATOR\" content=\"EasyEngine 3.0 www.lmm.ru\">"
                .'</head><body>'
                ;
      foreach($names as $name){
        
        $tmp = explode("\t", $name);
        $name = $tmp[0];
        $price = $tmp[1];
        $price= str_replace(",", ".", $price);

        $pr = "";
        if ((int)$price){
          $pr = ", PRICE = $price ";
        }

        $name= str_replace("\n", "", $name);
        $name= str_replace("\r", "", $name);
        $name= str_replace("\t", "", $name);
        $SQL = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=0 $pr WHERE FIRM=$_GET[firm] and TITLE='$name'";
        mysql_query($SQL);

        echo "$name - done<br>";
      }
    };
  break;
  };

?>