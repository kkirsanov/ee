<?php

class eeRandomText{
	var $conf;
	function eeRandomText($conf)
	{
		$this->conf = $conf;
	}
	function install() {
		$conf = $this->conf;

		$SQL = "CREATE TABLE `$conf[DB_PREFIX]randtext` ("
		."`ID` int(11) NOT NULL auto_increment,"
		."`NAME` VARCHAR(100) NOT NULL,"
		."`CONTENT` mediumtext NOT NULL,"
		."PRIMARY KEY  (`ID`)"
		.") ENGINE=MyISAM CHARACTER SET utf8";

		mysql_query($SQL, $conf[DB]);
		return 1;
	}
	function properties(){
		$action = $_GET[a];
		$conf = $this->conf;
		$id = $_GET[id];
		//include_once ("core_file_works.php");
		//$filew = new Fileworks($_GET[id], 'randtext');

		if ($action == "")
		$action="view";

		switch ($action){
			case "view":
				$conf = $this->conf;
				$this->printheader();
				$sql = "SELECT * FROM $conf[DB_PREFIX]randtext";
				@$result=mysql_query($sql, $conf[DB]);
				while (@$row=mysql_fetch_assoc($result)){
					echo stripslashes($row['NAME']),
        "<form action = \"properties.php?a=edit&id=$row[ID]&module=randomtext\" method=\"post\"><input name=\"submit\" type=\"submit\" class=\"mainoption\" value=\"Изменить($row[ID])\"></form>",
        "<hr>";
				}
				break;
			case "addtext":
				$this->printheader();
				echo "<br>";
				?>
<form name="forma"
	action="properties.php?a=addnewcommit&module=randomtext" method="post">
<input type="text" value="Название Нового Текста" name="title" size="45"><br>
<input type="submit" value="Принять" class="mainoption"></form>
				<?
				break;
case "addnewcommit":
	$sql="INSERT INTO `$conf[DB_PREFIX]randtext` (`NAME`) VALUES ('$_POST[title]')";
	$res = mysql_query($sql, $conf[DB]) or die (mysql_error());
	$id=mysql_insert_id($conf[DB]);
	mysql_close($conf[DB]);
	header("Location: properties.php?a=edit&id=$id&module=randomtext");
	break;
case "delete":
	$sql="delete from `$conf[DB_PREFIX]randtext` where ID=$_GET[id]";
	$res = mysql_query($sql, $conf[DB]) or die (mysql_error());
	header("Location: properties.php?module=randomtext");
	break;
case "update":
	$text=addslashes($_POST['text']);
	$name =$_POST['title'];
	$sql="UPDATE $conf[DB_PREFIX]randtext SET `CONTENT`=\"$text\", `NAME`=\"$name\" where ID=$_GET[id]";
	mysql_query($sql, $conf[DB]);
	header ("Location: properties.php?a=edit&id=$id&module=randomtext");
	break;
	break;
case "edit":
	$sql="SELECT * FROM `$conf[DB_PREFIX]randtext` WHERE ID=$id";
	$result=mysql_query($sql, $conf[DB]);
	$row=mysql_fetch_assoc($result);
	mysql_free_result ($result);
	?>
<html>
<head>
<title></title>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel='stylesheet' type='text/css' href='normal.css'>
<link href="css.css" rel="stylesheet" type="text/css">
</head>
<body scroll="auto">
<center>
<form name="form" method="post"
	action="properties.php?a=update&id=<?echo $id?>&module=randomtext"><input
	type="text" value="<?=$row[NAME]?>" name="title" size="45"><br>
<table width="100%" border="1">
	<tr>
		<td width="100%"><?
		include("fckeditor.php");
		$sBasePath ="./";
		$oFCKeditor = new FCKeditor('text') ;
		$oFCKeditor->BasePath = $sBasePath ;
		$oFCKeditor->Value    = stripslashes($row[CONTENT]);
		$oFCKeditor->Create();
		?></td>
	</tr>
</table>
<hr>
<center><input name="submit" type="submit" class="mainoption"
	value="Сохранить изменения"></center>
</form>
<hr>
<br>

<form name="form" method="post"	action="properties.php?a=delete&id=<?echo $id?>&module=randomtext">
	<center><input name="submit" type="submit" class="mainoption"
	value="Удалить"></center>

</body>
</html>
		<?
		break;
}//CASE
}
function add(){
	$conf = $this->conf;
	$SQL = "INSERT INTO $conf[DB_PREFIX]randtextblock values()";
	mysql_query($SQL, $conf[DB]);
	return mysql_insert_id($conf[DB]);
}
function del($id){
	$conf = $this->conf;
	return 1;
}
function renderEx($id, &$template){
}
function render($regionID, $id, &$template){
	$conf = $this->conf;

	$_SQL = "SELECT * FROM $conf[DB_PREFIX]randtext ORDER BY RAND() LIMIT 1";
	$_res = mysql_query($_SQL, $conf[DB]);
	$_text= mysql_fetch_assoc ($_res);
	return stripslashes($_text[CONTENT]);
}
function edit(){
	$conf = $this->conf;
	switch ($_GET[a]){
		case "update":
			$sql="UPDATE $conf[DB_PREFIX]randtextblock SET `TEXTID`=$_GET[add] where ID=$_GET[id]";
			mysql_query($sql, $conf[DB]);
			header ("Location: edit.php?id=$_GET[id]&module=randtext");
			break;
		case "":
			echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">';

			$sql = "SELECT * FROM $conf[DB_PREFIX]randtextblock WHERE ID=$_GET[id]";
			$result=mysql_query($sql, $conf[DB]);
			$block  =mysql_fetch_assoc($result);

			$sql = "SELECT * FROM $conf[DB_PREFIX]randtext";
			$result=mysql_query($sql, $conf[DB]);

			while ($row=mysql_fetch_assoc($result))
			{
				$a = $row[NAME];
				if ($block[TEXTID]==$row[ID])
				$a = "<B>$a</B>";
				echo "<a href=\"edit.php?module=randomtext&a=update&id=$_GET[id]&add=$row[ID]\">$a</a><hr>";
			};
			break;
	};
}
function printheader(){
	$templ = $_GET[template];
	echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"><body>'
	.'<center><a href="properties.php?a=addtext&module=randomtext" >Добавить Текст</a></center>'
	;
}
};

$info = array(
  'plugin'      => "randomtext",
  'cplugin'     => "eeRandomText",
  'pluginName'    => "Случайный текст",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>