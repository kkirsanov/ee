<?php
# ���-������ 2008
# ������ ��� ������ �� ������ ��������

# ����� ������ �����������
ini_set('display_errors', 0);
error_reporting(0);
include ('../db.php');
# ������ ���� (�������������) �������, ������� ������ � ������� '������ ��������' � ����� ��������
$project_md5 = "9f8ce0ae97758ef9c9bfcfffbbc0da40";

# ��������� ������� ������
if (!isset($_POST['_md5_hash']) || !isset($_POST['_session_code']) || !isset($_POST['_sms_id']) || !isset($_POST['_sms_number']) || !isset($_POST['_sms_operator']) || !isset($_POST['_sms_phone']) || !isset($_POST['_sms_message']) || !isset($_POST['_sms_price'])) return_result("err void", true);
if (!$_POST['_md5_hash'] || !$_POST['_session_code'] || !$_POST['_sms_id'] || !$_POST['_sms_number'] || !$_POST['_sms_operator'] || !$_POST['_sms_phone'] || !$_POST['_sms_message'] || !$_POST['_sms_price']) return_result("err false", true);

# ��������� ����������� ������
$_md5hash = md5($project_md5.$_POST['_session_code'].$_POST['_sms_id'].$_POST['_sms_number'].$_POST['_sms_operator'].$_POST['_sms_phone'].stripslashes($_POST['_sms_message']).$_POST['_sms_price']);
if ($_md5hash != $_POST['_md5_hash']) return_result("err hash", true);

/* ����������, ��� � ������ ������� ��������� _is_debug ������������ ������������ �������,
���� �� ������ ���������� �����, ���������� �������� � ��� ����� - ������, ��� ������� ���� �� ������������! */

# ���������� ��������� � ��������� ������

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
  "��� ������ ��� ������ $key"
);

# ������ ��� ����������� �����, �������� � ���������� ����� ��������
/*
  �������� ������ (���� ������������� ��� ������������ � �� �������� ���������������):
  _is_debug = 1 // �������� ������������ �������, ��-��������� �� ����������
  _md5_hash = a123456789b123456789c123456789d1 // ���� �������� ����������� ������
  _session_code = a123456789b123456789c123456789d1 // ���� ������� ������
  _sms_id=1234567890 // ���������� ������������� ��� ���������
  _sms_number=1234 // �������� ����� �� ������� �������� ��� ���������
  _sms_operator=Megafon // �������� ���������, ��������, ��������
  _sms_operator_full=Megafon_moscow // �������� ���������, ��������, ������
  _sms_phone=7912xxxx345 // ����� �������� ����������� ��� ���������
  _sms_country=ru // ������ �������� ����������� ��� ���������
  _sms_message=ttslovo // ������ ����� ���������
  _sms_plain=dHRzbG92bw%3D%3D // ����� ��������� rawurlencoded base64_encoded � ��������� utf-8
  _sms_price=12.34 // ���� ������� � ������� ��� ��������� � ������� ��� ������ � ������
  _sms_exchrate=25.00 // ������� ���� ��������� ����� � ������� � ������� ��� ������
  _sms_trusted=3 // ������������ ��������, � ��������� ������� ������ �������� � ���� ����� �� 0 �� 10
*/

# ��������� ��������� ���������.
# ��� ��������� ������ ��������� ��� ����������� ���������� ��������� ��������:
/*
$message_text = rawurldecode($_POST['_sms_plain']); // ������ URL-�����������
$message_text = base64_decode($message_text); // ��������� ������ �� MIME base64
$message_text = iconv("utf-8", "cp1251", $message_text); // �������� ��������� � utf-8 �� cp1251
$message_text = stripslashes($message_text); // ������� ��������� ���� �������
*/

# ��� �������� �������� ��� �� ���������� �������� _sms_message � ������� ��� ��� �������� ��� �����������,
# �� ���� �� ��������� ���������� ��� ���������� �������, �������� � ���� ���� ������� � ������� ����, �� ����� �������� � ���������� _sms_plain

# ���� �������� ������ � ���� � �������
# ! � ������ ���� ������� �������� _is_debug, �� ���� � ������� ������ �� �������. ��� ���������� ���� ������� �� �����������������.
# ! ������� ����� � ������ ������� ��������� _is_debug ���������� � ��������� ������� <SMSDOSTUP>OK</SMSDOSTUP>

# ������ ����� ��� �������� �������
# ! ������ �������������� ������� �������������� <SMSDOSTUP> � �������������� </SMSDOSTUP> �����
# ���������� ������ ����� � ����� �������� �������, � ������ ��������� ������� ������, ��� �� ����� ���������
# ��� ������ ����������� ��������� Windows-1251

# ������� �������� ������
function return_result($message, $is_error = false) {
  if ($is_error) exit("<SMSDERR>".stripslashes($message)."</SMSDERR>");
  exit("<SMSDOSTUP>".stripslashes($message)."</SMSDOSTUP>");
}
?>