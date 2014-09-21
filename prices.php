<div id="progress" style="width:500px;border:1px solid #ccc;"><div id="bar" style="width:0%;background-color:#ddd;">&nbsp;</div></div>
<?php
$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);

$query="SELECT id FROM types ORDER BY id DESC LIMIT 10000";
$result=mysql_query($query);
$num=mysql_numrows($result);
$i=0;
while ($i < $num) {
	//pprog
    $percent = intval($i/$num * 100)."%";
    echo "<script language='javascript'>document.getElementById('bar').style.width=\"$percent\";</script>";
	
	//body
	$id=mysql_result($result,$i,"id");
	$feed = file_get_contents("http://api.eve-central.com/api/marketstat?typeid=$id&regionlimit=10000002");
	if($feed){
		$xml = simplexml_load_string($feed);
		$entry = $xml->marketstat->type->sell;
		$sell_avg = $entry->avg;
		$query="UPDATE types SET price='$sell_avg' WHERE id='$id'";
		//echo "Updated 1 Entry<br>";
		mysql_query($query);
	}
	$i++;
}



mysql_close();
?>