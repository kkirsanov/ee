<?
header ('Content-Type: text/html; charset="utf-8"');
include("../db.php");
include_once("protect.php");
$tmp = explode("_", $_GET[id]);
$command = $tmp[0];
$id = $tmp[1];

session_start();
function doItem($rusName, $addres){
  return "<li><a href=\"$addres\" target=\"contentFrame\">$rusName</a></li>";
}
function doLi($engName, $rusName, $link=0, $linkaddr="")
{
  if ($linkaddr!="")
  $rusName = "<a href=\"$linkaddr\" target=\"contentFrame\">$rusName</a>";

  if ($link==0)
  return "<li id=$engName xid=$xid><a href=\"javascript:expand(\'$engName\')\"><img border=0 src=\"./images/tree_expand.png\"></a>$rusName</li>";

  return "<li id=$engName xid=$xid><a href=\"javascript:collaps(\'$engName\')\"><img border=0 src=\"./images/tree_collapse_corner.png\"></a>$rusName</li>";
}

function doNoLi($engName, $rusName, $link=0, $linkaddr="")
{
  if ($linkaddr!="")
  $rusName = "<a href=\"$linkaddr\" target=\"contentFrame\">$rusName</a>";

  if ($link==0)
  return "<a href=\"javascript:expand(\'$engName\')\"><img border=0 src=\"./images/tree_expand.png\"></a>$rusName</li>";

  return "<a href=\"javascript:collaps(\'$engName\')\"><img border=0 src=\"./images/tree_collapse_corner.png\"></a>$rusName</li>";
}

function doLiElement($engName, $rusName, $linkaddr="")
{
  if ($linkaddr!="")
  $rusName = "<a href=\"$linkaddr\" target=\"contentFrame\">$rusName</a>";

  return "<li>$rusName</li>";
}

function echo_start()
{
  echo '<?xml version="1.0" encoding="utf-8"?><html><head>' . '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
  echo '<script>';
  echo 'function xget(id){';
  echo 'if(window.parent.document.getElementById) return window.parent.document.getElementById(id);';
  echo 'if(window.parent.document.all) return window.parent.document.all[id];';
  echo 'return null;';
  echo '};';
};
switch ($_GET[a])
{
  case "region_up":
    echo_start();
    $region_id=(int)$command;
    $SQL = "SELECT `PARENT` FROM $conf[DB_PERFIX]regions WHERE ID=$region_id";
    $res = mysql_query($SQL, $conf[DB]);
    $tmp = mysql_fetch_assoc($res);
    $tmp = (int)$tmp[PARENT];

    include ("core_regions.php");
    $REG = new Regions;
    $REG->up($region_id);

    //chek for parent region
    if ($tmp==0)
    {
      echo "var temp = xget('regions');";
    }else{
      echo "var temp = xget('regions_$tmp');";
    };

    echo ';window.parent.transaction_Active=0;';

    if ($tmp==0)
    {
      echo "window.location='?a=expand&id=regions';";
    }else{
      echo "window.location='?a=expand&id=regions_$tmp';";
    };
    echo '</script></head></html>';
    break;
  case "region_down":
    echo_start();
    $region_id=(int)$command;
    $SQL = "SELECT `PARENT` FROM $conf[DB_PERFIX]regions WHERE ID=$region_id";
    $res = mysql_query($SQL, $conf[DB]);
    $tmp = mysql_fetch_assoc($res);
    $tmp = (int)$tmp[PARENT];

    include ("core_regions.php");
    $REG = new Regions;
    $REG->down($region_id);

    //chek for parent region
    if ($tmp==0)
    {
      echo "var temp = xget('regions');";
    }else{
      echo "var temp = xget('regions_$tmp');";
    };

    echo ';window.parent.transaction_Active=0;';

    if ($tmp==0)
    {
      echo "window.location='?a=expand&id=regions';";
    }else{
      echo "window.location='?a=expand&id=regions_$tmp';";
    };
    echo '</script></head></html>';
    break;
  case "expand":
    echo_start();
    switch ($command)
    {
      case "register":
        echo "var temp = xget('$command');";
        echo "temp.innerHTML = '<a href=\"javascript:collaps(\'register\')\">-</a>Вход в систему:</span>';";
        if (!$_SESSION[login])
        {
          $LOGIN ="<TABLE id=\"register_table\" cellSpacing=\"0\" cellPadding=\"0\" border=\"0\"><tr><td>"
          ."<form method=post action=\"?a=login\" target=\"commandframe\">"
          ."<TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">"
          ."<tr><td><input size=10 type=text name=\"login\" class=\"mainoption\"></td><td><input type=password name=\"password\" class=\"mainoption\" size=10></td></tr>"
          ."<tr><td>Логин</td><td>Пароль</td></tr>"
          ."</TABLE>"
          ."<input type=submit class=\"mainoption\" value=\"Войти\" style=\"WIDTH:100%\"></form>"
          ."</td></tr></table>"
          ;
        }else{
          $LOGIN= "<form method=post action=\"?a=logout\" target=\"commandframe\"><input type=submit value=\"Выйти\" style=\"WIDTH:100%\"></form>";
        };
        echo "temp.innerHTML += '<br>$LOGIN</a>';";
        break;

      case "site":
        echo "var temp = xget('$command');";
        if ($_SESSION[login])
        {
          echo "temp.innerHTML = '<a href=\"javascript:collaps(\'site\')\">-</a>Управление сайтом:';";//
          $SITE = "<ul>"
          .doLi("regions", "РАЗДЕЛЫ", 0, "index.php?a=regions&s=viewtotal")
          .doLi("modulenews", "НОВОСТИ",0, "properties.php?module=news")
          .doLi("gallery", "ГАЛЕРЕЯ", 0, 'properties.php?module=gallery2')
          .doLi("shop", "МАГАЗИН", 0, "properties.php?module=shop")
          .doLi("templates", "ШАБЛОНЫ",0, "?a=templates")
          .doLi("modules", "МОДУЛИ")
          .doLi("setup", "CИСТЕМНЫЕ НАСТРОЙКИ")
          .doLi("update", "ОБНОВЛЕНИЕ/КОНТАКТ/ПОМОЩЬ", 0, "http://www.easyengine.ru")
          ."</ul>"
          ;
          echo "temp.innerHTML += '$SITE';";
        }else{
          echo "temp.innerHTML = '<a href=\"javascript:expand(\'site\')\">+</a>Управление сайтом:</span><br><a href=\"command.php?a=expand&id=register\" target=\"commandframe\"><font color=red>Сначала войдите в cистему!</font></a>';";

        };
        break;
      case "shop":
        echo "var temp = xget('$command');";
        echo "temp.innerHTML = '". doNoLi("shop", "МАГАЗИН", 1, "properties.php?module=shop").doItem ("Загрузка прайсов", "priceparcer.php")."';";

        $SETUP = "<ul>"
        .doItem ("Корзина магазина", "properties.php?module=cart&a=list&f=open")
        .doLi ("shopsetup","Настройки магазина")
        ."</ul>"
        ;
        echo "temp.innerHTML += '$SETUP';";
        break;
      case "shopsetup":
        echo "var temp = xget('$command');";
        echo "temp.innerHTML = '". doNoLi("shopsetup", "Настройки магазина", 1)."';";

        $SETUP = "<ul>"
        .doItem ("Навигация магазина", "properties.php?module=NavigateShop&a=editactive")
        .doItem ("По фирмам", "properties.php?action=edit&task=byfirm&module=shop")
        .doItem ("Комментарии", "properties.php?action=edit&task=comments&module=shop")
        //.doItem ("Предложения", "properties.php?action=edit&task=offers&module=shop")
        .doItem ("Артикулы", "articulparcer.php")
        .doItem ("Скидки", "properties.php?module=cart&a=skidki")
        //.doItem ("Опияния пользователей", "properties.php?action=edit&task=userdescription&module=shop")
       .doItem ("Фирмы", "properties.php?action=firm&module=shop")
        ."</ul>"
        ;
        echo "temp.innerHTML += '$SETUP';";
        break;
      case "shopshopsetup":
        echo "var temp = xget('$command');";
        echo "temp.innerHTML = '". doNoLi("shopsetup", "Настройки магазина", 1)."';";

        $SETUP = "<ul>"
        .doItem ("Навигация магазина", "")
        .doItem ("Крошка магазина", "")
        .doItem ("Поиск в магазине", "")
        .doItem ("Горячие товары", "")
        .doItem ("Фирмы", "")
        ."</ul>"
        ;
        echo "temp.innerHTML += '$SETUP';";
        break;
      case "modulenews":
        echo "var temp = xget('$command');";
        echo "temp.innerHTML = '". doNoLi("modulenews", "НОВОСТИ", 1, "properties.php?module=news")."';";
        $SETUP = "<ul>"
        .doItem ("Добавить новости", "properties.php?a=addnews&module=news")
        #.doItem ("Темы", "properties.php?a=type&module=news")
        .doItem ("Комментарии", "properties.php?a=comments&module=news")
        ."</ul>"
        
        ."</ul>"
        ;
        echo "temp.innerHTML += '$SETUP';";
        break;
      case "gallery":
        echo "var temp = xget('$command');";
        echo "temp.innerHTML = '". doNoLi("gallery", "ГАЛЕРЕЯ", 1)."';";
        $sql = "select * from $conf[DB_PREFIX]galleries";
        $result = mysql_query($sql, $conf[DB]) or die(mysql_error());
        $galdata ="";
        while ($gallery = mysql_fetch_assoc($result)){
          $galdata .= doItem ($gallery[NAME], "properties.php?a=editgallery&id=$gallery[ID]&module=gallery2");
        };

        $SETUP = "<ul>"
        .doItem ("Добавить галерею", "properties.php?a=upload&module=gallery2")
        .$galdata
        .doItem ("Настройки галереи", "properties.php?module=gallery2")
        ."</ul>"
        ;
        echo "temp.innerHTML += '$SETUP';";
        break;

      case "setup":
        echo "var temp = xget('$command');";
        if (false)
        {
          echo "var temp = xget('$command');";
          echo "temp.innerHTML = '<font color=red>Acces Denied</font>';";
        }else{
          echo "temp.innerHTML = '". doNoLi("setup", "СИСТЕМНЫЕ НАСТРОЙКИ", 1)."';";

          $SETUP = "<ul>"
          .doItem ("Пользователи", "?a=users")
          .doItem ("Встроенные коды", "?a=properties&s=JS")
          .doItem ("Бекап", "")
          .doItem ("Системный протокол", "?a=properties&s=viewlog")
          ."</ul>"
          ;
          echo "temp.innerHTML += '$SETUP';";
        };
        break;
      case "regions":
        if (false)
        {
          echo "var temp = xget('$command');";
          echo "temp.innerHTML = '<font color=red>Acces Denied</font>';";
        }else{
          $tmp = "";
          include ("core_regions.php");
          $REG = new Regions;
          echo $REG->expand($id);
        };
        break;
      case "special":
        include ("core_regions.php");
        $REG = new Regions;
        echo $REG->expandspecail();
        break;
      case "modules":
        if (false)
        {
          echo "var temp = xget('$command');";
          echo "temp.innerHTML = '<font color=red>Acces Denied</font>';";
        }else{
          include ("core_module.php");
          $conf[module_path] ="../modules/";
          $MOD= new Modules($conf);
          echo $MOD->expand();
        };
        break;
      case "update":
        if (false)
        {
          echo "var temp = xget('$command');";
          echo "temp.innerHTML = '<font color=red>Acces Denied</font>';";
        }else{
          include ("core_update.php");
          $UPDATE= new Update;
          echo $UPDATE->expand();
        };
        break;
      case "templates":
      case "template":
        if (false)
        {
          echo "var temp = xget('$command');";
          echo "temp.innerHTML = '<font color=red>Acces Denied</font>';";
        }else{
          include ("core_template.php");
          $TEMPLATE= new Templates;
          echo $TEMPLATE->expand($id);
        };
        break;
      case "users":
        if (false)
        {
          echo "var temp = xget('$command');";
          echo "temp.innerHTML = '<font color=red>Acces Denied</font>';";
        }else{
          include ("core_users.php");
          $USERS= new Users;
          echo $USERS->expand($command);
        };
        break;
    };
    echo ";window.parent.transaction_Active=0;</script></head></html>";
    break;

      case "collaps":
        echo_start();
        switch ($command)
        {
          case "users":
            include ("core_regions.php");
            $REG = new Regions;
            echo "temp = xget('$command');temp.innerHTML='" . $REG->doLi("users", "Пользователи", 0, "")."';";
            break;
          case "special":
            include ("core_regions.php");
            $REG = new Regions;
            echo "temp = xget('$command');temp.innerHTML='" . $REG->doLi("special", "Специальные разделы", 0, "")."';";
            break;
          case "gallery":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '".doNoLi("gallery", "ГАЛЕРЕЯ")."';";
            break;
          case "template":
          case "templates":
            if (!$id){
              include ("core_regions.php");
              $REG = new Regions;
              echo "temp = xget('$command');temp.innerHTML='" . $REG->doLi("templates", "ШАБЛОНЫ", 0, "?a=templates")."';";
            }else{
              include ("core_regions.php");
              $SQL = "SELECT * FROM $conf[DB_PREFIX]templates where ID = $id";
              $result = mysql_query($SQL, $conf[DB]);
              $template= mysql_fetch_assoc($result);
              //$tmp .= "var temp = xget('template_$id');\n";
              //$tmp .="temp.innerHTML = '".doNoLi("templates_$id", $template[NAME], 1, "")

              echo "temp = xget('template_$id');temp.innerHTML='" . doLi("template_$id", $template[NAME])."';";

            }
            break;
          case "modules":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '<a href=\"javascript:expand(\'modules\')\"><img border=0 src=\"./images/tree_expand.png\"></a>МОДУЛИ';";
            break;
          case "modulenews":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '<a href=\"javascript:expand(\'modulenews\')\"><img border=0 src=\"./images/tree_expand.png\"></a><a href=\"properties.php?module=news\">НОВОСТИ</a>';";
            break;
          case "update":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '".doLi("update", "ОБНОВЛЕНИЕ/КОНТАКТ/ПОМОЩЬ", 0, "http://www.ee.ru")."';";
            break;
          case "special":
            include ("core_regions.php");
            $REG = new Regions;
            echo "temp = xget('$command');temp.innerHTML='" . $REG->doLi("special", "Специальные Разделы", 0, "")."';";
            break;
          case "register":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '<a href=\"javascript:expand(\'register\')\"><img border=0 src=\"./images/tree_expand.png\"></a>ВХОД В СИСТЕМУ';";
            $LIST = "<ul><li id=setup>"
            ."<li id=>"
            ;
            echo "window.location='command.php?a=expand&id=site';";
            break;
          case "site":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '<a href=\"javascript:expand(\'site\')\"><img border=0 src=\"./images/tree_expand.png\"></a>Управление сайтом';";
            break;
          case "setup":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '<a href=\"javascript:expand(\'setup\')\"><img border=0 src=\"./images/tree_expand.png\"></a>СИСТЕМНЫЕ НАСТРОЙКИ';";
            break;

          case "shop":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '". doLi("shop", "МАГАЗИН", 0, "properties.php?module=shop")."';";
            break;
          case "shopsetup":
            echo "var temp = xget('$command');";
            echo "temp.innerHTML = '". doLi("shopsetup", "Настройки магазина")."';";
            break;
          case "regions":
            include ("core_regions.php");
            if ($id==0)
            {
              echo "var temp = xget('$command');";
              //echo "temp.innerHTML = '<a href=\"javascript:expand(\'regions\')\"><img border=0 src=\"./images/tree_expand.png\"></a>Разделы';";
              echo "temp.innerHTML = '" . doLi("regions", "РАЗДЕЛЫ", 0, "index.php?a=regions&s=viewtotal") . "';";
            }else{
              $REG = new Regions;
              echo "var temp = xget('$command"."_$id');";
              echo "temp.innerHTML='" . $REG->generateLi((int)$id, 1, 0)."';";
            };
            break;
        };
        echo ';window.parent.transaction_Active=0;</script></head></html>';
        break;
};
?>
