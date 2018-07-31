<?php
Error_Reporting(E_ALL & ~E_NOTICE);

set_time_limit (0);

class Images
{
	var $DB;
	var $filetypes='zip,jpg,jpeg,png,gif';
	var $TABLE_IMG ;
	var $TABLE_GAL ;


	function Images($DB, $T_IMG, $T_GAL ) //resourse id to db , db prefix
	{  $this->DB = $DB;
	$this->TABLE_IMG =$T_IMG;
	$this->TABLE_GAL = $T_GAL;
	}
	function GetGalleryInfo($id)
	{  $SQL = "SELECT * FROM ".$this->TABLE_GAL." WHERE ID=$id";
	$res = mysql_query($SQL, $this->DB);
	$info = mysql_fetch_assoc($res);
	return $info;
	}
	function CreateGallery($gname, $galcolls, $galrows)
	{ $SQL = "SELECT max(`ORDER`) as `maximum` FROM `".$this->TABLE_GAL."`";
	$res = mysql_query($SQL, $this->DB);
	$ordr = mysql_fetch_assoc($res);
	$order = 0 + (int)$ordr[maximum]+1;
	$sql = "INSERT INTO `".$this->TABLE_GAL."` (`ID`, `ORDER`, `NAME`, `COLLS`, `ROWS`) VALUES ('', '$order', '$gname', '$galcolls', '$galrows')";
	$res= mysql_query($sql,$this->DB);
	if(res)
	{ $id = mysql_insert_id($this->DB);
	return $id;
	}
	}
	function SelectGallery( $gname, $galcolls, $galrows)
	{ $sql = "SELECT * FROM `".$this->TABLE_GAL."` WHERE `NAME` = '$gname' ";
	$res= mysql_fetch_assoc (mysql_query($sql,$this->DB));//or die(mysql_error();
	if($res[ID])
	{ return $res[ID];
	}
	else //create
	{  $SQL = "SELECT max(`ORDER`) as `maximum` FROM `".$this->TABLE_GAL."`";
	$res = mysql_query($SQL, $this->DB);
	$ordr = mysql_fetch_assoc($res);
	$order = 0 + (int)$ordr[maximum]+1;
	$sql = "INSERT INTO `".$this->TABLE_GAL."` (`ID`, `ORDER`, `NAME`, `COLLS`, `ROWS`) VALUES ('', '$order', '$gname', '$galcolls', '$galrows')";
	$res= mysql_query($sql,$this->DB);
	if(res)
	{ $id = mysql_insert_id($this->DB);
	return $id;
	}
	}
	}
	function SetGalleryInfo($id, $gname, $galcolls, $galrows)
	{ $sql = "UPDATE `".$this->TABLE_GAL."` SET `NAME` = '$gname', `COLLS`='$galcolls', `ROWS` = '$galrows' WHERE `ID` = '$id' ";
	mysql_query($sql,$this->DB);
	}
	function DeleteGallery($id, $pics_dir, $pics_norm, $pics_prew)
	{ $img =$this->ShowGallery($id);
	#@foreach($img as $im){
#		$this->DeleteImg($im[ID], $pics_dir, $pics_norm, $pics_prew);
#	}
	$sql = "DELETE FROM ".$this->TABLE_GAL." WHERE ID=$id";
	mysql_query($sql, $this->DB);
	}

	function DeleteImg($id, $pics_dir, $pics_norm, $pics_prew)
	{ $sql = "SELECT * FROM `".$this->TABLE_IMG."` WHERE `ID` = $id";
	$res= mysql_query($sql, $this->DB);
	$im = mysql_fetch_assoc($res);
	unlink($pics_dir.'/'.$pics_norm.'/'.$im[NAME]);
	unlink($pics_dir.'/'.$pics_prew.'/'.$im[NAME]);
	$sql = "DELETE FROM ".$this->TABLE_IMG." WHERE ID=$id";
	mysql_query($sql, $this->DB);
	}

	function DownGalleryOrder($id)
	{ $sql = "SELECT * FROM `".$this->TABLE_GAL."` WHERE `ID` = $id";
	$result = mysql_query($sql, $this->DB);
	$gallery = mysql_fetch_assoc($result);
	$sql = "SELECT min(`ORDER`) as `co` FROM `".$this->TABLE_GAL."` where `ORDER`>$gallery[ORDER]";
	if ($result5 = mysql_query($sql, $this->DB))
	{ $tmp = mysql_fetch_assoc($result5);
	$orderD = $tmp[co];
	$sql = "SELECT * FROM `".$this->TABLE_GAL."` where `ORDER`=$orderD";
	if ($result6 = mysql_query($sql, $this->DB))
	{ $down = mysql_fetch_assoc($result6);
	$sql = "UPDATE `".$this->TABLE_GAL."` SET `ORDER`=$gallery[ORDER] WHERE `ID`=$down[ID]";
	mysql_query($sql, $this->DB);
	$sql = "UPDATE `".$this->TABLE_GAL."` SET `ORDER`=$orderD WHERE `ID`=$id";
	mysql_query($sql,  $this->DB);
	}
	}
	}

	function UpGalleryOrder($id)
	{ $sql = "SELECT * FROM `".$this->TABLE_GAL."` WHERE `ID` = $id";
	$result = mysql_query($sql, $this->DB);
	$gallery = mysql_fetch_assoc($result);
	$sql = "SELECT max(`ORDER`) as `co` FROM `".$this->TABLE_GAL."` where `ORDER`<$gallery[ORDER]";
	if ($result5 = mysql_query($sql, $this->DB))
	{ $tmp = mysql_fetch_assoc($result5);
	$orderU = $tmp[co];
	$sql = "SELECT * FROM `".$this->TABLE_GAL."` where `ORDER`=$orderU";
	if ($result6 = mysql_query($sql, $this->DB))
	{ $up = mysql_fetch_assoc($result6);
	$sql = "UPDATE `".$this->TABLE_GAL."` SET `ORDER`=$gallery[ORDER] WHERE `ID`=$up[ID]";
	mysql_query($sql, $this->DB);
	$sql = "UPDATE `".$this->TABLE_GAL."` SET `ORDER`=$orderU WHERE `ID`=$id";
	mysql_query($sql, $this->DB);
	}
	}
	}

	function DownImgOrder($id)
	{ $sql = "SELECT * FROM `".$this->TABLE_IMG."` WHERE `ID` = $id";
	$result = mysql_query($sql, $this->DB);
	$gallery = mysql_fetch_assoc($result);
	$sql = "SELECT min(`ORDER`) as `co` FROM `".$this->TABLE_IMG."` where `ORDER`>$gallery[ORDER]";
	if ($result5 = mysql_query($sql, $this->DB))
	{ $tmp = mysql_fetch_assoc($result5);
	$orderD = $tmp[co];
	$sql = "SELECT * FROM `".$this->TABLE_IMG."` where `ORDER`=$orderD";
	if ($result6 = mysql_query($sql,  $this->DB))
	{ $down = mysql_fetch_assoc($result6);
	$sql = "UPDATE `".$this->TABLE_IMG."` SET `ORDER`=$gallery[ORDER] WHERE `ID`=$down[ID]";
	mysql_query($sql, $this->DB);
	$sql = "UPDATE `".$this->TABLE_IMG."` SET `ORDER`=$orderD WHERE `ID`=$id";
	mysql_query($sql,  $this->DB);
	}
	}
	}

	function UpImgOrder($id)
	{ $sql = "SELECT * FROM `".$this->TABLE_IMG."` WHERE `ID` = $id";
	$result = mysql_query($sql, $this->DB);
	$gallery = mysql_fetch_assoc($result);
	$sql = "SELECT max(`ORDER`) as `co` FROM `".$this->TABLE_IMG."` where `ORDER`<$gallery[ORDER]";
	if ($result5 = mysql_query($sql, $this->DB))
	{ $tmp = mysql_fetch_assoc($result5);
	$orderU = $tmp[co];
	$sql = "SELECT * FROM `".$this->TABLE_IMG."` where `ORDER`=$orderU";
	if ($result6 = mysql_query($sql, $this->DB))
	{ $up = mysql_fetch_assoc($result6);
	$sql = "UPDATE `".$this->TABLE_IMG."` SET `ORDER`=$gallery[ORDER] WHERE `ID`=$up[ID]";
	mysql_query($sql, $this->DB);
	$sql = "UPDATE `".$this->TABLE_IMG."` SET `ORDER`=$orderU WHERE `ID`=$id";
	mysql_query($sql, $this->DB);
	}
	}
	}
	function GetGalleries()
	{ $sql = "SELECT * FROM `".$this->TABLE_GAL."` ORDER BY `ORDER`";
	$r = mysql_query($sql,$this->DB);
	while(@$res = mysql_fetch_assoc($r))
	{ $gals[]=$res;
	}
	return $gals;
	}
	function ShowGallery($galid)
	{ $sql = "SELECT * FROM `".$this->TABLE_IMG."` WHERE `GALLERY` ='$galid' ORDER BY `ORDER`";
	$r = mysql_query($sql,$this->DB);
	while($res = mysql_fetch_assoc($r))
	{ $img[]=$res;
	}
	return $img;
	}
	function GetGallerySet($galid)
	{ $sql = "SELECT * FROM `".$this->TABLE_GAL."` WHERE `ID` ='$galid'";
	$r = mysql_query($sql,$this->DB);
	$set = mysql_fetch_assoc($r);
	return $set;
	}
	function SetImageInfo($img_id, $desc, $content)
	{
		$sql = "UPDATE $this->TABLE_IMG  SET  `DESC` = '$desc', `CONTENT` = '$content' WHERE `ID` = $img_id";
		mysql_query($sql,$this->DB);
	}

	function GetImg($fieldname, $maxfilesize, $upload_dir, $pics_dir, $pics_norm, $pics_prew, $norm_w, $norm_h, $prew_w, $prew_h, $galleryid)    //загрузить картинку юзера, сохранить, return  $img_id
	{ if (!is_dir($pics_dir)){mkdir($pics_dir);}
	if (!is_dir($upload_dir)){mkdir($upload_dir);}

	$upfile['temp']    = $_FILES[$fieldname]['tmp_name'];
	$upfile['name']    = $_FILES[$fieldname]['name'];
	$upfile['size']    = $_FILES[$fieldname]['size'];
	$upfile['ext']    = strtolower(substr($upfile['name'],strrpos($upfile['name'],'.')+1));
	$upfile['uname']  = $_FILES[$fieldname]['name'];

	if (empty($upfile['name']))
	{ return 'Incorrect name';
	}
	if ($upfile['size'] > $maxfilesize)
	{ return "too big file";
	}
	if (!in_array($upfile['ext'],split(',',strtolower($this->filetypes))))
	{   return "type is not supported";
	}

	$upfile['name'] = date("Ymd_His").'_'.rand(100,999).'.'.$upfile['ext'];
	$err = move_uploaded_file($upfile['temp'], $upload_dir.'/'.$upfile['name']);
	if(!$err){return "error while uploading $err";}

	if ($upfile['ext']=='zip')
	{ if (!is_dir($pics_dir.'/unzip')){mkdir($pics_dir.'/unzip');}
	require_once('pclzip.lib.php');
	$zip = new PclZip($upload_dir.'/'.$upfile['name']);
	$zip->extract($pics_dir.'/unzip');
	unlink($upload_dir.'/'.$upfile['name']);
	$startdir= $pics_dir.'/unzip';
	$start=$startdir;
	$DirToScan  = array($startdir);
	$DirScanned = array();

	while (count($DirToScan) > 0)
	{ foreach ($DirToScan as $DirKey => $startdir)
	{ if ($directory = @opendir($startdir))
	{ while (($file = readdir($directory)) !== false)
	{ if (($file != '.') && ($file != '..'))
	{ $RealPathName = $startdir.'/'.$file;
	if (is_dir($RealPathName))
	{ if (!in_array($RealPathName, $DirScanned) && !in_array($RealPathName, $DirToScan))
	{ $DirToScan[] = $RealPathName;
	}
	}
	elseif (is_file($RealPathName))
	{ $files[] =$RealPathName ;
	}
	}
	}
	closedir($directory);
	}
	$DirScanned[] = $startdir;
	unset($DirToScan[$DirKey]);
	}
	}

	if(count($files))
	{ foreach ($files as $filename)
	{ $ext= strtolower(substr($filename,strrpos($filename,'.')+1));
	if ((in_array($ext, split(',',strtolower($this->filetypes))))and ($ext!='.zip'))             //!!!!!!!!!
	{ $name=date("Ymd_His").'_'.rand(100,999).'.'.strtolower(substr($filename, strrpos($filename,'.')+1));
	$uname = substr($filename, strrpos($filename,'/')+1);

	if (!is_dir($pics_dir.'/'.$pics_norm)){mkdir($pics_dir.'/'.$pics_norm);}
	if (!is_dir($pics_dir.'/'.$pics_prew)){mkdir($pics_dir.'/'.$pics_prew);}

	$res = rename($filename, $pics_dir.'/'.$pics_norm.'/'.$name);
	if ($res)
	{
		$size = getimagesize($pics_dir.'/'.$pics_norm.'/'.$name);
		die ($size);
		$k=0.0;
		if ($norm_w!=0){
			$k = $norm_w/$size[0];
		}else{
			$k = $norm_h/$size[1];
		}
		$newsizeX =$k *$size[0];
		$newsizeY =$k *$size[1];
			
		//$r= $this->ResizeInFile($pics_dir.'/'.$pics_norm.'/'.$name, $pics_dir.'/'.$pics_norm.'/'.$name, $norm_w, $norm_h);
		$r= $this->ResizeInFile($pics_dir.'/'.$pics_norm.'/'.$name, $pics_dir.'/'.$pics_norm.'/'.$name, $newsizeX, $newsizeY);
			
		$k=0.0;
		if ($prew_w!=0){
			$k = $prew_w/$size[0];
		}else{
			$k = $prew_h/$size[1];
		}
		$newsizeX =$k *$size[0];
		$newsizeY =$k *$size[1];
			
		//$r = $this->ResizeInFile($pics_dir.'/'.$pics_norm.'/'.$name, $pics_dir.'/'.$pics_prew.'/'.$name, $prew_w, $prew_h);
		$r = $this->ResizeInFile($pics_dir.'/'.$pics_norm.'/'.$name, $pics_dir.'/'.$pics_prew.'/'.$name, $newsizeX, $newsizeY);
			
		if ($r)
		{ $sql = "INSERT INTO `".$this->TABLE_IMG."` (`ID`, `NAME`, `GALLERY`) VALUES ('', '$name',  '$galleryid')";
		$rs= mysql_query($sql, $this->DB) or die (mysql_error());
		$img_id[]= mysql_insert_id($this->DB);
		}
	}
	}
	else
	{unlink($filename);}
	}
	}
	return $img_id;
	}
	else              //1 file
	{ if (!is_dir($pics_dir.'/'.$pics_norm)){mkdir($pics_dir.'/'.$pics_norm);}
	if (!is_dir($pics_dir.'/'.$pics_prew)){mkdir($pics_dir.'/'.$pics_prew);}
	$res = rename ($upload_dir.'/'.$upfile['name'], $pics_dir.'/'.$pics_norm.'/'.$upfile['name']);
	if ($res)
	{
		$size = getimagesize($pics_dir.'/'.$pics_norm.'/'.$upfile['name']);

		$k=0.0;
		if ($norm_w!=0){
			$k = $norm_w/$size[0];
		}else{
			$k = $norm_h/$size[1];
		}
		$newsizeX =(int)($k *$size[0]);
		$newsizeY =(int)($k *$size[1]);

		$r= $this->ResizeInFile($pics_dir.'/'.$pics_norm.'/'.$upfile['name'], $pics_dir.'/'.$pics_norm.'/'.$upfile['name'], $newsizeX, $newsizeY);
			
		$k=0.0;
		if ($prew_w!=0){
			$k = $prew_w/$size[0];
		}else{
			$k = $prew_w/$size[1];
		}
		$newsizeX =$k *$size[0];
		$newsizeY =$k *$size[1];

		//$r= $this->ResizeInFile($pics_dir.'/'.$pics_norm.'/'.$upfile['name'], $pics_dir.'/'.$pics_prew.'/'.$upfile['name'], $prew_w, $prew_h);
		$r= $this->ResizeInFile("../pics/normalized/$upfile[name]", "../pics/prewiews/$upfile[name]", $newsizeX, $newsizeY);




		if ($r)
		{ $sql = "INSERT INTO `".$this->TABLE_IMG."` (`ID`, `NAME`, `GALLERY`) VALUES ('', '$upfile[name]', '$galleryid')";
		$rs= mysql_query($sql, $this->DB) or die (mysql_error());
		}
		if( $rs)
		{ $imgid= mysql_insert_id($this->DB);
		return $imgid;
		}
	}
	else
	{ return "error while uploading";
	}
	}
	}
	function ResizeInFile($st, $dst, $width, $height)
	{
		$size = getimagesize($st);
		$ext = strtolower(substr($st, strrpos($st,'.')+1));

		if ($width==0)
		{ if($height==0)
		{ return 1;
		}
		else
		{ $h=$height;
		$w= round($h*$size[0]/$size[1]);
		}
		}
		else
		{ $w=$width;
		if($height==0)
		{ $h= round($w*$size[1]/$size[0]);
		}
		else
		{ $h=$height;
		}
		}
		if (($ext=='jpg')or($ext=='jpeg'))
		{ $im=imagecreatefromjpeg($st);
		$im1=imagecreatetruecolor($w,$h);
		imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
		$image= imagejpeg($im1, $dst);
		imagedestroy($im);
		imagedestroy($im1);
		return $image;
		}
		elseif ($ext=='png')
		{ $im=imagecreatefrompng($st);
		$im1=imagecreatetruecolor($w,$h);
		imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
		$image= imagepng($im1,$dst);
		imagedestroy($im);
		imagedestroy($im1);
		return $image;
		}
		elseif ($ext=='gif')
		{ $im=imagecreatefromgif($st);
		$im1=imagecreatetruecolor($w,$h);
		imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
		$image= imagegif($im1,$dst);
		imagedestroy($im);
		imagedestroy($im1);
		return $image;
		}
	}


	function ScaleSize($img_id, $width=0, $height=0)    //масштабировать до размера, сохранить настройки
	{  $sql = "SELECT * FROM $this->TABLE_IMG  WHERE `ID`=$img_id";
	$img= mysql_fetch_assoc( mysql_query($sql,$this->DB));
	$path = $this->upload_dir.'/'.$img[NAME];
	$ext = substr($img['NAME'],strpos($img['NAME'],'.')+1);
	$size = getimagesize($path);

	$sql = "UPDATE $this->TABLE_IMG  SET `SCALESIZE_X` = '$width', `SCALESIZE_Y` = '$height' WHERE `ID` = '$img_id' ";
	mysql_query($sql,$this->DB);

	if ($width==0)
	{  if($height==0)
	{ return 0;     //incorrect w & h
	}
	else
	{ $h=$height;
	$w= round($h*$size[0]/$size[1]);
	}
	}
	else
	{ $w=$width;
	if($height==0)
	{$h= round($w*$size[1]/$size[0]);
	}
	else
	{ $h=$height;
	}
	}

	if (($ext=='jpg')or($ext=='jpeg'))
	{ $im=imagecreatefromjpeg($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
	// header("Content-type: image/jpeg");
	$image= imagejpeg($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='png')
	{ $im=imagecreatefrompng($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
	$image= imagepng($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='gif')
	{ $im=imagecreatefromgif($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
	$image= imagegif($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	}
	function ScalePercent($img_id, $percent=100)       //   масштабировать в процент от исходного, сохранить настройки
	{  $sql = "SELECT * FROM $this->TABLE_IMG  WHERE `ID`=$img_id";
	$img= mysql_fetch_assoc( mysql_query($sql,$this->DB));
	$path = $this->upload_dir.'/'.$img[NAME];
	$ext = substr($img['NAME'],strpos($img['NAME'],'.')+1);
	$size = getimagesize($path);

	$sql = "UPDATE $this->TABLE_IMG  SET `SCALEPERCENT` = '$percent' WHERE `ID` = '$img_id' ";
	mysql_query($sql,$this->DB);
	$w=$size[0]*$percent/100;
	$h=$size[1]*$percent/100;

	if (($ext=='jpg')or($ext=='jpeg'))
	{ $im=imagecreatefromjpeg($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
	$image = imagejpeg($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='png')
	{ $im=imagecreatefrompng($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
	$image = imagepng($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='gif')
	{  $im=imagecreatefromgif($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
	$image= imagegif($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	}
	function AddText($img_id, $text,  $font, $angle=0, $x=10, $y=20, $textsize=10, $color = array ('r'=>255, 'g'=>255, 'b'=>255)) //   надписать text, сохранить настройки, добавить проверку координат?
	{  $sql = "SELECT * FROM $this->TABLE_IMG  WHERE `ID`=$img_id";
	$img= mysql_fetch_assoc( mysql_query($sql,$this->DB));
	$path = $this->upload_dir.'/'.$img[NAME];
	$ext = substr($img['NAME'],strpos($img['NAME'],'.')+1);
	$size = getimagesize($path);

	$sql = "UPDATE $this->TABLE_IMG  SET `ADDTEXT` = '$text', `ADDTEXT_FONT` = '$font', `ADDTEXT_ANGLE`= '$angle', `ADDTEXT_X`= '$x', `ADDTEXT_Y` = '$y', `ADDTEXT_TEXTSIZE` = '$textsize', `ADDTEXT_R` = '$color[r]', `ADDTEXT_G` = '$color[g]', `ADDTEXT_B` = '$color[b]' WHERE `ID` = '$img_id' ";
	mysql_query($sql,$this->DB);

	if (($ext=='jpg')or($ext=='jpeg'))
	{ $im=imagecreatefromjpeg($path);
	$c = imagecolorallocate($im, $color[r], $color[g], $color[b]);
	imagettftext($im, $textsize, $angle, $x, $y, $c, $font, $text);
	$image = imagejpeg($im);
	imagedestroy($im);
	return $image;
	}
	elseif ($ext=='png')
	{ $im=imagecreatefrompng($path);
	$c = imagecolorallocate($im, $color[r], $color[g], $color[b]);
	imagettftext($im, $textsize, $angle, $x, $y, $c, $font, $text);
	$image = imagepng($im);
	imagedestroy($im);
	return $image;
	}
	elseif ($ext=='gif')
	{ $im=imagecreatefromgif($path);
	$c = imagecolorallocate($im, $color[r], $color[g], $color[b]);
	imagettftext($im, $textsize, $angle, $x, $y, $c, $font, $text);
	$image = imagegif($im);
	imagedestroy($im);
	return $image;
	}
	}
	function Crop($img_id, $x=0, $y=0, $w=100, $h=100)  //   кадрировать, сохранить настройки
	{  $sql = "SELECT * FROM $this->TABLE_IMG  WHERE `ID`=$img_id";
	$img= mysql_fetch_assoc( mysql_query($sql,$this->DB));
	$path = $this->upload_dir.'/'.$img[NAME];
	$ext = substr($img['NAME'],strpos($img['NAME'],'.')+1);

	$sql = "UPDATE $this->TABLE_IMG  SET `CROP_X` = '$x', `CROP_Y` = '$y', `CROP_W` = '$w', `CROP_H` = '$h' WHERE `ID` = '$img_id' ";
	mysql_query($sql,$this->DB);

	if (($ext=='jpg')or($ext=='jpeg'))
	{  $im=imagecreatefromjpeg($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopy($im1,$im,0,0,$x,$y,$w,$h);
	$image = imagejpeg($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='png')
	{ $im=imagecreatefrompng($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopy($im1,$im,0,0,$x,$y,$w,$h);
	$image = imagepng($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='gif')
	{ $im=imagecreatefromgif($path);
	$im1=imagecreatetruecolor($w,$h);
	imagecopy($im1,$im,0,0,$x,$y,$w,$h);
	$image = imagegif($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	}
	function ColorCorrect($img_id, $r, $g, $b)  //   насыщ цветов, сохранить настройки
	{  $sql = "SELECT * FROM $this->TABLE_IMG  WHERE `ID`=$img_id";
	$img= mysql_fetch_assoc( mysql_query($sql,$this->DB));
	$path = $this->upload_dir.'/'.$img[NAME];
	$ext = substr($img['NAME'],strpos($img['NAME'],'.')+1);
	$size = getimagesize($path);

	$sql = "UPDATE $this->TABLE_IMG  SET `COLORCORRECT_R` = '$r', `COLORCORRECT_G` = '$g', `COLORCORRECT_B` = '$b' WHERE `ID` = '$img_id' ";
	mysql_query($sql,$this->DB);

	if (($ext=='jpg')or($ext=='jpeg'))
	{  $im=imagecreatefromjpeg($path);
	$im1=imagecreatetruecolor($size[0],$size[1]);
	for ($i=0;$i<$size[0];$i++)
	{  for ($j=0;$j<$size[1];$j++)
	{ $rgb= imagecolorat($im, $i, $j);
	$nr = (($rgb >> 16) & 0xFF)+$r;
	$ng = (($rgb >> 8) & 0xFF)+$g;
	$nb = ($rgb & 0xFF)+$b;
	$nr = ($nr > 255) ? 255 : (($nr < 0) ? 0 : (int)($nr));
	$ng = ($ng > 255) ? 255 : (($ng < 0) ? 0 : (int)($ng));
	$nb = ($nb > 255) ? 255 : (($nb < 0) ? 0 : (int)($nb));
	$col1 = imagecolorallocate($im1, $nr, $ng, $nb);
	imagesetpixel($im1,$i,$j,$col1);
	}
	}
	$image = imagejpeg($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='png')
	{  $im=imagecreatefrompng($path);
	$im1=imagecreatetruecolor($size[0],$size[1]);
	for ($i=0;$i<$size[0];$i++)
	{  for ($j=0;$j<$size[1];$j++)
	{ $rgb= imagecolorat($im, $i, $j);
	$nr = (($rgb >> 16) & 0xFF)+$r;
	$ng = (($rgb >> 8) & 0xFF)+$g;
	$nb = ($rgb & 0xFF)+$b;
	$nr = ($nr > 255) ? 255 : (($nr < 0) ? 0 : (int)($nr));
	$ng = ($ng > 255) ? 255 : (($ng < 0) ? 0 : (int)($ng));
	$nb = ($nb > 255) ? 255 : (($nb < 0) ? 0 : (int)($nb));
	$col1 = imagecolorallocate($im1, $nr, $ng, $nb);
	imagesetpixel($im1,$i,$j,$col1);
	}
	}
	$image = imagepng($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	elseif ($ext=='gif')     //!!!!!!!!!!!!!!!!
	{ $im=imagecreatefromgif($path);
	$im1=imagecreatetruecolor(imagesx($im),imagesy($im));
	for ($i=0;$i<imagesx($im);$i++)
	{ for ($j=0;$j<imagesy($im);$j++)
	{ $rgb= imagecolorat($im, $i, $j);
	$nr = (($rgb >> 16) & 0xFF)+ $img[COLORCORRECT_R];
	$ng = (($rgb >> 8) & 0xFF)+ $img[COLORCORRECT_G];
	$nb = ($rgb & 0xFF)+ $img[COLORCORRECT_B];
	$nr = ($nr > 255) ? 255 : (($nr < 0) ? 0 : (int)($nr));
	$ng = ($ng > 255) ? 255 : (($ng < 0) ? 0 : (int)($ng));
	$nb = ($nb > 255) ? 255 : (($nb < 0) ? 0 : (int)($nb));
	$col = imagecolorallocate($im1, $nr, $ng, $nb);
	imagesetpixel($im1,$i,$j,$col);
	}
	}

	$image = imagegif($im1);
	imagedestroy($im);
	imagedestroy($im1);
	return $image;
	}
	}
	function ShowImg($img_id)
	{ $sql = "SELECT * FROM $this->TABLE_IMG  WHERE `ID`=$img_id";
	$img= mysql_fetch_assoc( mysql_query($sql,$this->DB));
	$path = $this->upload_dir.'/'.$img[NAME];
	$ext = substr($img['NAME'],strpos($img['NAME'],'.')+1);

	if (($ext=='jpg')or($ext=='jpeg'))
	{ $im=imagecreatefromjpeg($path);
	}
	elseif ($ext=='png')
	{ $im=imagecreatefrompng($path);
	}
	elseif ($ext=='gif')
	{ $im=imagecreatefromgif($path);
	}

	if ($img[ADDTEXT])
	{ $c = imagecolorallocate($im, $img[ADDTEXT_R], $img[ADDTEXT_G], $img[ADDTEXT_B]);
	imagettftext($im, $img[ADDTEXT_TEXTSIZE],$img[ADDTEXT_ANGLE], $img[ADDTEXT_X], $img[ADDTEXT_Y], $c, $img[ADDTEXT_FONT], $img[ADDTEXT]);
	}

	if (($img[CROP_X]!=0)or($img[CROP_Y]!=0)or($img[CROP_W]!=0)or($img[CROP_H]!=0))
	{ $im1=imagecreatetruecolor($img[CROP_W],$img[CROP_H]);
	imagecopy($im1,$im,0,0,$img[CROP_X],$img[CROP_Y],$img[CROP_W],$img[CROP_H]);
	imagedestroy($im);
	$im=imagecreatetruecolor($img[CROP_W],$img[CROP_H]);
	imagecopy($im, $im1, 0,0,0,0,$img[CROP_W],$img[CROP_H]);
	imagedestroy($im1);
	}

	if (($img[SCALESIZE_X]!=0) or ($img[SCALESIZE_Y]!=0))
	{ if($img[SCALESIZE_X]!=0)
	{$width= $img[SCALESIZE_X];
	}
	else
	{$width = round($img[SCALESIZE_Y]*imagesx($im)/imagesy($im));
	}
	if($img[SCALESIZE_Y]!=0)
	{ $height = $img[SCALESIZE_Y];
	}
	else
	{ $height = round($img[SCALESIZE_X]*$imagesx($im)/imagesy($im));
	}
	$im1=imagecreatetruecolor($width,$height);
	imagecopyresampled($im1,$im,0,0,0,0,$width,$height,imagesx($im),imagesy($im));
	imagedestroy($im);
	$im=imagecreatetruecolor($width,$height);
	imagecopy($im, $im1, 0,0,0,0,$width,$height);
	imagedestroy($im1);
	}

	if ($img[SCALEPERCENT]!=0)
	{ $w=imagesx($im)*$img[SCALEPERCENT]/100;
	$h=imagesy($im)*$img[SCALEPERCENT]/100;
	$im1=imagecreatetruecolor($w,$h);
	imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
	imagedestroy($im);
	$im=imagecreatetruecolor($w, $h);
	imagecopy($im, $im1, 0,0,0,0,$w,$h);
	imagedestroy($im1);
	}

	if (($img[COLORCORRECT_R]!=0) or ($img[COLORCORRECT_G]!=0) or ($img[COLORCORRECT_B]!=0))
	{ $im1=imagecreatetruecolor(imagesx($im),imagesy($im));
	for ($i=0;$i<imagesx($im);$i++){
		for ($j=0;$j<imagesy($im);$j++){
			$rgb= imagecolorat($im, $i, $j);
			$nr = (($rgb >> 16) & 0xFF)+ $img[COLORCORRECT_R];
			$ng = (($rgb >> 8) & 0xFF)+ $img[COLORCORRECT_G];
			$nb = ($rgb & 0xFF)+ $img[COLORCORRECT_B];
			$nr = ($nr > 255) ? 255 : (($nr < 0) ? 0 : (int)($nr));
			$ng = ($ng > 255) ? 255 : (($ng < 0) ? 0 : (int)($ng));
			$nb = ($nb > 255) ? 255 : (($nb < 0) ? 0 : (int)($nb));
			$col = imagecolorallocate($im1, $nr, $ng, $nb);
			imagesetpixel($im1,$i,$j,$col);
		}
	}
	imagedestroy($im);
	$im=imagecreatetruecolor(imagesx($im1),imagesy($im1));
	imagecopy($im, $im1,0,0,0,0,imagesx($im1),imagesy($im1));
	imagedestroy($im1);
	}

	if (($ext=='jpg')or($ext=='jpeg'))
	{ $image= imagejpeg($im);
	imagedestroy($im);
	return $image;
	}
	elseif ($ext=='png')
	{ $image= imagepng($im);
	imagedestroy($im);
	return $image;
	}
	elseif ($ext=='gif')
	{ $image= imagegif($im);
	imagedestroy($im);
	return $image;
	}
	}
}

?>
