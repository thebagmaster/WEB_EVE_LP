<div id="progress" style="width:500px;border:1px solid #ccc;"><div id="bar" style="width:0%;background-color:#ddd;">&nbsp;</div></div>
<?php
include_once('/simple_html_dom.php');

$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);
for ($ind=1;$ind<=29802;$ind+=20){

	//pprog
    $percent = intval($ind/$29802 * 100)."%";
    echo "<script language='javascript'>document.getElementById('bar').style.width=\"$percent\";</script>";

    $html = file_get_html('http://www.ellatha.com/eve/LPIndex-'.$ind);
	$table = $html->find('table.box',11);
	//echo $table->outertext;
	$r = 0;
	$c = 0;
	$query = $typeID = $factionID = $lp = $isk = $mats = "";
    foreach($table->find('tr') as $row) {
		foreach($row->find('td') as $cell) {
			if($r > 0 && $c != 1){
				$txt = $cell->innertext;
				if($txt != "")
				switch($c){
					case 0:
						//faction
						$txt = $cell->find('a',0)->innertext;
						$txt = str_replace("'","''",$txt);
						$txt = trim($txt);
						$query="SELECT id FROM factions WHERE name LIKE '%$txt%'";
						$result=mysql_query($query);
						if(!mysql_numrows($result)){
							$query="INSERT INTO factions (name) VALUES ('$txt')";
							mysql_query($query);
							$query="SELECT id FROM factions WHERE name LIKE '%$txt%'";
							$result=mysql_query($query);
						}
						$factionID=mysql_result($result,0,"id");
						break;
					case 2:
						//itemname
						$txt = trim($txt);
						$txt = str_replace("&nbsp;","",$txt);
						$txt = str_replace("'","''",$txt);
						$txt = str_replace("<font color=\"red\">","",$txt);
						$txt = str_replace("</font>","",$txt);
						$txt = str_replace(" x ","",$txt);
						$num = str2int($txt);
						$txt = substr($txt,strlen((string) $num));
						$query="SELECT id FROM items WHERE name LIKE '%$txt%'";
						$result=mysql_query($query);
						if(!mysql_numrows($result)){
							$query="INSERT INTO items (name) VALUES ('$txt')";
							mysql_query($query);
							$query="SELECT id FROM items WHERE name LIKE '%$txt%'";
							$result=mysql_query($query);
						}
						$query="SELECT id FROM items WHERE name LIKE '%$txt%'";
						$result=mysql_query($query);
						$typeID=mysql_result($result,0,"id");
						$typeIDnum = $num;
						break;
					case 3:
						//lp
						$txt = str_replace(",","",$txt);
						$lp=$txt;
						break;
					case 4:
						//isk
						$txt = str_replace(",","",$txt);
						$isk=$txt;
						break;
					case 5:
						//mats
						if(strlen($txt)>8)
							foreach(explode("<br>", trim($txt)) as $line) {
							$item = trim($line);
							$item = str_replace("\r\n","",$item);
							$item = str_replace(" x ","",$item);
							$item = str_replace("&nbsp;","",$item);
							$item = str_replace("'","''",$item);
							$num = str2int($item,false);
							$item = substr($item,strlen((string) $num));
							$query="SELECT id FROM items WHERE name LIKE '%$item%'";
							$result=mysql_query($query);
							if(!mysql_numrows($result)){
								$query="INSERT INTO items (name) VALUES ('$item')";
								mysql_query($query);
								$query="SELECT id FROM items WHERE name LIKE '%$item%'";
								$result=mysql_query($query);
							}
							$mats.= $num.",".mysql_result($result,0,"id").",";
						}
						break;
				}
			}
			$c+=1;
		}
		$query="SELECT id FROM lps WHERE typeID = $typeID AND factionID = $factionID";
		$result=mysql_query($query);
		if($typeID != 0 && !mysql_numrows($result)){
			$query="INSERT INTO lps (typeID,makeNum,factionID,lp,isk,mats) VALUES ('$typeID','$typeIDnum','$factionID','$lp','$isk','$mats')";
			mysql_query($query);
			echo "index:$ind,type:$typeID,many:$typeIDnum,fact:$factionID,lp:$lp,isk:$isk,mats:$mats<br>";
		}
		$typeID = $factionID = $lp = $isk = $mats = "";
		$c=0;
		$r+=1;
	}
}
function str2int($string, $concat = true) {
    $length = strlen($string);    
    for ($i = 0, $int = '', $concat_flag = true; $i < $length; $i++) {
        if (is_numeric($string[$i]) && $concat_flag) {
            $int .= $string[$i];
        } elseif(!$concat && $concat_flag && strlen($int) > 0) {
            $concat_flag = false;
        }        
    }
    
    return (int) $int;
}
mysql_close();
?>