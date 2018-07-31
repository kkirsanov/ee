<?php
# СМС-Доступ 2008
# Скрипт для ответа на запрос Биллинга

# Вывод ошибок нежелателен
ini_set('display_errors', 0);
error_reporting(0);
include ('../db.php');
# Задаем ключ (идентификатор) проекта, который указан в разделе 'Список проектов' в вашем аккаунте

$key = rand(1000000,9999999);
$number  = $_GET[num];
$operator= urldecode($_GET[operator_id]);

#$SQL = "SELECT * from smstarif where `number`=$number and `operatorlatin`='$operator' and `code`='$country'";
#$r=mysql_query($SQL);
#$tarif=mysql_fetch_assoc($r);
#$_POST[_sms_price] = $tarif[usdprice];

#die($_GET[smsid]);
if ($_GET[smsid] ==0)
 die ("Error");

$SQL = "insert into smspay(`smsid`,`number`,`operator`,`price`,`key`,`phone`) VALUES(";
$SQL .="'$_GET[smsid]', '$_GET[num]','$_GET[operator_id]','$_GET[cost_rur]','$key','$_GET[user_id]')";
$r =mysql_query($SQL) or die (mysql_error());

echo "SMS Vash parol dla oplaty: $key";
?>
