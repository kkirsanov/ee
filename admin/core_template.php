<?
include_once ("file_get_contents.php");
include_once ("file_put_contents.php");

class Templater{
  var $fname;
  var $template;
  function Templater($fname){
    $this->fname = $fname;
    $this->template = json_decode(file_get_contents($fname), true);
  }
  function save()
  {
    file_put_contents($this->fname, json_encode($this->template));
  }
  function GetTemplate($template, $path){
    if (array_key_exists ( $path, $template)){

    }else{
      $template[$path]='';
    }
   return $template[$path];
  }
  function Set($path, $value){
    $this->template[$path]=$value;
  }
  function Get($path){
    return $this->GetTemplate($this->template, $path);
  }
}

class Template
{
  var $name;
  var $_fname;
  var $count;
  var $content;
  var $registrated;
  var $description;
  var $locations;
  var $id;

  function backup($id){
    return ;
    $fname = $this->_fname;
    $path = "../skins/$fname";

    $archive_dir = "./";
    $src_dir = "$path/";

    $zip = new ZipArchive();
    $fileName = "./backup_$id"."-".date('j_m_Y_h_m_s').".zip";
    //if ( !== true) {
    // return "Error while creating archive file";
    //};

    $dirHandle = opendir($path);
    while (false !== ($file = readdir($dirHandle))) {
      $zip->addFile($src_dir.$file, $file);
    }
    $zip->close();
    return  "<h3>Создана резервная копия - <a href='./$fileName'>$fileName (сохранить на диск) </a></h3><br>" ;
  }
  function edit($id){
    $_skin= $this->Get("main.skin");
    $_count= $this->Get("main.count");
    $_meta= $this->Get("main.meta");

    $this->skin = stripslashes($_skin);
    $this->meta = stripslashes($_meta);
    $this->count = (int)$_count;

    $temp = '<form method = "post" action = "?a=templates&s=update&template=' . $id .'">';
    $temp .='<center><input type=text name="count" value="'. $this->count .'"><br></center>';
    $temp .='<textarea name="skin" style="WIDTH: 100%; HEIGHT: 450px">' . $this->skin . '</textarea><br>';
    $temp .='META:<br><textarea name="meta" style="WIDTH: 100%; HEIGHT: 120px">' . $this->meta . '</textarea>';

    for ($i=1;$i<=$this->count;$i++){
      $tmp =$this->Get("main.locations.$i");
      $temp .="%BLOCK$i% <input type=text name=location$i value=\"" . $tmp ."\"><br>";
    }
    $temp .='<center><input type=submit value="Принять"></center></form>';

    return  $temp;
  }

  function LoadFromDisk($fname){
    $this->_fname = $fname;
    global $conf;
    include ("path.php");
    @$data = file_get_contents("$conf[skin_path]/$fname/template.dat");
    $this->template = json_decode($data, true);

    $SQL = "select count(ID) as reg FROM $conf[DB_PREFIX]templates where PATH='$fname'";
    $result = mysql_query($SQL, $conf[DB]);
    $isreg = mysql_fetch_assoc($result);
    $this->registrated =  (int)$isreg[reg];

    $SQL = "select * FROM $conf[DB_PREFIX]templates where PATH='$fname'";
    $result = mysql_query($SQL, $conf[DB]);
    $tmp = mysql_fetch_assoc($result);
    $this->id = $tmp[ID];
    $tmp = $this->Get("main.count");
    $this->count = (int)$tmp;

  }
  function Set($path, $value){
    $this->template[$path]=$value;
  }
  function Get($path){
    return $this->GetTemplate($this->template, $path);
  }
  function GetTemplate($template, $path){
    if (array_key_exists ( $path, $template)){
    }else{
      $template[$path]='';
    }
   return $template[$path];

  }
  function Template ($fname)
  {
    global $conf;
    if ((int)$fname && strlen("$fname")<7){
      $SQL = "select * FROM $conf[DB_PREFIX]templates where ID=$fname";
      $result = mysql_query($SQL, $conf[DB]);
      $template = @mysql_fetch_assoc($result);
      @$this->LoadFromDisk($template[PATH]);
    }else{
      @$this->LoadFromDisk($fname);
    }
  }

  function  install()
  {
    global $conf;
    include ("path.php");
    $this->registrated = 1;
    $this->save();
    $SQL = "INSERT INTO $conf[DB_PREFIX]templates (NAME, PATH) values ('$this->name', '$this->_fname')";
    mysql_query($SQL, $conf[DB]);
    return 1;
  }
  function save()
  {
    global $conf;
    include ("path.php");
    file_put_contents("$conf[skin_path]/$this->_fname/template.dat", json_encode($this->template));
  }
};

class Templates
{
  var $data;
  var $templates = array ();

  function commitrestore($id){
    global $conf;
    $SQL = "SELECT * FROM $conf[DB_PREFIX]templates WHERE ID=$id";
    $result=mysql_query($SQL, $conf[DB]);
    $template=mysql_fetch_assoc($result);
    $filehandle=@fopen($_FILES['file']['tmp_name'], "rb");
    if($filehandle){
      $name=$_FILES['file']['name'];
      $mime=$_FILES['file']['type'];

      if ($mime!='application/zip'){
        die("Format error");
      };

      $pathtounpack = "../skins/$template[PATH]";

      $zip = new ZipArchive;
      $res = $zip->open($_FILES['file']['tmp_name']);
      if ($res === TRUE) {
        echo 'ok';
        $zip->extractTo($pathtounpack);
        $zip->close();
      } else {
        echo 'failed, code:' . $res;
      }
      die ('Сделано');
    };
    echo "oblom";
  }
  function restore($id){
    //show form
    ?>
<html>
<head>
<title></title>
</head>
<body>
<center>
<form method="post"
  action="index.php?template=<?echo $_GET[template]; ?>&a=templates&s=commitrestore"
  enctype="multipart/form-data"><input type="File" name="file"> <br>
<input type="submit" value="Загрузить файл""></form>
</center>
    <?
    die();
  }


  function backup($id)
  {
    global $conf;
    $SQL = "SELECT * FROM $conf[DB_PREFIX]templates WHERE ID=$id";
    $result=mysql_query($SQL, $conf[DB]);
    $template=mysql_fetch_assoc($result);

    $temp=new Template($template[PATH]);
    return $temp->backup($id);
  }
  function Templates()
  {
    global $conf;
    include ("path.php");
    $i =0;
    $d = dir("$conf[skin_path]/");

    while (false !== ($entry = $d->read()))
    if ($entry != "." && $entry != ".."){
      $template = new Template($entry);
      $this->templates[$template->id] = $template;
    }
    $d->close();
  }
  function getUnregistrated()
  {
    global $conf;
    $this->data= "";
    foreach ( $this->templates as $template)
    {
      if (!$template->registrated){
        $this->data .= "<a href=\"?a=templates&s=install&name=$template->_fname\">$template->name</a> <br>";
      };
    };
    return $this->data;
  }

  function getRegistrated()
  {
    global $conf;
    $this->data= "";
    $SQL = "SELECT * FROM `$conf[DB_PREFIX]templates`";
    $res =  mysql_query($SQL, $conf[DB]);
    while ($row=mysql_fetch_assoc($res))
    {
      $this->data .= "<a href=\"?a=templates&s=edit&template=$row[ID]\">$row[ID] - $row[NAME]</a><br>";
    };
    return $this->data;
  }

  function edit($id)
  {

    global $conf;
    $SQL = "SELECT * FROM $conf[DB_PREFIX]templates WHERE ID=$id";
    $result=mysql_query($SQL, $conf[DB]);
    $template=mysql_fetch_assoc($result);

    $temp=new Template($template[PATH]);
    return $temp->edit($id);
  }

  function update ($id, $text, $count, $meta)
  {
    global $conf;
    $SQL = "SELECT * FROM $conf[DB_PREFIX]templates WHERE ID=$id";
    $result=mysql_query($SQL, $conf[DB]);
    $template=mysql_fetch_assoc($result);

    $temp=new Template($template[PATH]);
    $temp->Set("main.skin", $text);
    $temp->Set("main.meta", $_POST[meta]);
    $temp->Set("main.count", $count);

       

    for ($i=1;$i<=$_count ; $i++){
      $temp->Set("main.locations.$i",$_POST["location$i"]);
    }
    $temp->save();
  }
  function createondisk()
  {
    global $conf;
    $TEMP = "";
    $TEMP = '<form action="?a=templates&s=commitcreate" method = post>'
    .'<input name="NAME" type=text value="Введите название для шаблона" size=50><input type=submit>'
    .'</form>'
    ;
    return $TEMP;
  }
  function commitcreateondisk()
  {
    #die("NOT IMPEMENTATED");
    if ($_POST[NAME])
    {
      global $conf;
      include ('path.php');

      srand ((double) microtime() * 1000000);

      $a = md5(rand(1,10000));
      $dirname=substr($a, 1, 25);
      $i = 100;
      while (! ($tmp =@ mkdir("$conf[skin_path]/$dirname")))
      {
        $i--;
        echo $a = md5(rand(1,10000));
        $dirname=substr($a, 1, 25);
        if ($i<0)break;
      };
      if ($i > 0){
        copy ( "$conf[skin_path]/template.dat", "$conf[skin_path]/$dirname/template.dat");
        $SQL = "insert into $conf[DB_PREFIX]templates(PATH,NAME) values ('$dirname', '$_POST[NAME]')";
        $r = mysql_query($SQL) or die (mysql_eror());
        header("Location: index.php?a=templates");
        die();
      };
    };
  }
  function del()
  {
    global $conf;
    $SQL = "select * FROM $conf[DB_PREFIX]templates";
    $result = mysql_query($SQL, $conf[DB]);

    $ret="";
    while ($template= mysql_fetch_assoc($result))
    {
      $SQL = "select count(ID) as `count` FROM $conf[DB_PREFIX]regions WHERE TEMPLATE=$template[ID]";
      $result2 = mysql_query($SQL, $conf[DB]);
      $co = mysql_fetch_assoc($result2);
      if ($co[count]==0)
      {
        $ret .="<b><a href=\"?a=templates&s=checkdelete&template=$template[ID]\">Удалить $template[NAME]</a><br>";
      }else{
        $ret .="$template[NAME]<br>";
      };
    };
    return $ret;
  }

  function doLi($engName, $rusName, $link=0, $linkaddr="")
  {
    if ($linkaddr!="")
    $rusName = "<a href=\"$linkaddr\" target=\"contentFrame\">$rusName</a>";

    if ($link==0)
    return "<li id=$engName xid=$xid><a href=\"javascript:expand(\'$engName\')\"><img border=0 src=\"./images/tree_expand.png\"></a>$rusName</li>";

    return "<li id=$engName xid=$xid><a href=\"javascript:collaps(\'$engName\')\"><img border=0 src=\"./images/tree_collapse_corner.png\"></a>$rusName</li>";
  }
  function doItem($rusName, $addres){
    return "<li><a href=\"$addres\" target=\"contentFrame\">$rusName</a></li>";
  }
  function expand($id)
  {

    global $conf;
    if ((int)$id==0){
      $SQL = "SELECT * FROM $conf[DB_PREFIX]templates";
      $result = mysql_query($SQL, $conf[DB]);
      $tmp2 = "";

      while ($template= mysql_fetch_assoc($result))
      {
        $tmp2 .= doLi("template_$template[ID]", $template[NAME]);
      };
      $tmp .= "var temp = xget('templates');";

      //$tmp2 = "<li id=\"template_create\"><a href=\"?a=templates&s=create\" target=\"contentFrame\">Создать</a>" . $tmp2;
      return "$tmp;temp.innerHTML = '".doNoLi("templates", "ШАБЛОНЫ", 1, "?a=templates")
      ."<ul>".$tmp2."</ul>';";
    }else{
      $SQL = "SELECT * FROM $conf[DB_PREFIX]templates where ID = $id";
      $result = mysql_query($SQL, $conf[DB]);
      $template= mysql_fetch_assoc($result);
      $tmp .= "var temp = xget('template_$id');\n";
      $tmp .="temp.innerHTML = '".doNoLi("templates_$id", $template[NAME], 1, "")
      .doItem("Шаблон сайта", "index.php?a=templates&s=edit&template=$id")
      .doItem("CSS", "?a=properties&s=css&template=$id")
      .doItem("Шаблон Новостей", "properties.php?template=$id&module=news&a=editouter")
      .doItem("Шаблон галлереи", "properties.php?template=$id&module=gallery2&a=template")
      .doItem("Шаблон карты",  "properties.php?template=$id&module=map&a=editouter")
      .doItem("Шаблон крошки",  "properties.php?template=$id&module=path")    
      .doItem("Шаблон текста",  "properties.php?template=$id&module=text")
      .doItem("Шаблон навигации", "properties.php?template=$id&module=navigate&a=edit")
      .doItem("Шаблон голосования", "properties.php?template=$id&module=vote&a=template")
      .doItem("Шаблон случайных текстов", "properties.php?template=$id&module=randomtext&a=editouter")
      .doItem("Шаблон почты",  "properties.php?template=$id&module=mail")
      .doItem("Шаблон крошки Магазина",  "properties.php?template=$id&module=Path_shop")
      .doItem("Шаблон поиска Магазина",  "properties.php?template=$id&module=search_shop")
      .doItem("Шаблон навигации Магазина",  "properties.php?template=$id&module=NavigateShop&a=editouter")
      .doItem("Шаблон корзины Магазина",  "properties.php?template=$id&module=cart")
      .doItem("Шаблон Магазина",  "properties.php?template=$id&module=shop&action=editelement")
      .doItem("Регистрация",  "properties.php?template=$id&module=register&a=ubertemplate")
      .doItem("Горячие товары",  "properties.php?template=$id&module=hot_shop")
      .doItem("Создать резервную копию",  "index.php?template=$id&a=templates&s=backup")
      .doItem("Восстановитоь из резервной копии",  "index.php?template=$id&a=templates&s=restore")
    
      ;
      return "$tmp';";
    }
  }
};?>
