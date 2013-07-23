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
p.trebMS14{
font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;
font-size:14px;
}
</style>

<h2 style="color:#40B3DF;letter-spacing:10px"> Department of Computer Science </h2>
<p class="trebMS20">Football Analytics Project </p>

<p class="trebMS14">If Michel Vorm was substituted for an average goal keeper would Swansea City A.F.C get relegated from the EPL? </p>

<p class="trebMS20">Goals Conceded by Goal Keepers</p>

<table id="GK" cellspacing="0">
  <tbody>
    <tr>
        <th>PlayerID<br> </th> 
     	<th>Surname<br> </th> 
        <th>Forename<br></th>
        <th>TotalConceded<br></th>
        <th>TotalConcededIB<br></th>
     	<th>TotalConcededOB<br></th>
        </tr>
   <tr>

<?php 
 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpassword = 'root';

$conn = mysql_connect($dbhost, $dbuser, $dbpassword) or die ('Error connecting to mysql');

$dbname = 'testdb';

mysql_select_db($dbname);

$query  = "SELECT P.PlayerID, P.Surname, P.Forename, P.TeamID, 
SUM( GC.Total ) AS TotalConceded, 
SUM( GC.InsideBox ) AS TotalConcededIB, 
SUM( GC.OutsideBox ) AS TotalConcededOB
FROM Players AS P
INNER JOIN PlayerMatches AS PM ON PM.PlayerID = P.PlayerID
INNER JOIN GoalsConceded AS GC ON GC.ID = PM.ID
INNER JOIN MatchInfo AS MI ON MI.ID = PM.ID
INNER JOIN Positions AS PO ON PO.PositionID = MI.PositionID
AND PO.Position = 'GK'
GROUP BY P.PlayerID
HAVING SUM( GC.Total ) >10
ORDER BY TotalConceded DESC";

$result = mysql_query($query);

$PlayerIDList="<option values='1'> &nbsp</option>";

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
    echo "<tr>";
    echo "<td> {$row['PlayerID']}</td>";
    echo "<td> {$row['Surname']}</td>";
    echo "<td> {$row['Forename']} </td>";
    echo "<td> {$row['TotalConceded']} </td>";
    echo "<td> {$row['TotalConcededIB']} </td>";
    echo "<td> {$row['TotalConcededOB']} </td>";
    echo "</tr>";
    
    $PlayerIDList="{$PlayerIDList} <option value='{$row['PlayerID']}:{$row['TeamID']}:{$row['TotalConcededIB']}:{$row['TotalConcededOB']}:{$row['Surname']}:{$row['Forename']}'> {$row['PlayerID']} </option> ";

}
 echo "</tbody>";
 echo "</table>";

	echo "<div style=\"position:absolute;top:370px;right:365px;\">";
	echo "<form action=\"GoalKeeperModel.php\" method=\"post\" enctype=\"multipart/form-data\">";
	echo "Select goal keeper :";
    echo "<select name ='GoalKeeper'> {$PlayerIDList} </select><br><br>";
    echo "<input type='submit' value='Analyze'> </br> ";
    echo "</div>";
?>
 
 </form>
<br><br>   
<button onClick="history.go(-1)">Back</button>
<button onClick="window.location='Home.php'">Home</button><br><br><br>

<button style="background-color:lightblue" onClick="window.location='http://www.cs.ox.ac.uk'">Department of CS</button>

</html> 