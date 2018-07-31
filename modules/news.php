<?
class eeNews{
  var $conf;
  function eeNews($conf)
  {
    $this->conf = $conf;
  }
  function install() {
    $conf = $this->conf;
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]news` ("
    ."`ID` int(11) unsigned NOT NULL auto_increment,"
    ."`TITLE` mediumtext NOT NULL,"
    ."`HEADER` mediumtext NOT NULL,"
    ."`CONTENT` mediumtext NOT NULL,"
    ."`ACTIVE` tinyint(1) NOT NULL default '0',"
    ."`KW` mediumtext NOT NULL,"
    ."`TYPE` int(11) NOT NULL default '0',"
    ."`DESCR` mediumtext NOT NULL,"
    ."`DATE` datetime NOT NULL default '0000-00-00 00:00:00',"
    ."PRIMARY KEY  (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]newsblock` ("
    ."`ID` int(11) NOT NULL auto_increment,"
    ."`NEWID` int(11) NOT NULL default '0',"
    ."`TYPE` int(11) NOT NULL default '0',"
    ."`NAVIGATE` int(11) NOT NULL default '0',"
    ."`ANN` int(11) NOT NULL default '0',"
    ."`PAGE` int(11) NOT NULL default '0',"
    ."`COUNT` int(11) NOT NULL default '3',"
    ."PRIMARY KEY  (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]newsblocklist` ("
    ."`BLOCKID` int(11) NOT NULL default '0',"
    ."`TYPEID` int(11) NOT NULL default '0'"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]news_rel_tag` ("
    ."`news_id` int(11) NOT NULL default '0',"
    ."`tag_id` int(11) NOT NULL default '0'"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);
    $SQL = "CREATE TABLE `$conf[DB_PREFIX]newsblock_rel_tag` ("
    ."`block_id` int(11) NOT NULL default '0',"
    ."`tag_id` int(11) NOT NULL default '0'"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]newstag` ("
    ."`tag_id` int(11) NOT NULL auto_increment,"
    ."`name` mediumtext NOT NULL,"
    ."PRIMARY KEY  (`tag_id`)"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]newstype` ("
    ."`ID` int(11) NOT NULL auto_increment,"
    ."`NAME` mediumtext NOT NULL,"
    ."PRIMARY KEY  (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]news_comments` ("
    ."`ID` int(11) NOT NULL auto_increment,"
    ."`DATE` datetime NOT NULL default '0000-00-00 00:00:00',"
    ."`NEWSID` int(11) NOT NULL default '0',"
    ."`TEXT` text NOT NULL,"
    ."`NAME` varchar(100) NOT NULL default '',"
    ."`MAIL` varchar(100) NOT NULL default '',"
    ."PRIMARY KEY  (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8"
    ;
    @mysql_query($SQL, $conf[DB]);

    //@registerAccess("module_news_design", "Новости - <b>Дизайн</b>");
    //@registerAccess("module_news_edit", "Новости - <b>управление</b>");
    //@registerAccess("module_news_public", "Новости - <b>Публикация</b>");
    return 1;
  }
  function properties(){
    #$this->install();
    $action = $_GET[a];
    $task = $_GET[task];
    if ($action == "")
    $action="viewnews";
    $conf = $this->conf;
    $id = $_GET[id];

    switch ($action){
      case "comments":
        $this->printheader();

        $page = $_GET[page];
        $page=(int) 20*$page;
        $sql = "SELECT count(`ID`) as `coun` FROM $conf[DB_PREFIX]news_comments";
        $result=mysql_query($sql, $conf[DB]);
        $tmp = mysql_fetch_assoc($result);
        $coun = $tmp[coun];

        $sql = "SELECT * FROM $conf[DB_PREFIX]news_comments ORDER BY `DATE` DESC LIMIT $page, 50 ";
        $result=mysql_query($sql, $conf[DB]);
        $coun = (int) $coun/20;
        for ($i=0;$i<=$coun; $i++){
          echo "<a href=\"?page=$i&action=edit&task=comments&module=news\">$i</a> &nbsp;&nbsp;";
        }
        echo "<hr>";
        $_SQL = "SELECT * FROM `$conf[DB_PREFIX]news_comments` ORDER BY `DATE` DESC LIMIT $page, 50";
        $_result=@mysql_query($_SQL, $conf[DB]);
        while ($_comm=@mysql_fetch_assoc($_result)){
          $_NAME= stripslashes($_comm[NAME]);
          //$_MAIL= stripslashes($_comm[MAIL]);
          $_TEXT= stripslashes($_comm[TEXT]);
          //            print_r($_comm);
          $_TEXT = "<a target=blank href=\"../news/$_comm[NEWSID]/\">$_TEXT</a>($_comm[DATE])";
          $_comment .= "<li><input type=checkbox name=\"comment$_comm[ID]\">$_TEXT</li>";
        };
        echo "<form action=\"?a=delcom&module=news\" method=post><ul>$_comment</ul><input type= submit></form>";
        break;
      case "delcom":
        $_SQL = "SELECT * FROM `$conf[DB_PREFIX]news_comments`";
        $_result=@mysql_query($_SQL, $conf[DB]);
        while($_comm=@mysql_fetch_assoc($_result)){
          if ($_POST["comment$_comm[ID]"]=="on"){
            mysql_query("DELETE FROM `$conf[DB_PREFIX]news_comments` WHERE ID=$_comm[ID]", $conf[DB]);
          };
        };
        header("Location: ?a=comments&module=news");

        break;
      case "editouter":
        $this->printheader();
        $tID = $_GET[template];
        if (!$tID)
        die("template error");

        include_once ("core_template.php");
        $template = new Template($_GET[template]);

        $MAIN =& $template->Get("news.main");
        $TEMPLATE_SMALL =& $template->Get("news.small");
        $TEMPLATE_BIG =& $template->Get("news.big");
        $DATE =& $template->Get("news.date");

        $TEMPLATE_COMMENT=& $template->Get("news.comment");

        $TEMPLATE_NAVIGATE_MAIN =& $template->Get("news.navigate.main");
        $TEMPLATE_NAVIGATE_ELEMENT =& $template->Get("news.navigate.element");

        $ARCHIVE_MAIN   =& $template->Get("news.archive.main");
        $ARCHIVE_TEMPLATE =& $template->Get("news.archive.template");
        $ARCHIVE_TOPIC    =& $template->Get("news.archive.topic");
        $ARCHIVE_PAGESA   =& $template->Get("news.archive.pages.acive");
        $ARCHIVE_PAGESI   =& $template->Get("news.archive.pages.inactive");

        ?>
<form method="POST"
  action="?a=saveouter&module=news&template=<?echo $_GET[template] ?>">
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Обрамление Блока</h>
    
    </td>
  </tr>
  <tr>
    <td>Блок<br>
    %main% - содержание блока</td>
    <td width="100%"><TEXTAREA rows="3" style="WIDTH: 100%" name="MAIN"><?=$MAIN;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Шаблон краткого содержания</h>
    
    </td>
  </tr>
  <tr>
    <td><br>
    %TITLE% - заголовок <br>
    %HEADER% - описание <br>
    <nobr>%LINK% - текст ссылки</nobr> <br>
    %DATE% - дата <br>
    %CONTENTLINK% - Ссылка на раскрытие новости прямо тут <br>
    %THISLINK% - Ссылка на раскрытие раздела <br>
    %PATHTHISLINK% Ссылка на раскрытие новости в разделе <br>
    %CONTENT% Полное содержание</td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="TEMPLATE_SMALL"><?=$TEMPLATE_SMALL;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Шаблон полного содержания</h>
    
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%TITLE% - заголовок</nobr> <br>
    <nobr>%HEADER% - описание</nobr> <br>
    <nobr>%LINK% - текст ссылки</nobr> <br>
    <nobr>%CONTENT% - содержание</nobr> <br>
    <nobr>COMMENT_NAME - имя для формы</nobr> <br>
    <nobr>COMMENT_MAIL - почта для формы</nobr> <br>
    <nobr>COMMENT_TEXT - текст для формы</nobr> <br>
    <nobr>%action% - адрес для формы</nobr> <br>
    <nobr>%DATE% - дата</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="TEMPLATE_BIG"><?=$TEMPLATE_BIG;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Шаблон Обсуждения</h>
    
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%NAME% - имя</nobr> <br>
    <nobr>%MAIL% - EMAIL</nobr> <br>
    <nobr>%TEXT% - текст</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="TEMPLATE_COMMENT"><?=$TEMPLATE_COMMENT;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Дата</h>
    
    </td>
  </tr>
  <tr>
    <td>формат даты</td>
    <td width="100%"><TEXTAREA rows="2" style="WIDTH: 100%" name="DATE"><?=$DATE;?></TEXTAREA></td>
  </tr>
</TABLE>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</FORM>
        <?
        break;
case "saveouter":

  $tID = $_GET[template];
  if (!$tID)
  die("template error");
  include_once ("core_template.php");
  $template = new Template($_GET[template]);
  $template->Set("news.main", $_POST[MAIN]);
  $template->Set("news.small", $_POST[TEMPLATE_SMALL]);
  $template->Set("news.big", $_POST[TEMPLATE_BIG]);
  $template->Set("news.date", $_POST[DATE]);

  $template->Set("news.comment", $_POST[TEMPLATE_COMMENT]);

  $template->Set("news.navigate.main", $_POST[TEMPLATE_NAVIGATE_MAIN]);
  $template->Set("news.navigate.element", $_POST[TEMPLATE_NAVIGATE_ELEMENT]);

  $template->Set("news.archive.main", $_POST[ARCHIVE_MAIN]);
  $template->Set("news.archive.template", $_POST[ARCHIVE_TEMPLATE]);
  $template->Set("news.archive.date", $_POST[ARCHIVE_DATE]);
  $template->Set("news.archive.topic", $_POST[ARCHIVE_TOPIC]);
  $template->Set("news.archive.pages.acive", $_POST[ARCHIVE_PAGEA]);
  $template->Set("news.archive.pages.inactive", $_POST[ARCHIVE_PAGEI]);
  //die("asd");

  $template->Save();

  header("Location: properties.php?a=editouter&module=news&template=$_GET[template]");

  die();
  break;
case "viewnews":
  $this->printheader();
  echo "<center>";
  $page = $_GET[page];
  $page=(int) 20*$page;
  $sql = "SELECT count(`ID`) as `coun` FROM $conf[DB_PREFIX]news";
  $result=mysql_query($sql, $conf[DB]);
  $tmp = mysql_fetch_assoc($result);
  $coun = $tmp[coun];


  $sql = "SELECT * FROM $conf[DB_PREFIX]news ORDER BY `DATE` DESC LIMIT $page, 20 ";
  $result=mysql_query($sql, $conf[DB]);
  $coun = (int) $coun/20;
  for ($i=0;$i<=$coun; $i++){
    echo "<a href=\"?page=$i&module=news\">$i</a> &nbsp;&nbsp;";
  }
  echo "<hr>";

  while ($row=mysql_fetch_assoc($result)){
    $tmpID = $row['ID'];

    $sql = "SELECT NAME FROM $conf[DB_PREFIX]newstype WHERE ID=$row[TYPE]";
    $result2=mysql_query($sql, $conf[DB]);
    $type=mysql_fetch_assoc($result2);

    echo "$type[NAME]";
    if ($row['ACTIVE'] == 1){
      echo "<h1>" . stripslashes($row['TITLE']) . "</h1><br>";
    }else{
      echo "<s>" . stripslashes($row['TITLE']) . "</s><br>";
    };
    echo stripslashes($row['HEADER']),
         "<form action = \"?a=editnewsframe&id=$tmpID&module=news\" method=\"post\"><input name=\"submit\" type=\"submit\" class=\"mainoption\" value=\"Изменить($row[ID])\"></form>",
         "<hr>";
  }
  mysql_free_result($result);
  echo "</center>";
  break;
case "addnews":
  $sql
  ="INSERT INTO `$conf[DB_PREFIX]news` ( `ID` , `TITLE` , `HEADER` , `CONTENT` ,`ACTIVE`, `DATE` )" . " VALUES ( '', '', '', '', 0, '')";

  mysql_query($sql, $conf[DB]);
  $id=mysql_insert_id($conf[DB]);
  mysql_close($conf[DB]);
  header("Location: ?a=editnewsframe&id=$id&module=news");
  break;
case "editnewsframe":
case "editnews":
  switch ($task){
    case "":
      $sql="SELECT * FROM $conf[DB_PREFIX]news WHERE ID=$id";
      $result=mysql_query($sql, $conf[DB]);
      echo "<center>";
      $row=mysql_fetch_assoc($result);
      mysql_free_result($result);
      ?>
<html>
<head>
<title></title>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript" src="calendar1.js"></script>
</head>
<body scroll="auto">
<center>
<form name="form" method="post"
  action="?a=editnews&task=commit&id=<?echo $id?>&module=news">
<table width="100%" border="1">
  <tr>
    <td>Теги:</td>
    <td><textarea rows="2" cols="70" name=tags><?php
    $SQL = "select distinct $conf[DB_PREFIX]newstag.name from $conf[DB_PREFIX]newstag, $conf[DB_PREFIX]news, $conf[DB_PREFIX]news_rel_tag  where $conf[DB_PREFIX]newstag.tag_id = $conf[DB_PREFIX]news_rel_tag.tag_id and $conf[DB_PREFIX]news.ID=$id and $conf[DB_PREFIX]news_rel_tag.news_id=$id order by $conf[DB_PREFIX]newstag.name";
    $result=mysql_query($SQL, $conf[DB]) or die(mysql_error());
    while (@$type = mysql_fetch_assoc($result)){
      echo "$type[name],";
    };
    ?></textarea>
  
  </tr>
  <tr>
    <td>Заголовок</td>
    <td width="100%"><textarea name="title" cols="50" rows="1"><?
    echo stripslashes($row['TITLE'])?></textarea></td>
  </tr>
  <tr>
    <td>Краткое содержание</td>
    <td width="100%"><?
    include("fckeditor.php");
    $sBasePath ="./";
    $oFCKeditor = new FCKeditor('header') ;
    $oFCKeditor->BasePath = $sBasePath ;
    $oFCKeditor->Value    = stripslashes($row[HEADER]);
    $oFCKeditor->Create();
    ?></textarea></td>
  </tr>
  <tr>
    <td>Содержание</td>
    <td width="100%"><?
    $sBasePath ="./";
    $oFCKeditor = new FCKeditor('text') ;
    $oFCKeditor->BasePath = $sBasePath ;
    $oFCKeditor->Value    = stripslashes($row[CONTENT]);
    $oFCKeditor->Create();
    ?>
  
  </tr>
  <tr>
    <td>Ключевые слова</td>
    <td width="100%"><textarea name="kw" rows="5" cols="50"><? echo stripslashes($row['KW'])?></textarea></td>
  </tr>
  <tr>
    <td>Описание</td>
    <td width="100%"><textarea name="desc" rows="5" cols="50"><?echo stripslashes($row[DESCR])?></textarea></td>
  </tr>
  <tr>
    <td>Опубликовать?</td>
    <td><? 
    ?> <input type="checkbox" name="active" value="1" class="mainoption"
    <?
    if ($row['ACTIVE'] == "1")
    echo "checked"?>> <?//if
    ?></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Дата</td>
    <td>
    <table align=center width=600 border=0>
    <?

    $today[year]=substr($row[DATE], 0, 4);
    $today[mon]=substr($row[DATE], 5, 2);
    $today[mday]=substr($row[DATE], 8, 2);

    if ($today[year]==0)
    $today = getdate();
    ;
    ?>
      <tr>
        <td class="toleft" nowrap><textarea cools="5" rows="1" name="DATE"
          value=""><?
          echo "$today[mday]-$today[mon]-$today[year]"
          ?></textarea> <a href="javascript:cal1.popup();"> <img
          src="images/cal.gif" width="16" height="16" border="0"></a></td>
      </tr>
    </table>
  
  </tr>
</table>
<hr>
          <?
          $_SQL = "SELECT * FROM `$conf[DB_PREFIX]news_comments` WHERE NEWSID=$id ORDER BY ID";
          $_result=@mysql_query($_SQL, $conf[DB]);
          while ($_comm=@mysql_fetch_assoc($_result)){
            $_NAME= stripslashes($_comm[NAME]);
            $_MAIL= stripslashes($_comm[MAIL]);
            $_TEXT= stripslashes($_comm[TEXT]);
            $_comment .= "<li><input type=checkbox name=\"comment$_comm[ID]\">$_TEXT</li>";
          };
          echo "<ul>$_comment</ul>";
          ?> <input name="submit" type="submit" class="mainoption"
  value="Далее"></form>
<hr>
<p align="left">


<form name="formdd" method="post"
  action="?a=editnews&task=delete&id=<?echo $id?>&module=news"><input
  name="submit" type="submit" class="deloption" value="Удалить"></form>
</p>
<script language="JavaScript">
            var cal1 = new calendar1(document.forms['form'].elements['DATE']);
            cal1.year_scroll = true;
            cal1.time_comp = false;
          </script> <?
          break;
case "delete":
  $sql="delete from $conf[DB_PREFIX]news where ID=$id;";
  mysql_query($sql, $conf[DB]);
  header("Location: ?module=news");
break;
case "addtag":
  $sql = "insert into $conf[DB_PREFIX]news_rel_tag(news_id, tag_id) values($id,$_POST[tags])";
  @mysql_query($sql);
  header("Location: ?a=editnews&id=$id&module=news");
  break;
case "removetag":
  $sql = "delete from $conf[DB_PREFIX]news_rel_tag where news_id=$id and tag_id=$_POST[tags]";
  mysql_query($sql);
  header("Location: ?a=editnews&id=$id&module=news");
  break;
case "commit":
  $title=addslashes($_POST[title]);

  $type = (int) $_POST[RadioGroup];
  $header=addslashes($_POST[header]);
  $text=addslashes($_POST[text]);
  $kw=addslashes($_POST[kw]);
  $desc=addslashes($_POST[desc]);

  $DATE=$_POST['DATE'];
  $newDate = explode("-",$DATE);
  $myD=$newDate[0];//substr($DATE, 0, 2);
  $myM=$newDate[1];//substr($DATE, 3, 2);
  $myY=$newDate[2];//substr($DATE, 6, 4);
  $date="$myY-$myM-$myD 0:0:0";
  $active=0;
  if ($_POST['active'] == "1")
  $active=1;
  $sql
  ="UPDATE $conf[DB_PREFIX]news SET `KW`= '$kw', `DESCR`= '$desc', `TITLE`='$title', `HEADER`='$header', `CONTENT`='$text', `ACTIVE`=$active, `DATE`=\"$date\" where ID=$id";
  mysql_query($sql, $conf[DB]);

  $_SQL = "SELECT * FROM `$conf[DB_PREFIX]news_comments` WHERE NEWSID=$id ORDER BY ID";
  $_result=@mysql_query($_SQL, $conf[DB]);
  while($_comm=@mysql_fetch_assoc($_result)){
    if ($_POST["comment$_comm[ID]"]=="on"){
      mysql_query("DELETE FROM `$conf[DB_PREFIX]news_comments` WHERE ID=$_comm[ID]", $conf[DB]);
    };
  };

  $tags = split(',', $_POST[tags]);
  //clear tags
  $r = mysql_query("delete from $conf[DB_PREFIX]news_rel_tag where news_id = $id") or die(mysql_error());

  foreach($tags as $tag){
    trim($tag);
    $tag = strtolower($tag);
    $res = @mysql_query("select * from $conf[DB_PREFIX]newstag where name = '$tag'");
    $tagb = @mysql_fetch_assoc($res);
    //if !ok - insert tag
    if (! $tagb[tag_id]){
      mysql_query("insert into $conf[DB_PREFIX]newstag(name) values('$tag')" ) or die (mysql_error());
      $tagb[tag_id] = mysql_insert_id();
    }
    //assing tag
    ;
    if (strlen($tag)>=1)
    $r = mysql_query("insert into $conf[DB_PREFIX]news_rel_tag(news_id, tag_id) values($id, $tagb[tag_id])") or die (mysql_error());
  }

  header("Location: ?a=editnews&id=$id&module=news");

  break;
  }
  break;
case "tags":
  //if (!CA("module_news_edit"))
  //  die("Acces Denied");
  $this->printheader();
  echo "<center>";
  $SQL = "SELECT * FROM $conf[DB_PREFIX]newstag";
  $result=mysql_query($SQL, $conf[DB]);
  echo "<form action=\"?module=news&a=edittags\" method=POST><OL>";
  while (@$type = mysql_fetch_assoc($result)){
    echo "<li><input type=text name=\"type$type[tag_id]\" value =\"$type[name]\" size=20>";
  };
  echo "</OL>Добавить Тег: <input type=text name=\"name\" value =\"\" size=20></br>"
  .'<input type=submit value="Применить"></form>'
  ;
  break;
case "edittags":
  $SQL = "SELECT * FROM $conf[DB_PREFIX]newstag";
  $result=mysql_query($SQL, $conf[DB]);
  while (@$tag = mysql_fetch_assoc($result)){
    $newname = $_POST["tag$tag[tag_id]"];
    if ($newname!=""){
      mysql_query("UPDATE $conf[DB_PREFIX]newstag SET name='$newname' WHERE tag_id=tag[tag_id]");
    }else{
      //mysql_query("DELETE FROM $conf[DB_PREFIX]newstype WHERE ID=$type[ID]", $conf[DB]);
    };
  };
  if ($_POST[name]!=""){
    $SQL = "INSERT INTO $conf[DB_PREFIX]newstag(name) values('$_POST[name]')";
    mysql_query($SQL, $conf[DB]);
  };
  header ("Location: ?a=tags&module=news");
  break;

case "type":
  //if (!CA("module_news_edit"))
  //  die("Acces Denied");
  $this->printheader();
  echo "<center>";
  $SQL = "SELECT * FROM $conf[DB_PREFIX]newstype";
  $result=mysql_query($SQL, $conf[DB]);
  echo "<form action=\"?module=news&a=edittype\" method=POST><OL>";
  while (@$type = mysql_fetch_assoc($result)){
    echo "<li><input type=text name=\"type$type[ID]\" value =\"$type[NAME]\" size=20>";
  };
  echo "</OL>Добавить: <input type=text name=\"name\" value =\"\" size=20></br>"
  .'<input type=submit value="Применить"></form>'
  ;
  break;
case "edittype":
  $SQL = "SELECT * FROM $conf[DB_PREFIX]newstype";
  $result=mysql_query($SQL, $conf[DB]);
  while (@$type = mysql_fetch_assoc($result)){
    $newname = $_POST["type$type[ID]"];
    if ($newname!=""){
      mysql_query("UPDATE $conf[DB_PREFIX]newstype SET NAME='$newname' WHERE ID=$type[ID]", $conf[DB]);
    }else{
      mysql_query("DELETE FROM $conf[DB_PREFIX]newstype WHERE ID=$type[ID]", $conf[DB]);
    };
  };
  if ($_POST[name]!=""){
    $SQL = "INSERT INTO $conf[DB_PREFIX]newstype(NAME) values('$_POST[name]')";
    mysql_query($SQL, $conf[DB]);
  };
  header ("Location: ?a=type&module=news");
  break;
    }

    //*******************
  }
  function add(){
    $conf = $this->conf;
    $SQL = "INSERT INTO `$conf[DB_PREFIX]newsblock` values()";
    mysql_query($SQL, $conf[DB]);
    return mysql_insert_id($conf[DB]);
  }
  function del($id){
    $conf = $this->conf;
    $SQL = "DELETE FROM $conf[DB_PREFIX]newsblock WHERE ID=$id";
    mysql_query($SQL, $conf[DB]);
    return 1;
  }
  function renderEx($id, &$template){
    $conf = $this->conf;
    $path = split("/", $_GET[path]);
    if ($path[2]=="addcomment" &&  ($_POST[nobot]==1)){
      if ($_POST[COMMENT_TEXT]!=""){

        $data =& $template->Get("news.stoplist");

        $words = @split($data, "\n");
        foreach($words as $word){
          if (strpos("$_POST[COMMENT_TEXT]", $word))
          die();
        }
        $_SQL = "INSERT INTO `$conf[DB_PREFIX]news_comments` (`DATE`, `NEWSID`, `TEXT`, `NAME`, `MAIL`)"
        ."VALUES (now(), $path[1], '$_POST[COMMENT_TEXT]', '$_POST[COMMENT_NAME]', '$_POST[COMMENT_MAIL]')";
        $res = mysql_query($_SQL, $conf[DB]) or die(mysql_error());
      };
      header ("Location: ./news/$path[1]");
      die();
    };
    if ($path[1]=='rss'){
        header("Content-Type: text/xml");
        ob_start();
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
        echo "<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\">\r\n";
        echo "<channel>\r\n";
        //echo "<title>Muzbazar</title>\r\n";
        //echo "<link>http://www.muzbazar.ru</link>\r\n";
        echo "<description>News</description>\r\n";
        echo "<language>ru-ru</language>\r\n";
        //echo "<copyright>Muzbazar</copyright>\r\n";
        echo "<generator>Easy Engine 4.0</generator>\r\n";
        //echo "<webMaster>info@muzbazar.ru</webMaster>\r\n";
  
  
        $template="<item>\r\n<title>%TITLE%</title>";
        $template.="<link>%LINK%</link>\r\n";
        $template.="<author>Admin</author>\r\n";
        $template.="<comments>%LINK%</comments>\r\n";//<category domain=\"%LINK%\">News</category>
        $template.="<guid isPermaLink=\"true\">%LINK%</guid>";
        $template.="<pubDate>%DATE%</pubDate>";

        $template.="<description><![CDATA[%HEADER%]]></description>";
        $template.="</item>\r\n";

        $SQL2="SELECT * FROM $conf[DB_PREFIX]news WHERE ACTIVE=1 ORDER BY `DATE` DESC LIMIT 0, 20";
        $res = mysql_query($SQL2, $conf[DB]);

        $all="";
        $cattemplate="";
        global $baseaddr;
        
        while ($news=mysql_fetch_assoc($res)){
          //get top-level category
          
          $_TITLE = stripslashes($news[TITLE]);
          $_TITLE= str_replace("\n","", $_TITLE);
          $_TITLE= str_replace("\r","", $_TITLE);
          $_TITLE= str_replace("&","", $_TITLE);
  
          
          $_HEADER  = stripslashes($news[HEADER]);
          $_CONTENT = stripslashes($news[CONTENT]);
          //$_DATE  = date($DATE, strtotime($_source[DATE]));
  
          $_LINK = "$baseaddr/news/$news[ID]/index.html";
  
          $_ret=$template;
          $_ret = str_replace("%DATE%", $news[DATE], $_ret);
          $_ret = str_replace("%TITLE%", $_TITLE, $_ret);
          $_ret = str_replace("%HEADER%", "$_HEADER", $_ret);
          $_ret = str_replace("%LINK%", $_LINK."?partner=$_GET[partner]", $_ret);
          $all.=$_ret;
        };
        if ((int)$_GET[partner]!=0){
            $all = str_replace(".htm\"", ".htm?partner=$_GET[partner]\"", $all);
            $all = str_replace(".html\"", ".html?partner=$_GET[partner]\"", $all);
        }
  
        echo $all;
        echo "</channel>\r\n</rss>\r\n";
        ob_end_flush();
        exit(1);
      }
    if ($path[1]==""){

      $conf = $this->conf;

      $MAIN     =& $template->Get("news.archive.main");
      $TEMPLATE =& $template->Get("news.archive.template");
      $DATE     =& $template->Get("news.date");
      $TOPIC    =& $template->Get("news.archive.topic");
      $PAGESA   =& $template->Get("news.archive.pages.active");
      $PAGESI   =& $template->Get("news.archive.pages.inactive");
      $page = (int)$path[2];

      $SQL="SELECT * FROM $conf[DB_PREFIX]newstype";
      $result=mysql_query($SQL, $conf[DB]);

      $max=0;
      $pagesize=5;
      while ($topic=mysql_fetch_assoc($result)){
        $SQL="SELECT count(ID) as `count` FROM $conf[DB_PREFIX]news WHERE ACTIVE=1 AND `TYPE`=$topic[ID] ORDER By `DATE` DESC";
        $res=mysql_query($SQL, $conf[DB]);
        $ctmp = mysql_fetch_assoc($res);
        if ($max<(int)$ctmp[count])
        $max = $ctmp[count];
      };
      $pagescount =(int)( $max / $pagesize);

      for ($i=0; $i<$pagescount;$i++)
      {
        $_t = " " . 1 + (int)$i . " ";
        if ($i==(int)$_page)
        {
          $str = str_replace("%LINK%", "./news//$i", $PAGESA);
          $str = str_replace("%I%", "$_t", $str);
          $_ptmp .= $str;
        }else{
          $str = str_replace("%LINK%", "./news//$i", $PAGESI);
          $str = str_replace("%I%", "$_t", $str);
          $_ptmp .= $str;
        };
      };
      $start = ($page)*$pagesize;

      $SQL="SELECT * FROM $conf[DB_PREFIX]newstype";
      $result=mysql_query($SQL, $conf[DB]);
      $_top = $TOPIC;

      while ($topic=mysql_fetch_assoc($result)){
        $_top = $TOPIC;
        $_top = str_replace("%NAME%", $topic[NAME], $_top);
        //*********************************
        $_SQL2  ="SELECT * FROM $conf[DB_PREFIX]news WHERE ACTIVE=1 AND `TYPE`=$topic[ID] AND `DATE` <= NOW() ORDER BY `DATE` DESC LIMIT $start, $pagesize";
        $_result=mysql_query($_SQL2, $conf[DB]);
        $newslist = "";
        while ($_news=mysql_fetch_assoc($_result)){
          $_TITLE = stripslashes($_news[TITLE]);
          $_HEADER=stripslashes($_news[HEADER]);
          $_DATE=date($DATE, strtotime($_news[DATE]));
          $_LINK = "./news/$_news[ID]";
          $_ret=$TEMPLATE;
          $_ret = str_replace("%TITLE%", $_TITLE, $_ret);
          $_ret = str_replace("%HEADER%", $_HEADER, $_ret);
          $_ret = str_replace("%LINK%", $_LINK, $_ret);
          $_ret = str_replace("%DATE%", $_DATE, $_ret);
          $newslist .= $_ret;
        }
        //*********************************
        $news.= str_replace("%MAIN%", $newslist, $_top);
      };
      $RETURN= str_replace("%MAIN%", $news, $MAIN);
      $RETURN= str_replace("%PAGES%", $_ptmp, $RETURN);
      return "";#$RETURN;
    }else{

      if ($path[1]=="list"){
        $conf = $this->conf;

        $MAIN     =& $template->Get("news.main");
        $TEMPLATE_SMALL =& $template->Get("news.small");
        $TEMPLATE_BIG =& $template->Get("news.big");
        $DATE     =& $template->Get("news.date");

        $_SQL2="SELECT * FROM $conf[DB_PREFIX]news WHERE ACTIVE=1 AND `TYPE`=$path[2] `DATE` <= NOW() ORDER BY `DATE` DESC";
        $_result=mysql_query($_SQL2, $conf[DB]);
        while ($_news=mysql_fetch_assoc($_result)){
          $_TITLE = stripslashes($_news[TITLE]);
          $_HEADER=stripslashes($_news[HEADER]);
          //    $_CONTENT=stripslashes($_news[CONTENT]);
          $_DATE=date($DATE, strtotime($_news[DATE]));
          $_LINK = "./news/$_news[ID]";
          $_ret=$TEMPLATE_SMALL;
          $_ret = str_replace("%TITLE%", $_TITLE, $_ret);
          $_ret = str_replace("%HEADER%", $_HEADER, $_ret);
          $_ret = str_replace("%LINK%", $_LINK, $_ret);
          $_ret = str_replace("%DATE%", $_DATE, $_ret);
          $RETURN .= str_replace("%main%", $_ret, $MAIN);
        }
        return $RETURN;
      }
      $path[1] = (int)$path[1];
      $SQL = "SELECT * FROM $conf[DB_PREFIX]news WHERE ID=$path[1]";
      $result=mysql_query($SQL, $conf[DB]);
      $news=mysql_fetch_assoc($result);

      //   var_dump ($template);
      //  die("D");
      $MAIN         =& $template->Get("news.main");

      $TEMPLATE_BIG =& $template->Get("news.big");
      $DATE         =& $template->Get("news.date");
      $COMMENT      =& $template->Get("news.comment");

      $_TITLE = stripslashes($news[TITLE]);
      $_HEADER=stripslashes($news[HEADER]);
      $_CONTENT=stripslashes($news[CONTENT]);
      $_DATE=date($DATE, strtotime($news[DATE]));

      $_LINK = "./news/";
      $_ret=$TEMPLATE_BIG;
      global $DATA;

      $_ret = str_replace("%TITLE%", $_TITLE, $_ret);
      $DATA[TITLE] = $_TITLE;
      $DATA[KW]  = stripslashes($_news[KW]);
      $DATA[DESC]  = stripslashes($_news[DESCR]);

      $_LINK = "?module=news&add=$_news[ID]";
      $_ret = str_replace("%HEADER%", $_HEADER, $_ret);
      $_ret = str_replace("%CONTENT%", $_CONTENT, $_ret);
      $_ret = str_replace("%LINK%", $_LINK, $_ret);
      $_ret = str_replace("%action%", "./news/$path[1]/addcomment" , $_ret);
      $_ret = str_replace("%DATE%", $_DATE, $_ret);
      $_comment = "";

      $_SQL = "SELECT * FROM `$conf[DB_PREFIX]news_comments` WHERE NEWSID=$path[1] ORDER BY ID";
      $_result=@mysql_query($_SQL, $conf[DB]);

      while ($_comm=@mysql_fetch_assoc($_result)){
        $_NAME= stripslashes($_comm[NAME]);
        $_MAIL= stripslashes($_comm[MAIL]);
        $_TEXT= stripslashes($_comm[TEXT]);
        $_tmp=" ";
        $_tmp = str_replace("%NAME%", $_NAME, $COMMENT);
        $_tmp = str_replace("%MAIL%", $_MAIL, $_tmp);
        $_tmp = str_replace("%TEXT%", $_TEXT, $_tmp);
        $_comment .= $_tmp;
      };
      $_ret = str_replace("%COMMENT%", $_comment, $_ret);
      return str_replace("%main%", $_ret, $MAIN);
    };
  }
  function render($regionID = 0, $id, &$template) {
	$conf = $this->conf;

	$path_M = split("/", $_GET[path]);
	#print_r($path_M);
	$name_M="";

	$_SQL2 = "SELECT * FROM $conf[DB_PREFIX]newsblock WHERE ID=$id";
	$_result=mysql_query($_SQL2, $conf[DB]);
	$_newsblock=mysql_fetch_assoc($_result);
	mysql_free_result($_result);
	
	$S = "SELECT tag_id from $conf[DB_PREFIX]newsblock_rel_tag where block_id=$id";
	$r = mysql_query($S);
	$tags = "";
	

        $_SQL2="select * from $conf[DB_PREFIX]news, $conf[DB_PREFIX]news_rel_tag where tag_id in (SELECT tag_id from $conf[DB_PREFIX]newsblock_rel_tag where block_id=$id) and ID=news_id ORDER BY `DATE` DESC LIMIT 0, $_newsblock[COUNT]";
        

        $MAIN =& $template->Get("news.main");
        $TEMPLATE_SMALL =& $template->Get("news.small");
        $DATE =& $template->Get("news.date");

        $_result=mysql_query($_SQL2)or die(mysql_error());
/*
$SQL = "select distinct $conf[DB_PREFIX]newstag.name from $conf[DB_PREFIX]newstag, $conf[DB_PREFIX]news, $conf[DB_PREFIX]news_rel_tag  where $conf[DB_PREFIX]newstag.tag_id = $conf[DB_PREFIX]news_rel_tag.tag_id and $conf[DB_PREFIX]news.ID=$id and $conf[DB_PREFIX]news_rel_tag.news_id=$id order by $conf[DB_PREFIX]newstag.name";
*/
#	echo $_SQL2;
	$SQL;
        while ($_news=@mysql_fetch_assoc($_result)){
		$_TITLE = stripslashes($_news[TITLE]);
		$_HEADER=stripslashes($_news[HEADER]);
		$_CONTENT=stripslashes($_news[CONTENT]);
		$_DATE=date($DATE, strtotime($_news[DATE]));
		$_LINK = "./news/$_news[ID]";


		$_ret=$TEMPLATE_SMALL;
		$_ret = str_replace("%CONTENT%", "", $_ret);
		$_ret = str_replace("%TITLE%", $_TITLE, $_ret);
		$_ret = str_replace("%HEADER%", $_HEADER, $_ret);
		$_ret = str_replace("%LINK%", $_LINK, $_ret);

		$_ret = str_replace("%DATE%", $_DATE, $_ret);
		$RETURN .= str_replace("%main%", $_ret, $MAIN);
	};
    return $RETURN;
  }
  function edit() {
    $action = $_GET[a];
    $conf = $this->conf;
    $id = $_GET[id];
    if ($action == "")
    $action="edit";
    switch ($action){
      case "addtag":
        $sql = "insert into $conf[DB_PREFIX]newsblock_rel_tag(block_id, tag_id) values($id,$_POST[tags])";
        @mysql_query($sql);
        header("Location: ?a=edit&id=$id&module=news");
        break;
      case "removetag":
        $sql = "delete from $conf[DB_PREFIX]newsblock_rel_tag where block_id=$id and tag_id=$_POST[tags]";
        mysql_query($sql);
        header("Location: ?a=edit&id=$id&module=news");
        break;
      case "update":
        $navigate = 0;
        $ann = 0;
        $pages =0;
        if ($_POST[ann]=='on')
        $ann = 1;
        if ($_POST[page]=='on')
        $pages = 1;
        if ($_POST[navigate]=='on')
        $navigate = 1;
        $SQL = "UPDATE $conf[DB_PREFIX]newsblock SET `NAVIGATE`=$navigate, `COUNT`=$_POST[count] where ID=$id";
        $r = mysql_query($SQL, $conf[DB]) or die (mysql_error());

        $SQL = "UPDATE $conf[DB_PREFIX]newsblock SET `ANN`=$ann where ID=$id";
        $r = mysql_query($SQL, $conf[DB]) or die (mysql_error());

        $SQL = "UPDATE $conf[DB_PREFIX]newsblock SET `PAGE`=$pages where ID=$id";
        $r = mysql_query($SQL, $conf[DB]) or die (mysql_error());

        $SQL = "DELETE FROM $conf[DB_PREFIX]newsblocklist WHERE BLOCKID=$id";
        mysql_query($SQL, $conf[DB]);
        $SQL = "SELECT * FROM $conf[DB_PREFIX]newstype";
        $result=mysql_query($SQL, $conf[DB]) or die(mysql_error());
        while ($type = mysql_fetch_assoc($result)){
          if ($_POST["chb$type[ID]"]!=''){

            $SQL =  "INSERT INTO $conf[DB_PREFIX]newsblocklist(BLOCKID, TYPEID) values ($id, $type[ID])";
            $r = mysql_query($SQL, $conf[DB]) or die(mysql_error());
          }
        };

        $tags = split(',', $_POST[tags]);
        //clear tags dsadasdas
	#die ($_POST[tags])




        $r = mysql_query("delete from $conf[DB_PREFIX]newsblock_rel_tag where block_id = $id") or die(mysql_error());

        foreach($tags as $tag){
          trim($tag);
          $tag = strtolower($tag);
          $res = @mysql_query("select * from $conf[DB_PREFIX]newstag where name = '$tag'");
          $tagb = @mysql_fetch_assoc($res);
          //if !ok - insert tag
          if (! $tagb[tag_id]){
            mysql_query("insert into $conf[DB_PREFIX]newstag(name) values('$tag')" ) or die (mysql_error());
            $tagb[tag_id] = mysql_insert_id();
          }
          //assing tag
          ;
          if (strlen($tag)>=1)
          $r = mysql_query("insert into $conf[DB_PREFIX]newsblock_rel_tag(block_id, tag_id) values($id, $tagb[tag_id])") or die (mysql_error());
        }

        header("Location: edit.php?a=edit&id=$id&module=news");
        break;
      case "edit":
        $sql = "SELECT * FROM `$conf[DB_PREFIX]newsblock` WHERE ID=$id";
        $result = mysql_query($sql, $conf[DB]);
        $newsblock = mysql_fetch_assoc($result);
        mysql_free_result($result);
        ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css.css" rel="stylesheet" type="text/css">
</head>
<body scroll="auto">
<center>

<form name="forma" action="edit.php?a=update&id=<?=$id?>&module=news"
  method="POST">
<table width="200" border="1">
  <tr>
    <td><INPUT type="text" maxlength="11" name="count"
      value="<?echo $newsblock[COUNT]?>"></td>
    <td>Сколько показывать</td>
  </tr>
  <tr>
    <td><textarea rows="2" cols="70" name=tags><?php
    $SQL = "select distinct $conf[DB_PREFIX]newstag.name from $conf[DB_PREFIX]newstag, $conf[DB_PREFIX]newsblock, $conf[DB_PREFIX]newsblock_rel_tag  where $conf[DB_PREFIX]newstag.tag_id = $conf[DB_PREFIX]newsblock_rel_tag.tag_id and $conf[DB_PREFIX]newsblock.ID=$id and $conf[DB_PREFIX]newsblock_rel_tag.block_id=$id order by $conf[DB_PREFIX]newstag.name";
    $result=mysql_query($SQL, $conf[DB]) or die(mysql_error());
    while (@$type = mysql_fetch_assoc($result)){
      echo "$type[name],";
    };
    ?></textarea>
    </td><td>Теги</td>  
  </tr>
  <tr>
    <td><?
    $SQL = "SELECT * FROM $conf[DB_PREFIX]newstype";
    $result2=mysql_query($SQL, $conf[DB]);
    while (@$type = mysql_fetch_assoc($result2)){
      $SQL = "SELECT count(*) as `count` from $conf[DB_PREFIX]newsblocklist WHERE BLOCKID=$id and TYPEID=$type[ID]";
      $result = mysql_query($SQL, $conf[DB]);
      $nbl = mysql_fetch_assoc($result) or die (mysql_error());
      $ch="";
      if ($nbl[count]>=1)
      $ch ="CHECKED";

      echo "<INPUT type=\"checkbox\" name=\"chb$type[ID]\" $ch> $type[NAME]<br>";
    };
    ?></td>
    
  </tr>
</table>
<center><INPUT type="checkbox" name="navigate"
<?
if ($newsblock[NAVIGATE])
echo "checked";
?>>Навигация<br>
<INPUT type="checkbox" name="ann"
<?
if ($newsblock[ANN])
echo "checked";
?>>Анонс<br>
<INPUT type="checkbox" name="page"
<?
if ($newsblock[PAGE])
echo "checked";
?>>Страницы<br>

<input name="submit" type="submit" class="mainoption" value="Далее">

</FORM>
</center>
<?
break;
    }
  }
  function printheader()
  {
    $templ = $_GET[template];
    echo '<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">'
    .'<center>'
    ."<center><a href=\"?a=viewnews&module=news&template=$templ\">Просмотреть новости</a>&nbsp"
    ."<a href=\"?a=addnews&module=news&template=$templ\">Добавить новость</a>&nbsp"
    ."<a href=\"?a=tags&module=news&template=$templ\" >Теги </a>&nbsp</center>"
//    ."<a href=\"?a=type&module=news&template=$templ\">Темы</a>" 
    .'</center>'
    .'<script>function DoConfirm(message, url){if (confirm(message))location.href = url;}</script>'
    ;
  }
};
$info = array(
  'plugin'      => "news",
  'cplugin'     => "eeNews",
  'pluginName'    => "Новости",
  'ISMENU'      =>0,
  'ISENGINEMENU'    =>0,
  'ISBLOCK'     =>1,
  'ISEXTRABLOCK'    =>1,
  'ISSPECIAL'     =>1,
  'ISLINKABLE'    =>0,
  'ISINTERFACE'   =>0,
);
?>
