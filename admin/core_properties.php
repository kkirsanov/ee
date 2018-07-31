<?php
class properties_Editor
{
	var $_modulename;
	var $_DATA;

	function properties_Editor($modulename)
	{
		$this->_modulename = $modulename;
		$this->_DATA = "<form method=\"post\" action=\"?a=modules&s=editproperties&m=" . $this->_modulename ."&action=save\">";
		$this->_DATA .= '<TABLE border="1" width="90%" bgcolor="#CCCCCC">';
	}
	function adddatafield($name, $content, $description, $help, $rows=6,$type=0)
	{
		switch ($type)
		{
			case 0:
				$this->_DATA .=	'<tr><td colspan="2" align="center"><h2>' . $description . '</h></td></tr>'
				."<td>$help</td>"
				.'<td width="100%"><TEXTAREA rows="'.$rows.'" style="WIDTH: 100%" name="'.$name.'" ID="'.$name.'">' . $content .'</TEXTAREA></td></tr>'
				;
				break;
			case 1:
				break;
		};
	}


	function savedata($dname, $fname)
	{
		global $ADMINPATH;
		$fname= $ADMINPATH ."/data/" . $this->_modulename ."$fname.dat";
		if (isset($_POST["$dname"]))
		if ($h = fopen($fname, 'w'))
		{
			fwrite($h, $_POST["$dname"]);
			fclose ($h);
		};
	}

	function loaddata($name)
	{
		global $ADMINPATH;
		$fname= $ADMINPATH ."/data/" . $this->_modulename ."$name.dat";
		return stripslashes(file_get_contents($fname));
	}

	function finish()
	{
		$this->_DATA .= '</table><center><INPUT type="submit" value="Принять" class="mainoption"></center><form>';
	}
	function get()
	{
		return $this->_DATA;
	}

}
?>