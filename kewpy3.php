<?php
$con = mysql_connect("192.168.1.100","821449_root","daniel");
mysql_select_db("eve", $con);
$query="SELECT * FROM lps ORDER BY iskperlp DESC LIMIT 1000";
$result=mysql_query($query);
$num=mysql_numrows($result);

echo "<table>";
echo "<th>corp</th><th>name</th><th>netisk</th><th>iskperlp</th>";
$i=0;
while ($i < $num) {
	echo "<tr>";
	$typeID = mysql_result($result,$i,"typeID");
	$name = mysql_result(mysql_query("SELECT name FROM items WHERE typeID='$typeID'"),0,"name");
	$fact = mysql_result($result,$i,"factionID");
	$faction = mysql_result(mysql_query("SELECT name FROM factions WHERE id='$fact'"),0,"name");
	$netisk = mysql_result($result,$i,"netisk");
	$iskperlp = mysql_result($result,$i,"iskperlp");
	echo "<td>$faction</td><td>$name</td><td>$netisk</td><td>$iskperlp</td>";
	$i++;
	echo "</tr>";
}

echo "</table>";

mysql_close();
?>