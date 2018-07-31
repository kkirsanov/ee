<?php
class Fileworks
{
	var $_parent;
	var $_type;
	function Fileworks($parent=0, $type=0)
	{
		$this->_parent = $parent;
		$this->_type        = $type;
	}
	function upload_file()
	{
		global $conf;
		if (is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$filehandle=fopen($_FILES['file']['tmp_name'], "rb");
			$filedata=fread($filehandle, filesize($_FILES['file']['tmp_name']));
			$name=$_FILES['file']['name'];
			$mime=$_FILES['file']['type'];
			$desc=$_POST['desc'];

			$orig_im = imagecreatefromstring($filedata);
			$sizeX = imagesx($orig_im);
			$sizeY = imagesy($orig_im);
			$maxSizeTh=125;
			$maxSize=400;
			if (max($sizeX, $sizeY)==$sizeX){
				//size to X
				$propTh = (float)$maxSizeTh/$sizeX;
				$prop = (float)$maxSize / $sizeX;

			}else{
				//size to Y
				$propTh = (float)$maxSizeTh / $sizeY;
				$prop = (float)$maxSize /$sizeY;
			};

			$query="INSERT INTO `$conf[DB_PREFIX]files` (`NAME`,`MIME`,`SIZE`,`DESC`,`COUNT`, `PARENT`, `TYPE`)" . "values ('$name','$mime','$size','$desc', 1,  $this->_parent, '$this->_type');";
				
			mysql_query($query, $conf[DB]);
			$id=mysql_insert_id($conf[DB]);

			$im_prw =imagecreatetruecolor($sizeX*$propTh,$sizeY*$propTh);

			imagecopyresampled($im_prw, $orig_im, 0,0,0,0, $sizeX*$propTh,$sizeY*$propTh,$sizeX,$sizeY);
			$im = imagejpeg($im_prw,"../files/$id.dat");
			if ($im)
			{ $size= filesize("../files/$id.dat");
			$query="UPDATE `$conf[DB_PREFIX]files` SET `SIZE`='".$size."', `MIME`= 'image/jpeg' WHERE ID=$id";
			mysql_query($query, $conf[DB]);
			}else{
				$query="DELETE FROM `$conf[DB_PREFIX]files` WHERE ID=$id";
				mysql_query($query, $conf[DB]);
			};

			$query="INSERT INTO `$conf[DB_PREFIX]files` (`NAME`,`MIME`,`SIZE`,`DESC`,`COUNT`, `PARENT`, `TYPE`)" . "values ('$name','$mime','$size','$desc', 1,  $this->_parent, '$this->_type');";
			mysql_query($query, $conf[DB]);
			$id=mysql_insert_id($conf[DB]);

			$im_norm =imagecreatetruecolor($sizeX*$prop,$sizeY*$prop);
			imagecopyresampled($im_norm, $orig_im, 0,0,0,0, $sizeX*$prop,$sizeY*$prop,$sizeX,$sizeY);
			$im = imagejpeg($im_norm,"../files/$id.dat");

			if ($im)
			{ clearstatcache();
			$size= filesize("../files/$id.dat");
			$query="UPDATE `$conf[DB_PREFIX]files` SET `SIZE`='".$size."', `MIME`= 'image/jpeg' WHERE ID=$id";
			mysql_query($query, $conf[DB]);
			}else{
				$query="DELETE FROM `$conf[DB_PREFIX]files` WHERE ID=$id";
				mysql_query($query, $conf[DB]);
			};
		}
	}
	function clear_file()
	{
		global $conf;
		$query="DELETE FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=". $this->_parent." AND `TYPE` = '". $this->_type."'";
		mysql_query($query, $conf[DB]);
	}

	function show_file()
	{
		global $conf;
		$sql="SELECT * FROM `$conf[DB_PREFIX]files` WHERE `PARENT`=$this->_parent and `TYPE` ='". $this->_type."' ORDER by ID";
		if (@$res=mysql_query($sql, $conf[DB])){
			while ($f=mysql_fetch_assoc($res)){
				if (substr($f[MIME], 0, 5) == "image")
				echo "<img src=\"../files/$f[ID]\"><br>";
				else
				echo "<a href=\"../files/$f[ID]\"><br>";
			}
		}
	}
}
?>
