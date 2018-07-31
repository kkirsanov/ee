<? class eeMap{
	var $conf;
	function eeMap($conf) {
		$this->conf = $conf;
	}
	function install() {
		$conf = $this->conf;
		$SQL = "CREATE TABLE `$conf[DB_PREFIX]map_active` ("
		."`REGION` int(11) NOT NULL default '0'"
		.") ENGINE=MyISAM CHARACTER SET utf8;;"
		;
		mysql_query($SQL, $conf[DB]);
		return 1;
	}

	function domap($id)
	{
		$conf = $this->conf;
		$sql="SELECT * FROM $conf[DB_PREFIX]regions INNER JOIN $conf[DB_PREFIX]map_active ON ($conf[DB_PREFIX]regions.ID = $conf[DB_PREFIX]map_active.REGION) WHERE `PARENT`=$id";
		$result=mysql_query($sql, $conf[DB]);
		$_TEMP="";
		while ($region = mysql_fetch_assoc($result))
		{
			$_TEMP2 = str_replace("%text%", $region[TITLE], $this->ELEMENT);
			$_TEMP2 = str_replace("%link%",  getFullPath($region[ID]), $_TEMP2);

			$_TEMP .= str_replace("%main%", $_TEMP2, "%main%");//$this->OUTER_SUB
			$_TEMP .= str_replace("%main%", $this->domap($region[ID]), $this->OUTER_SUB);
		}
		return $_TEMP;
	}
	function render($regionID = 0, $id, &$template) {

		$this->OUTER_MAIN =& $template->Get("map.main");
		$this->OUTER_SUB  =& $template->Get("map.sub");
		$this->ELEMENT    =& $template->Get("map.element");

		$a = str_replace("%main%", $this->domap(0), $this->OUTER_MAIN);
		return $a;
	}

	function add() {
		return 0;
	}
	function del($id){
	}
	function printRegionsList($i){
		$conf = $this->conf;

		static $level;
		$level++;
		$sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$i ORDER BY `ORDER` ASC";

		$result=mysql_query($sql, $conf[DB]);
		echo "<table><tr><td>";

		while ($row=mysql_fetch_assoc($result))
		{
			$tmpID = $row['ID'];
			$tmp=0;

			for ($tmp=0; $tmp <= $level; $tmp++)
			echo "&nbsp;&nbsp;";

			$checked= "checked";

			$sql = "SELECT COUNT(REGION) as `count` FROM $conf[DB_PREFIX]map_active WHERE REGION=$row[ID]";
			$result2=mysql_query($sql, $conf[DB]);
			$che=mysql_fetch_assoc($result2);

			if ($che[count]==0)
			$checked= "";

			echo "<INPUT type=\"checkbox\" name=\"region$row[ID]\" $checked>" . $row['TITLE'], "<br>";
			$this->printRegionsList($tmpID);
			echo "</tr></td></table>";
		}
		mysql_free_result($result);
		$level--;
	}
	function printheader()
	{
		$templ = $_GET[template];
		
		echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"><body>'
		
		;
		//."<center><a href=\"?a=editactive&module=map&template=$templ\">Активные Разделы</a>&nbsp<a href=\"?a=editouter&module=map&template=$templ\">Обромление</a>";
	}
	function properties(){

		if ($_GET[a]=="")
		$_GET[a]="editactive";

		$conf = $this->conf;
		switch ($_GET[a])
		{
			case "editactive":
				$this->printheader();
				echo '<form method="POST" action="?a=saveactive&module=map">';

				$this->printRegionsList(0);
				?>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
				<?
				echo '</form>';
				break;
case "saveactive":
	$sql="SELECT * FROM $conf[DB_PREFIX]regions";
	$result=mysql_query($sql, $conf[DB]);

	while ($region=mysql_fetch_assoc($result))
	{
		if ($_POST["region$region[ID]"]!="on")
		{
			$SQL = "DELETE FROM $conf[DB_PREFIX]map_active WHERE REGION=$region[ID]";
			mysql_query($SQL, $conf[DB]);
		}else{

			$SQL = "DELETE FROM $conf[DB_PREFIX]map_active WHERE REGION=$region[ID]";
			mysql_query($SQL, $conf[DB]);
			$SQL = "insert INTO $conf[DB_PREFIX]map_active VALUES($region[ID])";
			mysql_query($SQL, $conf[DB]);
		};
	}
	header("Location: ?a=editactive&module=map&template=$_GET[template]");
	break;

case "editouter":
	$this->printheader();

	$tID = $_GET[template];
	if (!$tID)
	die("template error");

	include_once ("core_template.php");
	$template = new Template($_GET[template]);

	//global $OUTER_MAIN;
	//global $OUTER_SUB;
	$OUTER_MAIN   =& $template->Get("map.main");
	$OUTER_SUB    =& $template->Get("map.sub");
	$ELEMENT      =& $template->Get("map.element");

	?>
<form method="POST"
	action="?a=saveouter&module=map&template=<?echo $_GET[template]; ?>">
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
	<tr>
		<td colspan="2" align="center">
		<h2>Общее Окружение</h>
		
		</td>
	</tr>
	<tr>
		<td>%main% - содержание</td>
		<td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"
			name="OUTER_MAIN"><?=$OUTER_MAIN;?></TEXTAREA></td>
	</tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
	<tr>
		<td colspan="2" align="center">
		<h2>Окружение Подуровня</h>
		
		</td>
	</tr>
	<tr>
		<td>%main% - содержание</td>
		<td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%"
			name="OUTER_SUB"><?=$OUTER_SUB;?></TEXTAREA></td>
	</tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
	<tr>
		<td colspan="2" align="center">
		<h2>Элемент</h>
		
		</td>
	</tr>
	<tr>
		<td>%link%</td>
		<td width="100%"><TEXTAREA rows="6" style="WIDTH: 100%" name="ELEMENT"><?=$ELEMENT;?></TEXTAREA></td>
	</tr>
</TABLE>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
<FORM><?
break;
case "saveouter":

	$tID = $_GET[template];
	if (!$tID)
	die("template error");
	include_once ("core_template.php");
	$template = new Template($_GET[template]);
	$template->Set("map.main", $_POST[OUTER_MAIN]);
	$template->Set("map.sub", $_POST[OUTER_SUB]);
	$template->Set("map.element", $_POST[ELEMENT]);

	$template->save();
	header("Location: ?a=editouter&module=map&template=$_GET[template]");
	die();
	break;
		}
	}
};

$info = array(
  'plugin'      => "map",
  'cplugin'     => "eeMap",
  'pluginName'    => "Карта",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>