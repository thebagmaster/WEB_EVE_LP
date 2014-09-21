<div id="progress" style="width:500px;border:1px solid #ccc;"><div id="bar" style="width:0%;background-color:#ddd;">&nbsp;</div></div>
<?php
$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);

$query="SELECT * FROM items ORDER BY id ASC";
$result=mysql_query($query);
$num=mysql_numrows($result);
$i=0;
while ($i < $num) {
	//pprog
    $percent = intval($i/$num * 100)."%";
    echo "<script language='javascript'>document.getElementById('bar').style.width=\"$percent\";</script>";
	
	//body
	$id=mysql_result($result,$i,"typeID");
	$feed = file_get_contents("http://api.eve-central.com/api/marketstat?typeid=$id&regionlimit=10000002");
	if($feed){
		$xml = simplexml_load_string($feed);
		$entry = $xml->marketstat->type->buy;
		$buy_avg = $entry->avg;
		$buy_max = $entry->max;
		$buy_min = $entry->min;
		$buy_median = $entry->median;
		$entry = $xml->marketstat->type->sell;
		$sell_avg = $entry->avg;
		$sell_max = $entry->max;
		$sell_min = $entry->min;
		$sell_median = $entry->median;
		
		$query="UPDATE items SET 
		price_sell_avg='$sell_avg', 
		price_sell_max='$sell_max',	
		price_sell_min='$sell_min',
		price_sell_mid='$sell_median',
		price_buy_avg='$buy_avg',
		price_buy_max='$buy_max',		
		price_buy_min='$buy_min',
		price_buy_mid='$buy_median'
		WHERE typeID='$id'";
		//echo "Updated 1 Entry<br>";
		mysql_query($query);
	}
	$i++;
}



mysql_close();
?>