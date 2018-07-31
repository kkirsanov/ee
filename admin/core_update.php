<?
class Update{
	var $data;

	function doUpdate($serial){
		return $this->loadlist($serial);
	}
	function getRemoteFile($id){
		$name = $this->remote."?a=file&id=$id&serial=$this->serial";
		if ($h = fopen($name, 'r'))
		{
			$data="";
			while ($line=fgets($h, 100))
			$data.=$line;
			fclose ($h);
			$data2 = unserialize($data);
			if (md5($data2['data'])==$data2['md5'])
			return $data2;
			return null;
		};
	}

	function loadlist($serial){

		$this->serial = $serial;
		$this->remote = "http://www.lmm.ru/ent/install.php";

		if ($h = fopen("$this->remote?a=list&serial=$serial", 'r')){
			$data="";
			while ($line=fgets($h, 100))
			{
				$data.=$line;
			}
			fclose ($h);
			if ($data=="")
			return "<h4>Update server not reachable</h4>";
			$data = unserialize($data);
			foreach($data as $fd){
				$name = "../$fd[path]/$fd[name]";
				$name = str_replace("//", "/", $name);
				$name = str_replace("//", "/", $name);
				$i++;
				if (!$fd[isdir])
				{
					if ($fd[up]==1){
						$md5 =@md5(@file_get_contents($name));
						if (($md5!="")&&($md5!=$fd[md5])){
							$upd = 1;
							$data = $this->getRemoteFile($fd[id]);
							if($f = fopen($name, "w"))
							{
								fwrite ($f, $data[data]);
								fclose($f);
								$ret.= "$name Update Complite! (<i><b>$data[watsnew]</b></i>)<br>";
							};
						};
						;
					}
				}
			}
		}
		if ($upd!=1)
		return "Nothing to do";
		return $ret;
	}
	function UpdateEE(){
		return '<form method=POST action=?a=update&s=doupdate><input type=text name=serial><input type=submit></form>';
	}
	function doLi($engName, $rusName, $link=0, $linkaddr="")
	{
		if ($linkaddr!="")
		$rusName = "<a href=\"$linkaddr\" target=\"contentFrame\">$rusName</a>";

		if ($link==0)
		return "<li id=$engName xid=$xid>$rusName</li>";
		 
		return "<li id=$engName xid=$xid>$rusName</li>";
	}
	function expand()
	{
		$tmp .= "temp = xget('update');";
		$tmp .= "temp.innerHTML = '". doNoLi("update", "ОБНОВЛЕНИЕ/КОНТАКТ/ПОМОЩЬ", 1,"http://www.easyengine.ru")."';";//.doItem("Сообщения об ошибках", "?a=bugs")
		$tmp2 .=$this->doLi("as", "Обновить EE", 0, "?a=update&s=updateee");
		$tmp2 .=$this->doLi("as2", "Отправить запрос в техподдержку", 0, "index.php?a=bugs");
		return "$tmp;temp.innerHTML += '<ul>".$tmp2."</ul>';";
	}
};
?>