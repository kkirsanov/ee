<? class eeProtect{
  var $conf;
  function eeProtect($conf) {
    $this->conf = $conf;
  }
  function install() {
    $conf = $this->conf;
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]protect` ("
      ."`user` int(11) NOT NULL default '0',"
      ."`protect` int(11) NOT NULL default '0',"
      ."KEY `users` (`user`,`protect`)"
    .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]protector` ("
      ."`protect` int(11) NOT NULL auto_increment,"
      ."PRIMARY KEY  (`protect`)"
    .") ENGINE=MyISAM CHARACTER SET utf8;"
    ;
    mysql_query($SQL, $conf[DB]);
    return 1;
  }
  function render($regionID = 0, $id) {
    $conf = $this->conf;
    $_tmp = $_SESSION[register][id];

    $_SQL = "SELECT count(user) as `count` FROM $conf[DB_PERFIX]protect WHERE user=$_tmp";
    
    $_result=mysql_query($_SQL, $conf[DB]);
    @$_che=mysql_fetch_assoc($_result);
    
    if ($_che[count]==0)
    {
      header ("Location: ?module=register");
      die();
    };
  }
  
  function add() {
    $conf = $this->conf;
    $SQL = "INSERT INTO `$conf[DB_PERFIX]protector` values()";
    mysql_query($SQL, $conf[DB]);
    return mysql_insert_id($conf[DB]);
  }
  function del($id){
    return 1;
  }
  function properties(){
  }
  function edit(){
    $action = $_GET[a];
    $id = $_GET[id];

    if ($_GET[a] == $action)
    $action="edit";

    switch ($action){
    case "update":
      $conf = $this->conf;
      $sql ="delete from $conf[DB_PREFIX]protect WHERE protect = $id";
      mysql_query($sql, $conf[DB]);

      $sql="SELECT * FROM $conf[DB_PREFIX]accounts";
      $result=mysql_query($sql, $conf[DB]);

      while ($user=mysql_fetch_assoc($result))
      {
        if ($_POST["user$user[ID]"]=="on")
        {
          $SQL = "insert INTO $conf[DB_PREFIX]protect (protect, user) VALUES($id, $user[ID])";
          mysql_query($SQL, $conf[DB]);
        };
      }
      header ("Location: ?a=edit&id=$id&module=protect");
    break;
    case "edit":
      $sql = "SELECT * FROM `$conf[DB_PREFIX]accounts`";
      $result = mysql_query($sql, $conf[DB]);

      echo '<form method="POST" action="?a=update&module=protect">';
      while ($user = mysql_fetch_assoc($result))
      {
        $tmpID = $user['ID'];
        $tmp=0;
        $checked= "checked";

        $sql = "SELECT COUNT(user) as `count` FROM $conf[DB_PREFIX]protect WHERE USERID=$tmpID";
        $result2=mysql_query($sql, $conf[DB]);
        $che=mysql_fetch_assoc($result2);

        if ($che[count]==0)
          $checked= "";

        echo "<INPUT type=\"checkbox\" name=\"user$user[ID]\" $checked>" . $user['LOGIN'], "<br>";
        };
      ?><center><INPUT type="submit" value="Принять" class="mainoption"></center><?
      echo '</form>';
    break;
  }
  } 
};

$info = array(
  'plugin'      => "protect",
  'cplugin'     => "eeProtect",
  'pluginName'    => "Ограничение доступа",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>0,
  'ISSPECIAL'     =>0,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>