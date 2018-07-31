<?php
# СМС-Доступ 2008
# Скрипт для ответа на запрос Биллинга

# Вывод ошибок нежелателен
ini_set('display_errors', 0);
error_reporting(0);
include ('../db.php');
# Задаем ключ (идентификатор) проекта, который указан в разделе 'Список проектов' в вашем аккаунте
$project_md5 = "9f8ce0ae97758ef9c9bfcfffbbc0da40";

# Проверяем наличие данных
if (!isset($_POST['_md5_hash']) || !isset($_POST['_session_code']) || !isset($_POST['_sms_id']) || !isset($_POST['_sms_number']) || !isset($_POST['_sms_operator']) || !isset($_POST['_sms_phone']) || !isset($_POST['_sms_message']) || !isset($_POST['_sms_price'])) return_result("err void", true);
if (!$_POST['_md5_hash'] || !$_POST['_session_code'] || !$_POST['_sms_id'] || !$_POST['_sms_number'] || !$_POST['_sms_operator'] || !$_POST['_sms_phone'] || !$_POST['_sms_message'] || !$_POST['_sms_price']) return_result("err false", true);

# Проверяем целостность данных
$_md5hash = md5($project_md5.$_POST['_session_code'].$_POST['_sms_id'].$_POST['_sms_number'].$_POST['_sms_operator'].$_POST['_sms_phone'].stripslashes($_POST['_sms_message']).$_POST['_sms_price']);
if ($_md5hash != $_POST['_md5_hash']) return_result("err hash", true);

/* Напоминаем, что в случае наличия параметра _is_debug производится ТЕСТИРОВАНИЕ проекта,
если Вы ведете внутренние учеты, зачисляете средства и так далее - учтите, эти запросы нами не оплачиваются! */

# Возвращаем результат и завершаем работу

$key = rand(1000000,9999999);
$country = urldecode($_POST[_sms_country]);
$number  = $_POST[_sms_number];
$operator= urldecode($_POST[_sms_operator]);

$SQL = "SELECT * from smstarif where `number`=$number and `operatorlatin`='$operator' and `code`='$country'";
$r=mysql_query($SQL);
$tarif=mysql_fetch_assoc($r);

$_POST[_sms_price] = $tarif[usdprice];


$SQL = "insert into smspay(`smsid`,`number`,`operator`,`country`,`price`,`key`,`phone`) VALUES(";
$SQL .="'$_POST[_sms_id]', '$_POST[_sms_number]','$_POST[_sms_operator]','$_POST[_sms_country]',$_POST[_sms_price],'$key','$_POST[_sms_phone]')";
      
$r =mysql_query($SQL) or die (mysql_error());


return_result(
  "Ваш пароль для оплаты $key"
);

# Делаем все необходимые учеты, проверки и определяем ответ абоненту
/*
  Входящие данные (даны исключительно для ознакомления и не являются действительными):
  _is_debug = 1 // Параметр тестирования проекта, по-умолчанию не передается
  _md5_hash = a123456789b123456789c123456789d1 // Ключ проверки целостности данных
  _session_code = a123456789b123456789c123456789d1 // Ключ текущей сессии
  _sms_id=1234567890 // Уникальный идентификатор смс сообщения
  _sms_number=1234 // Короткий номер на который прислано смс сообщение
  _sms_operator=Megafon // Название оператора, латиница, короткое
  _sms_operator_full=Megafon_moscow // Название оператора, латиница, полное
  _sms_phone=7912xxxx345 // Номер абонента приславшего смс сообщение
  _sms_country=ru // Страна абонента приславшего смс сообщение
  _sms_message=ttslovo // Полный текст сообщения
  _sms_plain=dHRzbG92bw%3D%3D // Текст сообщения rawurlencoded base64_encoded в кодировке utf-8
  _sms_price=12.34 // Ваша прибыль с данного смс сообщения в системе СМС Доступ в рублях
  _sms_exchrate=25.00 // Текущий курс отношения рубля к доллару в системе СМС Доступ
  _sms_trusted=3 // Опциональный параметр, с указанием доверия номеру абонента в виде цифры от 0 до 10
*/

# Обработка входящего сообщения.
# Для получения текста сообщения Вам потребуется произвести следующие операции:
/*
$message_text = rawurldecode($_POST['_sms_plain']); // Убрать URL-кодирование
$message_text = base64_decode($message_text); // Перевести данные из MIME base64
$message_text = iconv("utf-8", "cp1251", $message_text); // Поменять кодировку с utf-8 на cp1251
$message_text = stripslashes($message_text); // Удалить возможные слэш символы
*/

# Для большего удобства так же передается параметр _sms_message в котором все эти действия уже произведены,
# но если же сообщения приходящие Вам достаточно большие, включают в себя спец символы и русский язык, то лучше работать с параметром _sms_plain

# Ваша проверка данных и учет в системе
# ! В случае если получен параметр _is_debug, то учет в системе делать не следует. Был произведен тест скрипта на работоспособность.
# ! вернуть ответ в случае наличия параметра _is_debug необходимо в следующем формате <SMSDOSTUP>OK</SMSDOSTUP>

# Выдаем ответ для передачи клиенту
# ! Учтите обязательность наличия открывающегося <SMSDOSTUP> и закрывающегося </SMSDOSTUP> тегов
# Содержимое внутри тегов и будет передано клиенту, в случае неверного формата ответа, смс не будет засчитана
# При ответе используйте кодировку Windows-1251

# Функция передачи данных
function return_result($message, $is_error = false) {
  if ($is_error) exit("<SMSDERR>".stripslashes($message)."</SMSDERR>");
  exit("<SMSDOSTUP>".stripslashes($message)."</SMSDOSTUP>");
}
?>