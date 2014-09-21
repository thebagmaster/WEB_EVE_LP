<?php
include_once('/simple_html_dom.php');

$con = mysql_connect("localhost","821449_root","daniel");
mysql_select_db("eve", $con);

    $html = file_get_html('http://www.ellatha.com/eve/LPIndex-1');
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
						$txt = substr($txt,0,-1);
						echo $txt;
						$query="SELECT factionID FROM chrfactions WHERE factionName LIKE '%$txt%'";
						$result=mysql_query($query);
						$factionID=mysql_result($result,0,"factionID");
						break;
					case 2:
						//itemname
						$txt = substr($txt,4,-6);
						$query="SELECT typeID FROM invtypes WHERE typeName LIKE '%$txt%'";
						$result=mysql_query($query);
						$typeID=mysql_result($result,0,"typeID");
						break;
					case 3:
						//lp
						$lp=$txt;
						break;
					case 4:
						//isk
						$isk=$txt;
						break;
					case 5:
						//mats
						if(strlen($txt)>8)
						foreach(explode("\n", $txt) as $line) {
							$num = substr($line,0,1);
							$item = substr($line,4);
							echo $line;
							$query="SELECT typeID FROM invtypes WHERE typeName LIKE '%$item%'";
							$result=mysql_query($query);
							$mats.=(mysql_result($result,0,"typeID").",");
						}
						break;
				}
			}
			$c+=1;
			$query="INSERT INTO lps ('$typeID','$factionID','$lp','$isk','$mats')";
			mysql_query($query);
			$typeID = $factionID = $lp = $isk = $mats = "";
		}
		$c=0;
		$r+=1;
	}

mysql_close();
?>