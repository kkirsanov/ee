<?
    $_tmp = $_SESSION[register][id];
    function RuEncode ($ruString) {
		$re_ar= array(" "=>"_", "à"=>"a", "À"=>"A", "á"=>"b", "Á"=>"B", "â"=>"v", "Â"=>"V", "ã"=>"g", "Ã"=>"G", "ä"=>"d", "Ä"=>"D", "å"=>"e", "Å"=>"E", "¸"=>"e", "¨"=>"E", "æ"=>"j", "Æ"=>"J", "ç"=>"z", "Ç"=>"Z", "è"=>"i", "È"=>"I", "é"=>"i", "É"=>"I", "ê"=>"k", "Ê"=>"K", "ë"=>"l", "Ë"=>"L", "ì"=>"m", "Ì"=>"M", "í"=>"n", "Í"=>"N", "î"=>"o", "Î"=>"O", "ï"=>"p", "Ï"=>"P", "ð"=>"r", "Ð"=>"R", "ñ"=>"s", "Ñ"=>"S", "ò"=>"t", "Ò"=>"T", "ó"=>"y", "Ó"=>"Y", "ô"=>"f", "Ô"=>"F", "õ"=>"h", "Õ"=>"H", "ö"=>"c", "Ö"=>"C", "÷"=>"ch", "×"=>"CH", "ø"=>"sh", "Ø"=>"SH", "ù"=>"sh", "Ù"=>"SH", "ú"=>"'", "Ú"=>"'", "û"=>"y", "Û"=>"Y", "ü"=>"'", "Ü"=>"'", "ý"=>"e", "Ý"=>"E", "þ"=>"u", "Þ"=>"U", "ÿ"=>"ia", "ß"=>"IA");
		foreach ($re_ar as $key=>$val) $ruString = preg_replace ("/{$key}/", "{$val}", $ruString);
		return $ruString;
   };

//	if (!$_tmp)	die();
    if ($_GET[a] =="stat")
    {
    	include_once("./config.php");    	
    	$query="SELECT * FROM `$conf[DB_PERFIX]files` WHERE ID=$id";
	    $res=mysql_query($query, $conf[DB]);
    	$file=mysql_fetch_assoc($res);

    	echo "NAME: <b>$file[NAME]</b><br>";
    	echo "SIZE: <b>$file[SIZE]</b><br>";
    	echo "MIME: <b>$file[MIME]</b><br>";
    	echo "Download Count: <b>$file[FILE]</b><br>";
    	echo "OWNER: <b>$file[TYPE]</b><br>";

    }else{
	    $id=(int)$HTTP_GET_VARS[id];
    	if ($id==0) die();

	    include_once("./config.php");   

    	$query="SELECT * FROM `$conf[DB_PERFIX]files` WHERE ID=$id";
	    $res=mysql_query($query, $conf[DB]);
    	$file=mysql_fetch_assoc($res);
    
	    $fname = "./files/$file[ID].dat";

	    $count = (int)$file[FILE];
    	$count++;

    	
	    $SQL = "UPDATE $conf[DB_PERFIX]files SET `FILE`='$count' WHERE ID = $file[ID]";
		mysql_query($SQL, $conf[DB]);		

		$file_exists = file_exists($fname);
	
	   	$handle = fopen($fname, 'r');
	   	$file[FILE] = fread ($handle, filesize ($fname )); 
		fclose ($handle);
    
	    header("Content-type: $file[MIME]");
		header("Content-Length: $file[SIZE]");
		$name = RuEncode($file[NAME]);
		header("filename=$name");
	    echo $file[FILE];
	};
?>