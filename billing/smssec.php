<?php

include ('../db.php');
$key="d87f89ccf9f42c0798d496c6acca0c28eaed751b63ff0e268e4eb4488f9104cd";



$s = $_POST[s];
$p = $_POST[p];

if ( $s != md5($p.$key)) die("Wrong key");


$params = base64_decode($p);
$list = explode ('&',$params);


$d = array();

foreach($list as $item){
  $pair = explode ('=',$item);
  $d[$pair[0]] =  urldecode($pair[1]);
}

#print_r($_POST);

$key = rand(1000000,9999999);


$SQL = "insert into smspay(`smsid`,`number`,`operator`,`price`,`key`,`phone`) VALUES(";
$SQL .="'$d[smsid]', '$d[num]','$d[operator_id]','$d[cost_rur]','$key','$d[user_id]')";
$r =mysql_query($SQL) or die (mysql_error());

echo "SMS Vash parol dla oplaty: $key";
?>
