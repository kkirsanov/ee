<?
    $_tmp = $_SESSION[register][id];
    function RuEncode ($ruString) {
		$re_ar= array(" "=>"_", "�"=>"a", "�"=>"A", "�"=>"b", "�"=>"B", "�"=>"v", "�"=>"V", "�"=>"g", "�"=>"G", "�"=>"d", "�"=>"D", "�"=>"e", "�"=>"E", "�"=>"e", "�"=>"E", "�"=>"j", "�"=>"J", "�"=>"z", "�"=>"Z", "�"=>"i", "�"=>"I", "�"=>"i", "�"=>"I", "�"=>"k", "�"=>"K", "�"=>"l", "�"=>"L", "�"=>"m", "�"=>"M", "�"=>"n", "�"=>"N", "�"=>"o", "�"=>"O", "�"=>"p", "�"=>"P", "�"=>"r", "�"=>"R", "�"=>"s", "�"=>"S", "�"=>"t", "�"=>"T", "�"=>"y", "�"=>"Y", "�"=>"f", "�"=>"F", "�"=>"h", "�"=>"H", "�"=>"c", "�"=>"C", "�"=>"ch", "�"=>"CH", "�"=>"sh", "�"=>"SH", "�"=>"sh", "�"=>"SH", "�"=>"'", "�"=>"'", "�"=>"y", "�"=>"Y", "�"=>"'", "�"=>"'", "�"=>"e", "�"=>"E", "�"=>"u", "�"=>"U", "�"=>"ia", "�"=>"IA");
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