<div id="progress" style="width:500px;border:1px solid #ccc;"><div id="bar" style="width:0%;background-color:#ddd;">&nbsp;</div></div>
<?php
$buy_avg = $buy_max = $buy_min = $buy_mid = $sell_avg = $sell_max = $sell_min = $sell_mid = 0;

$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);

$query="SELECT * FROM lps ORDER BY id ASC";
$result=mysql_query($query);
$num=mysql_numrows($result);
$i=0;
while ($i < $num) {
	//pprog
    $percent = intval($i/$num * 100)."%";
    echo "<script language='javascript'>document.getElementById('bar').style.width=\"$percent\";</script>";
	//body
	$isk = $lp = $costs = $gains = 0;
	$id = mysql_result($result,$i,"id");
	$typeID = mysql_result($result,$i,"typeID");
	$lp = mysql_result($result,$i,"lp");
	$costs += mysql_result($result,$i,"isk");
	
	$itemResult = mysql_query("SELECT price_sell_min FROM items WHERE typeID='$typeID'");
	$gains += mysql_result($itemResult,0,"price_sell_min");
	
	$name = mysql_result(mysql_query("SELECT name FROM items WHERE typeID='$typeID'"),0,"name");
	
	$matstr = mysql_result($result,$i,"mats");
	if($matstr<>""){
		$mats = explode(",",$matstr);
		for($k = 1; $k < count($mats); $k+=2){
			$matresult = mysql_query("SELECT * FROM items WHERE id='".$mats[$k]."'");
			// $buy_avg = mysql_result($matresult,0,"price_buy_avg");
			// $buy_max = mysql_result($matresult,0,"price_buy_max");
			// $buy_min = mysql_result($matresult,0,"price_buy_min");
			// $buy_mid = mysql_result($matresult,0,"price_buy_mid");
			if(is_int(strstr("lueprin",$name)))
				$costs += mysql_result($matresult,0,"price_buy_avg");
			else
				$costs += mysql_result($matresult,0,"price_sell_avg");
			// $sell_max += mysql_result($matresult,0,"price_sell_max");
			// $sell_min += mysql_result($matresult,0,"price_sell_min");
			// $sell_mid += mysql_result($matresult,0,"price_sell_mid");
		}
	}
	
	$netisk = $gains - $costs;
	$iskperlp = $netisk/$lp;
	mysql_query("UPDATE lps SET netisk = '$netisk', iskperlp = '$iskperlp' WHERE id='$id'");
	$i++;
}

mysql_close();
?>