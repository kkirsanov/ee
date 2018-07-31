<?
class eeVote{
	var $conf;
	function eeVote($conf)
	{
		$this->conf = $conf;
	}
	function install() {
		$conf = $this->conf;
		$SQL = "CREATE TABLE `$conf[DB_PREFIX]pool_data` ("
		."`ID` int(11) NOT NULL auto_increment,"
		."`PARENT` int(11) NOT NULL default '0',"
		."`TEXT` varchar(900) NOT NULL default '',"
		."`COUNT` int(10) unsigned NOT NULL default '0',"
		."`ORDER` int(11) NOT NULL default '0',"
		."PRIMARY KEY  (`ID`)"
		.") ENGINE=MyISAM CHARACTER SET utf8;"
		;
		mysql_query($SQL, $conf[DB]);
		$SQL = "CREATE TABLE `$conf[DB_PREFIX]pool_main` ("
		."`ACTIVE` int(11) NOT NULL default '0',"
		."`PARENTSOURCE` int(11) NOT NULL default '0',"
		."`ID` int(11) NOT NULL auto_increment,"
		."`TITLE` varchar(250) NOT NULL default '',"
		."PRIMARY KEY  (`ID`)"
		.") ENGINE=MyISAM CHARACTER SET utf8;"
		;
		mysql_query($SQL, $conf[DB]);

		$SQL = "CREATE TABLE `$conf[DB_PREFIX]voteblock` ("
		."`VOTEID` int(11),"
		."`ID` int(11) NOT NULL auto_increment, "
		."PRIMARY KEY  (`ID`)"
		.") ENGINE=MyISAM CHARACTER SET utf8;"
		;

		//@registerAccess("module_vote_edit", "Голосование - <b>управление</b>");
		//@registerAccess("module_vote_design", "Голосование  - <b>дизайн</b>");
		mysql_query($SQL, $conf[DB]);
		return 1;
	}
	function properties(){
		$action = $_GET[a];
		$conf = $this->conf;
		$id = $_GET[id];
		if ($action == "")
		$action="view";

		switch ($action){
			case "view":
				$this->printheader();

				$sql = "SELECT * FROM `$conf[DB_PREFIX]pool_main`";
				$result = mysql_query($sql, $conf[DB]);
				while ($row = mysql_fetch_assoc($result))
				{
					echo "<a href=\"JavaScript:DoConfirm('Вы действительно зхотите удалить данный Опрос?','menu.php?a=Pdel&id=$row[ID]')\"><img src=\"images/del.gif\" border=0></a>";
					echo "<a class=\"phplm\" href=\"?a=edit&id=$row[ID]&module=vote\">$row[TITLE]</a>";
					echo "<br>";
				}
				break;
			case "add":
				$this->printheader();
				?>
<form name="forma" action="?a=addnew&module=vote" method="post"><input
	type="text" value="Заголовок нового Опроса" name="title" size=35> <br>
<input type="submit" value="Принять" class="mainoption" size=15></form>
				<?
				break;
case "addnew":
	$sql="INSERT INTO `$conf[DB_PREFIX]pool_main` (`TITLE`) VALUES ('$_POST[title]')";
	mysql_query($sql, $conf[DB]);
	$id=mysql_insert_id($conf[DB]);
	mysql_close($conf[DB]);
	header("Location: ?a=edit&id=$id&module=vote");
	break;

case "Qadd":
	$SQL="SELECT max(`ORDER`) as `maximum` FROM `$conf[DB_PREFIX]pool_data` WHERE `PARENT`=$id";
	$res=mysql_query($SQL, $conf[DB]);
	$ord=mysql_fetch_assoc($res);
	$order=0 + (int)$ord[maximum] + 1;

	$sql="INSERT INTO `$conf[DB_PREFIX]pool_data` (`TEXT`, `ORDER`, `PARENT`) VALUES ('$_POST[pTITLE]', $order, $id)";
	mysql_query($sql, $conf[DB]);
	mysql_close($conf[DB]);
	header("Location: ?a=edit&id=$id&module=vote");
	break;
case "updatename":
	$sql="UPDATE `$conf[DB_PREFIX]pool_main` SET `TITLE`= '$_POST[pTITLE]' WHERE `ID`=$_GET[id]";
	mysql_query($sql, $conf[DB]);
	mysql_close($conf[DB]);
	header("Location: ?a=edit&id=$_GET[id]&module=vote");
	break;
case "Qdel":
	$sql="delete from `$conf[DB_PREFIX]pool_data` WHERE ID=$_GET[add]";
	$r = mysql_query($sql, $conf[DB])or die (mysql_error());
	header("Location: ?a=edit&id=$id&module=vote");
	break;
case "edit":
	$conf = $this->conf;
	$id=$_GET[id];
	$this->printheader();?>
<script>
          function DoConfirm(message, url)
            {
            if (confirm(message))
              location.href = url;
            }
        </script>
<link href="css.css"
	rel="stylesheet" type="text/css">
</head>
<body scroll="auto">
<table border="0">
<?
$sql = "SELECT * FROM `$conf[DB_PREFIX]pool_main` WHERE ID=$id";
$result=mysql_query($sql, $conf[DB]);
$pool=mysql_fetch_assoc($result);
echo "<tr><td>Название опроса</td><td><form name=\"forma\" method = \"post\" action=\"?a=updatename&id=$pool[ID]&module=vote\"><input size=\"40\" name = \"pTITLE\" type=text value=\"$pool[TITLE]\"class=\"mainopton\"><input type=\"submit\" value = \"Изменить\" class=\"mainoption\"></form></td></tr>";
echo "<tr><td>Добавить новый ответ</td><td><form name=\"forma\" method = \"post\" action=\"?a=Qadd&id=$pool[ID]&module=vote\"><textarea cols=52 name = \"pTITLE\">Текст нового ответа...</textarea><input type=\"submit\" value = \"Добавить\" class=\"mainoption\"></form></td></tr>";
$sql="select * from `$conf[DB_PREFIX]pool_data` WHERE `PARENT` = $id";
$result=mysql_query($sql, $conf[DB]);
if ($result)
{
	echo "<tr><td colspan=\"2\"><table>";
	echo "<form method = \"post\" name=\"form\" action=\"?a=update&id=$id&module=vote\">";
	?>
	<tr>
		<td></td>
		<td>Ответ</td>
		<td>Количество кликов</td>
	</tr>
	<?
	while ($variant=mysql_fetch_assoc($result))
	{
		echo "<tr>";

		echo "<td><a href=\"JavaScript:DoConfirm('Вы действительно зхотите удалить данный Ответ?','?a=Qdel&id=$id&add=$variant[ID]&module=vote')\"><img src=\"images/del.gif\" border=0></a></td>";
		echo "<td><input size=\"40\" name = \"vNAME$variant[ID]\" type=text value=\"$variant[TEXT]\"class=\"mainopton\"></td>";
		echo "<td><input size=\"0\" name = \"vCOUNT$variant[ID]\" type=text value=\"$variant[COUNT]\"class=\"mainopton\"></td>";
		echo "</tr>";
	}
	echo "</tr></td></table>";
	echo "<input type=\"submit\" value = \"Принять\" class=\"mainoption\">";
	echo "</form>";
	echo "</table>";
	?>

	<?
}
echo "</body></html>";
break;

case "update":
	$sql="select * from `$conf[DB_PREFIX]pool_data` WHERE `PARENT` = $id";
	$result=mysql_query($sql, $conf[DB]);
	if ($result)
	{
		while ($variant=mysql_fetch_assoc($result))
		{
			$SQL = "UPDATE `$conf[DB_PREFIX]pool_data` set `TEXT` = '" . $_POST["vNAME$variant[ID]"] . "', `COUNT` = " . (int)$_POST["vCOUNT$variant[ID]"] . " WHERE `ID` = $variant[ID]";
			mysql_query($SQL, $conf[DB]);
		}
	}
	;
	header("Location: ?a=edit&id=$id&module=vote");
	break;

case "view":
	$this->printheader();
	break;

case "template":
	$this->printheader();

	$tID = $_GET[template];
	if (!$tID)die("template error");

	include_once ("core_template.php");
	$template = new Template($_GET[template]);

	$this->ELEMENT  = &$template->Get("vote.element");
	$this->TEMPLATE = &$template->Get("vote.main");

	?>
	<form method="POST"
		action="?a=save&module=vote&template=<?echo $_GET[template] ?>">
	<TABLE border="1" width="90%" bgcolor="#ECECEC">
		<tr>
			<td colspan="2" align="center">
			<h2>Шаблон</h2>
			</td>
		</tr>
		<tr>
			<td><br>
			<nobr>%title% - название голосования</nobr> <br>
			<nobr>%main% - набор элементов</nobr></td>
			<td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
				name="TEMPLATE"><?=$this->TEMPLATE;?></TEXTAREA></td>
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
			<nobr>%link% - ссылка голосования</nobr> <br>
			<nobr>%100% - значение от 1 до 100</nobr> <br>
			<nobr>%value% - количество проголосовавших</nobr> <br>
			<nobr>%text% - текстовое описание</nobr></td>
			<td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
				name="ELEMENT"><?=$this->ELEMENT;?></TEXTAREA></td>
		</tr>
	</TABLE>
	<center><INPUT type="submit" value="Принять" class="mainoption"></center>
	</FORM>
	<?
	break;
case "save":
	$tID = $_GET[template];
	if (!$tID)die("template error");

	include_once ("core_template.php");
	$template = new Template($_GET[template]);

	$template->Set("vote.element", $_POST[ELEMENT]);
	$template->Set("vote.main", $_POST[TEMPLATE]);
	$template->Save();
	header("Location: ?a=template&module=vote&template=$_GET[template]");
	break;
		}
	}
	function add() {
		$conf = $this->conf;
		$SQL = "INSERT INTO `$conf[DB_PREFIX]voteblock` values()";
		mysql_query($SQL, $conf[DB]);
		return mysql_insert_id($conf[DB]);

		return 0;
	}
	function del($id){
		$conf = $this->conf;
		$SQL = "DELETE FROM $conf[DB_PREFIX]voteblock WHERE ID=$id";
		mysql_query($SQL, $conf[DB]);
		return 1;
	}
	function renderEx($id, &$template) {
		$conf = $this->conf;
		$path = split("/", $_GET[path]);
		$sql="SELECT * FROM `$conf[DB_PREFIX]pool_data` where `ID`=$path[1]";
		$result=mysql_query($sql, $conf[DB]);
		$variant=mysql_fetch_assoc($result);
		if (!isset($_SESSION["pool$variant[PARENT]"]))
		{
			$_SESSION["pool$variant[PARENT]"]=1;
			$sql="update `$conf[DB_PREFIX]pool_data` set `COUNT`=`COUNT`+1 where `ID`=$path[1]";
			mysql_query($sql, $conf[DB]);
		}
		;
		$link = "../";
		if ($path[2]!=""){
			$link .= "../$path[2]";
		};
		header ("Location: $link");
	}
	function render($regionID = 0, $id, &$template) {
		$conf = $this->conf;
		$_SQL = "SELECT * FROM $conf[DB_PREFIX]voteblock WHERE ID=$id";
		$_res = mysql_query($_SQL, $conf[DB]);
		$votebl= mysql_fetch_assoc ($_res);
		$poolID = $votebl[VOTEID];

		if(!$poolID)
		return "";

		$path = split("/", $_GET[path]);

		$this->RETURN = "";
		$this->TEMPLATE = "";
		$this->TEMPLATE =& $template->Get("vote.template");
		$this->ELEMENT  =& $template->Get("vote.element");

		$conf = $this->conf;
		$_sql="SELECT * FROM `$conf[DB_PREFIX]pool_main` where `ID` = $poolID";
		$_result=mysql_query($_sql, $conf[DB]);
		$_pool=mysql_fetch_assoc($_result);

		$_SQL="SELECT max(`COUNT`) as `max` FROM `$conf[DB_PREFIX]pool_data` WHERE `PARENT` = $_pool[ID]";
		$_result=mysql_query($_SQL, $conf[DB]);
		$_tmp=mysql_fetch_assoc($_result);
		mysql_free_result ($_result);
		$_max=$_tmp[max];
		$_SQL="SELECT * FROM `$conf[DB_PREFIX]pool_data` where `PARENT` = $_pool[ID]";

		$_result=mysql_query($_SQL, $conf[DB]);
		$_tmp_element="";
		$_ret="";
		while ($_variant=mysql_fetch_assoc($_result)){
			if ($_variant[COUNT] != 0){
				$_COUNT=(int)(($_variant[COUNT] / $_max) * 100);
			}else{
				$_COUNT=0;
			};
			$_tmp_element = $this->ELEMENT;

			$link = "./vote/$_variant[ID]";

			if ($path[0]!=""){
				$link .= "/$path[0]";
			}

			$_tmp_element = str_replace("%link%", $link, $_tmp_element);
			$_tmp_element = str_replace("%100%", "$_COUNT", $_tmp_element);
			$_tmp_element = str_replace("%value%", "$_variant[COUNT]", $_tmp_element);
			$_tmp_element = str_replace("%text%", "$_variant[TEXT]", $_tmp_element);
			$_ret.=$_tmp_element;
		}
		$this->TEMPLATE= str_replace("%title%", "$_pool[TITLE]", $this->TEMPLATE);
		$this->TEMPLATE= str_replace("%main%", $_ret, $this->TEMPLATE);
		return $this->TEMPLATE;

	}
	function edit() {

		$conf = $this->conf;
		switch ($_GET[a]){
			case "update":
				$sql="UPDATE $conf[DB_PREFIX]voteblock SET `VOTEID`=$_GET[voteid] where ID=$_GET[id]";
				mysql_query($sql, $conf[DB]);
				header ("Location: edit.php?id=$_GET[id]&module=vote");
				break;
			case "":
				echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">';

				$sql = "SELECT * FROM $conf[DB_PREFIX]voteblock WHERE ID=$_GET[id]";
				$result=mysql_query($sql, $conf[DB]);
				$block  =mysql_fetch_assoc($result);

				$sql = "SELECT * FROM $conf[DB_PREFIX]pool_main";
				$result=mysql_query($sql, $conf[DB]);
				echo "<ul>";
				while ($row=mysql_fetch_assoc($result))
				{
					$a = $row[TITLE];
					if ($block[VOTEID]==$row[ID]) $a = "<B>$a</B>";
					echo "<li><a href=\"edit.php?module=vote&a=update&id=$_GET[id]&voteid=$row[ID]\">$a</a><br>";
				};
				echo "</ul>";
				break;
		};
	}
	function printheader()
	{

		echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">'
		.'<center>'
		.'<a href="?a=add&module=vote" >Добавить </a>'
		.'<a href="?a=view&module=vote" >Просмотреть </a>'
		//.'<a href="?a=template&module=vote">Шаблон </a>'
		.'</center>'
		.'<script>function DoConfirm(message, url){if (confirm(message))location.href = url;}</script>'
		;
	}
};
$info = array(
  'plugin'      => "vote",
  'cplugin'     => "eeVote",
  'pluginName'    => "Голосование",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>1,
  'ISSPECIAL'     =>1,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>
