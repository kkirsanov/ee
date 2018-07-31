<?php

class eeStatictext{
	var $conf;
	function eeStatictext($conf)
	{
		$this->conf = $conf;
	}
	function install() {
		$conf = $this->conf;

		$SQL = "CREATE TABLE `$conf[DB_PREFIX]statictext` ("
		."`ID` int(11) NOT NULL auto_increment,"
		."`NAME` VARCHAR(100) NOT NULL,"
		."`CONTENT` mediumtext NOT NULL,"
		."PRIMARY KEY  (`ID`)"
		.") ENGINE=MyISAM CHARACTER SET utf8";

		mysql_query($SQL, $conf[DB]);

		$SQL = "CREATE TABLE `$conf[DB_PREFIX]statictextblock` ("
		."`ID` int(11) NOT NULL auto_increment,"
		."`TEXTID` int(11) NOT NULL default '1',"
		."PRIMARY KEY  (`ID`)"
		.") ENGINE=MyISAM CHARACTER SET utf8;";

		mysql_query($SQL, $conf[DB]);

		//@registerAccess("module_statictext_design", "Статический текст - <b>Дизайн</b>");
		//@registerAccess("module_statictext_edit", "Статический текст - <b>управление</b>");
		return 1;
	}
	function properties(){
		$action = $_GET[a];
		$conf = $this->conf;
		$id = $_GET[id];
		include_once ("core_file_works.php");
		$filew = new Fileworks($_GET[id], 'statictext');

		if ($action == "")
		$action="view";

		switch ($action){
			case "view":
				$conf = $this->conf;
				$this->printheader();
				$sql = "SELECT * FROM $conf[DB_PREFIX]statictext";
				@$result=mysql_query($sql, $conf[DB]);
				while (@$row=mysql_fetch_assoc($result)){
					echo stripslashes($row['NAME']),
        "<form action = \"properties.php?a=editframe&id=$row[ID]&module=statictext\" method=\"post\"><input name=\"submit\" type=\"submit\" class=\"mainoption\" value=\"Изменить($row[ID])\"></form>",
        "<hr>";
				}
				break;
			case "addtext":
				$this->printheader();
				echo "<br>";
				?>
<form name="forma"
	action="properties.php?a=addnewcommit&module=statictext" method="post">
<input type="text" value="Название Нового Текста" name="title" size="45"><br>
<input type="submit" value="Принять" class="mainoption"></form>
				<?
				break;
case "addnewcommit":
	$sql="INSERT INTO `$conf[DB_PREFIX]statictext` (`NAME`) VALUES ('$_POST[title]')";
	$res = mysql_query($sql, $conf[DB]) or die (mysql_error());
	$id=mysql_insert_id($conf[DB]);
	mysql_close($conf[DB]);
	header("Location: properties.php?a=editframe&id=$id&module=statictext");
	break;
case "update":
	$text=addslashes($_POST['text']);
	$name =$_POST['title'];
	$sql="UPDATE $conf[DB_PREFIX]statictext SET `CONTENT`=\"$text\", `NAME`=\"$name\" where ID=$_GET[id]";
	mysql_query($sql, $conf[DB]);
	header ("Location: properties.php?a=edit&id=$id&module=statictext");
	break;
case "editframe":
	?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=геа-8">
</head>
<frameset rows="*">
	<frameset cols="100%,170">
		<frame name="editframe"
			src="properties.php?a=edit&id=<?echo $_GET[id]?>&module=statictext">
		<frame name="editfilesframe"
			src="properties.php?a=editfiles&id=<?echo $_GET[id]?>&module=statictext">
	</frameset>

</html>
	<?
	break;
case "edit":
	$sql="SELECT * FROM `$conf[DB_PREFIX]statictext` WHERE ID=$id";
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
	action="properties.php?a=update&id=<?echo $id?>&module=statictext"><input
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
	value="С охранить изменения"></center>
</form>

</body>
</html>
		<?
		break;
case "uploadfiles":
	$filew->upload_file();
	header ("Location: properties.php?a=editfiles&id=$id&module=statictext");

	break;
case "clearfiles":
	$filew->clear_file();
	header ("Location: properties.php?a=editfiles&id=$id&module=statictext");
	break;
case "editfiles":
	?>
<html>
<head>
<title></title>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<center>
<div CONTENTEDITABLE><?$filew->show_file();?></div>
</td>
<form method="post"
	action="properties.php?a=uploadfiles&id=<?echo $id?>&module=statictext"
	enctype="multipart/form-data"><input type="file" name="file" size="5">
<br>
<input type="submit" value="Загрузить" class="mainoption"></form>
<form method="post"
	action="properties.php?a=clearfiles&id=<?echo $id?>&module=statictext">
<input type="submit" value="ОЧИCТИТЬ" class="deloption"></form>
	<?
	break;
}//CASE
}
function add(){
	$conf = $this->conf;
	$SQL = "INSERT INTO $conf[DB_PREFIX]statictextblock values()";
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
	$_SQL = "SELECT * FROM $conf[DB_PREFIX]statictextblock WHERE ID=$id";
	$_res = mysql_query($_SQL, $conf[DB]);
	$_text= mysql_fetch_assoc ($_res);

	$_SQL = "SELECT * FROM $conf[DB_PREFIX]statictext WHERE ID=$_text[TEXTID]";
	$_res = mysql_query($_SQL, $conf[DB]);
	$_text= mysql_fetch_assoc ($_res);
	return stripslashes($_text[CONTENT]);
}
function edit(){
	$conf = $this->conf;
	switch ($_GET[a]){
		case "update":
			$sql="UPDATE $conf[DB_PREFIX]statictextblock SET `TEXTID`=$_GET[add] where ID=$_GET[id]";
			mysql_query($sql, $conf[DB]);
			header ("Location: edit.php?id=$_GET[id]&module=statictext");
			break;
		case "":
			echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">';

			$sql = "SELECT * FROM $conf[DB_PREFIX]statictextblock WHERE ID=$_GET[id]";
			$result=mysql_query($sql, $conf[DB]);
			$block  =mysql_fetch_assoc($result);

			$sql = "SELECT * FROM $conf[DB_PREFIX]statictext";
			$result=mysql_query($sql, $conf[DB]);

			while ($row=mysql_fetch_assoc($result))
			{
				$a = $row[NAME];
				if ($block[TEXTID]==$row[ID])
				$a = "<B>$a</B>";
				echo "<a href=\"edit.php?module=statictext&a=update&id=$_GET[id]&add=$row[ID]\">$a</a><hr>";
			};
			break;
	};
}
function printheader(){
	$templ = $_GET[template];
	echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"><body>'
	.'<center><a href="properties.php?a=addtext&module=statictext" >Добавить Текст</a></center>'
	;
}
};

$info = array(
  'plugin'      => "statictext",
  'cplugin'     => "eeStatictext",
  'pluginName'    => "Статический текст",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>