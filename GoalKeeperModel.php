<html>

<img src="Oxford.png"/>

<?php

$selectval=$_POST["GoalKeeper"];

$values=explode(":",$selectval);

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

echo "Percentage goals conceded inside box :$PercentageConIB";

$TotalConcededOBForGK = $values[3];
$TotalShotsOnOBForGK = $row['TotalShotsOnOB'];

$PercentageConOB = ((double)$TotalConcededOBForGK/(double)$TotalShotsOnOBForGK)*100;


echo "<br><br>";
echo "Percentage goals conceded outside box :$PercentageConOB";
}
?>
</html> 