<?
//<html><head><link href = "css.css" rel = "stylesheet" type = "text/css"><meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8"><body>
function toNumeral($Number, $Thousands=false){
 $units = Array("один","два","три","четыре","пять","шесть", "семь","восемь","девять"); 
 $tens  = Array("десять","двадцать","тридцать","сорок","пятьдесят","шестьдесят", "семьдесят", "восемьдесят","девяносто"); 
 $hundreds =Array("сто","двести","триста","четыреста","пятьсот","шестьсот","семьсот","восемьсот","девятьсот"); 
 $secondten= Array("одиннадцать","двенадцать","тринадцать","четырнадцать","пятнадцать","шестнадцать","семнадцать","восемнадцать","девятнадцать");
 $Women= Array("одна", "две");

 $result="";

 $digits = Array(0,0,0);
 $n =$Number;

 $digits[0] = $n % 10;
 $digits[1] = ($n / 10) % 10;      //--средняя цифра
 $digits[2] = ($n / 100);            //--старшая цифра
 
 if ($digits[2]>0){
 	$tmp=(int)$digits[2]-1;
 	$result .= ($hundreds[$tmp]." ");
 }
 
 if ($digits[1]>0){
 	if (($digits[1]==1)&&($digits[0]!=0)){
 		$result .=($secondten[$digits[0]-1]." ");
 		return $result; 
 	}else{
 		$result .=($tens[$digits[1]-1]." ");
 	}
  }

  if ($digits[0]>0){
  	if ((($digits[0]>2)&&($digits[1]!=1))||(!$Thousands)){
      $result .=($units[$digits[0]-1]." ");
      }else{
      	$result .=($Women[$digits[0]-1]." ");
      }
  }
  return $result;

};

function Numeral($Number){
	$what_ = array(array("миллион", "миллиона", "миллионов"), array("тысяча",  "тысячи",   "тысяч"), array("рубль","рубля","рублей"));
	$t = $Number;
    $treads = array(0,0,0);     //--вычисление троек числа
    
    $treads[0]=$t%1000;
    $treads[1]=$t/1000%1000;
    $treads[2]=(int)($t/1000000);
    
    $result = "";
    
 	if ($treads[2] > 0){
 		$result .=toNumeral($treads[2], false);
    	if ($treads[2]/10 % 10==1) 
   		 	$result .= $what_[0][2];    //-1-
    else 
		switch ($treads[2] % 10){
		case 0: case 5: case 6: case 7: case 8: case 9: 
     		$result .= $what_[0][2]; break;
      	case 2: case 3: case 4: 
      		$result .= $what_[0][1]; break;
      	case 1:
      		$result .= $what_[0][0]; break;
    	}
    	
    	$result.=' ';
    
    }
    
    if ($treads[1] > 0) {
    	$result .=toNumeral($treads[1], true);
    	if ($treads[1]/10 % 10==1) $result .= $what_[1][2];    //-2-
    	else switch ($treads[1] % 10){
    		case 0: case 5: case 6: case 7: case 8: case 9: 
    			$result .= $what_[1][2]; break;
      		case 2: case 3: case 4: 
      			$result .= $what_[1][1];break;
	      	case 1:
	      		$result .= $what_[1][0];break;

    	}
    	$result.=' ';
    }
    $result .=toNumeral($treads[0], false);
    
    if ($treads[0]/10 % 10==1)
    	$result .= $what_[2][2];    //-3-
    else switch ($treads[0] % 10){
    	case 0: case 5: case 6: case 7: case 8: case 9: 
    		$result .= $what_[2][2];break;
    	case 2: case 3: case 4: 
    		$result .= $what_[2][1];break;
      	case 1:
      		$result .= $what_[2][0];break;
    }
    $result.=' ';
    return $result . (int)(($Number - (int)$Number)*100);
};

//echo Numeral($_GET[price]);
//echo Numeral(1111111111, true);
 
?>