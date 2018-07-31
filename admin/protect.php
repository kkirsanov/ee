<?
@session_start();
function registerAccess($name, $description){
  global $conf;
  $SQL = "insert into $conf[DB_PREFIX]access(name, description) values ('$name', '$description')";
  mysql_query($SQL, $conf[DB]);
};
function GiveAccess($group, $name){
  global $conf;
  $SQL = "SELECT id FROM $conf[DB_PREFIX]access where name='$name'";
  $res = mysql_query($SQL, $conf[DB]);
  $tmp = mysql_fetch_assoc($res);
  $id = (int)$tmp[id];
  if ($id)
  {
    $SQL = "insert into $conf[DB_PREFIX]accessrights(group_id, access_id) values ($group, $id)";
    mysql_query($SQL, $conf[DB]);
  };
};
function checkGroup($group_id, $name){
  global $conf;
  $SQL = "SELECT id FROM $conf[DB_PREFIX]access where name='$name'";
  $res = mysql_query($SQL, $conf[DB]);
  $tmp = mysql_fetch_assoc($res);
  $id = (int)$tmp[id];
  if ($id)
  {
    $SQL = "SELECT count(*) as 'c' FROM $conf[DB_PREFIX]accessrights where group_id=$group_id and access_id=$id";
    $res = mysql_query($SQL, $conf[DB]);
    $tmp = mysql_fetch_assoc($res);
    return $tmp[c];
  };
  return 0;
};
function CA ($name){  
  //return true;
  return checkGroup ($_SESSION[group_id], $name);
};
?>
