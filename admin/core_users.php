<?
class Users
{
	var $data;
	function doItem($rusName, $addres){
		return "<li><a href=\"$addres\" target=\"contentFrame\">$rusName</a></li>";
	}
	function _printaccessRegions($id){
		global $conf;
		$this->data .= "<ul>";


		$sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$id ORDER BY `ORDER` ASC";
		$result=mysql_query($sql, $conf[DB]);
		while ($row=mysql_fetch_assoc($result))
		{
			$cheked = "";
			$sql="SELECT count(*) as `c` FROM $conf[DB_PREFIX]accessregions WHERE region_id=$row[ID] and group_id=$_GET[id]";
			$count=mysql_fetch_assoc(mysql_query($sql, $conf[DB]));
			$count = $count[c];
			if($count>=1)
			$cheked = "checked";
			$this->data .="<li><input name=regionaccess$row[ID] type=checkbox $cheked>$row[TITLE]</li>";
			$this->_printaccessRegions($row[ID]);
		}
		$this->data .= "</ul>";
	}
	function printAccesRegions(){
		$this->data = "";
		$this->_printaccessRegions(0);
		return $this->data;
	}

	function expand($command)
	{
		global $conf;

		switch ($command)
		{
			case "":
				break;

		}
		;
		$SQL = "SELECT * FROM `$conf[DB_PREFIX]accessgroups`";
		@$result =  mysql_query($SQL, $conf[DB]);

		$tmp2 = "Группы пользователей:<br>";
		while ($usergroup= mysql_fetch_assoc($result))
		{
			$tmp2 .= "<li id=\"usersgroup_$usergroup[ID]\"><a href=\"?a=users&s=edit&id=$usergroup[ID]\" target=\"contentFrame\">$usergroup[NAME]</a>";
		};
		$tmp .= "var temp = xget('users');";

		return "$tmp;temp.innerHTML = '".doNoLi("users", "Пользователи", 1, "?a=users")
		."<ul>".$tmp2."<hr>". $this->doItem("Добавить группу", "?a=users&s=addgroup")."</ul>';";

	}
};
?>
