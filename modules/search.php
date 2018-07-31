<?
class eeSearch{
	var $conf;
	function eeSearch($conf)
	{
		$this->conf = $conf;
	}
	function printheader(){
		echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"><body>'
		;
	}
	function install(){
		$conf = $this->conf;
		$SQL = "ALTER TABLE `$conf[DB_PREFIX]texts` DROP PRIMARY KEY, CHANGE `ID` `ID` int(11) NOT NULL AUTO_INCREMENT FIRST,"
		."CHANGE `CONTENT` `CONTENT` mediumtext NOT NULL AFTER `ID`,"
		."ADD FULLTEXT `text`(`CONTENT`), ADD PRIMARY KEY (`ID`), ENGINE=MyISAM PACK_KEYS=DEFAULT ROW_FORMAT=DEFAULT"
		;
		mysql_query($SQL, $conf[DB]);
		return 1;
	}
	function add()
	{
		return 0;
	}
	function del()
	{
		return 1;
	}
	function render($rID, $fID, &$template){
		$conf = $this->conf;
		return "<form method=post action=\"./search/\"><input type=text name=term><input type=submit></form>";
	}
	function renderEx($id, &$template){

		$this->MAIN		=&$template->Get("search.main");
		$this->ELEMENT	&$template->Get("search.element");
		if ($_POST[term]=="")
		return;
		$conf = $this->conf;

		//		$SQL ="select * from $conf[DB_PREFIX]regions join $conf[DB_PREFIX]blocks (blocks.PARENTREGION=regions.ID) join $conf[DB_PREFIX]texts on (blocks.FID=texts.ID) where texts.CONTENT like '%$_POST[term]%'";
		//		$res = mysql_query($SQL, $conf[DB]) or die (mysql_error());
		//echo $SQL = "SELECT ID FROM `$conf[DB_PREFIX]texts` WHERE MATCH (`CONTENT`) AGAINST ('$_POST[term]')";

		$SQL = "SELECT ID FROM `$conf[DB_PREFIX]texts` WHERE `CONTENT` like '%$_POST[term]%'";

		while ($text=mysql_fetch_assoc($res)){
			echo  "$text[ID] ";
			$SQL = "SELECT * FROM `$conf[DB_PREFIX]blocks` WHERE FID=$text[ID] and `TYPE`='text'";
			$res2 = mysql_query($SQL, $conf[DB]);
			$i=0;
			while($block=mysql_fetch_assoc($res2)){
				$tmp["$block[PARENTREGION]"] = $block[PARENTREGION];
			};
		};
		$ret ="<OL>";
		$i=0;



		if ($tmp){
			foreach($tmp as $reg){
				$i++;
				$SQL = "SELECT * FROM `$conf[DB_PREFIX]regions` WHERE ID=$reg";
				$res = mysql_query($SQL, $conf[DB]);
				$region = mysql_fetch_assoc($res);
				$link = getFullPath($region[ID]);
				$title = $region[TITLE];

				$TEMP = str_replace("%link%", $link, $this->ELEMENT);
				$TEMP = str_replace("%title%", $title, $TEMP);
				$ret.=$TEMP;
			};
		};
		$ret .="</OL>";

		return str_replace("%main%", $ret, $ret);;
	}

	function properties(){
		switch ($_GET[a]){
			case "":
				$tID = $_GET[template];
				if (!$tID)
				die("template error");

				include_once ("core_template.php");
				$template = new Template($_GET[template]);


				$this->printheader();
				$MAIN		=&$template->Get("search.main");
				$ELEMENT	&$template->Get("search.element");
				?>
<form method="POST" action="?a=save&module=search&template=<?echo $_GET[template];?>">
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
	<tr>
		<td>Обрамление Блока<br>
		<b>%main%</b> - Содержание</td>
		<td width="100%"><TEXTAREA rows="3" style="WIDTH: 100%" name="MAIN"><?=$MAIN;?></TEXTAREA></td>
	</tr>
	<tr>
		<td>Обрамление Элемента <br>
		<b>%link%</b> - ссылка на раздел <br>
		<b>%title%</b> - загловок раздела</td>
		<td width="100%"><TEXTAREA rows="8" style="WIDTH: 100%" name="ELEMENT"><?=$ELEMENT;?></TEXTAREA></td>
	</tr>
</TABLE>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
<FORM><?
break;
case "save":
	
	$tID = $_GET[template];
	if (!$tID)
	die("template error");

	include_once ("core_template.php");
	$template = new Template($_GET[template]);
	$template->Set("search.main", $_POST[MAIN]);
	$template->Set("search.element", $_POST[ELEMENT]);

	header("Location: ?module=search&template=$_GET[template]");
	break;
};
}
};
$info = array(
	'plugin'			=> "search",
	'cplugin'			=> "eeSearch",
	'pluginName'		=> "Поиск",
	'ISMENU'			=>0,
	'ISENGINEMENU'		=>0,
	'ISBLOCK'			=>1,
	'ISEXTRABLOCK'		=>1,
	'ISSPECIAL'			=>1,
	'ISLINKABLE'		=>0,
	'ISINTERFACE'		=>0,
);
?>