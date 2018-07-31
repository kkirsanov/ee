<?

class Module
{
	var $module;
	var $info;
	function Module($info, $module, $check=1){
		global $conf;
		$this->module = $module;
		$this->info = $info;
		if ($check){
			$name = $this->info[plugin];
			$SQL = "select count(ID) as reg from $conf[DB_PREFIX]modules where PATH='$name'";
			@$result = mysql_query($SQL, $conf[DB]);
			@$isreg = mysql_fetch_assoc($result);
			@$this->info[registrated] =  $isreg[reg];
			@$this->registrated =  $isreg[reg];
		};
	}
	function install()
	{	
		global $conf;
		$info = $this->info;
		if($this->module->install())
		{
			$SQL = "INSERT INTO $conf[DB_PREFIX]modules (`ISLINKABLE`, `ISMENU`, `ISEXTRABLOCK`, `ISBLOCK`, `NAME`, `PATH`, `ISSPECIAL`, `ISENGINEMENU`) values "
			."($info[ISLINKABLE], $info[ISMENU], $info[ISEXTRABLOCK], $info[ISBLOCK], '$info[pluginName]', '$info[plugin]', $info[ISSPECIAL], $info[ISENGINEMENU])"
			;
			mysql_query($SQL, $conf[DB]);

			if ($info[ISEXTRABLOCK] == 1){
				$sql="SELECT * FROM $conf[DB_PREFIX]templates";
				if ($result=mysql_query($sql, $conf[DB])){
					$template=mysql_fetch_assoc($result);
					$id= 0;
					$SQL="SELECT max(`ORDER`) as `maximum` FROM `$conf[DB_PREFIX]regions` WHERE `PARENT`=$id";
					$res=mysql_query($SQL, $conf[DB]);
					$ord=mysql_fetch_assoc($res);
					$order=0 + (int)$ord[maximum] + 1;
					$TITLE = "_" . $this->name;
					$tmp = $this->_fname;
					$sql="INSERT INTO $conf[DB_PREFIX]regions (TITLE, PARENT, TEMPLATE, `ORDER`, `KW`, `DESC`, `WEBTITLE`, `SPECIAL`) VALUES ('$info[pluginName]', $id, $template[ID], $order, '', '', '$info[pluginName]', '$info[plugin]');";
					mysql_query($sql, $conf[DB]);
					$idreg=mysql_insert_id($conf[DB]);
					$sql="INSERT INTO $conf[DB_PREFIX]blocks (`ACTIVE`, `EXTRABLOCK`, `ORDER`, `TYPE`, `PARENTREGION`, `FID`, `LOCATION` ) VALUES"
					."(1, 1, 1, '$info[plugin]', $idreg, 0, 1);";
					mysql_query($sql, $conf[DB]);
				};

			};
			return 1;
		};
		return 0;
	}
	function addnew()
	{
		return $this->module->add();
	}

	function del($id)
	{
		return $this->module->del($id);
	}

	function uninstall()
	{
		return $this->module->uninstall();
	}
	function render($rID, $FID, $template)
	{
		return $this->module->render($rID, $FID, $template);
	}
	function  renderEx($id, $template)
	{
		return $this->module->renderEx($id, $template);
	}

	function  showlist()
	{
		global $conf;
		include ("path.php");

		global $_ID;
		$_ID=$id;
		$modulepath = "$conf[module_path]/$this->_fname";
		include ("$modulepath/showlist.php");
		return $RETURN;
	}
	function  upwrite($text, $id)
	{
		global $conf;
		include ("path.php");

		global $_ID;
		$_ID=$id;
		global $_TEXT;
		$_TEXT=$text;

		$modulepath = "$conf[module_path]/$this->_fname";
		include ("$modulepath/upwrite.php");
		return $RETURN;
	}
};
class Modules
{
	var $conf;
	var $modules;
	function Modules($conf, $check=1)
	{
		$this->conf = $conf;
		$i =0;
		$d = dir($this->conf[module_path]);
		while (false !== ($entry = $d->read()))
		if ($entry != "." && $entry != "..")
		{
			include_once ($this->conf[module_path].$entry);
			$name = $info[plugin];
			eval("\$tmp = new $info[cplugin](\$this->conf);");
			$this->modules["$name"] = new Module($info, $tmp, $check);
			if ($this->modules["$name"]->registrated != 1)
			$this->modules["$name"]->install();
		};
		$d->close();
	}
	function expand()
	{
		global $conf;
		$SQL = "SELECT * FROM $conf[DB_PREFIX]modules ORDER BY `NAME`";
		$result = mysql_query($SQL, $conf[DB]);
		$tmp2 = "";
		$ok_m = array('hot_shop', 'calendar', 'path', 'cart', 'path_shop', 'NavigateShop', 'news', 'text', 'mail', 'shop');		
		while ($module= mysql_fetch_assoc($result))
		{
			if (!in_array($module[PATH], $ok_m))
				$tmp2 .= "<li id=\"modules_$module[ID]\"><a href=\"properties.php?module=$module[PATH]\" target=\"contentFrame\">$module[NAME]</a>";
		};
		$tmp .= "var temp = xget('modules');";
		return "$tmp;temp.innerHTML = '".doNoLi("modules", "МОДУЛИ", 1, "?a=modules")
		."<ul>".$tmp2."</ul>';";
	}
};
?>
