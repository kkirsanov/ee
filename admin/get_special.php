<?php
	global $conf;
	
	if ($conf[DB])
	{

	$sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE SPECIAL=1";
	
	@$result=mysql_query($sql, $conf[DB]);
	if	($result)
	if ($sp=mysql_fetch_assoc($result))
		{
		mysql_free_result ($result);
		$conf[startpage]=$sp[ID];
	}
}	
?>