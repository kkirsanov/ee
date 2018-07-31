<? class eeMail{
  var $conf;
  function eeMail($conf) {
    $this->conf = $conf;
  }
  function install(){
    //@registerAccess("module_gallery_design", "Почта/Дизайн");
    return 1;
  }
  function add() {
    return 0;
  }
  function del($id) {
    return 0;
  }
  function render($regionID = 0, $id, &$template) {
    $TEMPLATE =& $template->Get("mail.template");
    $ADDR =& $template->Get("mail.addr");
    $TEMPLATE2 = str_replace("<tarea", "<textarea", $TEMPLATE);
    $TEMPLATE2 = str_replace("</tarea>", "</textarea>", $TEMPLATE2);
    return "<form method=POST action=\"./mail\">$TEMPLATE2</form>";
  }
  function renderEx($id, &$template) {
    $RETURN = "";
    $MAIL= &$template->Get("mail.addr");
    $_headers .= "From: <$MAIL>\n";
    $_headers .= "X-Sender: <$MAIL>\n";
    $_headers .= "X-Mailer: PHP/mail()\n"; //mailer
    $_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
    $_headers .= "Return-Path: <$MAIL>\n";
    $_headers .= "Content-type: text/html; charset=windows-1251\r\n";
    $_headers .= "cc: $MAIL\n"; // CC to
    $_headers .= "bcc: $MAIL";
    mail($MAIL, iconv("utf-8", "windows-1251","Письмо с сайта."), iconv("utf-8", "windows-1251",$_POST[TEXT]), $_headers);
    header ("Location: ./?id=$_GET[id]");
    die();
  }

  function properties() {
    $conf = $this->conf;

    switch ($_GET[a]){
      case "":
        $tID = $_GET[template];
        if (!$tID)
        die("template error");

        include_once ("core_template.php");
        $template = new Template($_GET[template]);

        echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">';
        $ADDR=&  $template->Get("mail.addr");
        $TEMPLATE= $template->Get("mail.template");
        ?>
<form method="POST"
  action="?a=save&module=mail&template=<?echo $_GET[template];?>">
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Шаблон</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>TEXT - Название поля с текстом</nobr> Внимание! Вместо тега
    TEXTAREA используйте tarea!!!</td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="TEMPLATE"><?=$TEMPLATE;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Элемент</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    Адрес электронной почты</td>
    <td width="100%"><input type=text name="ADDR" value="<?=$ADDR?>"></td>
  </tr>
</TABLE>

<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</FORM>
        <?
        break;
case "save":
  $tID = $_GET[template];
  if (!$tID)
  die("template error");

  include_once ("core_template.php");
  $template = new Template($_GET[template]);

  $template->Set("mail.template", $_POST[TEMPLATE]);
  $template->Set("mail.addr", $_POST[ADDR]);
  $template->Save();
  header("Location: ?module=mail&template=$_GET[template]");
  break;
    };
  }
};
$info = array(
  'plugin'      => "mail",
  'cplugin'     => "eeMail",
  'pluginName'     => "Почта",
  'ISMENU'        =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'        =>1,
  'ISEXTRABLOCK'    =>1,
  'ISSPECIAL'     =>1,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>
