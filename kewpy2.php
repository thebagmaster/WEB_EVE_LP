<?php
$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);
$query="SELECT * FROM items ORDER BY id ASC LIMIT 1000";
$result=mysql_query($query);
$num=mysql_numrows($result);

echo "<table>";
echo "<th>typeID</th><th>name</th><th>sell_mid</th><th>sell_min</th><th>sell_max</th><th>sell_avg</th><th>buy_mid</th><th>buy_min</th><th>buy_max</th><th>buy_avg</th>";
$i=0;
while ($i < $num) {
	echo "<tr>";
	$typeID = mysql_result($result,$i,"typeID");
	$name = mysql_result(mysql_query("SELECT name FROM items WHERE typeID='$typeID'"),0,"name");
	$sell_mid = mysql_result($result,$i,"price_sell_mid");
	$sell_min = mysql_result($result,$i,"price_sell_min");
	$sell_max = mysql_result($result,$i,"price_sell_max");
	$sell_avg = mysql_result($result,$i,"price_sell_avg");
	$buy_mid = mysql_result($result,$i,"price_buy_mid");
	$buy_min = mysql_result($result,$i,"price_buy_min");
	$buy_max = mysql_result($result,$i,"price_buy_max");
	$buy_avg = mysql_result($result,$i,"price_buy_avg");
	echo "<td>$typeID</td><td>$name</td><td>$sell_mid</td><td>$sell_min</td><td>$sell_max</td><td>$sell_avg</td><td>$buy_mid</td><td>$buy_min</td><td>$buy_max</td><td>$buy_avg</td>";
	$i++;
	echo "</tr>";
}

echo "</table>";

mysql_close();
?>