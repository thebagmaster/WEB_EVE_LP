<div id="progress" style="width:500px;border:1px solid #ccc;"><div id="bar" style="width:0%;background-color:#ddd;">&nbsp;</div></div>
<?php

$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);

$lines = array();
if($_POST["paste"])
	$lines = explode("\r\n", $_POST["paste"]);
echo "<table>";
$total=0;
$num=count($lines);
$i=0;
while ($i < $num) {
	//pprog
    $percent = intval($i/$num * 100)."%";
	if($i == $num-1)
		$percent = "100%";
    echo "<script language='javascript'>document.getElementById('bar').style.width=\"$percent\";</script>";
	if($lines[$i] <> "" and strlen(strstr($lines[$i],"[empty"))==0){
		if($i == 0)
			$lines[$i] = str_replace("[","",explode(',',$lines[$i])[0]);
		$lines[$i] = str_replace("'","''",$lines[$i]);
		$lines[$i] = explode(',',$lines[$i])[0];
		$query="SELECT typeID FROM invTypes WHERE typeName LIKE '$lines[$i]'";
		$result=mysql_query($query);
		$id=mysql_result($result,0,"typeID");
		$feed = file_get_contents("http://api.eve-central.com/api/marketstat?typeid=$id&regionlimit=10000002");
		if($feed){
			$xml = simplexml_load_string($feed);
			$entry = $xml->marketstat->type->buy;
			// $buy_avg = $entry->avg;
			// $buy_max = $entry->max;
			// $buy_min = $entry->min;
			// $buy_median = $entry->median;
			// $entry = $xml->marketstat->type->sell;
			$sell_avg = $entry->avg;
			// $sell_max = $entry->max;
			//$sell_min = $entry->min;
			// $sell_median = $entry->median;
			echo "<tr><td>$lines[$i]</td><td>" . printisk($sell_avg) . "<br></td></tr>";
			$total+=$sell_avg;
		}
	}
	$i++;
}

echo "<tr></tr><tr><td></td><td>" . printisk($total) . " ISK<br></tr></table>";

mysql_close();

function printisk($price)
{
	if($price > 1000000000)
		return number_format(($price/1000000000), 2, '.', '') . " B";
	if($price > 1000000)
		return number_format(($price/1000000), 2, '.', '') . " M";
	if($price > 10000)
		return number_format(($price/1000), 2, '.', '') . " k";
}

?>