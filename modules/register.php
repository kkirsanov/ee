<?php
class eeRegister{
  var $conf;
  function CreateFile($fname, $fhash){
    if ($h = fopen("http://video.magazindoc.ru/makefile.php?file=$fname&hash=$fhash", 'r'))
    {
      $data="";
      while ($line=fgets($h, 100))
      $data.=$line;
    }
  }
  function eeRegister($conf){
    $this->conf = $conf;
  }

  function install() {
    $conf = $this->conf;
  $SQL="CREATE TABLE `$conf[DB_PREFIX]messages` ("
  ."`MESSAGE` mediumtext,"
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`DATE` datetime default '0000-00-00 00:00:00',"
  ."`TYPE` varchar(45) default NULL,"
  ."`ADMIN` int(11) default '0',"
  ."`USER_ID` int(11) default NULL,"
  ."`STATE` int(10) unsigned NOT NULL default '1',"
  ."primary KEY  (`ID`)"
.") ENGINE=MyISAM CHARACTER SET utf8 ;";
  mysql_query($SQL);

    $SQL ="CREATE TABLE IF NOT EXISTS `$conf[DB_PREFIX]accounts` ("
  ."`ID` int(11) NOT NULL auto_increment,"
  ."`LOGIN` varchar(100) NOT NULL default '',"
  ."`NAME` varchar(100) default '',"
  ."`EMAIL` varchar(100) default '',"
  ."`ADDR` mediumtext NOT NULL,"
  ."`TEL` varchar(45) default NULL,"
  ."`PASSWORD` varchar(100) NOT NULL default '',"
  ."`total` decimal(10,2) default '0.00',"
  ."`money` decimal(10,2) default '0.00',"
  ."`discount` int(10) default '0',"
  ."`discount_admin` int(11) NOT NULL default '-1',"
  ."`resume` text NOT NULL,"
  ."`resume_type` int(10) NOT NULL default '0',"
  ."`rcity` varchar(40) NOT NULL default '',"
  ."`rdop` text NOT NULL,"
  ."`rtel` varchar(40) NOT NULL default '',"
  ."`rmail` varchar(100) NOT NULL default '',"
  ."`rwww` varchar(100) NOT NULL default '',"
  ."`rbird` varchar(40) NOT NULL default '',"
  ."`rname` varchar(200) NOT NULL default '',"
  ."`ban` tinyint(1) NOT NULL default '0',"
  ." PRIMARY KEY  (`ID`)"
.") ENGINE=MyISAM  CHARACTER SET utf8 ;";

    mysql_query($SQL);

    return 1;
  }
  function properties(){
    $action = $_GET[a];
    $task = $_GET[task];
    if ($action == "")
    	$action="ubertemplate";
	
    $conf = $this->conf;
    $id = $_GET[id];
    //*************************************
    switch ($action){
      case "ubertemplate":  
          $tID = $_GET[template];
			$this->printheader();          
			if (!$tID)
				return;

        include_once ("core_template.php");
        $template = new Template($_GET[template]);
        

        $KV = $template->Get("zakaz.kv");
        $PROFILEMAIN  = $template->Get("register.profile.main");
        $MANAGEMAIN= $template->Get("register.manage.main");
        $MANAGEIMAGE= $template->Get("register.manage.image");

        $ZAKAZMAIN= $template->Get("register.zakaz.main");
        $ZAKAZ= $template->Get("register.zakaz.zakaz");
        $ZAKAZEL= $template->Get("register.zakaz.element");
        $SELF= $template->Get("register.profile.self");
        $MESSAGE= $template->Get("register.profile.message");
        $USERGOODSELEMENT= $template->Get("register.profile.usergoodselement");
        $DATE = $template->Get("register.date");
        
        $NAME_PASS= $template->Get("register.error_name_pass");
        $ENAME= $template->Get("register.error_name");
        $CAPTCHA= $template->Get("register.captcha");

        $ENTEROK  = $template->Get("register.enterok");
        $ENTER  = $template->Get("register.enter");
        $REGISTER   = $template->Get("register.register");
        $OK = $template->Get("register.ok");
        $EMAIL = $template->Get("register.email");
        $NAME= $template->Get("register.name");
        $ADDR= $template->Get("register.addr");

        $NEWMESSAGE= $template->Get("register.newmessage");
        $NEWMESSAGERESUME= $template->Get("register.newmessageresume");
        $NEWMESSAGEADMIN= $template->Get("register.newmessageadmin");

        $FAIL= $template->Get("register.fail");
        $BAN= $template->Get("register.ban");

        ?>
        <form method="POST" action="properties.php?a=saveubertemplate&module=register&template=<?=$_GET[template]?>">
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Общий</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%PRICE%</b> - Цена</nobr> <br>
    <nobr><b>%NAME%</b> - Имя</nobr> <br>
    <nobr><b>%DATE%</b> - Дата</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="KV"><?=$KV;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Вход</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%link%</b> - Адрес для Общего профиля</nobr> <br>
    <nobr><b>%link2%</b> - Адрес для Заказов</nobr> <br>
    <nobr><b>%main%</b> - Основное смодержание</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="PROFILEMAIN"><?=$PROFILEMAIN;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>main</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%image%</b> - image</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="MANAGEMAIN"><?=$MANAGEMAIN;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Заказы</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%link%</b> - Заказ Подробнее</nobr> <br>
    <nobr><b>%date%</b> - Дата</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="MANAGEIMAGE"><?=$MANAGEIMAGE;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Общий</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%main%</b> - Список Заказов</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="ZAKAZMAIN"><?=$ZAKAZMAIN;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Заказы</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%link%</b> - Заказ Подробнее</nobr> <br>
    <nobr><b>%date%</b> - Дата</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="ZAKAZ"><?=$ZAKAZ;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Заказы</h2>
    </td>
  </tr>
  <tr>
    <td></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="ZAKAZEL"><?=$ZAKAZEL;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Вход</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%action%</b> - Адрес для редактирования профиля</nobr> <br>
    <nobr><b>%actionsend%</b> - Адрес Формы для отсылки сообщений</nobr> <br>
    <nobr><b>%fio%</b> - Фамилия Имя Отчество</nobr> <br>
    <nobr><b>%mail%</b> - Адрес электронной почты</nobr> <br>
    <nobr><b>text</b> - сообщения</nobr> <br>
    <nobr><b>%MESSAGES%</b> - сообщения</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="SELF"><?=$SELF;?></TEXTAREA></td>
  </tr>
</TABLE>


<form method="POST" action="properties.php?a=save_self&module=register">
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>User goods</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%goods_price%, %goods_name%, %goods_kink%, %dellink%</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="USERGOODSELEMENT"><?=$USERGOODSELEMENT;?></TEXTAREA></td>
  </tr>
</TABLE>

<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Вход</h2>
    </td>

  </tr>
  <tr>
    <td><br>
    <nobr><b>%DATE%</b> - Дата</nobr> <br>
    <nobr><b>%text%</b> - Текст сообщения</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="MESSAGE"><?=$MESSAGE;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Дата</h>
    
    </td>
  </tr>
  <tr>
    <td>Формат Даты</td>
    <td width="100%"><TEXTAREA rows="2" style="WIDTH: 100%" name="DATE"><?=$DATE;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Ошибки</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>Если Имя\Пароль - несовпадают</nobr></td>
    <td width="100%"><TEXTAREA rows="5" style="WIDTH: 100%"
      name="NAME_PASS"><?=$NAME_PASS;?></TEXTAREA></td>
  </tr>
  <tr>
    <td><br>
    <nobr>Если такое имя уже существует</nobr></td>
    <td width="100%"><TEXTAREA rows="5" style="WIDTH: 100%" name="ENAME"><?=$ENAME;?></TEXTAREA></td>
  </tr>
  <tr>
    <td><br>
    <nobr>Captcha</nobr></td>
    <td width="100%"><TEXTAREA rows="5" style="WIDTH: 100%" name="CAPTCHA"><?=$CAPTCHA;?></TEXTAREA></td>
  </tr>
</TABLE>

<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Вход</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%action%</b> - Адрес для формы</nobr> <br>
    <nobr><b>%error%</b> - Сообщение Об ошибке</nobr> <br>
    <nobr><b>%login%</b> - сохраненный Логин</nobr> <br>
    <nobr><b>%pass%</b> - сохраненный Пароль</nobr> <br>
    <nobr><b>LOGIN</b> - Имя для формы</nobr> <br>
    <nobr><b>PASS</b> - Пароль для формы</nobr> <br>
    <nobr><b>%link%</b> - Адрес для регистрации</nobr> <br>
    <nobr><b>%link2%</b> - Адрес для Профиля</nobr> <br>
    <nobr><b>%link3%</b> - Адрес для Восстановления пароля</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="ENTER"><?=$ENTER;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>OK</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%action%</b> - Адрес для формы</nobr> <br>
    <nobr><b>%error%</b> - Сообщение Об ошибке</nobr> <br>
    <nobr><b>%login%</b> - сохраненный Логин</nobr> <br>
    <nobr><b>%pass%</b> - сохраненный Пароль</nobr> <br>
    <nobr><b>LOGIN</b> - Имя для формы</nobr> <br>
    <nobr><b>PASS</b> - Пароль для формы</nobr> <br>
    <nobr><b>%link%</b> - Адрес для регистрации</nobr> <br>
    <nobr><b>%link2%</b> - Адрес для Профиля</nobr> <br>
    <nobr><b>%link3%</b> - Адрес для Восстановления пароля</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="ENTEROK"><?=$ENTEROK;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Регистрация</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%action%</b> - Адрес для формы</nobr> <br>
    <nobr><b>LOGIN</b> - Имя для формы</nobr> <br>
    <nobr><b>PASS</b> - Пароль для формы</nobr> <br>
    <nobr><b>PASS2</b> - Пароль для формы</nobr> <br>
    <nobr><b>EMAIL</b> - EMAIL</nobr> <br>
    <nobr><b>%error%</b> - Сообщение Об ошибке</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="REGISTER"><?=$REGISTER;?></TEXTAREA></td>
  </tr>
</TABLE>

<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>NEWMESSAGE</h2>
    </td>
  </tr>
  <tr>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="NEWMESSAGE"><?=$NEWMESSAGE;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>NEWMESSAGE ADIMN</h2>
    </td>
  </tr>
  <tr>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="NEWMESSAGEADMIN"><?=$NEWMESSAGEADMIN;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>NEWMESSAGE RESUME</h2>
    </td>
  </tr>
  <tr>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="NEWMESSAGERESUME"><?=$NEWMESSAGERESUME;?></TEXTAREA></td>
  </tr>
</TABLE>


<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>FAIL</h2>
    </td>
  </tr>
  <tr>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="FAIL"><?=$FAIL;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>BAN</h2>
    </td>
  </tr>
  <tr>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="BAN"><?=$BAN;?></TEXTAREA></td>
  </tr>
</TABLE>

<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Уведомление о регитрации</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr><b>%addr%</b> - Адрес сайта</nobr> <br>
    <nobr><b>%name%</b> - Имя</nobr> <br>
    <nobr><b>%pass%</b> - Пароль</nobr></td>
    <td width="100%"><TEXTAREA rows="8" style="WIDTH: 100%" name="OK"><?=$OK;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Дополнительная информация</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    EMAIL сайта</nobr></td>
    <td width="100%"><input type="text" value="<?=$EMAIL;?>" name="EMAIL">
  
  </tr>
  <tr>
    <td><br>
    Название сайта</nobr></td>
    <td width="100%"><input type="text" value="<?=$NAME;?>" name="NAME">
  
  </tr>
  <tr>
    <td><br>
    Адрес сайта</nobr></td>
    <td width="100%"><input type="text" value="<?=$ADDR;?>" name="ADDR">
  
  </tr>
</TABLE>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</form><?


      break;
      case "saveubertemplate":
        $tID = $_GET[template];
          if (!$tID)
            die("template error");
    
        include_once ("core_template.php");
        $template = new Template($_GET[template]);
        //###################
        $template->Set("zakaz.kv", $_POST[KV]);
        $template->Set("register.profile.main", $_POST[PROFILEMAIN]);
        $template->Set("register.manage.main", $_POST[MANAGEMAIN]);
        $template->Set("register.manage.image", $_POST[MANAGEIMAGE]);
        $template->Set("register.zakaz.main", $_POST[ZAKAZMAIN]);
        $template->Set("register.zakaz.zakaz", $_POST[ZAKAZ]);
        $template->Set("register.zakaz.element", $_POST[ZAKAZEL]);
        $template->Set("register.profile.self", $_POST[SELF]);
        $template->Set("register.profile.message", $_POST[MESSAGE]);
        $template->Set("register.profile.usergoodselement", $_POST[USERGOODSELEMENT]);
        $template->Set("register.date", $_POST[DATE]);
        $template->Set("register.error_name_pass", $_POST[NAME_PASS]);
        $template->Set("register.error_name", $_POST[ENAME]);
        $template->Set("register.captcha", $_POST[CAPTCHA]);

        $template->Set("register.enter", $_POST[ENTER]);
        $template->Set("register.enterok", $_POST[ENTEROK]);
        $template->Set("register.register", $_POST[REGISTER]);
        $template->Set("register.ok", $_POST[OK]);
        $template->Set("register.email", $_POST[EMAIL]);
        $template->Set("register.name", $_POST[NAME]);
        $template->Set("register.addr", $_POST[ADDR]);


        $template->Set("register.newmessage", $_POST[NEWMESSAGE]);
        $template->Set("register.newmessageresume", $_POST[NEWMESSAGERESUME]);
        $template->Set("register.newmessageadmin", $_POST[NEWMESSAGEADMIN]);

        $template->Set("register.fail", $_POST[FAIL]);
        $template->Set("register.ban", $_POST[BAN]);

        $template->Save();

        header("Location: properties.php?a=ubertemplate&module=register&template=$_GET[template]");
 break;
case "edittype":
  $SQL = "SELECT * FROM $conf[DB_PREFIX]resume_type";
  $result=mysql_query($SQL, $conf[DB]) or die (mysql_error());
  while (@$type = mysql_fetch_assoc($result)){
    $newname = $_POST["type$type[id]"];
    if ($newname!=""){
      mysql_query("UPDATE $conf[DB_PREFIX]resume_type SET name='$newname' WHERE id=$type[id]");
    }else{
      mysql_query("DELETE FROM $conf[DB_PREFIX]resume_type WHERE id=$type[id]");
    };
  };
  if ($_POST[name]!=""){
    $SQL = "INSERT INTO $conf[DB_PREFIX]resume_type(name) values('$_POST[name]')";
    $z = mysql_query($SQL) or die (mysql_error());
  };

  header ("Location: ?a=view_resume&module=register");
  break;


case "view_user":
  $this->printheader();
  $id = (int)$_GET[id];
  $SQL="SELECT * FROM $conf[DB_PREFIX]accounts  WHERE ID = $id";
  $result=mysql_query($SQL, $conf[DB]);
  $account=mysql_fetch_assoc($result);

  $OUTER= GLOBAL_LOAD("register_main.dat");
  $ZAKAZ= GLOBAL_LOAD("register_zakaz.dat");
  $ZAKAZEL= GLOBAL_LOAD("register_element.dat");

  echo "<form method=post action=\"properties.php?a=saveuser&id=$account[ID]&module=register\">";
  echo "<table>";
  echo "<tr><td>Имя</td><td><input type=text name=\"name\" size=30 value=\"$account[NAME]\"></td></tr>";
  echo "<tr><td>Почта</td><td> <input type=text name=\"mail\" size=25 value=\"$account[EMAIL]\"></td></tr>";
  echo "<tr><td>телефон</td><td><input type=text name=\"tel\" size=15 value=\"$account[TEL]\"></td></tr>";
  echo "<tr><td>Адрес</td><td><textarea name=addr rows=3 cols=50>$account[ADDR]</textarea></td></tr>";
  echo "<tr><td>Скидка (автоматически  рассчитанная скидка: $account[discount] при суммe $account[total] )</td><td><input type=text name=\"discount\" size=15 value=\"" . (int)$account[discount_admin]." \"></td></tr>";
  echo "<tr><td>Пароль</td><td><input type=text name=\"password\" size=15 value=\"$account[PASSWORD]\"></td></tr></table>";
    echo "<tr><td>Money</td><td><input type=text name=\"money\" size=2 value=\"$account[money]\"></td></tr>";
  echo "<tr><td>Ban</td><td><input type=text name=\"ban\" size=2 value=\"$account[ban]\"></td></tr></table>";

  echo "<input type=submit value=\"Ok\"</form>";

  $_sql= "select * FROM $conf[DB_PREFIX]messages WHERE USER_ID=$id ORDER BY `DATE` desc";
  $_result=mysql_query($_sql, $conf[DB]);
  $_mes="";
  while (@$_message=mysql_fetch_assoc($_result)){
    $author = "";
    if ($_message[ADMIN]!=0){
      $author = "Admin";
    }else{
      $SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_message[USER_ID]";
      $res2 = mysql_query($SQL, $conf[DB]) or die (mysql_error());
      $user = mysql_fetch_assoc($res2);
      $author = "$user[NAME]($user[EMAIL])";

    }
    $date = $_message[DATE];
    $message = $_message[MESSAGE];

    $mes .= "<li> <b>$author: </b> ($date) $message</li>";
    //die($mes);
    //  $_mes .= "<li><i><small>$_message[ID] - $_message[DATE].</small></i><a href=\"JavaScript:NW('properties.php?module=register&a=answer_message&id=$_message[ID]', 500, 400)\"></a> <b>$_message[MESSAGE]</b>";
  };
  echo "<hr><ul>$mes</ul> <br> <a href=\"JavaScript:NW('properties.php?module=register&a=answer_message&userid=$account[ID]', 500, 400)\">Write New</a><hr>";

  $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE MAIL='$account[EMAIL]' ORDER BY ID desc";
  $res = mysql_query($sql, $conf[DB]);
  $zakazList="";
  $total=0;

  while ($zak = @mysql_fetch_assoc($res)){
    $id = $zak[ID];
      
    $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
    $res2= mysql_query($sql, $conf[DB]) or die(mysql_error());
    $i=0;
    $goodsList="";
    $isOk=false;
    $sum=(float)0;
    while ($goods = mysql_fetch_assoc($res2)){
      $isOk=true;
      $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
      $res3 = @mysql_query($sql, $conf[DB]);
      $cat = @mysql_fetch_assoc($res3);
      $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms where id=$cat[FIRM]";
      $res3= @mysql_query($SQL, $conf[DB]);
      $firm= @mysql_fetch_assoc($res3);

      $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $cat[PARENT]";
      $tmpres = @mysql_query($tmpsql, $conf[DB]);
      $tmpcat = @mysql_fetch_assoc($tmpres);


      if ($goods[COUNT]>=1){
        $i++;
        //                      var_dump($goods);
        //                        die ($goods);
        $el = str_replace("%firmname%",$firm[NAME],$ZAKAZEL);
        $el = str_replace("%price%",$goods[PRICE],$el);
        $el = str_replace("%summrur%",$goods[PRICE]*$goods[COUNT],$el);
        $el = str_replace("%cattitle%",$tmpcat[TITLE],$el);
        $el = str_replace("%title%",$cat[TITLE],$el);
        $el = str_replace("%count%",$goods[COUNT],$el);
        if ($zak[STATE] == "ok")
        $total+= (int)$goods[COUNT]*$goods[PRICE];
        $goodsList.= $el;
        $sum+=(float)($goods[PRICE]*$goods[COUNT]);
      }
    }
    if ($isOk){
      $singleZAKAZ = $ZAKAZ;
      $singleZAKAZ = str_replace("%id%", $id, $singleZAKAZ);

      if ($zak[STATE] == "ok")
      $status = 'Доставлен';
      if ($zak[STATE] == "del")
      $status = 'Удален';
      if ($zak[STATE] == "process")
      $status = 'В обработке';
      if ($zak[STATE] == "new")
      $status = 'Постановка на обработку';
      if ($zak[STATE] == "go")
      $status = 'Доставляется';
      if ($zak[STATE] == "repeat")
      $status = 'Отложен';


      $singleZAKAZ = str_replace("%status%", $status, $singleZAKAZ);
      $singleZAKAZ = str_replace("%date%", $zak[DATE_START], $singleZAKAZ);
      $singleZAKAZ = str_replace("%list%", $goodsList, $singleZAKAZ);
      $singleZAKAZ = str_replace("%summ%", $sum, $singleZAKAZ);
      $zakazList.= $singleZAKAZ;
    }
  };
  $OUTER = str_replace("%link%", "../../register/profile/profile", $OUTER);
  $OUTER = str_replace("%link2%", "../../register/profile/history", $OUTER);
  $OUTER = str_replace("%link_resume%", "../../resume/edit", $OUTER);
  $OUTER = str_replace("%link3%", "../../register/profile/manage", $OUTER);
  $OUTER = str_replace("%total%", $total, $OUTER);
  $SQL = "UPDATE $conf[DB_PREFIX]accounts set total=$total where  ID=$account[ID]";

  $r = mysql_query($SQL) or die (mysql_error());

  $ue = $total;
  $data = GLOBAL_LOAD("skidki", "skidki");
  $dat = unserialize ($data);

  if ($ue>=$dat['11'])
  $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[12] where  ID=$account[ID]";
  if ($ue>=$dat['21'])
  $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[22] where  ID=$account[ID]";
  if ($ue>=$dat['31'])
  $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[32] where  ID=$account[ID]";
  if ($ue>=$dat['41'])
  $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[42] where  ID=$account[ID]";

  $r = mysql_query($SQL) or die (mysql_error());

  echo  str_replace("%main%", $zakazList, $OUTER);


  break;
case "save_users":

  $perpage = 100;
  $page = $perpage*$_GET[page];

  $sql="SELECT * FROM $conf[DB_PREFIX]accounts  ORDER BY `EMAIL` ASC LIMIT $page, $perpage";

  $result = mysql_query($sql, $conf[DB]);
  //var_dump ($_POST);
  while ($u = mysql_fetch_assoc($result)) {
        
        if ($_POST["BAN$u[ID]"]==1){
          mysql_query("UPDATE $conf[DB_PREFIX]accounts set ban=1 where ID=$u[ID]" );
        }else{
          mysql_query("UPDATE $conf[DB_PREFIX]accounts set ban=0 where ID=$u[ID]" );
        };

  };
  header("Location: properties.php?module=register&a=view_users&page=$_GET[page]");

break;
case "view_users":
  $this->printheader();
  $SQL="SELECT count(*) as `count` FROM $conf[DB_PREFIX]accounts";
  $result=mysql_query($SQL, $conf[DB]);
  $co = mysql_fetch_assoc($result);
  $totalcount = $co['count'];
  $perpage = 100;
  $page = $perpage*$_GET[page];

  $SQL="SELECT * FROM $conf[DB_PREFIX]accounts  ORDER BY `EMAIL` ASC LIMIT $page, $perpage";
  $result=mysql_query($SQL, $conf[DB]);

  for ($i=0;$i<=$totalcount/$perpage; $i++){
    echo "<a href=\"properties.php?module=register&a=view_users&page=$i\">$i</a> &nbsp;&nbsp;";
  }

  echo "<form action =\"properties.php?a=save_users&module=register&page=$_GET[page]\" method=post><hr><input type=submit><ul>";
  while ($account=mysql_fetch_assoc($result)){    
    echo "<li>BAN: <input type = \"checkbox\" name = \"BAN$account[ID]\" value=\"1\"";
                if ($account['ban'] == 1)
                  echo " checked";
              echo '> ';
    echo "<a href=\"?module=register&a=view_user&id=$account[ID]\">$account[EMAIL]</a>";
  };

  echo "</ul><input type=submit></form><hr>";

  for ($i=0;$i<=$totalcount/$perpage; $i++){
    echo "<a href=\"properties.php?module=register&a=view_users&page=$i\">$i</a> &nbsp;&nbsp;";
  }

  break;
case "saveuser":
  if(0!=(int)$_GET[id])
  {
    $SQL = "UPDATE `$conf[DB_PREFIX]accounts` set `money`='$_POST[money]', `ban`=$_POST[ban], `NAME`='$_POST[name]', `TEL`='$_POST[tel]',`discount_admin`=$_POST[discount], `EMAIL`='$_POST[mail]', `ADDR`='$_POST[addr]' WHERE ID=$_GET[id]";
    $r=mysql_query($SQL, $conf[DB]) or die(mysql_error());
  };
  header("Location: properties.php?a=view_users&module=register");
  break;

case "answer_message":
  if ($_GET[userid]){

    $SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_GET[userid]";
    $res2 = mysql_query($SQL, $conf[DB]) or die (mysql_error());
    $user = mysql_fetch_assoc($res2);
    //$_SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE EMAIL='$zak[MAIL]'";
    //$user =mysql_fetch_assoc(mysql_query($_SQL, $conf[DB]));

    //      echo  "";

    echo " <a href=\"properties.php?module=register&a=view_user&id=$user[ID]\" target=_BLANK>$user[EMAIL]</a> $message[DATE]";
    echo "<hr><b>";
      
    echo "</b><br><form action=\"?module=register&a=commitanswer&userid=$_GET[userid]\" method=POST>";
    echo "<table border=1><tr><td>$message[MESSAGE]</td></tr><tr><td>";
    echo "<textarea cols=55 rows=6 name=answer></textarea></td></tr></table><input type=submit value=\"Ответить\"></form>";
      
    $_sql= "select * FROM $conf[DB_PREFIX]messages WHERE USER_ID=$user[ID] ORDER BY `DATE` desc";
    $_result=mysql_query($_sql);
    $_mes="";
    while (@$_message=mysql_fetch_assoc($_result)){
      $author = "";
      if ($_message[ADMIN]!=0){
        $author = "Admin";
      }else{
        $SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_message[USER_ID]";
        $res2 = mysql_query($SQL, $conf[DB]) or die (mysql_error());
        $user = mysql_fetch_assoc($res2);
        $author = "$user[NAME]($user[EMAIL])";

      }
      $date = $_message[DATE];
      $message = $_message[MESSAGE];

      $mes .= "<li> <b>$author: </b> ($date) $message</li>";

    };
    echo "<hr><ul>$mes</ul> <br> <a href=\"JavaScript:NW('properties.php?module=register&a=answer_message&userid=$account[ID]', 500, 400)\">Write New</a><hr>";
  }else{
    $SQL = "SELECT * FROM $conf[DB_PREFIX]messages WHERE ID=$_GET[id]";
    $res = mysql_query($SQL, $conf[DB]) or die (mysql_error());

    $message=mysql_fetch_assoc($res);

    $SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$message[USER_ID]";
    $res2 = mysql_query($SQL, $conf[DB]) or die (mysql_error());
    $user = mysql_fetch_assoc($res2);

    echo "$user[NAME](<b>$user[EMAIL]</b>) $message[DATE]";
    echo "<hr><b>";
    switch ($message[TYPE]){
      case"":
        echo "Проблема с заказом";
        break;
      case"":
        echo "Проблема с сайтом";
        break;
    };
    echo ":<b><br><form action=\"?module=register&a=commitanswer&id=$_GET[id]\" method=POST>";
    echo "<table border=1><tr><td>$message[MESSAGE]</td></tr><tr><td>";
    echo "<textarea cols=55 rows=6 name=answer></textarea></td></tr></table><input type=submit value=\"Ответить\"></form>";
  };
  break;
      case "commitanswer":
        if ($_POST[answer]!=""){

          if ($_GET[userid]!=0){
            $SQL = "INSERT INTO $conf[DB_PREFIX]messages(`MESSAGE`,`DATE`, `USER_ID`, `TYPE`, `STATE`, `ADMIN`) values ('$_POST[answer]',now(), $_GET[userid], '', 0, 1)";
            $res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
          }else{

            $SQL = "SELECT * FROM $conf[DB_PREFIX]messages WHERE ID=$_GET[id]";
            $res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
            $message=mysql_fetch_assoc($res);
            $SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$message[USER_ID]";
            $res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
            $user = mysql_fetch_assoc($res);

            $SQL = "INSERT INTO $conf[DB_PREFIX]messages(`MESSAGE`,`DATE`, `USER_ID`, `TYPE`, `STATE`, `ADMIN`) values ('$_POST[answer]',now(), $user[ID], '', 0, 1)";
            $res = mysql_query($SQL, $conf[DB]) or die (mysql_error());

            $SQL = "UPDATE $conf[DB_PREFIX]messages SET STATE=0 WHERE ID=$_GET[id]";
            $res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
          }

          $sql = "select * from $conf[DB_PREFIX]accounts where ID=$_GET[userid]";

          $r=mysql_query($sql, $conf[DB]) or die(mysql_error());
          $u=mysql_fetch_assoc($r);

          $_subject="В Вашем личном кабинете новое сообщение";

          $_headers .= "From: Muzbazar <$EMAIL>\n";
          $_headers .= "X-Sender: <$EMAIL>\n";
          $_headers .= "X-Mailer: PHP/mail()\n"; //mailer
          $_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

          $_headers .= "Return-Path: <$EMAIL>\n";
          $_headers .= "Content-type: text/html; charset=UTF-8\r\n";
          // $_headers .= "cc: $EMAIL\n"; // CC to
          // $_headers .= "bcc: $EMAIL";

          mail($u[EMAIL], $_subject,"Здравствуйте $u[NAME] <br>\r\n\r\n ".  $_POST[answer]. "\r\n\r\nПродолжить общение Вы можете либо через почту, либо через личный кабинет", $_headers);
        };
        echo "<html><head><script>window.close();</script><body></body></html>";
        break;
      case "view_messages":
        $this->printheader();
        $SQL = "SELECT * FROM $conf[DB_PREFIX]messages WHERE STATE=1 order by `DATE` desc";
        $res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
        while (@$message=mysql_fetch_assoc($res)){
          $SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$message[USER_ID]";
          $res2 = mysql_query($SQL, $conf[DB]) or die (mysql_error());
          $user = mysql_fetch_assoc($res2);
          //        properties.php?module=register&a=answer_message&=1768
          echo "<i><small>$message[ID] - $message[DATE].</small></i><a href=\"JavaScript:NW('properties.php?module=register&a=answer_message&userid=$message[USER_ID]', 500, 400)\"> <b>$message[MESSAGE]</b>";
          echo "</a>";
          echo "<small>";
          switch ($message[TYPE]){
            case"":
              echo "(проблема с заказом)";
              break;
            case"":
              echo "(проблема с сайтом)";
              break;
          };
          echo "</small>";
          echo "<br>";
        };
        break;
            case "send_messages":
              ?>
<form method=post
  action="properties.php?module=register&a=commit_send_messages"><textarea
  name=text cols=50 rows=10></textarea> <input type=submit></form>
              <?
              break;
case "commit_send_messages":
  $SQL = "SELECT distinct EMAIL from $conf[DB_PREFIX]accounts";
  $res=mysql_query($SQL, $conf[DB]);
  while ($u = mysql_fetch_assoc($res)){
    echo $u[EMAIL];
    $_subject="Muzbazar news.";

    $EMAIL = "info@muzbazar.ru";

    $_headers .= "From: Muzbazar <$EMAIL>\n";
    $_headers .= "X-Sender: <$EMAIL>\n";
    $_headers .= "X-Mailer: PHP/mail()\n"; //mailer
    $_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

    $_headers .= "Return-Path: <$EMAIL>\n";
    $_headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $_headers .= "cc: $EMAIL\n"; // CC to
    $_headers .= "bcc: $EMAIL";

    mail($u[EMAIL], $_subject, $POST[text], $_headers);
    echo "<br>";
  };
    break;
    }
  }
  function add(){
    $conf = $this->conf;
    return 0;
  }
  function del($id){
    $conf = $this->conf;
    return 1;
  }
  function RuEncodeUTF ($ruString) {
    //     $re_ar= array(" "=>"_", "ƭ"=>"a", "ǐ"=>"A", "ƭ"=>"b", "Ǒ"=>"B", "ƭ"=>"v", "ǒ"=>"V", "Ʀ"=>"g", "Ǔ"=>"G", "ƫ"=>"d", "ǔ"=>"D", "Ʀ"=>"e", "Ǖ"=>"E", "TѢ=>"e", "ǁ"=>"E", "Ʀ"=>"j", "ǖ"=>"J", "Ƭ"=>"z", "Ǘ"=>"Z", "Ƭ"=>"i", "ǘ"=>"I", "Ʀ"=>"i", "Ǚ"=>"I", "Ʀ"=>"k", "ǚ"=>"K", "Ƭ"=>"l", "Ǜ"=>"L", "ƭ"=>"m", "ǜ"=>"M", "ƭ"=>"n", "ǝ"=>"N", "ƭ"=>"o", "Ǟ"=>"O", "Ƭ"=>"p", "ǟ"=>"P", "T"=>"r", "Ǡ"=>"R", "Tb=>"s", "ǡ"=>"S", "T¢=>"t", "Ǣ"=>"T", "Tâ=>"y", "ǣ"=>"Y", "TĢ=>"f", "Ǥ"=>"F", "TŢ=>"h", "ǥ"=>"H", "TƢ=>"c", "Ǧ"=>"C", "TǢ=>"ch", "ǧ"=>"CH", "TȢ=>"sh", "Ǩ"=>"SH", "Tɢ=>"sh", "ǩ"=>"SH", "Tʢ=>"", "Ǫ"=>"", "Tˢ=>"y", "ǫ"=>"Y", "T̢=>"", "Ǭ"=>"", "T͢=>"e", "ǭ"=>"E", "T΢=>"u", "Ǯ"=>"U", "TϢ=>"ia", "ǯ"=>"IA");
    //   foreach ($re_ar as $key=>$val) $ruString = preg_replace ("/{$key}/", "{$val}", $ruString);
    // return $ruString;
  }
  function renderEx($id, &$template){
    $conf = $this->conf;
    $path = split("/", $_GET[path]);

    if ($path[2]=="")
    $path[2] = "register";

    if ($path[1] != ""){
      switch ($path[1]){
        
        case "register":
          $REGISTER = $template->Get("register.register");
          $REGISTER = str_replace("%action%", "./register/register2", $REGISTER);

          if ($_SESSION[LASTERROR]!="")
          {
            if ($_SESSION[LASTERROR]=="NAME_PASS")
            $REGISTER= str_replace("%error%", $template->Get("register.error_name_pass"), $REGISTER);
            if ($_SESSION[LASTERROR]=="NAME")
              $REGISTER= str_replace("%error%", $template->Get("register.error_name"), $REGISTER);
            if ($_SESSION[LASTERROR]=="CAPTCHA")
              $REGISTER= str_replace("%error%", $template->Get("register.captcha"), $REGISTER);

            $_SESSION[LASTERROR]="";
          };
          ;
          $REGISTER= str_replace("%sid%", md5(uniqid(time())), $REGISTER);
          $REGISTER= str_replace("%error%", "", $REGISTER);
          $_SESSION[LASTERROR]="";

          return $REGISTER;
          break;
        case "register2":

          //$_POST[NAME] = $this->RuEncodeUTF (NAME);

          $_SQL = "SELECT COUNT(ID) as `count` FROM $conf[DB_PREFIX]accounts WHERE (LOGIN= '$_POST[NAME]') or (EMAIL='$_POST[EMAIL]')";
          $res = mysql_query($_SQL, $conf[DB]) or die (mysql_error());
          $_ret2 =mysql_fetch_assoc($res) or die (mysql_error());
          include("securimage.php");
          $img = new Securimage();
          $valid = $img->check($_POST['code']);

          if($valid != true) {
            $_SESSION[LASTERROR] = "CAPTCHA";
            //die ("Asd");
            header("Location: /register/register");
            die();
          }
          if ($_ret2[count]==0 && $_POST[NAME]!=""){
            $_SESSION[ERRORMESSAGE]="";
            $_a = md5(rand(0,10000));
            $_pass=substr($_a, 1, 8);
            $_SQL   = "INSERT INTO `$conf[DB_PREFIX]accounts` (`LOGIN`, `EMAIL`, `PASSWORD`)"
            ." VALUES ('$_POST[NAME]', '$_POST[EMAIL]', '$_pass')";
            $res = mysql_query($_SQL, $conf[DB]) or die (mysql_error());
            $_SESSION[register][login]=$_POST[EMAIL];
            $_SESSION[register][pass]=$_pass;
            $_SESSION[register][id] = mysql_insert_id();
            $OK = $template->Get("register.ok");
            $EMAIL = $template->Get("register.email");
            $NAME= $template->Get("register.name");
            $ADDR= $template->Get("register.addr");


            $OK = str_replace("%name%", $_POST[NAME], $OK);
            $OK = str_replace("%pass%", $_pass, $OK);
            $OK = str_replace("%mail%", $_POST[EMAIL], $OK);
            $OK = str_replace("%discount%", $discount, $OK);
            $OK = str_replace("%zakaz%", $zakaz, $OK);


            $_subject="Регистрация: ";

            $_headers .= "From: Регистрация <$EMAIL>\n";
            $_headers .= "X-Sender: <$EMAIL>\n";
            $_headers .= "X-Mailer: PHP/mail()\n"; //mailer
            $_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

            $_headers .= "Return-Path: <$EMAIL>\n";
            $_headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $_headers .= "cc: $EMAIL\n"; // CC to
            $_headers .= "bcc: $EMAIL";

            @mail($_POST[EMAIL], $_subject, $OK, $_headers);
            //@header("Location: ../");
            @header("Location: ../register/profile/profile");
          }else{
            $_SESSION[LASTERROR] = "NAME";
            //die ("Asd");
            header("Location: /register/register");
          };
          break;
        case "logout":
          $_SESSION[register][login]='';
          $_SESSION[register][pass]='';
          $_SESSION[register][id] =='';
          unset($_SESSION[register]);
          $_SESSION[LASTERROR]="";
          header("Location: $_SERVER[HTTP_REFERER]");
          break;
        case "restore":
          return "<form method=POST action=\"register/restore2\">EMail: <input type=text name=EMAIL><input type=submit value=Restore></form>";
          break;
        case "restore2":
          $SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE EMAIL='$_POST[EMAIL]'";
          $ret =mysql_fetch_assoc(mysql_query($SQL, $conf[DB]));
          $PASS =$ret[PASSWORD];        
          $_to="$_POST[EMAIL]";
          $_from="mail@muzbazar.ru";
          
          $_subject="MuzBazar";
      
          $_headers .= "From: MuzaBazar<$_from>\n";
          $_headers .= "X-Sender: <$_from>\n";
          $_headers .= "X-Mailer: PHP/mail()\n"; //mailer
          $_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

          $_headers .= "Return-Path: <$_from>\n";
          $_headers .= "Content-type: text/html; charset=UTF-8\r\n";    
      
          mail($_to, "Password for muzbazar.ru", $PASS, $_headers);

          header("Location: /");
          break;
        case "login":

            $_SQL = "SELECT COUNT(ID) as `count` FROM $conf[DB_PREFIX]accounts WHERE EMAIL= '$_POST[LOGIN]' AND PASSWORD = '$_POST[PASS]'";
        
          $_ret2 =mysql_fetch_assoc(mysql_query($_SQL, $conf[DB]));

          $_SESSION[register]="";
          if ($_ret2[count]==1 && $_POST[LOGIN]!="")
          {
            $_SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE EMAIL= '$_POST[LOGIN]' AND PASSWORD = '$_POST[PASS]'";
            $_ret2 =mysql_fetch_assoc(mysql_query($_SQL, $conf[DB]));
            $_SESSION[register]  = array();
            $_SESSION[register][id] = (int)$_ret2[ID];

            $_SESSION[register][login]=$_ret2[EMAIL];
            $_SESSION[register][pass]=$_ret2[PASSWORD];

            $_SESSION[ERRORMESSAGE]="";
            $_SESSION[LASTERROR]="";

            unset($_SESSION[LASTERROR]);
            unset($_SESSION[ERRORMESSAGE]);
            header("Location: $_SERVER[HTTP_REFERER]");
          }else{
            unset($_SESSION[register]);
            unset($_SESSION[LASTERROR]);
            unset($_SESSION[ERRORMESSAGE]);
            $_SESSION[LASTERROR]="NAME_PASS";
            header("Location: $_SERVER[HTTP_REFERER]");
          };
          exit();

          break;
        case "commitprofile":
          $id = $_SESSION[register][id];

          if ($_POST[NAME]!=""){
            $_SQL = "UPDATE $conf[DB_PREFIX]accounts SET NAME='$_POST[NAME]' WHERE ID=$id";
            mysql_query($_SQL, $conf[DB]);

            $_SQL = "UPDATE $conf[DB_PREFIX]accounts SET TEL='$_POST[TEL]' WHERE ID=$id";
            $r = mysql_query($_SQL, $conf[DB]) or die (mysql_error());

            $_POST[ADDR] = addslashes($_POST[addr]);
            $_SQL = "UPDATE $conf[DB_PREFIX]accounts SET ADDR='$_POST[ADDR]' WHERE ID=$id";
            $r = mysql_query($_SQL, $conf[DB]) or die (mysql_error());
          };

          if ($_POST[PASS1]!="" &&($_POST[PASS1]==$_POST[PASS2]))
          {
            $_SQL = "UPDATE $conf[DB_PREFIX]accounts SET PASSWORD='$_POST[PASS1]' WHERE ID=$id";
            mysql_query($_SQL, $conf[DB]);
          }

          @header("Location: ./profile/profile");
          break;
        case "postmessage":
          if ($_POST[text]!="")
          {
            $_i = $_SESSION[register][id];

            $sql = "select * from $conf[DB_PREFIX]accounts where ID = $userID";
            $z = @mysql_query($sql);
            $u = @mysql_fetch_assoc($z);
            if ($u[ban])
              return $template->Get("register.ban");

            $_SQL = "INSERT INTO $conf[DB_PREFIX]messages(`MESSAGE`,`DATE`, `USER_ID`, `TYPE`) values ('$_POST[text]',now(), $_i, '$_POST[TYPE]')";
            $r=mysql_query($_SQL, $conf[DB]) or die(mysql_error());


            $sql = "select * from $conf[DB_PREFIX]accounts where ID=$_i";
            $r=mysql_query($sql, $conf[DB]) or die(mysql_error());
            $u=mysql_fetch_assoc($r);

            $_subject="На сайте оставленно сообщение от пользователя $u[EMAIL]";

            $EMAIL = "mail@muzbazar.com";

            $_headers .= "From: Muzbazar Message<$EMAIL>\n";
            $_headers .= "Content-type: text/html; charset=UTF-8\r\n";
            mail("mail@muzbazar.ru", $_subject,$_POST[text], $_headers);
          }
          @header("Location: ./profile/profile");
          die();
          break;
        case "profile":
          if ($_SESSION[register][id]==0){
            // die("asd");
            @header("Location: ../../register/register");
            $_SESSION[LASTERROR]="NAME_PASS";
            die();
          };

          $_TMP  = $template->Get("register.profile_main");
          $_TMP = str_replace("%link%", "../../register/profile/profile", $_TMP);
          $_TMP = str_replace("%link2%", "../../register/profile/history", $_TMP);
          $_TMP = str_replace("%link_resume%", "../../resume/edit", $_TMP);
          $_TMP = str_replace("%link3%", "../../register/profile/manage", $_TMP);

          switch ($path[2])
          {
          case "dopay":
              $zakid=(int)$path[3];
              $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID = $zakid";
              $r = mysql_query($sql) or die (mysql_error());
              $zak = mysql_fetch_assoc($r);

              $_tmp = (int)$_SESSION[register][id];
              $_SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_tmp";
              $_ret2 =mysql_fetch_assoc(mysql_query($_SQL, $conf[DB]));
              $user = $_ret2;
              
              if ($zak[virtual]){
                $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
                $res2= mysql_query($sql, $conf[DB]) or die(mysql_error());
                $sum=(float)0;
                while ($goods = mysql_fetch_assoc($res2)){
                  $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
                  $res3 = @mysql_query($sql, $conf[DB]);
                  $cat = @mysql_fetch_assoc($res3);
                  if ($goods[COUNT]>=1){
                    $sum+=(float)($goods[PRICE]*$goods[COUNT]);
                  }
                }

                if ($user[money]>=$sum){
                  $sql = "update `$conf[DB_PREFIX]zakaz` set state='payed' WHERE ID = $zakid";
                  mysql_query($sql);
                  $sql = "update `$conf[DB_PREFIX]accounts` set money=money-$sum WHERE ID = $user[ID]";
                  mysql_query($sql);

                  $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
                  $res2= mysql_query($sql, $conf[DB]) or die(mysql_error());
                  $sum=(float)0;
                  while ($goods = mysql_fetch_assoc($res2)){
                    if ($cat[file]){
                  //create symlinlk
                   $sql = "insert into $conf[DB_PREFIX]tmpfile_rel_order(`date`, `goods_id`, `order_id`) values(now(), $cat[ID], $zak[ID])";
                   $res3 = mysql_query($sql) or die(mysql_error());
                   $id = mysql_insert_id();
                   $hash = md5($zak[ID]+1234);
                   $this->CreateFile($cat[file], $hash);
                   $sql = "update $conf[DB_PREFIX]tmpfile_rel_order set `file`='$hash' where id=$id";
                   $res3 = mysql_query($sql) or die(mysql_error());
                   
                   // mkdir("$_SERVER[DOCUMENT_ROOT]/public/$hash");
                   //echo "$_SERVER[DOCUMENT_ROOT]private/$goods[file]";
                   //symlink("$_SERVER[DOCUMENT_ROOT]private/$cat[file]", "$_SERVER[DOCUMENT_ROOT]public/$hash/$cat[file]");
                 }                   
                }
               }
              };
             header("Location: /register/profile/history");
            break;
      case "addmoney":
        $key = $_POST[code];
        $SQL = "SELECT * from $conf[DB_PREFIX]smspay where `key` = '$key' and active=1";
        $r = mysql_query($SQL) or die (mysql_error());
        if($tiket = @mysql_fetch_assoc($r)){
          $userID = $_SESSION[register][id];
          if ($userID){
            $SQL = "UPDATE $conf[DB_PREFIX]accounts set money=money+$tiket[price] where ID = $userID LIMIT 1";
            $r = mysql_query($SQL) or die (mysql_error());
            $SQL = "UPDATE $conf[DB_PREFIX]smspay set active=0 where id=$tiket[id]";
            $r = mysql_query($SQL) or die (mysql_error());
          }

        }

        header("Location: /register/profile/history");
      break;
            case "delmessage":
              //die("Asd");
              $ID = (int)$path[3];
              $userID = $_SESSION[register][id];
              $_SQL = "delete from `$conf[DB_PREFIX]usermessages` WHERE (user_to=$userID or user_from=$userID) and id=$ID";
              $r = mysql_query($_SQL) or die (mysql_error());
              /*
              $ID = (int)$path[3];
              $userID = $_SESSION[register][id];
              $_SQL = "SELECT count(*) as `c` FROM `$conf[DB_PREFIX]usermessages` WHERE user_to=$userID and id=$ID";
              $_result=@mysql_query($_SQL);
              $_comm=@mysql_fetch_assoc($_result);
              if ($_comm[c]>=1){
                $_SQL = "delete from `$conf[DB_PREFIX]usermessages` WHERE user_to=$userID and id=$ID";
                $r = mysql_query($_SQL) or die (mysql_error());
              };
              */
              header("Location: /register/profile/messages");
              break;
            case "messages":
              $MAIN = "%comments%";
              $userID = $_SESSION[register][id];
              $_SQL = "SELECT * FROM `$conf[DB_PREFIX]usermessages` WHERE user_to=$userID or user_from=$userID ORDER BY `date`";
              $_result=@mysql_query($_SQL);
              $_comment="";
              $COMMENT = "<a href=\"%dellink%\">X</a>  %date% <i>%name1%</i> - &gt; %name2% <br> %TEXT% <br> <br>";
              while ($_comm=@mysql_fetch_assoc($_result)){
                $_TEXT= stripslashes($_comm[message]);
                $_tmp=" ";
                
                $_SQL2 = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_comm[user_from]";
                $u =mysql_fetch_assoc(mysql_query($_SQL2, $conf[DB]));
                $_tmp = str_replace("%name1%", "<a href=\"/resume/$u[ID]\">$u[NAME]</a>", $COMMENT);

                $_SQL2 = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_comm[user_to]";
                $u =mysql_fetch_assoc(mysql_query($_SQL2, $conf[DB]));
                $_tmp = str_replace("%name2%", "<a href=\"/resume/$u[ID]\">$u[NAME]</a>", $_tmp);

                $_tmp = str_replace("%dellink%", "/register/profile/delmessage/$_comm[id]", $_tmp);
                $_tmp = str_replace("%date%", "$_comm[date]", $_tmp);
                $_tmp = str_replace("%TEXT%", $_TEXT, $_tmp);
                $_comment .= $_tmp;
              };
              //die ($_comment);
              return $MAIN = str_replace("%comments%", $_comment, $MAIN);
              break;
            case "manage_upload_image":

              $id = $_SESSION[register][id];
              if (!$id)
              die();

              include_once ("admin/core_file_works.php");
              $filew = new Fileworks($id, "upics");
              $filew->clear_file(".");
              $filew->upload_file(".");
              sleep(1);
              header("Location: /register/profile/manage");
              die();
              break;
            case "deloff":

              $uid = (int)$_SESSION[register][id];
              if (!$uid)
              die();

              $oid = (int)$path[3];
              $SQL = "delete from $conf[DB_PREFIX]useroffer where user_id =$uid and offer_id=$oid";
              mysql_query($SQL);
              sleep(1);
              header("Location: /register/profile/profile");
              die();
              break;
            case "manage":
              //die("AD");
              $MAIN= $template->Get("register.manage.main");
              $IMAGE= $template->Get("register.manage.image");

              $id = $_SESSION[register][id];
              if (!$id)
              die();

              include_once ("admin/core_file_works.php");
              $filew = new Fileworks($id, "upics");



              $MAIN = str_replace("%link%", "../../register/profile/profile", $MAIN);
              $MAIN = str_replace("%link2%", "../../register/profile/history", $MAIN);
              $MAIN = str_replace("%link_resume%", "../../resume/edit", $MAIN);
              $MAIN = str_replace("%link3%", "../../register/profile/manage", $MAIN);

              $IMAGE = str_replace("%image%", $filew->get_sfile("."), $IMAGE);

              $MAIN = str_replace("%image%", $IMAGE, $MAIN);

              return $MAIN;
              break;
            case "profile":

              $_tmp = (int)$_SESSION[register][id];
              $_SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_tmp";
              $_ret2 =mysql_fetch_assoc(mysql_query($_SQL, $conf[DB]));
              $user = $_ret2;

              $MESSAGE = $template->Get("register.profile.message");
              $MAIN=$template->Get("register.profile.self");
              $DATE= $template->Get("register.date");

              $MAIN=str_replace("%NAME%", $_ret2[NAME], $MAIN);
              $MAIN=str_replace("%TEL%", $_ret2[TEL], $MAIN);
              $MAIN=str_replace("%ADDR%", $_ret2[ADDR], $MAIN);

              $MAIN=str_replace("%pass%", "", $MAIN);
              $MAIN=str_replace("%action%", "./register/commitprofile", $MAIN);
              $MAIN=str_replace("%actionsend%", "./register/postmessage", $MAIN);

              if($user[discount_admin]!=-1){
                $skidka = $user[discount_admin];
              }else{
                $skidka = $user[discount];
              }

              $MAIN=str_replace("%discount%", $skidka, $MAIN);

              $_i = $_SESSION[register][id];

              $_sql= "select * FROM $conf[DB_PREFIX]messages WHERE USER_ID=$_i ORDER BY `DATE` desc";
              @$_result=mysql_query($_sql, $conf[DB]);
              $_mes="";
              while (@$_message=mysql_fetch_assoc($_result))
              {
                $_TMP2 = str_replace("%text%", $_message[MESSAGE], $MESSAGE);

                $n = $user[NAME];
                if ($_message[ADMIN]!=0)
                $n = "Admin";

                $_TMP2 = str_replace("%name%", $n, $_TMP2);
                $_TMP2 = str_replace("%date%", $_message[DATE], $_TMP2);

                $_DATE=date($DATE, strtotime($_news[DATE]));
                $_TMP2 = str_replace("%DATE%", $_DATE, $_TMP2);

                if ($_message[ADMIN])
                $_TMP2 = "<i>$_TMP2</i>";
                $_mes .=$_TMP2;
              };

              $MAIN=str_replace("%MESSAGES%", $_mes, $MAIN);
              $usergoodslist = "";

              $sql = "SELECT * from $conf[DB_PREFIX]useroffer where user_id =$user[ID] order by `date`";
              $_result=@mysql_query($sql, $conf[DB]);

              $USERGOODSELEMENT= $template->Get("register.profile.usergoodselement");

              while ($_off=@mysql_fetch_assoc($_result)){
                $offertemplate = $USERGOODSELEMENT;

                $sql = "SELECT * from $conf[DB_PREFIX]catalog where ID=$_off[catalog_id]";
                $_resultc=mysql_query($sql);
                $offcat = mysql_fetch_assoc($_resultc);

                $offertemplate = str_replace("%goods_name%", $offcat[TITLE], $offertemplate);
                $offertemplate = str_replace("%goods_link%", "/shop/$offcat[ID]", $offertemplate);
                $offertemplate = str_replace("%goods_price%", $_off[price], $offertemplate);
                $offertemplate = str_replace("%dellink%", "/register/profile/deloff/$_off[offer_id]", $offertemplate);
                $offertemplate = str_replace("%description%", stripslashes($_off[description]), $offertemplate);
                $offertemplate = str_replace("%date%", stripslashes($_off['date']), $offertemplate);

                $usergoodslist.=$offertemplate;
              }

              $MAIN = str_replace("%usergoods%", $usergoodslist, $MAIN);

              $MAIN = str_replace("%link%", "/register/profile/profile", $MAIN);
              $MAIN = str_replace("%link2%", "/register/profile/history", $MAIN);
              $MAIN = str_replace("%link3%", "/register/profile/manage", $MAIN);
              $MAIN = str_replace("%link_resume%", "/resume/edit", $MAIN);
              //===========
              return $MAIN . "<hr>" . $ZAKAZ;
              break;
            case "history":

              $OUTER= $template->Get("register.zakaz.main");
              $ZAKAZ= $template->Get("register.zakaz.zakaz");
              $ZAKAZEL= $template->Get("register.zakaz.element");

              $_tmp = (int)$_SESSION[register][id];
              $_SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID=$_tmp";
              $_ret2 =mysql_fetch_assoc(mysql_query($_SQL, $conf[DB]));
              $user = $_ret2;

              $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE MAIL='$user[EMAIL]' ORDER BY ID desc";
              $res = mysql_query($sql, $conf[DB]);
              $zakazList="";
              $total=0;
              while ($zak = @mysql_fetch_assoc($res)){
                $id = $zak[ID];

                $sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
                $res2= mysql_query($sql, $conf[DB]) or die(mysql_error());
                $i=0;
                $goodsList="";
                $isOk=false;
                $sum=(float)0;
                while ($goods = mysql_fetch_assoc($res2)){
                  $isOk=true;
                  $sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
                  $res3 = @mysql_query($sql, $conf[DB]);
                  $cat = @mysql_fetch_assoc($res3);
                  $SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms where id=$cat[FIRM]";
                  $res3= @mysql_query($SQL, $conf[DB]);
                  $firm= @mysql_fetch_assoc($res3);

                  $tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $cat[PARENT]";
                  $tmpres = @mysql_query($tmpsql, $conf[DB]);
                  $tmpcat = @mysql_fetch_assoc($tmpres);

                  if ($goods[COUNT]>=1){
                    $i++;
                    $el = str_replace("%firmname%",$firm[NAME],$ZAKAZEL);
                    $el = str_replace("%price%",$goods[PRICE],$el);
                    $el = str_replace("%summrur%",$goods[PRICE]*$goods[COUNT],$el);
                    $el = str_replace("%cattitle%",$tmpcat[TITLE],$el);
                    
                    if ($zak[STATE]=="payed"){
                      
                      if ($cat[file]!=""){
                        $dlink="";
                        //get hash
                        $sql ="SELECT * from $conf[DB_PREFIX]tmpfile_rel_order where order_id=$zak[ID] and goods_id=$cat[ID]";
                        $dfr = @mysql_query($sql);
                        $df  = @mysql_fetch_assoc($dfr);
                        if ($df[file]){
                          // create link
                          $dlink="http://video.magazindoc.ru/$df[file]/$cat[file]";
                          $el = str_replace("%title%","<a href=\"/shop/$cat[ID].html\">$cat[TITLE]</a> <a href=\"$dlink\"><br>DOWNLOAD</b></a> ",$el);
                        }
                      }
                    }

                    $el = str_replace("%count%",$goods[COUNT],$el);
                    $el = str_replace("%title%","<a href=\"/shop/$cat[ID].html\">$cat[TITLE]</a>",$el);

                    if ($zak[STATE] == "ok")
                      $total+= (int)$goods[COUNT]*$goods[PRICE];
                    $goodsList.= $el;
                    $sum+=(float)($goods[PRICE]*$goods[COUNT]);
                  }
                }
                if ($isOk){
                  $singleZAKAZ = $ZAKAZ;
                  $singleZAKAZ = str_replace("%id%", $id, $singleZAKAZ);

                  if ($zak[STATE] == "ok")
                  $status = 'Доставлен';
                  if ($zak[STATE] == "del")
                  $status = 'Удален';
                  if ($zak[STATE] == "process")
        $status = 'В обработке';
        if ($zak[STATE] == "pay"){
              $status = 'Готов к оплате';
                //$id $sum
                $s2=round(((float)$sum - $user[money])/30, 2);
              if ($user[money]<=$sum-1){
                if ($zak[virtual])
                  $status .="<a href='/billing/pay.php?pay=$s2&user=$user[ID]'>Оплатить через SMS</a>";
              }else{
                if ($zak[virtual])
                  $status .=" У Вас на счету <b>$user[money]</b> руб. Этого достаточно что бы оплатить данный заказ.";
                if ($zak[virtual])
                  $status .="<form method=post action=\"/register/profile/dopay/$zak[ID]\"><input type=submit value=\"ОПЛАТИТЬ $sum руб с личного счета\"></form>";
              };
          }
          if ($zak[STATE] == "payed")
          $status = 'Оплачен';
                  if ($zak[STATE] == "new")
                  $status = 'Постановка на обработку';
                  if ($zak[STATE] == "go")
                  $status = 'Доставляется';
                  if ($zak[STATE] == "repeat")
                  $status = 'Отложен';

                  $singleZAKAZ = str_replace("%status%", $status, $singleZAKAZ);
                  $singleZAKAZ = str_replace("%date%", $zak[DATE_START], $singleZAKAZ);
                  $singleZAKAZ = str_replace("%list%", $goodsList, $singleZAKAZ);
                  $singleZAKAZ = str_replace("%summ%", $sum, $singleZAKAZ);
                  $zakazList.= $singleZAKAZ;
          if ($zak[STATE] == "pay"){
          ;
          };
                };
              };
              $OUTER = str_replace("%link%", "../../register/profile/profile", $OUTER);
              $OUTER = str_replace("%link2%", "../../register/profile/history", $OUTER);
              $OUTER = str_replace("%link3%", "../../register/profile/manage", $OUTER);
              $OUTER = str_replace("%total%", $total, $OUTER);
              $SQL = "UPDATE $conf[DB_PREFIX]accounts set total=$total where  ID=$user[ID]";
              $r = mysql_query($SQL) or die (mysql_error());

              $r = mysql_query($SQL) or die (mysql_error());

              $ue = $total;
              $data = GLOBAL_LOAD("skidki");
              $dat = unserialize ($data);

              if ($ue>=$dat['11'])
              $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[12] where  ID=$user[ID]";
              if ($ue>=$dat['21'])
              $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[22] where  ID=$user[ID]";
              if ($ue>=$dat['31'])
              $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[32] where  ID=$user[ID]";
              if ($ue>=$dat['41'])
              $SQL = "UPDATE $conf[DB_PREFIX]accounts set discount=$dat[42] where  ID=$user[ID]";

              $r = mysql_query($SQL) or die (mysql_error());

              
              $z= str_replace("%main%", $zakazList, $OUTER);
              return str_replace("%money%", $user[money], $z);
          }
      }
    }
  }
  function render($regionID = 0, $id, &$template) {
    $conf = $this->conf;
    
    //    die ($_SESSION[register][id]);
    if (!isset($_SESSION[register])){
      
      $ENTER= $template->Get("register.enter");
      $ENTER  = str_replace("%action%", "./register/login", $ENTER);
      $ENTER  = str_replace("%link%",   "./register/register", $ENTER);
      $ENTER  = str_replace("%link2%",  "./register/profile/profile", $ENTER);
      $ENTER  = str_replace("%login%", $_SESSION[register][login], $ENTER);
      $ENTER  = str_replace("%pass%", $_SESSION[register][pass], $ENTER);
      if ($_SESSION[LASTERROR]!=""){
        if ($_SESSION[LASTERROR]=="NAME_PASS")
        $ENTER  = str_replace("%error%", $template->Get("register.error_name_pass"), $ENTER);
        $_SESSION[LASTERROR]="";
      };
      
      $ENTER  = str_replace("%error%", "", $ENTER);
      
      return $ENTER;
    }else{
      //die();
      $ENTER= $template->Get("register.enterok");
      $ENTER  = str_replace("%link%",   "./register/register", $ENTER);
      $ENTER  = str_replace("%login%", $_SESSION[register][login], $ENTER);
      $ENTER  = str_replace("%link2%",  "./register/profile/profile", $ENTER);
      return $ENTER;
    };
  }
  function edit() {
    $action = $_GET[a];
    $conf = $this->conf;
    $id = $_GET[id];
  }
  function printheader()
  { 
	
	if ((int)$_GET[template]>=1){
		echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">'
		.'<center>'
		#.'<a href="?module=register&a=view&t" >Шаблоны</a> '
		#.'<a href="?module=register&a=ubertemplate" >Шаблоны 2</a> '
		#.'<a href="?module=register&a=view_profile" >Шаблоны отображения личной панели</a> '
		#.'<a href="?module=register&a=view_error" >Шаблоны сообщений об ошибках</a> '
		#.'<a href="?module=register&a=view_self">Личный Профиль</a> '
		#.'<a href="?module=register&a=view_zakaz">Список Заказов</a> '
		#.'<a href="?module=register&a=view_kv">Шаблон Квитанции</a> '
		#.'<a href="?module=register&a=view_users">Просмотреть список Пользователей</a> '
		#.'<a href="?module=register&a=view_messages">Сообщения</a> '
		#.'<a href="?module=register&a=view_manage">Manage</a> '
		#.'<a href="?module=register&a=view_resume">Resume</a> '
		#.'<a href="?module=register&a=send_messages">Spam!</a> '
		.'</center>'
		;
	}else{
		echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">'
		.'<center>'
		.'<a href="?module=register&a=view_users">Просмотреть список Пользователей</a> '
		.'<a href="?module=register&a=view_messages">Сообщения</a> '
		#.'<a href="?module=register&a=view_manage">Manage</a> '
		#.'<a href="?module=register&a=view_resume">Resume</a> '
		.'<a href="?module=register&a=send_messages">Массовая почтовая рассылка</a> '
		.'</center>'
		;
	}
    ?> <script>
      function NW(adr, w, h){
        win=window.open(adr,"_blank","toolbar=no,location=no,directories=no,status=no,menubar=no,width="+ w + ",height="+h);
        win.Target = document.forma.Target;
      };
  <?
  echo 'function DoConfirm(message, url){if (confirm(message))location.href = url;}</script>';
  }
};
$info = array(
  'plugin'      => "register",
  'cplugin'     => "eeRegister",
  'pluginName'    => "Регистрация пользователей",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>1,
  'ISSPECIAL'     =>1,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>
