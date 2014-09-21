<?php
$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);
$query="SELECT * FROM lps ORDER BY id ASC LIMIT 1000";
$result=mysql_query($query);
$num=mysql_numrows($result);

echo "<table>";

$i=0;
while ($i < $num) {
	echo "<tr>";
	$typeID = mysql_result($result,$i,"typeID");
	$makes = mysql_result($result,$i,"makeNum");
	$fact = mysql_result($result,$i,"factionID");
	$lp = mysql_result($result,$i,"lp");
	$isk = mysql_result($result,$i,"isk");
	$mats = mysql_result($result,$i,"mats");
	echo "<td>$typeID</td><td>$makes</td><td>$fact</td><td>$lp</td><td>$isk</td><td>$mats</td>";
	$i++;
	echo "</tr>";
}

echo "</table>";

mysql_close();
?>