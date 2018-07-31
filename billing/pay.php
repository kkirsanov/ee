<?
include ('../db.php');
mysql_query("SET NAMES 'utf8'");

$kurs=30.0;

function pr(){
echo "<html><title></title><head>"
  .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>';

}
if ($_GET[step]==1 or $_GET[step]==0){
    $countries = array();
    session_start();
    $_SESSION[summ]=0;
    
    echo "Выберете страну: <ul>";
    $sql = "select distinct country from smstarif";
    $r=mysql_query($sql) or die(mysql_error());
    while ($country = @mysql_fetch_assoc($r)){
      $co = urlencode($country[country]);
      echo "<li> <a href='?step=2&country=$co&user=$_GET[user]&pay=$_GET[pay]'>$country[country]</a>";
    };
    echo "</ul>";
};
if ($_GET[step]==2){
  session_start();
  
  echo "Страна: <b>$_GET[country]</b><a href='?step=1&user=$_GET[user]&pay=$_GET[pay]'>сменить</a><br>";
  echo "Выберете оператора: <ul>";

  $country = urldecode($_GET[country]);
  $sql = "select distinct operatorname from smstarif where country='$country' order by operatorname";
  $r=mysql_query($sql) or die(mysql_error());

  while ($op = @mysql_fetch_assoc($r)){
      $opu = urlencode($op[operatorname]);
      echo "<li> <a href='?step=3&country=$_GET[country]&operator=$opu&user=$_GET[user]&pay=$_GET[pay]'>$op[operatorname]</a>";
  };
  echo "</ul>";
}
  
if ($_GET[step]==3){
  session_start();
  $country = urldecode($_GET[country]);
  $opname = urldecode($_GET[operator]);
  

  echo "Страна: <b>$_GET[country]</b><a href='?step=1&user=$_GET[user]&pay=$_GET[pay]'>сменить</a><br>";
  echo "Оператор: <b>$opname</b> <a href='?step=2&country=$cnt&user=$_GET[user]&pay=$_GET[pay]'>сменить</a><br>";
  echo "Выберете тариф, все цены указаны без НДС:<ul>";
  
  $sql = "select * from smstarif where country='$country' and operatorname='$opname' order by usdprice";
  $r=mysql_query($sql) or die(mysql_error());
  while ($item = @mysql_fetch_assoc($r)){
      echo "<li><b>$item[usdprice]</b>$, - номер $item[number]";
  };
  echo "</ul><br>";
  echo "Пошлите <b>710doc</b> на выбранный номер, получите ответный SMS c котодом и введите его: ";
  echo "<form method=POST action=\"?step=4&country=$_GET[country]&operator=$_GET[operator]&user=$_GET[user]&pay=$_GET[pay]\"><input type=text name=code><input type=submit></form>";

  $pr = round(($_GET[pay]-$_SESSION[summ]),2);
  if ($pr>=0.1){
    echo "<br>Вы пока заплатили <b>$_SESSION[summ]</b>$ и Вам нехватает $pr $";
  }else{
    echo "<br>Нужная сумма накоплена и переведена на счет!<br>Вы <a href='../register/profile/history'>вернуться в магазин</a> и совершить покупку";
  };
};
if ($_GET[step]==4){
  $key = $_POST[code];
  $userID = (int)$_GET[user];
  $SQL = "SELECT * from smspay where `key` = '$key' and active=1";
  $r = mysql_query($SQL) or die (mysql_error());
  if($tiket = @mysql_fetch_assoc($r)){
     if ($userID){
        session_start();
        $_SESSION[summ]+=round(((float)$tiket[price]),2);
        $SQL = "UPDATE $conf[DB_PREFIX]accounts set money=money+$tiket[price]*$kurs where ID = $userID LIMIT 1";
        $r = mysql_query($SQL) or die (mysql_error());
        $SQL = "UPDATE smspay set active=0 where id=$tiket[id]";
        $r = mysql_query($SQL) or die (mysql_error());
     }
  }
  header("Location: ?step=3&country=$_GET[country]&operator=$_GET[operator]&user=$_GET[user]&pay=$_GET[pay]");
};

?><br><br>
<b>Сайт поддержки биллинга</b> <a href="http://sms2ru-help.ru">тут&gt;&gt;</a>