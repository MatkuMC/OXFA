<html>
<img src="Oxford.png"/>
<h2>Test Results</h2>
 <table border='1' cellpadding='1' cellspacing='0'>
   <tbody> 
     <tr bgcolor='#BBB87E'>
     <th>TestName</th>
     <th>Message</th>
     <th>Status</th>
     </tr>
     </tbody>
<?php
function PerformTest($TestName,$TestVariable,$Value)
{
	echo "<tr>";
	$TestResult =0;
	if ($TestVariable == $Value)
		{
		echo "<td><b>$TestName<b></td>";
		echo "<td><b>Passed</b></td>";
		echo "<td><img src='icon_tick.gif'/></td>";
		$TestResult=1;
		}
	else
		{
		echo "<td><b>$TestName<b></td>";
		echo "<td><b>Failed</b></td>";
		echo "<td><img src='cross_icon.gif'/></td>";
		}
	echo"</tr>";
	return $TestResult;
}
$dbhost = 'localhost';
$dbuser = 'root';
$dbpassword = 'root';

$conn = mysql_connect($dbhost, $dbuser, $dbpassword) or die ('Error connecting to mysql');

$dbname = 'testdb';

$TestVar = 'Resource id #2';
$TestType =' MAMP Server Connection Test';
$val = strcmp($TestVar,$conn);

$resultTest1 = performtest($TestType,$val,0);
$TestType ='Database Validation & Connection Test';
$val = mysql_select_db($dbname);

$resultTest2 = performtest($TestType,$val,1);
mysql_select_db($dbname);

$TestVar = 'Resource id #3';
$query  = "CHECK TABLE `Teams` , `Positions` , `Players` , `Matches`, `PlayerMatches` ";
$QueryResult = mysql_query($query);
$TestType = 'Validating Tables in Database';
$val = strcmp($TestVar,$QueryResult);
$resultTest3 = performtest($TestType,$val,0);
if($resultTest3 == 0)
{
 $QueryResult = mysql_query("SELECT * FROM Teams LIMIT 0,1");
 if ($QueryResult)
 {
  echo "<br> Table Teams OK";
  $QueryResult = mysql_query("SELECT * FROM Positions LIMIT 0,1");
  if ($QueryResult)
  {
   echo " <br> Table Positions OK";
   $QueryResult = mysql_query("SELECT * FROM Players LIMIT 0,1");
   if ($QueryResult)
   {
	echo " <br>Table Players OK";
	$QueryResult = mysql_query("SELECT * FROM Matches LIMIT 0,1");
	if ($QueryResult)
	{
	 echo " <br>Table Matches OK";
	 $QueryResult = mysql_query("SELECT * FROM PlayerMatches LIMIT 0,1");
	 if ($QueryResult)
	 {
	  echo " <br>Table PlayerMatches OK";
	 }
	 else
	 {
	  " <br> Repair Table PlayerMatches via localhost/PHPmyAdmin";
	 }
    }
	else
	{
	 " <br> Repair Table Matches via localhost/PHPmyAdmin";
    }
   }
   else
   {
    echo " <br>Repair Table Players via localhost/PHPmyAdmin";
   }
  }
  else
  {
   echo " <br>Repair Table Positions via localhost/PHPmyAdmin";
  }
}
else
{
 echo " <br>Repair Table Teams via localhost/PHPmyAdmin ";
}
}
?>
</table>
</html>
