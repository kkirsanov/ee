<?php
/*
 * Created on 06.04.2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
*/
session_start();

if (!(int)$_SESSION[manager_id])
  die("No Login");
include ("../db.php");
//WHERE ID = $_catalog[FIRM]
  switch ($_GET[a]){
  case "":
     echo   "<html><head>"
                .'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>'
                ."<META name=\"GENERATOR\" content=\"EasyEngine 3.0 www.lmm.ru\">"

                .'</head><body>'
                ;
      echo "<form method=post action=\"articulparcer.php?a=save\"><textarea name=\"names\" rows=35 cols=40></textarea><input type=submit></form>";
  break;
  case "save":
  $names = explode("\n", $_POST[names]);
//  print_r ($names);
/*
  $namesMap = array();
  foreach( $names as $name){
    $namesMap[$name] = 1;
  };
*/
  $SQL = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=2 WHERE NOT(ARTICUL ='NONE')";
  $tmpres = mysql_query($SQL, $conf[DB]); 
  foreach($names as $name){
    $newName = explode("\t", $name);

    $newName[0] = str_replace("\n", "",$newName[0]);
    
    $SQL = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=0 WHERE ARTICUL ='$newName[0]'";
    
    $tmpres = mysql_query($SQL, $conf[DB]);
    $newName[0]= trim($newName[0]);
    
    $SQL = "UPDATE $conf[DB_PREFIX]catalog SET INPRICE=0 WHERE ARTICUL ='$newName[0]'";
    $tmpres = mysql_query($SQL, $conf[DB]);

    /*
    if($newName[1]!="\n" && $newName[1]!=""){
      $newName[1] = str_replace(".", ",",$newName[0]);
      
      $SQL = "UPDATE $conf[DB_PREFIX]catalog SET PRICE=$newName[1] WHERE PRICE='$newName[0]'";
      //$tmpres = mysql_query($SQL, $conf[DB]);
    };
        */
  };  
  header("Location: http://muzbazar.ru/admin/properties.php?module=shop");
  break;
  };
?>
