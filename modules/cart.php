<?php
class eeCart{
	var $conf;
	function eeCart($conf){
		$this->conf = $conf;

	}
	function normalPrice($price)
	{
		$pr = round(($price - (int)$price)*100, 0);
		if ($pr>=10)
			return (int)$price .".". $pr;
		return $price;
	}
	function parseprice($price)
	{
		return $price;
		$newPrice="<table cellpadding=0 cellspacing=0 border=0>";
		$price=explode(";", $price);

		foreach ($price as $pr)
		{
			$split = explode(":", $pr);

			if (isset($split[1])){
				$newPrice.="<tr><td>$split[0]</td><td><font color='red'>$split[1]</font></td></tr>";
			}else{
				if (($pr != "") && (isset($pr)) && ($pr != 0))
				$newPrice.="<tr><td>&nbsp;</td><td><font color='red'>$pr</font></td></tr>";
			}
		};
		$newPrice.="</table>";
		return $newPrice;
	}

	function printheader()
	{?>
<html>
<head>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<body>
<a href="properties.php?module=cart&a=edit&template=<?echo $_GET[template]?>">Шаблон</a>
<a href="properties.php?module=cart&a=nak&template=<?echo $_GET[template]?>">Реализация</a>
<a href="properties.php?module=cart&a=nak2&template=<?echo $_GET[template]?>">Счет Ю.Л.</a>
<a href="properties.php?module=cart&a=nak3&template=<?echo $_GET[template]?>">Счет Ф.Л.</a>
<a href="properties.php?module=cart&a=nak4&template=<?echo $_GET[template]?>">Спецификация</a>
<a href="properties.php?module=cart&a=skidki&template=<?echo $_GET[template]?>">Скидки</a>
<hr>
	<?php

	//new reapeat out process go ok del out
	if ($_GET[f]=='open')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=open&template=$_GET[template]\">Открытые</a> ";
	if ($_GET[f]=='open')echo '</b>';

	if ($_GET[f]=='new')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=new&template=$_GET[template]\">Новые</a> ";
	if ($_GET[f]=='new')echo '</b>';

	if ($_GET[f]=='process')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=process&template=$_GET[template]\">В обработке</a> ";
	if ($_GET[f]=='process')echo '</b>';

	if ($_GET[f]=='pay')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=pay&template=$_GET[template]\">Готов к оплате</a> ";
	if ($_GET[f]=='pay')echo '</b>';

	if ($_GET[f]=='payed')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=payed&template=$_GET[template]\">Оплачен</a> ";
	if ($_GET[f]=='payed')echo '</b>';

	if ($_GET[f]=='go')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=go&template=$_GET[template]\">Доставляется</a> ";
	if ($_GET[f]=='go')echo '</b>';

	if ($_GET[f]=='ok')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=ok&template=$_GET[template]\">Доставлен</a> ";
	if ($_GET[f]=='ok')echo '</b>';


	if ($_GET[f]=='del')echo '<b>';
	echo "<a href=\"properties.php?module=cart&a=list&f=del&template=$_GET[template]\">Удален</a> ";
	if ($_GET[f]=='del')echo '</b>';

	}
	function pricein($a, $b)
	{
		$pr_3=explode(";", $a);
		$pr_ext = explode(":", $pr_3[0]);

		if (!isset ($pr_ext[1]))
		return  $pr_ext[0];
		$max = 0;

		foreach ($pr_3 as $pr)
		{
			$pr_ext = explode(":", $pr);
			$pr_2=explode("-", $pr_ext[0]);
			$lpr=$pr_2[0];
			$hpr=$pr_2[1];
			$max=$pr_ext[1];

			if (($b <= $hpr) && (($b >= $lpr)))
			{
				return $pr_ext[1];
			}
		}
		return $max;
	}
	function install() {
		$conf = $this->conf;
		return 1;
	}
	//*********************************************************************
	function printCatalogListParent($i, $exlude){
		$conf = $this->conf;
		global $level;
		$level++;
		$sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE `PARENT`=$i and `TYPE` = 0 ORDER BY `ORDER` ASC";
		$result = mysql_query($sql, $conf[DB]);
		echo "<table><tr><td>";

		while ($row = mysql_fetch_assoc($result)) {
			$tmpID = $row['ID'];
			if ($tmpID !=$exlude){
				$tmp=0;
				for ($tmp=0;$tmp<=$level;$tmp++) echo "&nbsp;&nbsp;";
				echo "<a href=\"javascript:dosort('$tmpID')\">";
				echo $row['TITLE'], "</a><br>";
			}
			printCatalogListParent($tmpID, $exlude);
			echo "</tr></td></table>";
		}
		mysql_free_result($result);
		$level--;
	}
	function properties(){
		$conf = $this->conf;

		$action = $_GET[a];

		switch ($action){
			case "editorder":
				$id = (int) $_GET[id];
				$SQL = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID=$id";
				$res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
				$zak = mysql_fetch_assoc($res)or die (mysql_error());

				$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
				$res2= mysql_query($sql, $conf[DB]);
				$i=0;
				echo "<form action=\"?module=cart&a=saveorder&id=$id\" method=post>";
				$_SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE EMAIL='$zak[MAIL]'";
				$user =mysql_fetch_assoc(mysql_query($_SQL, $conf[DB]));

				echo  "Name:<input type=text name=\"NAME\" value=\"$zak[NAME]\"> Tel:<input type=text name=\"TEL\" value=\"$zak[TEL]\"><a href=\"properties.php?module=register&a=view_user&id=$user[ID]\" target=_BLANK>$zak[MAIL]</a>";
				echo  "<br>";
				echo  "<textarea name=commoninfo cols=100>$zak[CONTENT]</textarea><br>";
				echo "<table border=1>";
				echo "<tr><td>Num</td><td>Title</td><td>Count</td><td>Price</td><td>Delete</td></tr>";
				while ($goods = @mysql_fetch_assoc($res2)){
					$sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
					$res3 = @mysql_query($sql, $conf[DB]);
					$cat = @mysql_fetch_assoc($res3);
					$i++;
					$SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms where id=$cat[FIRM]";
					$res3= @mysql_query($SQL, $conf[DB]);
					$firm=@mysql_fetch_assoc($res3);

					$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $cat[PARENT]";
					$tmpres = @mysql_query($tmpsql, $conf[DB]);
					$tmpcat = @mysql_fetch_assoc($tmpres);

					$price = $goods[PRICE]*$goods[VALUE]*$goods[VALUEK];
					echo "$i) $firm[NAME] $tmpcat[TITLE] $cat[TITLE] - $goods[COUNT](<b>$price</b>)";


					echo "<tr><td>$i)</td><td><a href=\"../shop/$goods[CATALOG_ID].html\">$firm[NAME] $tmpcat[TITLE] $cat[TITLE]</a> </td><td><input type=text value=\"$goods[COUNT]\" name=count$goods[ID] size=3></td><td>(<input type=text value=\"$goods[PRICE]\" name=price$goods[ID] size=6>)</td><td> <input type=checkbox value=del name=del$goods[ID]></td></tr>";
				}
				echo "</table>Add:<input type=text name=add><br><input type=submit></form>";
				echo "<form action=\"?module=cart&a=changeorderprice&id=$id\" method=post> Change<input type=text name=change>% <input type=submit></form>";
				$q1=$zak[q1];
				$q2=$zak[q2];
				$q3=$zak[q3];
				$q4=$zak[q4];
				echo "<form action=\"?module=cart&a=saveq&id=$id\" method=post>
      <ul><li>добрый день, Вас беспокоит  музыкальный базар, __ числа Вы получили заказ (наименование.)<input type=text value=\"$q1\"name=\"q1\"size=40>
            <li>нет ли претензий к доставке?<input type=text value=\"$q2\"name=\"q2\"size=40>
            <li>Все ли нормально работает?<input type=text value=\"$q3\"name=\"q3\" size=40>
            <li>а знаете ли Вы координаты Вашего личного менеджера?<input type=text value=\"$q4\"name=\"q4\" size=40>
      </ul>
      <input type=submit></form>
      ";

				//";
				break;
			case "saveq":
				$id = (int) $_GET[id];
				$q1=$_POST[q1];
				$q2=$_POST[q2];
				$q3=$_POST[q3];
				$q4=$_POST[q4];
				$sql = "UPDATE `$conf[DB_PREFIX]zakaz` SET q1='$q1',q2='$q2',q3='$q3',q4='$q4' WHERE id=$id";
				$r = mysql_query($sql) or die (mysql_error());
				header("Location: ?id=$id&module=cart&a=editorder");
				break;
			case "changeorderprice":
				$id = (int) $_GET[id];
				$SQL = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID=$id";
				$res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
				$zak = mysql_fetch_assoc($res)or die (mysql_error());
				$percent=(double)$_POST[change]/100.0;

				$sql = "UPDATE `$conf[DB_PREFIX]zakaz_goods` SET PRICE=PRICE+PRICE*($percent) WHERE ZAKAZ_ID=$zak[ID]";
				//    flush();
				//    die();
				$res2= mysql_query($sql, $conf[DB]);
				header("Location: ?id=$id$&module=cart&a=editorder");
				break;
			case "saveorder":
				$id = (int) $_GET[id];
				$SQL = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID=$id";
				$res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
				$zak = mysql_fetch_assoc($res)or die (mysql_error());

				$SQL = "UPDATE $conf[DB_PREFIX]zakaz set `NAME` = '$_POST[NAME]', TEL='$_POST[TEL]', CONTENT='$_POST[commoninfo]' WHERE ID=$id";
				$res = mysql_query($SQL, $conf[DB]) or die (mysql_error());

				$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
				$res2= mysql_query($sql, $conf[DB]);
				while ($goods = mysql_fetch_assoc($res2)){
					$price = $_POST["price$goods[ID]"]; // ($goods[VALUE]*$goods[VALUEK]);
					$count = $_POST["count$goods[ID]"];

					$sql = "UPDATE `$conf[DB_PREFIX]zakaz_goods` SET PRICE = $price, `COUNT`=$count WHERE ID=$goods[ID]";
					$res3 = mysql_query($sql, $conf[DB]) or die(mysql_error());


					$t = "del$goods[ID]";
					if ($_POST["$t"]=="del"){
						$sql = "delete from `$conf[DB_PREFIX]zakaz_goods` WHERE ID=$goods[ID]";
						$res3 = mysql_query($sql, $conf[DB]) or die(mysql_error());
					};
				}
				if ($_POST[add]!=0){
					$VALUEK = GLOBAL_LOAD("valuek.dat");
					$VALUE = GLOBAL_LOAD("value.dat") * $VALUEK;

					$SQL = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$_POST[add]";
					$res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
					$cat = mysql_fetch_assoc($res)or die (mysql_error());

					$SQL = "INSERT INTO `$conf[DB_PREFIX]zakaz_goods` (`CATALOG_ID`, `ZAKAZ_ID`, `PRICE`, `VALUE`, `VALUEK`, `COUNT`) ".
              "VALUES($_POST[add], $id, '$cat[PRICE]', '$VALUE','$VALUEK', 1)";
					$result = mysql_query($SQL, $conf[DB]) or die(mysql_error());

				}

				header("Location: ?id=$id$&module=cart&a=editorder");
				break;
			case "commitmail":
				$id = (int) $_GET[id];
				$SQL = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID=$id";
				$res = mysql_query($SQL, $conf[DB]);
				$zak = mysql_fetch_assoc($res);
				$data = unserialize($zak[DATA]);

				$SQL ="insert into $conf[DB_PREFIX]ee3_shop_mail(`EMAIL`, `MESSAGE`, `DATE`) values('$zak[MAIL]', '$_POST[message]', now())";
				$result = mysql_query($SQL, $conf[DB]) or die (mysql_error());

				$_content.="\r\n$_POST[message]";
				$_to="<$MAIL>";
				$_from=$_POST[mail];

				$_subject="MuzBazar";

				$_headers .= "From: MuzaBazar<sales@muzbazar.ru>\n";
				$_headers .= "X-Sender: <sales@muzbazar.ru>\n";
				$_headers .= "X-Mailer: PHP/mail()\n"; //mailer
				$_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

				$_headers .= "Return-Path: <sales@muzbazar.ru>\n";
				$_headers .= "Content-type: text/html; charset=UTF-8\r\n";

				mail($zak[MAIL], $_subject, $_content, $_headers);


				header ("Location: ?module=cart&a=mail&id=$_GET[id]");
				break;
			case "mail":
				$id = (int) $_GET[id];
				$SQL = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID=$id";
				$res = mysql_query($SQL, $conf[DB]);
				$zak = mysql_fetch_assoc($res);

				$data = unserialize($zak[DATA]);

				$SQL = "SELECT * FROM $conf[DB_PREFIX]ee3_shop_mail WHERE `EMAIL`='$zak[MAIL]' ORDER by `DATE` DESC";
				$result = mysql_query($SQL, $conf[DB]);

				?>
<h3><?=$zak[MAIL]?></h3>
<form method=post action="?id=<?=$id?>&module=cart&a=commitmail"><textarea cols=50 name=message></textarea> <input type=submit></form>
<hr>
				<?

				while ($mail = mysql_fetch_assoc($result)) {
					echo "$mail[MESSAGE]<br>$mail[DATE]<hr>";
				};
				break;
case "print4":
	$tID = $_GET[template];
	if (!$tID)
	die("template error");

	include_once ("core_template.php");
	$template = new Template($_GET[template]);
case "print3":
case "print2":
case "print":
	$MAIN = GLOBAL_LOAD( "nak.dat");
	$VALUEK = GLOBAL_LOAD("valuek.dat");
	$VALUE = GLOBAL_LOAD("value.dat") * $VALUEK;

	if ($action=="print2"){
		$MAIN = GLOBAL_LOAD( "nak2.dat");
	};
	if ($action=="print3"){
		$MAIN = GLOBAL_LOAD( "nak3.dat");
	};
	if ($action=="print4"){
		$MAIN = GLOBAL_LOAD( "nak4.dat");
	};


	if ($VALUE=="")
	$VALUE = 1;
	$id = (int) $_GET[id];
	$SQL = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID=$id";
	$res = mysql_query($SQL, $conf[DB]);
	$zak = mysql_fetch_assoc($res);


	$MAIN= str_replace("%ID%", $zak[ID], $MAIN);
	$MAIN= str_replace("%DATE%", date("Y-m-d", strtotime($zak[DATE_START])), $MAIN);
	$MAIN= str_replace("%NAME%", $zak[NAME], $MAIN);
	$count=0;
	$PRICETOTAL=0;
	$PRICETOTAL_ALL=0;
	$_18_ALL=0.0;
	$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
	$res2= mysql_query($sql, $conf[DB]);
	while ($goods = mysql_fetch_assoc($res2)){
		$_goods_count = $goods[COUNT];
		$_TITLE = $_temp[TITLE];
		$_DESC  = $_temp[DESC];
		$sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
		$res3 = mysql_query($sql, $conf[DB]) or die(mysql_error());
		$cat = mysql_fetch_assoc($res3)or die(mysql_error());

		$count++;
		$PRICE = $goods[PRICE];//*$goods[VALUE]*$goods[VALUEK];
		$PRICETOTAL = $PRICE*$goods[COUNT];
		$PRICETOTAL_ALL+=$PRICETOTAL;
		$_18_ALL+=round($PRICE/1.18*0.18*$goods[COUNT], 2);

		$SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms where id=$cat[FIRM]";
		$res3= mysql_query($SQL, $conf[DB]);
		$firm=@mysql_fetch_assoc($res3);

		$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $cat[PARENT]";
		$tmpres = mysql_query($tmpsql, $conf[DB]);
		$tmpcat = mysql_fetch_assoc($tmpres);

		//  echo "$i) $firm[NAME] $tmpcat[TITLE] $cat[TITLE] - $goods[COUNT](<b>$goods[PRICE]</b>)";
		$PRICE = round($PRICE, 2);
		$PRICETOTAL = round($PRICETOTAL, 2);
		//die ($firm[NAME]);
		if ($firm[NAME]==" no name")
		$firm[NAME]=" ";

		$_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$cat[ID] AND TYPE = 'catalog' order by ID";
		$_res=mysql_query($_sql, $conf[DB]);
		if (@$_image=mysql_fetch_assoc($_res)){
			$_IMAGE="../files/$_image[ID]";
		}else{
			$_IMAGE="../absent.jpg";
		};

		$ret.="<tr><td>$count</td><td>$firm[NAME] <i>$tmpcat[TITLE]</i> <br>%img% $cat[TITLE] %header%</td><td>$_goods_count</td><td>шт.</td><td>".$this->normalPrice($PRICE)."</td><td>".$this->normalPrice($PRICETOTAL)."</td></tr>";

		if ($action=="print4"){
			$ret = str_replace("%img%","<img align=left src=\"$_IMAGE\">",$ret);
			$ret = str_replace("%header%",$cat[HEADER],$ret);
		}

		$ret = str_replace("%img%","",$ret);
		$ret = str_replace("%header%","",$ret);
	}

	$MAIN= str_replace("%COUNT%", $count, $MAIN);
	$MAIN= str_replace("%SUM%", $this->normalPrice(round($PRICETOTAL_ALL, 2)), $MAIN);
	$MAIN= str_replace("%18%", $_18_ALL, $MAIN);
	$MAIN= str_replace("<!--%ROW%-->", $ret, $MAIN);
	include_once("../tools/numeral.php");

	$MAIN= str_replace("%WRITE%", Numeral(round($PRICETOTAL_ALL, 2)), $MAIN);
	$MAIN= str_replace("%ADDR%", $zak[CONTENT] .", ". $zak[TEL], $MAIN);

	$MAIN.="<script>window.print();</script>";
	echo $MAIN;
	break;
case "skidki":
	$this->printheader();
	$data = GLOBAL_LOAD("skidki");
	$dat = unserialize ($data);
	echo "<form method=post action=\"properties.php?module=cart&a=saveskidki\">" ;

	echo "<table>";
	echo "<tr><td><input type=text name=\"_11\" value=\"$dat[11]\"></td><td><input type=text name=\"_12\" value=\"$dat[12]\"></td></tr>";
	echo "<tr><td><input type=text name=\"_21\" value=\"$dat[21]\"></td><td><input type=text name=\"_22\" value=\"$dat[22]\"></td></tr>";
	echo "<tr><td><input type=text name=\"_31\" value=\"$dat[31]\"></td><td><input type=text name=\"_32\" value=\"$dat[32]\"></td></tr>";
	echo "<tr><td><input type=text name=\"_41\" value=\"$dat[41]\"></td><td><input type=text name=\"_42\" value=\"$dat[42]\"></td></tr>";

	echo "</table><input type=submit></form>";
	break;
case "saveskidki":
	$dat['11'] = $_POST[_11];$dat['12'] = $_POST[_12];
	$dat['21'] = $_POST[_21];$dat['22'] = $_POST[_22];
	$dat['31'] = $_POST[_31];$dat['32'] = $_POST[_32];
	$dat['41'] = $_POST[_41];$dat['42'] = $_POST[_42];

	GLOBAL_SAVE("skidki", serialize($dat));
	header("Location: properties.php?a=edit&module=cart&a=skidki");
	break;
case "":
	$_GET[f]="open";
case "list":
	$this->printheader();
	$isG=0;
	$where = "manager=$_SESSION[manager_id]";

	#if (CA("manage_cart")){
	#  $where = "1";
	#};
	#if (CA("ALL cart")){
	$where = "1";
	#}
	$assignst="";
	$movelinks="
        
          <a href=\"?id=&ID&&module=cart&a=editorder\" target=_BALNK >Редактировать</a>
          <a href=\"?id=&ID&&module=cart&a=print\" target=_BALNK >Печать</a>
           <a href=\"?id=&ID&&module=cart&a=print2\" target=_BALNK >Платежка</a>
           <a href=\"?id=&ID&&module=cart&a=print3\" target=_BALNK >Счет</a>
           <a href=\"?id=&ID&&module=cart&a=print4\" target=_BALNK >spec</a>
           <a href=\"?id=&ID&&module=cart&a=mail\" target=_BALNK >Письмо</a><br>

        <a href=\"?id=&ID&&old=$_GET[f]&module=cart&a=commitmove&f=new\">Новые</a> 
          <a href=\"?id=&ID&&old=$_GET[f]&module=cart&a=commitmove&f=process\">В обработке</a> 
          <a href=\"?id=&ID&&old=$_GET[f]&module=cart&a=commitmove&f=pay\">Готов к оплате</a> 
          <a href=\"?id=&ID&&old=$_GET[f]&module=cart&a=commitmove&f=payed\">Оплачен</a> 
          <a href=\"?id=&ID&&old=$_GET[f]&module=cart&a=commitmove&f=go\">Доставляется</a> 
          <a href=\"?id=&ID&&old=$_GET[f]&module=cart&a=commitmove&f=ok\">Доставлен</a>
          <a href=\"?id=&ID&&old=$_GET[f]&module=cart&a=commitmove&f=del\">Удален</a>
        ";

	$state = "STATE= '$_GET[f]'";
	if ($_GET[f]=="open"){
		$state = " not(STATE='ok' or STATE='del')";
	}
	$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE  $state and ($where) ORDER BY ID desc";
	$res = mysql_query($sql, $conf[DB]);

	while ($zak = @mysql_fetch_assoc($res)){
		echo  "<h3>$zak[ID]</h3>";
		if ($where=="1"){
			echo "Naznachit na ispolnene: ";
			$msql="select * from $conf[DB_PREFIX]managers";
			$mres = mysql_query($msql, $conf[DB]) or die(mysql_error());
			while ($man = @mysql_fetch_assoc($mres)){
				echo " . <a href=\"?id=$zak[ID]&old=$_GET[f]&module=cart&a=assign&manager=$man[id]\">";
				if (($zak[manager]== $man[id]) || CA("ALL cart")) echo  "|<b><font size=+2>$man[name]</font></b>|";
				else echo "<i>$man[name]</i>";
				echo "</a> . ";
			};
			echo "<br>";

		};
		if (($zak[manager]==$_SESSION[manager_id]) || CA("ALL cart")){
			echo str_replace("&ID&",$zak[ID], $movelinks);
		}else{
			//if ($where=="1") echo str_replace("&ID&",$zak[ID], $movelinks);
		};
		$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$zak[ID]";
		$res2= mysql_query($sql, $conf[DB]) or die(mysql_error());
		$i=0;

		echo  "<br>";
		echo "Статус: <font color=red>";
		switch ($zak[STATE]){
			case "new": echo "Новые";break;
			case "process": echo "В обработке";break;
			case "pay": echo "Готов к оплате";break;
			case "payed": echo "Оплачен";break;
			case "go":  echo "Доставляется";break;
			case "ok": echo "Доставлен";break;
			case "del": echo "Удален";break;
			break;

		}
		echo "</font>";

		if ($zak[partner])
		echo  "<br><font color=orange>Номер партнера - <b>$zak[partner]</b></font>";
		echo  "<br>";
		$totalprice=0;
		while ($goods = @mysql_fetch_assoc($res2)){

			$sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
			$res3 =@mysql_query($sql, $conf[DB]);
			$cat = @mysql_fetch_assoc($res3);
			$i++;
			if ($cat[INPRICE]==0)
			echo  "<font color=green size=+2>";

			$SQL = "SELECT * FROM $conf[DB_PREFIX]catalog_firms where id=$cat[FIRM]";
			$res3= @mysql_query($SQL, $conf[DB]);
			$firm=@mysql_fetch_assoc($res3);

			$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $cat[PARENT]";
			$tmpres = @mysql_query($tmpsql, $conf[DB]);
			$tmpcat = @mysql_fetch_assoc($tmpres);

			echo "$i) <b>$firm[NAME]</b> <i>$tmpcat[TITLE]</i> $cat[TITLE] - $goods[COUNT](<b>$goods[PRICE]</b>)<br>";
			$totalprice+=$goods[COUNT]*$goods[PRICE];
			if ($cat[INPRICE]==0)
			echo  "</font>";
		}

		//          echo $zak[CONTENT];
		echo "<br>Полная стоимость: $totalprice";
		echo "<br>$zak[DATE_START] - (<i> <b>$zak[NAME]</b> - $zak[TEL] - $zak[ADDR])</i><hr>";

		//if($isG)
		// echo  "</font>";
	}
	break;
case "assign":
	$sql = "UPDATE `$conf[DB_PREFIX]zakaz` SET manager= '$_GET[manager]' where ID=$_GET[id]";
	$res = mysql_query($sql, $conf[DB]);
	header ("Location: ?module=cart&a=list&f=$_GET[old]");
	break;
case "commitmove":
	$sql = "UPDATE `$conf[DB_PREFIX]zakaz` SET STATE= '$_GET[f]' WHERE ID=$_GET[id]";
	$res = mysql_query($sql);

	if ($_GET[f]=='payed'){
		//get all gootd from order
		$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz_goods` WHERE ZAKAZ_ID=$_GET[id]";
		$res= mysql_query($sql, $conf[DB]) or die(mysql_error());
		while ($goods = mysql_fetch_assoc($res)){
			$sql = "SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$goods[CATALOG_ID]";
			$res2= mysql_query($sql) or die(mysql_error());
			$cat = mysql_fetch_assoc($res2);

			//check file
			if ($cat[file]){
				//create symlinlk
/*

				$sql = "insert into $conf[DB_PREFIX]tmpfile_rel_order(`date`, `goods_id`, `order_id`) values(now(), $cat[ID], $_GET[id])";
				$res3 = mysql_query($sql) or die(mysql_error());
				$id = mysql_insert_id();
				$hash = md5($id+1234);
				$this->CreateFile($cat[file], $hash);
				$sql = "update $conf[DB_PREFIX]tmpfile_rel_order set `file`='$hash' where id=$id";
				$res3 = mysql_query($sql) or die(mysql_error());

				//@mkdir("../public/$hash");
				//echo "$_SERVER[DOCUMENT_ROOT]private/$goods[file]";
				//@symlink("$_SERVER[DOCUMENT_ROOT]private/$cat[file]", "$_SERVER[DOCUMENT_ROOT]public/$hash/$cat[file]");
*/
			}
		};
	};

	if ($_GET[f]=='ok'){
		$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE ID='$_GET[id]' ORDER BY ID desc";
		$res = mysql_query($sql, $conf[DB]);
		$zakMAil = mysql_fetch_assoc($res);
		$zakmail = $zakMAil[MAIL];


		$sql = "select * from $conf[DB_PREFIX]accounts where `EMAIL` = '$zakmail'";
		$c=@mysql_query($sql, $conf[DB]);// or die(mysql_error());
		$account=@mysql_fetch_assoc($c);// or die(mysql_error());
		if (!account){
			header ("Location: ?module=cart&a=list&f=$_GET[old]");
			exit();
		}

		$sql = "SELECT * FROM `$conf[DB_PREFIX]zakaz` WHERE MAIL='$zakmail' ORDER BY ID desc";
		$res = mysql_query($sql, $conf[DB]);
		$zakazList="";
		$total=0;

		while ($zak = @mysql_fetch_assoc($res)){
			$id = $zak[ID];
			/*
			 $f = fopen("http://rxpromotio.interpalace.ru/partner/addmoney.php?partner=1&sum=10.1&project_id=1&order_id=1", "r");
			 $payok="";
			 while ($line=fgets($h, 100)){
			 $payok.=$line;
			 };
			 if ($payok!="OK"){
			 die ("Payment error");
			 };
			 */
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
		$OUTER = str_replace("%link3%", "../../register/profile/manage", $OUTER);
		$OUTER = str_replace("%total%", $total, $OUTER);
		$SQL = "UPDATE $conf[DB_PREFIX]accounts set total=$total where  ID=$account[ID]";

		$r = mysql_query($SQL) or die (mysql_error());

		$ue = $total;
		$data = GLOBAL_LOAD("skidki");
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

	}
	header ("Location: ?module=cart&a=list&f=$_GET[old]");
	break;
case "nak":
	$this->printheader();
	$MAIN = GLOABL_LOAD("nak.dat");
	?>
<form method="POST" action="properties.php?a=savenak&module=cart&template=<?=$_GET[template];?>"><TEXTAREA rows="20"
	style="WIDTH: 100%" name="MAIN"><?=$MAIN;?></TEXTAREA>
</td>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</FORM>
	<?
	break;
case "nak2":
	$this->printheader();
    $MAIN = GLOABL_LOAD("nak2.dat");   	
    ?>
<form method="POST" action="properties.php?a=savenak2&module=cart&template=<?=$_GET[template];?>"><TEXTAREA rows="20"
	style="WIDTH: 100%" name="MAIN"><?=$MAIN;?></TEXTAREA>
</td>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</FORM>
	<?
	break;
case "nak3":
	$this->printheader();
	
$MAIN = GLOABL_LOAD("nak3.dat");
	?>
<form method="POST" action="properties.php?a=savenak3&module=cart&template=<?=$_GET[template];?>"><TEXTAREA rows="20"
	style="WIDTH: 100%" name="MAIN"><?=$MAIN;?></TEXTAREA>
</td>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</FORM>
	<?
	break;
case "nak4":
	$this->printheader();
	$MAIN = GLOABL_LOAD("nak4.dat");
	?>
<form method="POST" action="properties.php?a=savenak4&module=cart&template=<?=$_GET[template];?>"><TEXTAREA rows="20"
	style="WIDTH: 100%" name="MAIN"><?=$MAIN;?></TEXTAREA>
</td>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</FORM>
	<?
	break;
case "savenak":
		$MAIN = GLOBAL_SAVE("nak.dat", $_POST[MAIN]);
	
	header("Location: properties.php?a=edit&module=cart&a=nak&template=$_GET[template]");
	break;
case "savenak2":
	$MAIN = GLOBAL_SAVE("nak2.dat", $_POST[MAIN]);
	header("Location: properties.php?a=edit&module=cart&a=nak2&template=$_GET[template]");
	break;
case "savenak3":
    $MAIN = GLOBAL_SAVE("nak3.dat", $_POST[MAIN]);
	header("Location: properties.php?a=edit&module=cart&a=nak3&template=$_GET[template]");
	break;
case "savenak4":
    $MAIN = GLOBAL_SAVE("nak4.dat", $_POST[MAIN]);
	header("Location: properties.php?a=edit&module=cart&a=nak4&template=$_GET[template]");
	break;

case "edit":
	$this->printheader();
	$tID = $_GET[template];
	if (!$tID)
	die("template error");

	include_once ("core_template.php");
	$template = new Template($_GET[template]);

	$ADDR  = &$template->Get("shop.cart.addr");#CORE_LOAD("cart", "addr.dat");
	$ELEMENT  = &$template->Get("shop.cart.element");#CORE_LOAD("cart", "element.dat");
	$OUTER    = &$template->Get("shop.cart.outer");#CORE_LOAD("cart", "outer.dat");
	$SMALL    = &$template->Get("shop.cart.small");#CORE_LOAD("cart", "small.dat");
	$COMMIT   = &$template->Get("shop.cart.commit");#CORE_LOAD("cart", "commit.dat");
	$MAIL   = &$template->Get("shop.cart.mail");#CORE_LOAD("cart", "mail.dat");
	$OK     = &$template->Get("shop.cart.ok");#CORE_LOAD("cart", "ok.dat");
	?>
<form method="POST" action="properties.php?a=save&module=cart&template=<?=$_GET[template]?>">
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
	<tr>
		<td colspan="2" align="center">
		<h2>Элемент</h>
		
		</td>
	</tr>
	<tr>
		<td>Строка корзины<br>
		<nobr><b>%title%</b> - Заголовок<br>
		<nobr><b>%header%</b>- Кратокое Описание<br>
		<nobr><b>%description%</b>- Описание<br>
		<nobr><b>%price%</b>- цена<br>
		<nobr><b>%imageadr%</b>- Адрес кртинки<br>
		<nobr><b>%count%</b>- Текущее количество товара<br>
		<nobr><b>%parsedcount%</b>- расчитанная цена за еденицу товара<br>
		<nobr><b>%totalcount%</b>- расчитанная цена за товары<br>
		<nobr><b>%setcount%</b>- адрес для установления нового количества<br></td>
		<td width="100%"><TEXTAREA rows="9" style="WIDTH: 100%" name="ELEMENT"><?=$ELEMENT;?></TEXTAREA></td>
	</tr>

	<tr>
		<td>Окружение<br>
		<nobr><b>%main%</b>- Список товаров<br>
		<nobr><b>%parsedcount%</b>- расчитанная цена за товары<br>
		<nobr><b>%commitlink%</b>- Ссылка на оформление заказа<br></td>
		<td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="OUTER"><?=$OUTER;?></TEXTAREA></td>
	</tr>
	<tr>
		<td>Корзина<br>
		<nobr><b>%link%</b> - адрес для входа в корзину<br></td>
		<td width="100%"><TEXTAREA rows="7" style="WIDTH: 100%" name="SMALL"><?=$SMALL;?></TEXTAREA></td>
	</tr>
	<tr>
		<td>Оформление Заказа<br>
		Необходимо использовать следующие названия полей: <nobr><b>FIO</b> - ФИО<br>
		<nobr><b>MAIL</b> - EMAIL<br>
		<nobr><b>COMMONINFO</b> - Дополнительная информация о заказе<br>
		<nobr><b>%link%</b> - Адрес для формы.<br></td>
		<td width="100%"><TEXTAREA rows="9" style="WIDTH: 100%" name="COMMIT"><?=$COMMIT;?></TEXTAREA></td>
	</tr>
	<tr>
		<td>Заказ<br>
		%main% - Текст аказа показываемый пользователю и отсылаемый по почте.<br>
		</td>
		<td width="100%"><TEXTAREA rows="9" style="WIDTH: 100%" name="OK"><?=$OK;?></TEXTAREA></td>
	</tr>
	<tr>
		<td>Обратный Адрес</td>
		<td width="100%"><input type="text" value="<?=$MAIL;?>" name="MAIL">
	
	</tr>
	<tr>
		<td>Адрес после покупки</td>
		<td width="100%"><input type="text" value="<?=$ADDR;?>" name="ADDR">
	
	</tr>
</TABLE>
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
	$template->Set("shop.cart.element", $_POST[ELEMENT]);
	$template->Set("shop.cart.outer", $_POST[OUTER]);
	$template->Set("shop.cart.small", $_POST[SMALL]);
	$template->Set("shop.cart.commit", $_POST[COMMIT]);
	$template->Set("shop.cart.mail", $_POST[MAIL]);
	$template->Set("shop.cart.ok", $_POST[OK]);
	$template->Set("shop.cart.addr", $_POST[ADDR]);
	$template->Save();

	header("Location: properties.php?a=edit&module=cart&template=$_GET[template]");
	break;
		};
	}
	function add() {
		return 0;
	}
	function del($id){
		return 1;
	}
	function renderEx($id, $template){
		$conf = $this->conf;
		$path = split("/", $_GET[path]);
		$VALUE = GLOBAL_LOAD("value.dat");#CORE_LOAD("shop", "value.dat");
		$VALUEK = GLOBAL_LOAD("valuek.dat");
		if ($VALUE=="")
		$VALUE = 1;
		if ($_SESSION[register][id]){
			$userid = $_SESSION[register][id];
			$SQL = "select * from $conf[DB_PREFIX]accounts where ID = $userid";
			$_userRes = mysql_query($SQL);
			$account = mysql_fetch_assoc($_userRes);
			$ELEMENT = $template->Get("shop.elementskidka");
		}

		if ((int)$path[1]!=0){
			$temp=$_SESSION[cart][goods];

			$ty="";
			$add = $path[1];
			while (@$_goods_item=current($temp)){
				$_goods_id = key($temp);
				$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_goods_id";
				@$tmpres = mysql_query($tmpsql);
				@$tmpcat = mysql_fetch_assoc($tmpres);
				if  ($tmpcat[virtual]!=1){
					$ty="real";
				}else{
					$ty="virtual";
				}
				next($temp);
				break;
			};

			$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $add";
			@$tmpres = mysql_query($tmpsql, $conf[DB]);
			@$tmpcat = mysql_fetch_assoc($tmpres);

			if ($tmpcat[virtual]==1 and $ty=="virtual"){
				$_SESSION[cart][goods][$add]=1;#(int)($_SESSION[cart][goods][$add])+1;
				$_SESSION['virtual']=1;
			}

			if ($tmpcat[virtual]!=1 and $ty=="real"){
				$_SESSION[cart][goods][$add]=1;#(int)($_SESSION[cart][goods][$add])+1;
				$_SESSION['virtual']=0;
			}
			if ($ty=="")
			$_SESSION[cart][goods][$add]=1;#(int)($_SESSION[cart][goods][$add])+1;

			if ($_SESSION[cart][goods][$add] == 0)
			unset ($_SESSION[cart][goods][$add]);
			header("Location: /cart/view/");
			#header("Location: $_SERVER[HTTP_REFERER]");
		}else{
			switch ($path[1]){
				case "":
				case "view":
					$_temp=$_SESSION[cart][goods];

					$ELEMENT  = $template->Get("shop.cart.element");
					//die ($ELEMENT);
					$OUTER    = $template->Get("shop.cart.outer");
					$_RET = "";
					$_parsedcount = 0;
					while (@$_goods_item=current($_temp))
					{
						$_goods_id = key($_temp);
						$_goods_count=$_goods_item;

						//load the goods from database
						$_SQL="SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=$_goods_id";
						$_result=mysql_query($_SQL, $conf[DB])or die(mysql_error());
						$_catalog=mysql_fetch_assoc($_result);

						//load images for this goods
						$_sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$_catalog[ID] AND TYPE = 'catalog' order by ID";
						$_res=mysql_query($_sql, $conf[DB]);
						if (@$_image=mysql_fetch_assoc($_res)){
							$_IMAGE="./files/$_image[ID]";
						}else{
							$_IMAGE="./absent.jpg";
						};
						//parsing the output...

						$_ret = "";
						$_ret = $ELEMENT;

						$priceval = $_catalog[PRICE]*1;#*$VALUEK;

						if ($userid){
							if($account[discount_admin]!=-1){
								$skidka = $account[discount_admin];
							}else{
								$skidka = $account[discount];
							}
							$percent = (float)$priceval / 100;
							$priceval-=$percent*$skidka;
						}

						$_PRICE=$priceval;

						$_ret = str_replace("%title%", $_catalog[TITLE], $_ret);
						$_ret = str_replace("%id%", $_catalog[ID], $_ret);
						$_ret = str_replace("%header%", stripslashes($_catalog[HEADER]), $_ret);
						$_ret = str_replace("%description%", stripslashes($_catalog[CONTENT]), $_ret);
						$_ret = str_replace("%price%", $priceval*$VALUEK , $_ret);
						$_ret = str_replace("%price2%", $priceval* $VALUEK*$VALUE, $_ret);
						$_ret = str_replace("%imageadr%", $_IMAGE, $_ret);
						$_ret = str_replace("%count%", $_goods_count, $_ret);

						$_ret = str_replace("%parsedcount%", $_PRICE, $_ret);
						$_ret = str_replace("%parsedcount2%", $_PRICE*$VALUE, $_ret);
						$_ret = str_replace("%totalcount%", $_PRICE * $_goods_count, $_ret);
						$_ret = str_replace("%totalcount2%", $_PRICE *$VALUE*$_goods_count, $_ret);
						//      $_ret = str_replace("%convertlink%", "?module=cart&subaction=convert", $_ret);

						$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog[PARENT]";
						@$tmpres = mysql_query($tmpsql, $conf[DB]);
						@$tmpcat = mysql_fetch_assoc($tmpres);
						$_ret = str_replace("%parent%", $tmpcat[TITLE], $_ret);

						$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog[FIRM]";
						$tmpres = mysql_query($tmpsql, $conf[DB]);
						$tmpfir = mysql_fetch_assoc($tmpres);
						$_ret = str_replace("%firm%", $tmpfir[NAME], $_ret);

						$_parsedcount +=$_PRICE * $_goods_count;
						$_ret = str_replace("%setcount%", "./cart/updatecount/$_catalog[ID]", $_ret);
						next($_temp);

						$_RET .=$_ret;
					}

					$COMMIT = &$template->Get("shop.cart.commit");#CORE_LOAD("cart","commit.dat");
					$COMMIT = str_replace("%link%", "./cart/commit", $COMMIT);

					$RETURN = str_replace("%main%", $_RET . $COMMIT, $OUTER);
					$RETURN = str_replace("%parsedcount%", $_parsedcount, $RETURN);
					$RETURN = str_replace("%parsedcount2%", $_parsedcount*$VALUE, $RETURN);
					$RETURN = str_replace("%convertlink%", "./cart./convert", $RETURN);

					$uid = $_SESSION[register][id];

					$SQL = "SELECT * FROM $conf[DB_PREFIX]accounts WHERE ID = $uid";
					$u =@mysql_fetch_assoc(@mysql_query($SQL, $conf[DB]));


					$RETURN = str_replace("%NAME%", $u[NAME], $RETURN);
					$RETURN = str_replace("%TEL%", $u[TEEL], $RETURN);
					$RETURN = str_replace("%MAIL%", $u[EMAIL], $RETURN);
					$RETURN = str_replace("%ADDR%", $u[ADDR], $RETURN);

					#$calc   = &$template->Get("shop.credit.small");#CORE_LOAD("credit","small.dat");
					#$credit = &$template->Get("shop.credit.template");#CORE_LOAD("credit", "_template.dat");
					#$RETURN = str_replace("%credit%", $credit, $RETURN);

					$express = "";

					if ($_SESSION[country]==""){
						$countries = array();

						$express.= "Выберете страну: <ul>";
						$sql = "select distinct country from smstarif";
						$r=mysql_query($sql) or die(mysql_error());
						while ($country = @mysql_fetch_assoc($r)){
							$co = urlencode($country[country]);
							$express.= "<li> <a href='/cart/setcountry/?c=$co'>$country[country]</a>";
						};
						$express.= "</ul>";
					};

					if ($_SESSION[country]!="" and $_SESSION[operator]==''){

						$express.= "Страна: <b>$_SESSION[country]</b><a href='/cart/clearcountry'>сменить</a><br>";
						$express.= "Выберете оператора: <ul>";


						$sql = "select distinct operatorname from smstarif where country='$_SESSION[country]' order by operatorname";
						$r=mysql_query($sql) or die(mysql_error());

						while ($op = @mysql_fetch_assoc($r)){
							$opu = urlencode($op[operatorname]);
							$express.= "<li> <a href='/cart/setoperator/?o=$opu'>$op[operatorname]</a>";
						};
						$express.= "</ul>";
					}

					if ($_SESSION[country]!="" and $_SESSION[operator]!=''){
						$pr = round(($_parsedcount*$VALUE-$_SESSION[summ]),2);
						if ($pr>=0.1){
							$express.= "Страна: <b>$_SESSION[country]</b><a href='/cart/clearcountry'>сменить</a><br>";
							$express.= "Оператор: <b>$_SESSION[operator]</b> <a href='/cart/clearoperator'>сменить</a><br>";
							$express.= "Выберете тариф, все цены указаны без НДС:<ul>";

							$sql = "select * from smstarif where country='$_SESSION[country]' and operatorname='$_SESSION[operator]' order by usdprice";
							$r=mysql_query($sql) or die(mysql_error());
							while ($item = @mysql_fetch_assoc($r)){
								$printprice = $item[usdprice]*30;
								$express.= "<li><b>$item[usdprice]</b>$, - номер $item[number]";
							};
							$express.= "</ul><br>";
							$express.= "Пошлите <b>filmdoc</b> на выбранный номер, получите ответный SMS c котодом и введите его: ";
							$express.= "<form method=POST action=\"/cart/takesmskey/\"><input type=text name=code><input type=submit value='отправить код'></form>";
							$express.= "<br>Вы пока заплатили <b>$_SESSION[summ]</b> руб. и Вам нехватает $pr руб.";
						}else{
							$express.= "<form method=POST action=\"/cart/by/\">Вы набрали нужную сумму<br>";
							$express.= "Ваша почта: <input type=text name = 'mail'>";
							$express.= "<input type=submit value='Купить'></form>";
						};
					};

					$RETURN = str_replace("%express%", $express, $RETURN);

					return $RETURN;

					break;
				case "takesmskey":
					$key = $_POST[code];
					$SQL = "SELECT * from smspay where `key` = '$key' and active=1";
					$r = mysql_query($SQL) or die (mysql_error());
					if($tiket = @mysql_fetch_assoc($r)){
						$_SESSION[summ]+=round(((float)$tiket[price]),2);
						#$SQL = "UPDATE $conf[DB_PREFIX]accounts set money=money+$tiket[price]*$kurs where ID = $userID LIMIT 1";
						#$r = mysql_query($SQL) or die (mysql_error());
						$SQL = "UPDATE smspay set active=0 where id=$tiket[id]";
						$r = mysql_query($SQL) or die (mysql_error());
					};
					header("Location: /cart/view");
					break;
				case "by":
					break;
				case "setcountry":
					$c = urldecode($_GET[c]);
					$_SESSION[country]=$c;
					header ("Location: /cart/view");
					break;
				case "setoperator":
					$o = urldecode($_GET[o]);
					$_SESSION[operator]=$o;
					header ("Location: /cart/view");
					break;
				case "clearcountry":
					$_SESSION[country]="";
					$_SESSION[operator]="";
					header ("Location: /cart/view");
					break;
				case "clearopearator":
					$_SESSION[operator]="";
					header ("Location: /cart/view");
					break;
				case "updatecount":
					$add = $path[2];
					$_SESSION[cart][goods][$add]=(int)$_POST[count];
					if ($_SESSION[cart][goods][$add] == 0)
					unset ($_SESSION[cart][goods][$add]);
					header ("Location: ../view");
					break;
				case "clear":
					unset($_SESSION[cart]);
					header ("Location: ../cart/view");
				break;
				case "by":
					//////////////////////////////////////////////////////////
//============================================================
					$goods_id=0;
					$virtual = -1;

					$VALUEK   = GLOBAL_LOAD( "valuek.dat");
					$VALUE = GLOBAL_LOAD( "value.dat");
					$_content="";
					$_temp2=0;
					$_temp=$_SESSION[cart][goods];
					$MAIL= &$template->Get("shop.cart.mail");
					$_itemp=1;
					$TMPVAR = array();
					$zakazA = array();

					if ($_SESSION[register][id]){
						$userid = $_SESSION[register][id];
						$SQL = "select * from $conf[DB_PREFIX]accounts where ID = $userid";
						$_userRes = mysql_query($SQL);
						$account = mysql_fetch_assoc($_userRes);
					}

					while (@$_goods_item=current($_temp)){
						$_goods_id = key($_temp);
						$_goods_count=$_goods_item;
						$TMPVAR[$_goods_id]=$_goods_count;

						$_SQL2="SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$_goods_id";
						$_result4=mysql_query($_SQL2, $conf[DB])or die(mysql_error());
						$_catalog_item=mysql_fetch_assoc($_result4);
						if($virtual==-1){
							if ($_catalog_item[virtual]==1){
								$virtual=1;
							}
						}

						if ($userid){
							if($account[discount_admin]!=-1){
								$skidka = $account[discount_admin];
							}else{
								$skidka = $account[discount];
							}
							$percent = (float)$_catalog_item[PRICE] / 100;
							$_catalog_item[PRICE]-=$percent*$skidka;
						}
						$_PRICE = $_catalog_item[PRICE];

						$_DESC=$_catalog_item[HEADER];
						$_TITLE=$_catalog_item[TITLE];
						$_COUNT=$_goods_count;
						$_PRICETOTAL=(float)$_PRICE * $_COUNT;
						$_PRICETOTAL2 =$_PRICETOTAL *$VALUE;

						$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog_item[PARENT]";
						$tmpres = mysql_query($tmpsql, $conf[DB]);
						$tmpcat = mysql_fetch_assoc($tmpres);

						$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog_item[FIRM]";
						$tmpres = mysql_query($tmpsql, $conf[DB]);
						$tmpfir = mysql_fetch_assoc($tmpres);

						$_PRICE2 = $_PRICE*$VALUE;

						$TMPVAR["$_goods_id"]= array();
						$TMPVAR["$_goods_id"][count]=$_goods_count;
						$TMPVAR["$_goods_id"][TITLE]=$_TITLE;
						$TMPVAR["$_goods_id"][FIO]=$_POST[FIO];
						$TMPVAR["$_goods_id"][DESC]=$_DESC;
						$TMPVAR["$_goods_id"][PRICE]=$_PRICE;
						$TMPVAR["$_goods_id"][PRICE2]=$_PRICE2;
						$TMPVAR["$_goods_id"][PRICETOTAL]=$_PRICETOTAL;
						$TMPVAR["$_goods_id"][PRICETOTAL2]=$_PRICETOTAL2;
						$TMPVAR["$_goods_id"][FIRM]=$tmpfir[NAME];
						$TMPVAR["$_goods_id"][CAT]=$tmpcat[TITLE];

						$zakaz_line= array();
						$zakaz_line[CATALOG_ID] = $_goods_id;
						$zakaz_line[VALUE] = $VALUE;
						$zakaz_line[VALUEK] = $VALUEK;
						$zakaz_line[PRICE] = $_catalog_item[PRICE];
						$zakaz_line["COUNT"] = $_COUNT;

						$zakazA[] = $zakaz_line;
						$_content.="<br>$_itemp - $tmpcat[TITLE]) - $_TITLE - - по цене - $_PRICE2 на сумму $_PRICETOTAL2\r\n";
						$_temp2+=$_PRICETOTAL;
						$_SESSION[cart][count]=((int)($_SESSION[cart][count])) - 1;

						if ( $_SESSION[cart][count] == 0)
						unset ($_SESSION[cart][count]);
						unset ($_SESSION[cart][goods][$goods_id]);
						$_itemp++;
						next ($_temp);
					};
					$tt= $_temp2 * $VALUE;
					$_content.="\r\n<br>  Итого: $_temp2($tt)<br>";
					$_content.="\r\n-----------------------------------------------------\r\n<br>";
					$_content.="E-Mail - $_POST[mail]<br>\r\n";
					$_to="<$MAIL>";
					$_from=$_POST[mail];

					//$_subject="Ваш заказ принят! Номер заказа - $_tmp_id"

					$_headers .= "From: Magazindoc <$MAIL>\n";
					$_headers .= "X-Sender: <$MAIL>\n";
					$_headers .= "X-Mailer: PHP/mail()\n"; //mailer
					$_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

					$_headers .= "Return-Path: <$MAIL>\n";
					$_headers .= "Content-type: text/html; charset=UTF-8\r\n";

					$OK     = &$template->Get("shop.cart.ok");#CORE_LOAD("cart", "ok.dat");
					$RETURN = str_replace("%main%", $_content, $OK);

					$sql = "select count(*) as `count` from $conf[DB_PREFIX]accounts where `EMAIL` = '$_POST[mail]'";
					$c=mysql_query($sql, $conf[DB]);// or die(mysql_error());
					$c=mysql_fetch_assoc($c);// or die(mysql_error());

					unset ($_SESSION[cart]);
					$r = $RETURN;
					$u[ID]=0;

					$state='new';
					if ($virtual==1)	$state="pay";
					$par = (int)$_SESSION[partner];
					$sql = "INSERT INTO `$conf[DB_PREFIX]zakaz`(USER_ID, CONTENT, MAIL, TEL, NAME, DATE_START, `virtual`, `STATE`, `referal`, `partner`) values ($u[ID], '', '$_POST[mail]','','', now(), $virtual, '$state', '$_SESSION[referal]', $par)";
					$result = mysql_query($sql, $conf[DB])or die(mysql_error());
					$zakaz_id=mysql_insert_id($conf[DB]) or die(mysql_error());
					$_subject="Order from magazindoc.ru ($zakaz_id)";

					foreach($zakazA as $t){
						$SQL = "INSERT INTO `$conf[DB_PREFIX]zakaz_goods` (`CATALOG_ID`, `ZAKAZ_ID`, `PRICE`, `VALUE`, `VALUEK`, `COUNT`) VALUES($t[CATALOG_ID], $zakaz_id, '$t[PRICE]', '$t[VALUE]','$t[VALUEK]', $t[COUNT])";
						$result = mysql_query($SQL, $conf[DB]) or die(mysql_error());
					}
					;


					if (!$IsMAiled){
						mail($_to, $_subject, $RETURN, $_headers);
						mail($_from, $_subject, $RETURN, $_headers);
					};
					return $RETURN;

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
						};
					};
				break;
				case "commit":
					$goods_id=0;
					if($_POST['fio']=="" || $_POST['mail']==""){
						header ("Location: ../cart/view");
					}else{
						$virtual = -1;

						$VALUEK   = GLOBAL_LOAD( "valuek.dat");
						$VALUE = GLOBAL_LOAD( "value.dat");
						$_content="";
						$_temp2=0;
						$_temp=$_SESSION[cart][goods];
						$MAIL= &$template->Get("shop.cart.mail");#CORE_LOAD("cart","mail.dat");
						$_itemp=1;
						$TMPVAR = array();
						$zakazA = array();


						if ($_SESSION[register][id]){
							$userid = $_SESSION[register][id];
							$SQL = "select * from $conf[DB_PREFIX]accounts where ID = $userid";
							$_userRes = mysql_query($SQL);
							$account = mysql_fetch_assoc($_userRes);
						}

						while (@$_goods_item=current($_temp)){
							$_goods_id = key($_temp);
							$_goods_count=$_goods_item;
							$TMPVAR[$_goods_id]=$_goods_count;

							$_SQL2="SELECT * FROM `$conf[DB_PREFIX]catalog` WHERE ID=$_goods_id";
							$_result4=mysql_query($_SQL2, $conf[DB])or die(mysql_error());
							$_catalog_item=mysql_fetch_assoc($_result4);
							if($virtual==-1){
								if ($_catalog_item[virtual]==1){
									$virtual=1;
								}
							}

							if ($userid){
								if($account[discount_admin]!=-1){
									$skidka = $account[discount_admin];
								}else{
									$skidka = $account[discount];
								}
								$percent = (float)$_catalog_item[PRICE] / 100;
								$_catalog_item[PRICE]-=$percent*$skidka;


							}
							$_PRICE = $_catalog_item[PRICE];

							$_DESC=$_catalog_item[HEADER];
							$_TITLE=$_catalog_item[TITLE];
							$_COUNT=$_goods_count;
							$_PRICETOTAL=(float)$_PRICE * $_COUNT;
							$_PRICETOTAL2 =$_PRICETOTAL *$VALUE;

							$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID = $_catalog_item[PARENT]";
							$tmpres = mysql_query($tmpsql, $conf[DB]);
							$tmpcat = mysql_fetch_assoc($tmpres);

							$tmpsql = "SELECT * FROM $conf[DB_PREFIX]catalog_firms WHERE ID = $_catalog_item[FIRM]";
							$tmpres = mysql_query($tmpsql, $conf[DB]);
							$tmpfir = mysql_fetch_assoc($tmpres);

							$_PRICE2 = $_PRICE*$VALUE;

							$TMPVAR["$_goods_id"]= array();
							$TMPVAR["$_goods_id"][count]=$_goods_count;
							$TMPVAR["$_goods_id"][TITLE]=$_TITLE;
							$TMPVAR["$_goods_id"][FIO]=$_POST[FIO];
							$TMPVAR["$_goods_id"][DESC]=$_DESC;
							$TMPVAR["$_goods_id"][PRICE]=$_PRICE;
							$TMPVAR["$_goods_id"][PRICE2]=$_PRICE2;
							$TMPVAR["$_goods_id"][PRICETOTAL]=$_PRICETOTAL;
							$TMPVAR["$_goods_id"][PRICETOTAL2]=$_PRICETOTAL2;
							$TMPVAR["$_goods_id"][FIRM]=$tmpfir[NAME];
							$TMPVAR["$_goods_id"][CAT]=$tmpcat[TITLE];

							$zakaz_line= array();
							$zakaz_line[CATALOG_ID] = $_goods_id;
							$zakaz_line[VALUE] = $VALUE;
							$zakaz_line[VALUEK] = $VALUEK;
							$zakaz_line[PRICE] = $_catalog_item[PRICE];
							$zakaz_line["COUNT"] = $_COUNT;

							$zakazA[] = $zakaz_line;

							$_content.="<br>$_itemp - $tmpcat[TITLE]) $tmpfir[NAME] - $_TITLE - $_ARTICUL - $_DESC  - в количестве $_COUNT, по цене - $_PRICE ($_PRICE2) на сумму $_PRICETOTAL($_PRICETOTAL2)\r\n";

							$_temp2+=$_PRICETOTAL;

							$_SESSION[cart][count]=((int)($_SESSION[cart][count])) - 1;

							if ( $_SESSION[cart][count] == 0)
							unset ($_SESSION[cart][count]);
							unset ($_SESSION[cart][goods][$goods_id]);
							$_itemp++;
							next ($_temp);
						};
						$tt= $_temp2 * $VALUE;
						$_content.="\r\n<br>  Итого: $_temp2($tt)<br>";

						$_content.="\r\n-----------------------------------------------------\r\n<br>";
						$_content.="ФИО - $_POST[fio]<br>\r\n";
						$_content.="E-Mail - $_POST[mail]<br>\r\n";
						$_content.="Телефон- $_POST[tel]<br>\r\n";
						$_content.="Дополнительная информация - $_POST[commoninfo]<br>\r\n";

						$_to="<$MAIL>";

						$_from=$_POST[mail];

						//$_subject="Ваш заказ принят! Номер заказа - $_tmp_id"

						$_headers .= "From: Magazindoc <$MAIL>\n";
						$_headers .= "X-Sender: <$MAIL>\n";
						$_headers .= "X-Mailer: PHP/mail()\n"; //mailer
						$_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

						$_headers .= "Return-Path: <$MAIL>\n";
						$_headers .= "Content-type: text/html; charset=UTF-8\r\n";
						//        $_headers .= "cc: info@astrodesign.ru\n"; // CC to
						//        $_headers .= "bcc: info@astrodesign.ru";

						$OK     = &$template->Get("shop.cart.ok");#CORE_LOAD("cart", "ok.dat");
						$RETURN = str_replace("%main%", $_content, $OK);

						$sql = "select count(*) as `count` from $conf[DB_PREFIX]accounts where `EMAIL` = '$_POST[mail]'";
						$c=mysql_query($sql, $conf[DB]);// or die(mysql_error());
						$c=mysql_fetch_assoc($c);// or die(mysql_error());

						if ($c["count"]==0){
							//register user
							$_a = md5(rand(0,10000));
							$_pass=substr($_a, 1, 8);
							$_SQL   = "INSERT INTO `$conf[DB_PREFIX]accounts` (`LOGIN`, `EMAIL`, `PASSWORD`)"
							." VALUES ('$_POST[fio]', '$_POST[mail]', '$_pass')";
							mysql_query($_SQL);
							$u[ID]=mysql_insert_id();

							$OK = &$template->Get("register.ok");#CORE_LOAD("register", "ok.dat");
							$EMAIL = &$template->Get("register.email");#CORE_LOAD("register", "email.dat");
							$NAME= &$template->Get("register.name");#CORE_LOAD("register", "name.dat");
							$ADDR= &$template->Get("register.addr");#CORE_LOAD("register", "addr.dat");

							$OK = str_replace("%name%", $_POST[fio], $OK);
							$OK = str_replace("%pass%", $_pass, $OK);
							$OK = str_replace("%mail%", $_POST[mail], $OK);
							$OK = str_replace("%discount%", $discount, $OK);
							$OK = str_replace("%zakaz%", $_content, $OK);

							$_subject="Регистрация: ";

							$_headers .= "From: Регистрация <$EMAIL>\n";
							$_headers .= "X-Sender: <$EMAIL>\n";
							$_headers .= "X-Mailer: PHP/mail()\n"; //mailer
							$_headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

							$_headers .= "Return-Path: <$EMAIL>\n";
							$_headers .= "Content-type: text/html; charset=UTF-8\r\n";
							$_headers .= "cc: $EMAIL\n"; // CC to
							$_headers .= "bcc: $EMAIL";

							mail($_POST[mail], $_subject, $OK, $_headers);
							//mail("$_POST[mail]", $_subject, $OK, $_headers);
							$IsMAiled=true;
						}
						unset ($_SESSION[cart]);
						$r = $RETURN;

						$_POST[commoninfo] = addslashes($_POST[commoninfo]);

						if($u[ID]==0)
						$u[ID]=0;

						$u[ID]=0;

						$state='new';
						if ($virtual==1)
						$state="pay";
						$par = (int)$_SESSION[partner];
						$sql = "INSERT INTO `$conf[DB_PREFIX]zakaz`(USER_ID, CONTENT, MAIL, TEL, NAME, DATE_START, `virtual`, `STATE`, `referal`, `partner`) values ($u[ID], '$_POST[commoninfo]', '$_POST[mail]','$_POST[tel]','$_POST[fio]', now(), $virtual, '$state', '$_SESSION[referal]', $par)";
						//die();//flush();
						$result = mysql_query($sql, $conf[DB])or die(mysql_error());

						$zakaz_id=mysql_insert_id($conf[DB]) or die(mysql_error());
						$_subject="Order from Muzbazar.ru ($zakaz_id)";


						foreach($zakazA as $t){
							$SQL = "INSERT INTO `$conf[DB_PREFIX]zakaz_goods` (`CATALOG_ID`, `ZAKAZ_ID`, `PRICE`, `VALUE`, `VALUEK`, `COUNT`) ".
        "VALUES($t[CATALOG_ID], $zakaz_id, '$t[PRICE]', '$t[VALUE]','$t[VALUEK]', $t[COUNT])";
							$result = mysql_query($SQL, $conf[DB]) or die(mysql_error());
						}
						;


						if (!$IsMAiled){
							mail($_to, $_subject, $RETURN, $_headers);
							mail($_from, $_subject, $RETURN, $_headers);
						};
						$ADDR = &$template->Get("shop.cart.addr");#CORE_LOAD("cart", "addr.dat");
						header("Location: $ADDR");
						flush();
						die();
					};
					break;
				};
			};
		}
		function render($regionID = 0, $id, $template) {
			$conf = $this->conf;
			$VALUEK = GLOBAL_LOAD( "valuek.dat");
			$VALUE = GLOBAL_LOAD("value.dat") ;

			$_temp=$_SESSION[cart][goods];

			$_parsedcount = 0;
			$count=0;
			if ($_SESSION[register][id]){
				$userid = $_SESSION[register][id];
				$SQL = "select * from $conf[DB_PREFIX]accounts where ID = $userid";
				$_userRes = mysql_query($SQL);
				$account = mysql_fetch_assoc($_userRes);
				$ELEMENT = &$template->Get("shop.elementskidka");#CORE_LOAD("shop", "elementskidka.dat");
			}

			while (@$_goods_item=current($_temp)){
				$_goods_id = key($_temp);
				$_goods_count=$_goods_item;
				//load the goods from database
				$_SQL="SELECT * FROM $conf[DB_PREFIX]catalog WHERE ID=$_goods_id";
				$_result=mysql_query($_SQL, $conf[DB])or die(mysql_error());
				$_catalog=mysql_fetch_assoc($_result);

				$_ret = "";
				$_ret = $ELEMENT;

				$_PRICE=$this->pricein($_catalog[PRICE]*$VALUEK, $_goods_count);
				$count+=(int)$_goods_count;

				if ($userid){
					if($account[discount_admin]!=-1){
						$skidka = $account[discount_admin];
					}else{
						$skidka = $account[discount];
					}
					$percent = (float)$_PRICE / 100;
					$_PRICE-=$percent*$skidka;
				}else{
					//
				}

				$_parsedcount +=$_PRICE * $_goods_count;
				next($_temp);

				$_RET .=$_ret;
			}
			$RETURN = $template->Get("shop.cart.small");#CORE_LOAD("cart","small.dat");
			$RETURN = str_replace("%count%", $count, $RETURN);
			$RETURN = str_replace("%parsedcount%", $_parsedcount, $RETURN);
			$RETURN = str_replace("%parsedcount2%", $_parsedcount*$VALUE, $RETURN);

			$RETURN = str_replace("%link%", "./cart", $RETURN);
			return $RETURN;
		}
		function edit() {
			$action = $_GET[a];
			$conf = $this->conf;
		}
	};
	$info = array(
'plugin'      => "cart",
'cplugin'     => "eeCart",
'pluginName'    => "Корзина для магазина",
'ISMENU'      =>0,
'ISENGINEMENU'    =>0,
'ISBLOCK'     =>1,
'ISEXTRABLOCK'    =>1,
'ISSPECIAL'     =>1,
'ISLINKABLE'    =>0,
'ISINTERFACE'   =>0,
	);
?>
