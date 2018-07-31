<?
#$file = "billing-tarifs.xml";
$file = "http://www.smsdostup.ru/billing-tarifs.xml";
$tarif = array();
$cTarif=null;
$cIndex=0;

include ('../db.php');
mysql_query('set names utf8');
function startElement($parser, $name, $attrs) 
{
  global $cTarif, $cIndex;
  switch($name){
     case 'TARIFS':
        $tarif = array();
     break;
     case 'ITEM':
        $cTarif = array();
        $cTarif[ACCESS]=$attrs[ACCESS];
     break;
     case "COUNTRY":
        $cTarif[CODE] = $attrs[CODE];
     break;
  }
  $cIndex = $name;
}

function endElement($parser, $name) 
{
  global $cTarif, $cIndex;
  if ((is_array($cTarif)) && ($name=='ITEM')){
    $c = $cTarif;
      $SQL = "INSERT INTO `smstarif` (
          `access` ,`code` ,`number` ,`operatorname` ,`operatorlatin` ,`abonentprice` ,`price` ,
          `currency` ,`usdprice` ,`clientprofit` ,`clientprofitusd` ,`prefixesallowed`, `country` )
          VALUES (
          '$c[ACCESS]', '$c[CODE]', '$c[NUMBER]', '$c[OPERATORNAME]', '$c[OPERATORLATIN]', '$c[ABONENTPRICE]', '$c[PRICE]', 
          '$c[CURRENCY]', '$c[USDPRICE]', '$c[CLIENTPROFIT]', '$c[CLIENTPROFITUSD]', '$c[PREFIXESALLOWED]', '$c[COUNTRY]')";
      mysql_query($SQL);
      $cTarif=null;
  }
  $cIndex = null;
}

function CharacterData($parser, $data)
{
    global $cTarif, $cIndex;
    if ((is_array($cTarif)) && ($cIndex)){
        if ($cIndex!="ITEM")
          $cTarif[$cIndex] .= $data;
    }
}

mysql_query("delete from smstarif");
$xml_parser = xml_parser_create();

xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser,'CharacterData');

if (!($fp = fopen($file, "r"))) {
    die("could not open XML input");
}

while ($data = fread($fp, 4096)) {
    if (!xml_parse($xml_parser, $data, feof($fp))) {
        die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
    }
}
xml_parser_free($xml_parser);
echo "Done!";
?>