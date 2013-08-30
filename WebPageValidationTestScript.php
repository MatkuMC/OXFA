<html>
<img src="Oxford.png"/>
<?php
$links = file("ListOfLinks.txt");
$validlinks = array();
$invalidlinks = array();
foreach ($links as $link) 
{
	if ($LinkfromFile = @fopen(trim($link), "r")) 
		{
			$validlinks[] = $link;
			fclose($LinkfromFile);
		}
		else 
		{
			$invalidlinks[] = $link;
		}
}
echo"<h2>Valid Links</h2>";
echo "<table border='1' cellpadding='1' cellspacing='0'>";
echo "<tbody>"; 
echo "<tr bgcolor='#BBB87E'>";
echo "<th>Number</th>";
echo "<th>Link</th>";
echo "<th>Status</th>";
echo "</tr>";
echo "</tbody>";	
$count =1 ;
foreach ($validlinks as $link) 
	{
	 echo "<tr>";
	 echo"<td>$count</td>";
	 echo"<td>$link</td>";
	 echo "<td><img src='icon_tick.gif'/></td>";
	 echo "</tr>";
	 $count++;
	}
echo "</table>";
if(count($validlinks) === 13)
	{
	 echo " <h2> All Links working - Test Passed </h2>";
	}
else
	{
	 echo"<h2>Invalid Links</h2>";
	 echo "<table border='1' cellpadding='1' cellspacing='0'>";
	 echo "<tbody>"; 
	 echo "<tr bgcolor='#BBB87E'>";
	 echo "<th>Number</th>";
	 echo "<th>Link</th>";
	 echo "<th>Status</th>";
	 echo "</tr>";
	 echo "</tbody>";
	 $count =1;
	 foreach ($invalidlinks as $link) 
	 {
	  echo "<tr>";
	  echo"<td>$count</td>";
	  echo"<td>$link</td>";
	  echo "<td><img src='cross_icon.gif'/></td>";
	  echo "</tr>";
	  $count++;
	 }
	 echo "</table>";
	}
?>
</html>