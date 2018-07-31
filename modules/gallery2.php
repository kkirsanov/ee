<?php
class eeGallery2{
  var $conf;
  var $T_IMG;
  var $T_GAL;
  var $pics_dir='../pics';
  var $pics_norm = 'normalized';
  var $pics_prew = 'prewiews';
  function getbyid($table, $id){
    $SQL = "SELECT * from $table where ID=$id";
    $res = mysql_query($SQL) or die(mysql_error());
    return mysql_fetch_assoc($res);
    }   


  function eeGallery2($conf)
  {  
  $this->conf = $conf;
  $this->T_IMG = $this->conf[DB_PREFIX]."images";
  $this->T_GAL = $this->conf[DB_PREFIX]."galleries";
  }


  function install(){
    $conf = $this->conf;
    $SQL = "CREATE TABLE `$this->T_GAL`
    (  `ID` int(11) NOT NULL auto_increment,
    `ORDER` int(11) unsigned NOT NULL default '0',
    `NAME` varchar(255) NOT NULL default '',
    `COLLS` int(11) NOT NULL default '0',
    `ROWS` int(11) NOT NULL default '0',
    `SIZEXP` int(11) NOT NULL default '0',
    `SIZEYP` int(11) NOT NULL default '0',
    `SIZEX` int(11) NOT NULL default '0',
    `SIZEY` int(11) NOT NULL default '0',

    `BLOCK` int(11) default NULL,
    PRIMARY KEY  (`ID`)
    ) ENGINE=MyISAM CHARACTER SET utf8;";
    mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$this->T_IMG` (
    `ID` int(11) NOT NULL auto_increment,
    `ORDER` int(10) unsigned NOT NULL default '0',
    `NAME` varchar(255) NOT NULL default '',
    `UNAME` varchar(255) NOT NULL default '',
    `GALLERY` int(255) NOT NULL default '0',
    `DESC` mediumtext,
    `CONTENT` mediumtext,
    `SCALESIZE_X` int(11) default NULL,
    `SCALESIZE_Y` int(11) default NULL,
    `SCALEPERCENT` int(11) default NULL,
    `COLORCORRECT_R` int(11) default NULL,
    `COLORCORRECT_G` int(11) default NULL,
    `COLORCORRECT_B` int(11) default NULL,
    `ADDTEXT` varchar(255) default NULL,
    `ADDTEXT_FONT` varchar(255) default NULL,
    `ADDTEXT_ANGLE` int(11) default NULL,
    `ADDTEXT_X` int(11) default NULL,
    `ADDTEXT_Y` int(11) default NULL,
    `ADDTEXT_TEXTSIZE` int(11) default NULL,
    `ADDTEXT_R` int(11) default NULL,
    `ADDTEXT_G` int(11) default NULL,
    `ADDTEXT_B` int(11) default NULL,
    `CROP_X` int(11) default NULL,
    `CROP_Y` int(11) default NULL,
    `CROP_W` int(11) default NULL,
    `CROP_H` int(11) default NULL,
    `top` int(11) default 0,
    PRIMARY KEY  (`ID`)
    ) ENGINE=MyISAM CHARACTER SET utf8 ;";
    mysql_query($SQL, $conf[DB]);

    $SQL = "CREATE TABLE `$conf[DB_PREFIX]galleryblock` ("
    ."`ID` int(11) NOT NULL auto_increment,"
    ."`GALLERY` int(11) NOT NULL default '0',"
    ."PRIMARY KEY  (`ID`)"
    .") ENGINE=MyISAM CHARACTER SET utf8;" ;
    mysql_query($SQL, $conf[DB]);

    //@registerAccess("module_gallery_design", "Галлерея - <b>Дизайн</b>");
    //@registerAccess("module_gallery_edit", "Галлерея - <b>Управление</b>");

    return 1;
  }


  function properties(){
    $conf = $this->conf;
    require_once('img_funcs.php');
    $image = new Images($conf[DB], $this->T_IMG, $this->T_GAL);
    $maxfilesize = 52428800;
    $pics_dir= $this->pics_dir;
    $pics_norm = $this->pics_norm;
    $pics_prew = $this->pics_prew;
    $norm_w = 0;
    $norm_h = 0;
    $prew_w = 100;
    $prew_h =0;
    //form
    $upload_dir= '../up';
    $filefield = 'filefield';
    $gal= 'gal';
    $normsize = 'normsize';
    $sizeaspect= 'sizeaspect';
    $saveoriginal = 'saveoriginal';
    $prewsize = 'prewsize';
    $psizeaspect = 'psizeaspect';
    $galcolls = 3;
    $galrows = 3;

    $action = $_GET[a];
    if ($action=="")
    { $action="view";  }
    $id = (int)$_GET[id];
    $add =$_GET[add];

    switch ($action)
    { case "view":
      $this->printheader2();
      $gals = $image->GetGalleries();
      if($gals){
        foreach($gals as $gal){
          echo "&nbsp;&nbsp;";
          echo '<a href="JavaScript:DoConfirm(\'Вы действительно зхотите удалить данный раздел?\',\'?a=Gdel&id='.$gal[ID].'&module=gallery2\')"><img src="images/del.gif" border=0></a>';
          echo "<a href=\"?a=Gdown&id=$gal[ID]&module=gallery2\"><img src=\"images/d.gif\" border=0></a>";
          echo "<a href=\"?a=Gup&id=$gal[ID]&module=gallery2\"><img src=\"images/u.gif\" border=0></a>";
          //echo "<a href=\"JavaScript:NW('?a=Glistparent&module=gallery2&id=$row[ID]',370,470)\"><img src=\"images/parent.gif\" border=0></a>";
          echo "<a class=\"phplm2\" href=\"?a=editgallery&id=$gal[ID]&module=gallery2\">$gal[NAME]</a>";
          echo "<br>";
        }
      }else{
        echo "no galleries";
      }
      break;

    case "upload":
      if (isset($_FILES[$filefield])){
        if (isset($_GET[gid]))
        $galleryid = $_GET[gid];

        elseif (isset($_POST[$gal]))
        { if ((isset($_POST[$galcolls])) and (is_numeric($_POST[$galcolls])))
        { $galcolls = $_POST[$galcolls];  }
        if ((isset($_POST[$galrows])) and (is_numeric($_POST[$galrows])))
        { $galrows = $_POST[$galrows];    }
        $galleryid = $image->CreateGallery($_POST[$gal], $galcolls, $galrows);
        }
          

        $prew_w = (int)$_POST["SIZEP"];
        if ($_POST["directionP"]=="Y"){
          $prew_h = $prew_w;
          $prew_w =0;
        };

        $norm_w = (int)$_POST["SIZEN"];
        if ($_POST["directionN"]=="Y"){
          $norm_h = $norm_w;
          $norm_w =0;
        };

        //$norm_h = (int)$_POST["SIZEY"];
        //$prew_w = (int)$_POST["SIZEXP"];
        //$prew_h = (int)$_POST["SIZEYP"];

        if ($galleryid){
          if ($norm_w!=0 || $norm_h!=0 ){
            $SQL = "UPDATE $conf[DB_PREFIX]galleries set SIZEX=$norm_w,SIZEY=$norm_h,SIZEXP=$prew_w,SIZEYP=$prew_h WHERE ID=$galleryid";
            mysql_query($SQL,$conf[DB]);
          }else{
            $SQL = "SELECT * FROM $conf[DB_PREFIX]galleries WHERE ID=$galleryid";
            $r = mysql_query($SQL);
            $dat = mysql_fetch_assoc($r);
            //var_dump($dat);
            $norm_w = (int)$dat["SIZEX"];
            $norm_h = (int)$dat["SIZEY"];
            $prew_w = (int)$dat["SIZEXP"];
            $prew_h = (int)$dat["SIZEYP"];
          }
        };
        $id= $image->GetImg($filefield, $maxfilesize, $upload_dir, $pics_dir, $pics_norm, $pics_prew, $norm_w, $norm_h, $prew_w, $prew_h, $galleryid);

        header("Location: ?a=editgallery&id=$galleryid&module=gallery2");
      }
      else
      { $this->printheader2();
      echo '<form enctype="multipart/form-data"  name="upload" action ="?a=upload&module=gallery2" method="post">
                    <input name="'.$gal.'" type="text" size=30 value="Заголовок Новой Галлереи"><br><br>
                    Количество изображений по вертикали &nbsp;&nbsp;&nbsp;  - <input name="'.$galcolls.'" type="text" size=3 value=4><br>
                    Количество изображений по горизонтали - <input name="'.$galrows.'" type="text" size=3 value=4><br>
                      <br>
                      Размер превью изображения  <input name="SIZEP" type="text" size=3 value=100>
                        по ширине<input type="radio" name="directionP" value="X" checked="true"/>
            по высоте<input type="radio" name="directionP" value="Y"/>
              <br>
              Размер изображения  <input name="SIZEN" type="text" size=3 value=800>
                по ширине<input type="radio" name="directionN" value="X" checked="true"/>
          по высоте<input type="radio" name="directionN" value="Y"/>
              <br>

                    Выберите файл:    <input name="'.$filefield.'" type="file"> <input type="submit" value="Загрузить" class = "mainoption"><br>(.zip, .jpeg, .jpg, .png, .gif)
                    </form>';
      }
      break;

    case "editgallery":
      $this->printheader2();
      $gal=$image->GetGalleryInfo($_GET[id]);
      ?>
<html>
<head>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel='stylesheet' type='text/css' href='normal.css'>
<script language="JavaScript" src="calendar1.js"></script>
<script>
    function DoConfirm(message, url)
           { if (confirm(message))   location.href = url;  };
        function NW(adr, w, h)
        {  win=window.open(adr,"_blank","toolbar=no,location=no,directories=no,status=no,menubar=no,width="+ w + ",height="+h);
         win.Target = document.forma.Target;
        };
                     </script>
<link href="css.css" rel="stylesheet" type="text/css">
</head>
<body scroll="auto">
<form name="form" action="?a=update&id=<?=$_GET[id]?>&module=gallery2"
  method="post">Название - <input name="name" type="text"
  value="<?=$gal[NAME]?>" size=50><br>
Количество изображений по вертикали - <input type="text" name="W"
  size="3" value="<?=$gal[COLLS]?>"><br>
Количество изображений по горизонтали - <input type="text" name="H"
  size="3" value="<?=$gal[ROWS]?>"><br>
<center><input type="submit" value="Принять изменения"
  class="mainoption"></center>
</form>
<hr>
      <? echo '<form enctype="multipart/form-data" name="upload" action="?a=upload&gid='.$_GET[id].'&module=gallery2" method=post>
                         Выберите файл (.zip, .jpeg, .jpg, .png, .gif): <input name="'.$filefield.'" type="file">
                         <input type="submit" value="Загрузить"> </form> <hr><hr>';

      $imgs= $image->ShowGallery($_GET[id]);
      if($imgs)
      {
        $i =0;
        foreach($imgs as $img)
        {
            $i++;
            
            //$SQL="update ".$this->T_IMG." set `ORDER` = $i where ID=$img[ID]";
            //$z = mysql_query($SQL) or die(mysql_error());
            /*
<textarea name='desc' cols=30 rows=5>".$img[DESC]."</textarea>
                    <textarea name='content' cols=30 rows=5>".$img[CONTENT]. "</textarea>
*/

        $st = "<input type=checkbox name=top>";
        if ($img[top])
            $st = "<input type=checkbox checked name=top$img[ID]>";
          echo "$img[ORDER]<img src='".$pics_dir.'/'.$ownerid.'/'.$pics_prew.'/'.$img[NAME]."'>
                    <form name='Form' action='?a=setinfo&id=".$img[ID]."&gal=$img[GALLERY]&module=gallery2' method='post'>
          <input name='name' type='text' size=40 value=\"".$img[DESC]."\"><br> Обложка: $st
                    <br>
                    <input type=submit value='Принять'></form>";
          echo "<a href=\"javascript:DoConfirm('Вы действительно хотите удалить эту картинку?','?a=Idel&id=$img[ID]&gal=$img[GALLERY]&module=gallery2')\">
          <img src=\"images/del.gif\" border=0></a>
          <a href=\"?a=down&id=$img[ID]&gal=$img[GALLERY]&module=gallery2\"><img src=\"images/d.gif\" border=0></a>
          <a href=\"?a=up&id=$img[ID]&gal=$img[GALLERY]&module=gallery2\"><img src=\"images/u.gif\" border=0></a><br><br>";
        }
      }
      echo "</body></html>";
      break;

case "setinfo":
  $image->SetImageInfo($_GET[id],$_POST[name],'');
  $t = $this->T_IMG;
  
  
  if ($_POST[top]){
    $i = $this->getbyid($t, $_GET[id]);  
    $SQL = "update ".$this->T_IMG." set `top` = 0 where GALLERY=$i[GALLERY]";
    $r = mysql_query($SQL) or die(mysql_error());
    $SQL = "update ".$this->T_IMG." set `top` = 1 where ID=$i[ID]";
    mysql_query($SQL) or die(mysql_error());
  }

  header("Location: ?a=editgallery&id=$_GET[gal]&module=gallery2");
    
  break;

case "Gdel":
  $image->DeleteGallery($_GET[id], $pics_dir, $pics_norm, $pics_prew);
  header("Location: ?module=gallery2");
  break;

case "Idel":
  $image->DeleteImg($_GET[id],$pics_dir, $pics_norm, $pics_prew );
  header("Location: ?a=editgallery&id=$_GET[gal]&module=gallery2");
  break;

case "update":
  $image->SetGalleryInfo($_GET[id],$_POST[name], $_POST[W],$_POST[H]);
  header("Location: ?a=editgallery&id=$_GET[id]&module=gallery2");
  break;

case "up":
  
  $image->UpImgOrder($_GET[id]);
  header("Location: ?a=editgallery&id=$_GET[gal]&module=gallery2");
  break;

case "down":
  $image->DownImgOrder($_GET[id]);
  header("Location: ?a=editgallery&id=$_GET[gal]&module=gallery2");
  break;

case "Gup":
  $image->UpGalleryOrder($_GET[id]);
  header("Location: ?module=gallery2");
  break;

case "Gdown":
  $image->DownGalleryOrder($_GET[id]);
  header("Location: ?module=gallery2");
  break;

case "template":

  $this->printheader2();
  $tID = $_GET[template];
  if (!$tID)
  die("template error");

  include_once ("core_template.php");
  $template = new Template($_GET[template]);

  $WINDOW =& $template->Get("gallery.window");
  $PAGES =& $template->Get("gallery.pages");
  $BIGRAW =& $template->Get("gallery.raw.big");

  $BIGTEMPLATE=& $template->Get("gallery.big.main");
  $BIGELEMENT=& $template->Get("gallery.big.element");
  $SMALLTEMPLATE=& $template->Get("gallery.small.main");
  $SMALLELEMENT=& $template->Get("gallery.small.element");
  $SMALLRAW=& $template->Get("gallery.raw.small");

  ?>
<form method="POST"
  action="?a=save&module=gallery2&template=<?echo $_GET[template] ?>">
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Шаблон Списка Галерей</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%title% - Заголовок Галереи</nobr> <br>
    <nobr>%main% - Набор Элементов</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="BIGTEMPLATE"><?echo $BIGTEMPLATE;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Элемент Списка Галлерей</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%title% - название галлереи</nobr> <br>
    <nobr>%image% - адрес картинки</nobr> <br>
    <nobr>%src% - адрес показа</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="BIGELEMENT"><? echo $BIGELEMENT;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#ECECEC">
  <tr>
    <td colspan="2" align="center">
    <h2>Окружение строки элементов Списка Галлерей</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%main% - соджержание строки</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="BIGRAW"><?=$BIGRAW;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Шаблон</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%title% - заголовок галлереи</nobr> <br>
    <nobr>%main% - набор элементов</nobr> <br>
    <nobr>%pages% - страницы</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="SMALLTEMPLATE"><?echo $SMALLTEMPLATE;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Элемент</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%title% - название галереи</nobr> <br>
    <nobr>%image% - адрес картинки</nobr> <br>
    <nobr>%src% - адрес показа</nobr> <br>
    <nobr>%desc% - краткое содержание</nobr> <br>
    <nobr>%content% - полное содержание</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="SMALLELEMENT"><?echo $SMALLELEMENT;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Окружение строки элементов</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%main% - содержание строки</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%"
      name="SMALLRAW"><?echo $SMALLRAW;?></TEXTAREA></td>
  </tr>
</TABLE>
<TABLE border="1" width="90%" bgcolor="#CCCCCC">
  <tr>
    <td colspan="2" align="center">
    <h2>Всплывающее Окно</h2>
    </td>
  </tr>
  <tr>
    <td><br>
    <nobr>%title% - название галлереи</nobr> <br>
    <nobr>%image% - адрес картинки</nobr> <br>
    <nobr>%desc% - краткое содержание</nobr> <br>
    <nobr>%content% - полное содержание</nobr></td>
    <td width="100%"><TEXTAREA rows="13" style="WIDTH: 100%" name="WINDOW"><?echo $WINDOW;?></TEXTAREA></td>
  </tr>
</TABLE>
<center><INPUT type="submit" value="Принять" class="mainoption"></center>
</FORM>
  <?
  break;
case "save":
  $tID = (int)$_GET[template];
  if (!$tID) die("template error");
  include_once ("core_template.php");
  $template = new Template($tID);

#  print_r($_POST);
#  die();
    
  $template->Set("gallery.window", $_POST[WINDOW]);
  $template->Set("gallery.pages", $_POST[PAGES]);

  $template->Set("gallery.big.main", $_POST[BIGTEMPLATE]);
  $template->Set("gallery.big.element", $_POST[BIGELEMENT]);
  $template->Set("gallery.small.main", $_POST[SMALLTEMPLATE]);
  $template->Set("gallery.small.element", $_POST[SMALLELEMENT]);

  $template->Set("gallery.raw.big", $_POST[BIGRAW]);
  $template->Set("gallery.raw.small", $_POST[SMALLRAW]);

  $template->save();

  header("Location: ?a=template&module=gallery2&template=$_GET[template]");
  break;
    }
  }

  function printheader()
  { ?>
<html>
<head>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script>function DoConfirm(message, url){if (confirm(message))location.href = url;}
  function NW(adr, h, w){
      win=window.open(adr,"_blank","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,width="+ w + ",height="+h);
        win.parentw = window;
}
     </script>


<body>
<center><a href="?a=view&module=gallery2">Просмотреть список галлерей</a>&nbsp;
<a href="?a=upload&module=gallery2">Добавить галлерею</a>&nbsp;
  <br>
  <?
  }

function printheader2()
  { ?>
<html>
<head>
<link href="css.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script>function DoConfirm(message, url){if (confirm(message))location.href = url;}
            function NW(adr, h, w){
                  win=window.open(adr,"_blank","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,width="+ w + ",height="+h);
                  win.parentw = window;
                  }
            </script>


<body>
  <?
  }
  function add()
  {
    $conf = $this->conf;
    $SQL = "INSERT INTO `$conf[DB_PREFIX]galleryblock` values()";
    mysql_query($SQL, $conf[DB]);
    return mysql_insert_id($conf[DB]);
  }

  function del($id)
  {  return 1;
  }

  function renderEx($id, &$template)
  {
    $conf = $this->conf;
    $path = split("/", $_GET[path]);
    $baseaddr =&$template->Get("gallery.base");

    $this->BIGTEMPLATE      =&$template->Get("gallery.big.main");
    $this->BIGELEMENT       =&$template->Get("gallery.big.element");
    $this->SMALLTEMPLATE    =&$template->Get("gallery.small.main");
    $this->SMALLELEMENT     =&$template->Get("gallery.small.element");
    $this->SMALLRAW         =&$template->Get("gallery.raw.small");
    $this->BIGRAW           =&$template->Get("gallery.raw.big");

    $pics_dir= substr($this->pics_dir, strpos($this->pics_dir, "/")+1);
    $pics_norm = $this->pics_norm;
    $pics_prew = $this->pics_prew;

    if ($path[1] == "")
    { header ("Location: ./");
    };
    
    if (($path[1]!="")&&(0 ==(int)$path[1]))
    {  $WINDOW=&$template->Get("gallery.window");
    $_tmp .= $WINDOW;
    $SQL = "SELECT * FROM $this->T_IMG WHERE ID = $path[2]";
    @$gallery= mysql_fetch_assoc(mysql_query($SQL, $conf[DB]));

    $_tmp = str_replace("%title%", $gallery[UNAME], $_tmp);
    $_tmp = str_replace("%desc%", $gallery[DESC], $_tmp);
    $_tmp = str_replace("%content%", $gallery[CONTENT], $_tmp);

    $img = $baseaddr.$pics_dir.'/'.$pics_norm.'/'.$gallery[NAME];
    $_tmp = str_replace("%image%", $img, $_tmp);
    
    echo  $_tmp;
    flush();
    die();
    }
    
    else  //path 1 !=0,
    { $_SQL="SELECT * FROM  $this->T_GAL WHERE ID=$path[1]";
    $_result=mysql_query($_SQL, $conf[DB]);
    $_gallery=mysql_fetch_assoc($_result);

    $_H =  $_gallery[ROWS];
    $_W =  $_gallery[COLLS];

    $_SQL="SELECT count(ID)  as `coun` FROM  $this->T_IMG WHERE GALLERY=$_gallery[ID] ORDER by `ORDER`";
    $_result=mysql_query($_SQL, $conf[DB]);
    $_ctmp = mysql_fetch_assoc($_result);
    $_count = $_ctmp[coun];
    $_pagesize = $_H* $_W;
    $_pagescount =(int)( $_count/ $_pagesize);

    $_page = (int)$path[2];

    for ($i=0; $i<=$_pagescount;$i++)
    { $_t = " " . 1 + (int)$i . " ";
    if ($i==(int)$_page)
    {  $_ptmp .= "<a href=\"/gallery2/$path[1]/$i\"><b>$_t</b></a>";
    }else
    {  $_ptmp .= "<a href=\"/gallery2/$path[1]/$i\">$_t</a>";
    };
    };

    $_start = ($_page)*$_pagesize;
    $_SQL="SELECT * FROM $this->T_IMG WHERE GALLERY=$_gallery[ID]  ORDER by `ORDER` LIMIT $_start, $_pagesize";
    if ($_result=mysql_query($_SQL, $conf[DB]))
    {  $_counterH=0;
    $_counterW=0;
    $_ret="";
    $_tmp="";
    while ($_im=mysql_fetch_assoc($_result))   //array of gal images
    {
      $_SRC = "";
      $_IMAGE = "";
      if ($_counterH>=$_H)
      {
        $_counterH=0;
        $_counterW++;
        $_ret .= $this->SMALLRAW;
        $_ret = str_replace("%main%", $_tmp, $_ret);
        $_tmp="";
      };

      $_IMAGE =  './'.$pics_dir.'/'.$pics_prew.'/'.$_im[NAME];
      $_SRC =  "/gallery2/view/$_im[ID]";

      $_counterH++;
      $_tmp .= $this->SMALLELEMENT;
      $_tmp = str_replace("%title%", $_im[UNAME], $_tmp);
      $_tmp = str_replace("%desc%", $_im[DESC], $_tmp);
      $_tmp = str_replace("%content%", $_im[CONTENT], $_tmp);
      $_tmp = str_replace("%src%", $_SRC, $_tmp);
      $_tmp = str_replace("%image%", $_IMAGE, $_tmp);

    };
    $_ret .= $this->SMALLRAW;
    $_ret = str_replace("%main%", $_tmp, $_ret);
    $_RETURN = str_replace("%title%", $_TITLE, $this->SMALLTEMPLATE);
    $_RETURN = str_replace("%pages%", $_ptmp, $_RETURN);
    $_RETURN = str_replace("%main%", $_ret, $_RETURN);
    }
    $RETURN = str_replace("%main%", $_RETURN, $this->BIGTEMPLATE);
    $RETURN = str_replace("%title%", $_gallery[TITLE], $RETURN);
    return $RETURN;
    }
  }

  function render($regionID = 0, $id, &$template)
  {

    $pics_dir= substr($this->pics_dir, strpos($this->pics_dir, "/")+1);
    $pics_norm = $this->pics_norm;
    $pics_prew = $this->pics_prew;

    $conf = $this->conf;
    $sql = "SELECT * FROM `$conf[DB_PREFIX]galleryblock` WHERE ID=$id";
    $result = mysql_query($sql, $conf[DB]);
    $galleryblock = mysql_fetch_assoc($result);
    $id= $galleryblock[GALLERY];
    $baseaddr=&$template->Get("gallery.base");

    $this->BIGTEMPLATE       =&$template->Get("gallery.big.main");
    $this->BIGELEMENT        =&$template->Get("gallery.big.element");
    $this->SMALLTEMPLATE     =&$template->Get("gallery.small.main");
    $this->SMALLELEMENT      =&$template->Get("gallery.small.element");
    $this->SMALLRAW          =&$template->Get("gallery.raw.small");
    $this->BIGRAW            =&$template->Get("gallery.big.raw");
   
$_SQL="SELECT * FROM  $this->T_GAL WHERE ID=$id";
    $_result=mysql_query($_SQL, $conf[DB]);
    $_gallery=mysql_fetch_assoc($_result);

    $_W = $_gallery[COLLS];
    $_H = $_gallery[ROWS];
    $_pagesize = $_H* $_W;

    $_SQL="SELECT * FROM  $this->T_IMG WHERE GALLERY=$_gallery[ID] ORDER by `ORDER` LIMIT 0, $_pagesize";

    if ($_result=mysql_query($_SQL, $conf[DB]))
    {  $_counterH=0;
    $_counterW=0;
    $_ret="";
    $_tmp="";
    while ($_im=mysql_fetch_assoc($_result)) //
    {  
        $_SRC = "";
        $_IMAGE = "";
        if ($_counterH>=$_H)
        {  
            $_counterH=0;
            $_counterW++;
            $_ret .= $this->SMALLRAW;
            $_ret = str_replace("%main%", $_tmp, $_ret);
            $_tmp="";
        };

        $_IMAGE = $baseaddr.$pics_dir.'/'.$pics_prew.'/'.$_im[NAME];
        $_SRCBIG = $baseaddr.$pics_dir.'/'.$pics_norm.'/'.$_im[NAME];
        $_SRC   = "/gallery2/view/".$_im[ID];
        $_ptmp = '<a href="/gallery2/'.$id.'"/">view all..</a>';
        $_counterH++;
        $_tmp .= $this->SMALLELEMENT;
        $_tmp = str_replace("%title%", $_im[UNAME], $_tmp);
        $_tmp = str_replace("%desc%", $_im[DESC], $_tmp);
        $_tmp = str_replace("%id%", $_im[ID], $_tmp);
        $_tmp = str_replace("%content%", $_im[CONTENT], $_tmp);
        $_tmp = str_replace("%src%", $_SRC, $_tmp);
        $_tmp = str_replace("%srcbig%", $_SRCBIG, $_tmp);
        $_tmp = str_replace("%image%", $_IMAGE, $_tmp);
    };

    
    $_ret .= $this->SMALLRAW;
    $_ret = str_replace("%main%", $_tmp, $_ret);
    $_RETURN = str_replace("%title%", $_TITLE, $this->SMALLTEMPLATE);
    $_RETURN = str_replace("%pages%", $_ptmp, $_RETURN);
    $_RETURN = str_replace("%main%", $_ret, $_RETURN);
    }
    
    $RETURN = str_replace("%main%", $_RETURN, $this->BIGTEMPLATE);
    $RETURN = str_replace("%title%", $_gallery[TITLE], $RETURN);
    return $RETURN;
  }

  function edit()
  { $action = $_GET[a];
  $conf = $this->conf;
  $id = $_GET[id];
  if ($action == "")
  { $action="edit";}

  switch ($action)
  { case "edit":
    ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css.css" rel="stylesheet" type="text/css">
</head>
<body scroll="auto">
<center><?

$sql = "SELECT * FROM `$conf[DB_PREFIX]galleryblock` WHERE ID = $_GET[id]";
@$result = mysql_query($sql, $conf[DB]);
@$block = mysql_fetch_assoc($result);
$sql = "SELECT `ID`, `NAME` FROM $this->T_GAL ORDER BY `ORDER` ASC";
@$result = mysql_query($sql, $conf[DB]);
while (@$row = mysql_fetch_assoc($result))
{  if ($block[GALLERY]==$row[ID])
{  echo "<a href=\"edit.php?a=update&id=$_GET[id]&galleryid=$row[ID]&module=gallery2\"><strong>$row[NAME]</strong></a>";
}
else
{  echo "<a href=\"edit.php?a=update&id=$_GET[id]&galleryid=$row[ID]&module=gallery2\">$row[NAME]</a>";
}
echo "<br>";
}
@mysql_free_result($result);

?></center>
<?
break;
case "update":
  $sql = "UPDATE `$conf[DB_PREFIX]galleryblock` SET `GALLERY` = $_GET[galleryid] WHERE ID=$id";
  $result = mysql_query($sql, $conf[DB]) or die (mysql_error());
  header("Location: edit.php?a=edit&id=$id&module=gallery2");
  break;
  }

  }

};
$info = array(
        'plugin'    => "gallery2",
        'cplugin'      => "eeGallery2",
        'pluginName'       => "Галерея",
        'ISMENU'          =>1,
        'ISENGINEMENU'  =>1,
        'ISBLOCK'       =>1,
        'ISEXTRABLOCK'   =>1,
        'ISSPECIAL'       =>1,
        'ISLINKABLE'      =>1,
        'ISINTERFACE'     =>1,
);
?>
