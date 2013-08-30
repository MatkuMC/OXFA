<html>

<img src="Oxford.png"/>

<style>
#GK
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:30%;
border-collapse:collapse;
}
#GK td, #GK th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#GK th 
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}
#GK tr:nth-child(even) {
	color:#000000;
    background-color: #EAF2D3;
}
p.serif{font-family:"Times New Roman",Times,serif;}
p.sansserif{font-family:Arial,Helvetica,sans-serif;}
p.trebMS20{
font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;
font-size:20px;
}
p.trebMS16{
font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;
font-size:16px;
}
p.trebMS14{
font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;
font-size:14px;
}

#GKC
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:30%;
border-collapse:collapse;
}
#GKC td, #GKC th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#GKC th 
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}
#GKC tr:nth-child(even) {
	color:#000000;
    background-color: #EAF2D3;
}
</style>

<h2 style="color:#40B3DF;letter-spacing:10px"> Department of Computer Science </h2>
<p class="trebMS20">Football Analytics Project </p>

<p class="trebMS16">If Michel Vorm was substituted for an average goal keeper would Swansea City A.F.C get relegated from the EPL? </p>

<?php

$selectval=$_POST["GoalKeeper"];

$values=explode(":",$selectval);

echo "<p class=\"trebMS14\">Substituting Goal Keeper in the model = $values[5] $values[4]</p>";

$dbhost = 'localhost';
$dbuser = 'root';
$dbpassword = 'root';

$conn = mysql_connect($dbhost, $dbuser, $dbpassword) or die ('Error connecting to mysql');

$dbname = 'testdb';

mysql_select_db($dbname);

$query  = "SELECT T.TeamID, T.TeamName, 
SUM( IB.ShotsOn ) AS TotalShotsOnIB,
SUM( OB.ShotsOn ) AS TotalShotsOnOB
FROM Matches AS M
INNER JOIN Teams AS T ON ( T.TeamID = M.TeamID1
OR T.TeamID = M.TeamID2 )
INNER JOIN PlayerMatches AS PM ON PM.MatchID = M.MatchID
INNER JOIN Players AS P ON P.PlayerID = PM.PlayerID
AND P.TeamID != T.TeamID
INNER JOIN InsideBox AS IB ON IB.ID = PM.ID
INNER JOIN OutsideBox AS OB ON OB.ID = PM.ID
WHERE M.MatchID
IN (

SELECT M.MatchID
FROM Matches AS M
INNER JOIN PlayerMatches AS PM ON PM.MatchID = M.MatchID
INNER JOIN MatchInfo AS MI ON MI.ID = PM.ID
AND MI.Starts =1
INNER JOIN Players AS P ON P.PlayerID = PM.PlayerID
AND P.PlayerID =$values[0]
)
AND T.TeamID =$values[1]
GROUP BY T.TeamID";

$result = mysql_query($query);

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{

$TotalConcededIBForGK = $values[2];
$TotalShotsOnIBForGK = $row['TotalShotsOnIB'];

$PercentageConIB = ((double)$TotalConcededIBForGK/(double)$TotalShotsOnIBForGK)*100;
$roundedConIB = round($PercentageConIB);

echo "<p class=\"trebMS14\">Percentage goals conceded inside box : $roundedConIB % </p>";

$TotalConcededOBForGK = $values[3];
$TotalShotsOnOBForGK = $row['TotalShotsOnOB'];

$PercentageConOB = ((double)$TotalConcededOBForGK/(double)$TotalShotsOnOBForGK)*100;
$roundedConOB = round($PercentageConOB);

}
echo "<p class=\"trebMS14\">Percentage goals conceded outside box : $roundedConOB % </p>";

echo "<p class=\"trebMS14\"><b>Total shots on target by EPL teams: </b></p>";

echo "<table id=\"GK\" cellspacing=\"0\">";
echo  "<tbody>";
echo    "<tr>";
echo        "<th>TeamID<br> </th>"; 
echo     	"<th>TeamName<br> </th>"; 
echo        "<th>TotalShotsOnIB<br></th>";
echo        "<th>TotalShotsOnOB<br></th>";
echo        "</tr>";
echo   "<tr>";

$query  = "SELECT T.TeamID, T.TeamName, SUM( IB.ShotsOn ) AS TotalShotsOnIB, SUM( OB.ShotsOn ) AS TotalShotsOnOB
FROM Matches AS M
INNER JOIN Teams AS T ON ( T.TeamID = M.TeamID1
OR T.TeamID = M.TeamID2 )
INNER JOIN PlayerMatches AS PM ON PM.MatchID = M.MatchID
INNER JOIN Players AS P ON P.PlayerID = PM.PlayerID
AND P.TeamID != T.TeamID
INNER JOIN InsideBox AS IB ON IB.ID = PM.ID
INNER JOIN OutsideBox AS OB ON OB.ID = PM.ID
WHERE T.TeamID = 105
GROUP BY T.TeamID
ORDER BY T.TeamName ASC";

$result = mysql_query($query);

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
    echo "<tr>";
    echo "<td> {$row['TeamID']}</td>";
    echo "<td> {$row['TeamName']}</td>";
    echo "<td> {$row['TotalShotsOnIB']} </td>";
    echo "<td> {$row['TotalShotsOnOB']} </td>";
    echo "</tr>";
    
    if($row['TeamID'] == "105") {
    	$sfcShotsOnIB = $row['TotalShotsOnIB'];
    	$sfcShotsOnOB = $row['TotalShotsOnOB'];
    }
}
 echo "</tbody>";
 echo "</table>";

echo "<div style=\"position:absolute;top:340px;right:180px;\">";
echo "<p class=\"trebMS14\"><b>Total goals conceded by EPL teams: </b></p>"; 
echo "<table id=\"GKC\" cellspacing=\"0\">";
echo  "<tbody>";
echo    "<tr>";
echo        "<th>TeamID<br> </th>"; 
echo     	"<th>TeamName<br> </th>"; 
echo        "<th>TotalConcededIB<br></th>";
echo        "<th>TotalConcededOB<br></th>";
echo    "</tr>";


$query  = "SELECT T.TeamID, T.TeamName, SUM( IB.Goals ) AS TotalConcededIB, SUM( OB.Goals ) AS TotalConcededOB
FROM Matches AS M
INNER JOIN Teams AS T ON ( T.TeamID = M.TeamID1
OR T.TeamID = M.TeamID2 )
INNER JOIN PlayerMatches AS PM ON PM.MatchID = M.MatchID
INNER JOIN Players AS P ON P.PlayerID = PM.PlayerID
AND P.TeamID != T.TeamID
INNER JOIN InsideBox AS IB ON IB.ID = PM.ID
INNER JOIN OutsideBox AS OB ON OB.ID = PM.ID
WHERE T.TeamID = 105
GROUP BY T.TeamID
ORDER BY T.TeamName ASC";

$result = mysql_query($query);

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
    echo "<tr>";
    echo "<td> {$row['TeamID']}</td>";
    echo "<td> {$row['TeamName']}</td>";
    echo "<td> {$row['TotalConcededIB']} </td>";
    echo "<td> {$row['TotalConcededOB']} </td>";
    echo "</tr>";
    
    if($row['TeamID'] == "105") {
    	$sfcConcededIB = $row['TotalConcededIB'];
    	$sfcConcededOB = $row['TotalConcededOB'];
    }
    
}
 echo "</tbody>";
 echo "</table>";
 echo "</div>";
 
$predictedTotalGoals = ($sfcShotsOnIB * ($PercentageConIB/100)) + ($sfcShotsOnOB * ($PercentageConOB/100));
$actualTotalGoals = $sfcConcededIB + $sfcConcededOB;
$predRoundedTotalGoals = round($predictedTotalGoals);

echo "<p class=\"trebMS14\">Predicted number of Goals : $predRoundedTotalGoals </p>";
echo "<p class=\"trebMS14\">Actual number of Goals : $actualTotalGoals </p>";

$delta = $predRoundedTotalGoals - $actualTotalGoals;

if($delta > 6) {
	echo "<p class=\"trebMS20\">Swansea City would have been relegated. </p>";
} else {
	echo "<p class=\"trebMS20\">Swansea City would have qualified for next season. </p>";
}
?>

<br><br>   
<button onClick="history.go(-1)">Back</button>
<button onClick="window.location='Home.php'">Home</button><br><br><br>

<button style="background-color:lightblue" onClick="window.location='http://www.cs.ox.ac.uk'">Department of CS</button>

</html> 