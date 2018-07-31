<?
Error_Reporting(E_ALL & ~E_NOTICE);
$path = split("/", $_GET[path]);

include_once("db.php");
include_once 'admin/core_template.php';

function GLOBAL_LOAD($filename){
  global $ADMINPATH;
  return stripslashes(file_get_contents("./admin/data/$filename"));
};

$conf[get] = $path;

$isCachable     = false;
$readyForOutput = true;

$settings = new Templater("config.dat");
$META =& $settings->Get("meta");
$META2 = "";

$baseaddr=& $settings->Get("base");
$dJS = & $settings->Get("dJS");

if ($path[0]=="files"){
  $fileId = (int) $path[1];        // Get an ID of t file.
  if ($fileId!=0)                                // Check for valid integer ID.
  {//return the file
    $query="SELECT * FROM `$conf[DB_PREFIX]files` WHERE ID=$fileId";
    $res=mysql_query($query, $conf[DB]);
    if ($res){
      $file=mysql_fetch_assoc($res);
      $fname = "./files/$file[ID].dat";
      $count = (int)$file[COUNT];
      $count++;
      $SQL = "UPDATE $conf[DB_PREFIX]files SET `COUNT`='$count' WHERE ID = $file[ID]";
      mysql_query($SQL, $conf[DB]);
      $file_exists = file_exists($fname);
      $handle = fopen($fname, 'r');
      $file[data] = fread ($handle, filesize ($fname ));
      fclose ($handle);
      header("Content-type: $file[MIME]");
      //                        header("Content-Length: $file[SIZE]");
      $name = RuEncode($file[NAME]);
      header("filename=$name");

      header("Content-Type: image/jpeg");
      flush();

      //if($file[MIME] == "image/jpeg" && $file[TYPE]=="catalog"){
      /*
       if($file[TYPE]=="catalog"){
       $im=imagecreatefromjpeg($fname);
       $grey = imagecolorallocate($im, 128, 128, 128);

       $text = '(C) Muzbazar.ru';
       $font = './arial.ttf';
       //$k = imagesy($im) / imagesx($im);
       //$angle = atan ($k);
       //              imagettftext($im, 15, -rad2deg($angle), 20, 20, $grey, $font, $text);
       imagettftext($im, 10, 0, 10,imagesy($im)-20, $grey, $font, $text);

       imagejpeg($im);
       die();
       };
       */
      echo $file[data];
    }else{
      header("HTTP/1.0 404 Not Found");
    }
  }else{// inform about invalid file
    header("HTTP/1.0 404 Not Found");
  }
  $readyForOutput = false;
  die(); //exit
};

if ($path[0]=="pics"){
  $fileId = (int) $path[2];                // Get an ID of t file.
  if ($fileId!=0)                                // Check for valid integer ID.
  {   $pics_dir = './pics';
  $query="SELECT * FROM `$conf[DB_PREFIX]images` WHERE ID=$fileId";
  $res=mysql_query($query, $conf[DB]);
  if ($res)
  {  $img =mysql_fetch_assoc($res);
  if($path[1]=='originals')
  { $fname = $pics_dir.'/originals/'.$img[ID].'.dat';
  }
  if($path[1]=='normal')
  { $fname = $pics_dir.'/normal/'.$img[ID].'.dat';
  }
  if($path[1]=='preview')
  { $fname = $pics_dir.'/preview/'.$img[ID].'.dat';
  }
  if(file_exists($fname))
  {

    $name = RuEncode($img[NAME]);
    header("filename=$name");
    $size = filesize($fname);
    header("Pragma: public");
    header("Expires: Mon, 26 Jul 2009 05:00:00 GMT");
    header("Last-Modified: Mon, 26 Jul 2005 05:00:00 GMT");
    header("Cache-Control: public");
    header('Content-Type: image');
    header("Content-Length: ".$size);

    $query="UPDATE `$conf[DB_PREFIX]images` SET `COUNTER`= COUNTER+1 WHERE ID=$fileId";
    $res=mysql_query($query, $conf[DB]);
    readfile($fname);
  }
  }else
  {  header("HTTP/1.0 404 Not Found");
  }
  }else{// inform about invalid file
    header("HTTP/1.0 404 Not Found");
  }
  $readyForOutput = false;
  die(); //exit
};

$canGo = true;
$i=0;
$parent=0;

session_set_cookie_params(60*60*24*300, '/', '.magazindoc.ru');
if ((int)$_GET[partner]){
  if ($_COOKIE['partner']!=(int)$_GET[partner]){
   setcookie ("partner", (int)$_GET[partner], time()+60*60*24*300, '/', '.magazindoc.ru');
   }
}

session_start();
if ((int)$_GET[partner]){
  if ($_SESSION[partner]!=(int)$_GET[partner]){
    
    $_p = (int)$_GET[partner];
    $_ref = addslashes($_SERVER[HTTP_REFERER]);
    $url = addslashes($_SERVER['REQUEST_URI']);
    $sql = "insert into $conf[DB_PREFIX]referer(`partner`,`date`,`commited`,`referer`, `url`) values ($_p, NOW(), 0, '$_ref', '$url')";
    $r = mysql_query($sql) or die (mysql_error());
    $_SESSION[referal] = addslashes($_SERVER['HTTP_REFERER']);
  }
  $_SESSION[partner] = (int)$_GET[partner];
};
$_SESSION[partner]=$_COOKIE['partner'];

include_once("./admin/core_regions.php");
include_once("./admin/core_module.php");
include_once("./admin/core_template.php");
$conf[module_path]="./modules/";
$modules = new Modules ($conf);

while($canGo){
  $SQL = "SELECT * FROM $conf[DB_PREFIX]regions WHERE PARENT=$parent";
  if ($result=mysql_query($SQL, $conf[DB])){
    $localGo=true;
    $canGo = false;
    while(($reg = mysql_fetch_assoc($result))&&($localGo)){
      if (strtolower (RuEncodeUTF($reg[TITLE]))==strtolower($path[$i])){
        $path[0] = $reg[ID];
        $parent = $reg[ID];
        $localGo = false;
        $canGo = true;
      };
    };
  };
  $i++;
};

if((int)$path[0]==0){
  if($path[0]==""){
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `SPECIAL`='1'";
  }else{//empty
    $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `SPECIAL`='$path[0]'";
  }
}else{
  $sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `ID`=$path[0]";
}

//check for index.html
if ($path[0] == 'index')
$sql="SELECT * FROM $conf[DB_PREFIX]regions WHERE `SPECIAL`='1'";


$result=mysql_query($sql, $conf[DB]);
$region=mysql_fetch_assoc($result);


$sql="SELECT * FROM $conf[DB_PREFIX]templates WHERE `ID`=$region[TEMPLATE]";
if ($result=mysql_query($sql, $conf[DB]))
{
  $template=mysql_fetch_assoc($result);
  $templ = new Template($template[PATH]);
  $DATA[BODY] =stripslashes($templ->Get("main.skin"));
  $DATA[TITLE] .= $region[WEBTITLE];
  $DATA[KW] .= $region[KW];
  $DATA[DESC] .= $region[DESC];
  $META2=$templ->Get("main.meta");

  for($location=1; $location<=$templ->count; $location++){
    $sql = "SELECT * FROM `$conf[DB_PREFIX]blocks` WHERE `PARENTREGION`=$region[ID] and `LOCATION`=$location AND `ACTIVE` = 1 ORDER BY `ORDER` ASC";
    $result_block=mysql_query($sql, $conf[DB]);
    $resp = "";
    while($block = mysql_fetch_assoc($result_block)){
      $id = $region[ID];
      if (!$block[EXTRABLOCK])
      {
        $resp .=$modules->modules["$block[TYPE]"]->render($region[ID], $block[FID], $templ);
      }else{
        $resp .=$modules->modules["$block[TYPE]"]->renderEx($region[ID], $templ);
      };
    };
    $DATA[BODY] = str_replace("%BLOCK$location%", $resp, $DATA[BODY]);
  };
  //$DATA[HEADER] .='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
  //."<html xmlns=\"http://www.w3.org/1999/xhtml\"><head><title>$DATA[TITLE]</title>"
  $DATA[HEADER] .="<html><title>$DATA[TITLE]</title>"
  ."<Base href=\"$baseaddr\">"
  .stripslashes($META2)
  .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>'
  ."<META name=\"GENERATOR\" content=\"EasyEngine 3.0 www.lmm.ru\">"
  ."<META HTTP-EQUIV=\"description\" CONTENT=\"$DATA[DESC]\">"
  ."<META HTTP-EQUIV=\"keywords\" CONTENT=\"$DATA[KW]\">"
  .$META
  ."<link rel=\"Stylesheet\" type=\"text/css\" href=\"getcss.php?template=$template[ID]\" media=\"all\"/>"
  ."<link rel=\"shortcut icon\" href=\"/favicon.ico\">"
  ;
  
  if ($dJS==true){
    $DATA[HEADER] .=''
    .'<script>'
    .'function NW(adr, h, w)'
    .'{'
    .'   win=window.open(adr,"_blank","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,width="+ w + ",height="+h);'
    .'};'
    .'function DoConfirm(message, url, nw)'
    .'{'
    .'if (confirm(message))'
    .'{'
    .'if(nw)'
    .'{'
    .'NW(url, 220, 340)'
    .'}else{'
    .'location.href = url;'
    .'};'
    .'};'
    .'};'
    .'</script>'
    ;
  };
  ;
  header ('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
  header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header ("Cache-Control: no-cache, must-revalidate");
  header ("Pragma: no-cache");

  $tmp_data = $DATA[HEADER] ."</head>". $DATA[BODY];
  $tmp_data= str_replace("<tarea", "<textarea", $tmp_data);
  $tmp_data= str_replace("</tarea>", "</textarea>", $tmp_data);
  $tmp_data= str_replace("../files/", $baseaddr."files/", $tmp_data);
  echo $tmp_data;
};
?>