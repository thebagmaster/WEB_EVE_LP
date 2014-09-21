<div id="progress" style="width:500px;border:1px solid #ccc;"><div id="bar" style="width:0%;background-color:#ddd;">&nbsp;</div></div>
<?php
$buy_avg = $buy_max = $buy_min = $buy_mid = $sell_avg = $sell_max = $sell_min = $sell_mid = 0;

$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);

$query="SELECT * FROM items WHERE name LIKE '%lueprin%' ORDER BY id ASC";
$result=mysql_query($query);
$num=mysql_numrows($result);
$i=0;
while ($i < $num) {
	//pprog
    $percent = intval($i/$num * 100)."%";
    echo "<script language='javascript'>document.getElementById('bar').style.width=\"$percent\";</script>";

	$rootid = mysql_result($result,$i,"id");
	$id = mysql_result($result,$i,"typeID")-1;
	//echo "$id<BR>";
	$query="SELECT * FROM invtypematerials WHERE typeID='$id'";
	$result2=mysql_query($query);
	$num2=mysql_numrows($result2);
	//get sell price
	$buy_avg = $buy_max = $buy_min = $buy_mid = $sell_avg = $sell_max = $sell_min = $sell_mid = 0;
	$feed = file_get_contents("http://api.eve-central.com/api/marketstat?typeid=$id&regionlimit=10000002");
	if($feed){
		$xml = simplexml_load_string($feed);
		$entry = $xml->marketstat->type->sell;
		$sell_avg += ($entry->avg);
		$sell_max += ($entry->max);
		$sell_min += ($entry->min);
		$sell_mid += ($entry->median);
	}
	//get mats price
	$i2=0;
	while ($i2 < $num2) {
		$typeID=mysql_result($result2,$i2,"materialTypeID");
		$quantity=mysql_result($result2,$i2,"quantity");
		//echo "$typeID x $quantity<br>";
		$feed = file_get_contents("http://api.eve-central.com/api/marketstat?typeid=$typeID&regionlimit=10000002");
		if($feed){
			$xml = simplexml_load_string($feed);
			$entry = $xml->marketstat->type->buy;
			$buy_avg += ($entry->avg*$quantity);
			$buy_max += ($entry->max*$quantity);
			$buy_min += ($entry->min*$quantity);
			$buy_mid += ($entry->median*$quantity);			
			//echo "$sell_avg + ";
		}
		$i2++;
	}
	//echo " ($rootid) - $i<BR>";
	$query="UPDATE items SET 
			price_sell_avg='$sell_avg', 
			price_sell_max='$sell_max',	
			price_sell_min='$sell_min',
			price_sell_mid='$sell_mid',
			price_buy_avg='$buy_avg',
			price_buy_max='$buy_max',		
			price_buy_min='$buy_min',
			price_buy_mid='$buy_mid'
			WHERE id='$rootid'";
	mysql_query($query);
	$i++;
}

mysql_close();
?>