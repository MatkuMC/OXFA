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

<p class="trebMS16"> What is the ratio of goals scored to points for teams in the EPL? </p>

<?php
include('pChart/pData.class');  
include('pChart/pChart.class');

$selectval=$_POST["TeamName"];

$values=explode(":",$selectval);

echo "<p class=\"trebMS14\">Substituting Team in the model = $values[1]</p>";

echo "<p class=\"trebMS14\"><b>Total goals conceded by EPL teams: </b></p>"; 
echo "<table id=\"GKC\" cellspacing=\"0\">";
echo  "<tbody>";
echo    "<tr>";
echo        "<th>MatchID<br></th>"; 
echo     	"<th>Team1<br></th>"; 
echo        "<th>Team2<br></th>";
echo        "<th>TotalConceded<br></th>";
echo 		"<th>TotalScored<br></th>";
echo 		"<th>Points<br></th>";
echo    "</tr>";

$dbhost = 'localhost';
$dbuser = 'root';
$dbpassword = 'root';
$dbname = 'testdb';

$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
  }


$query = "CREATE TEMPORARY TABLE tGoalsConceded (SELECT M.MatchID, 
SUM( G.OpenPlay ) AS TotalOpen, 
SUM( G.Corners ) AS TotalCorner, 
SUM( G.Throws ) AS TotalThrows, 
SUM( G.DFKicks ) AS TotalKicks , 
SUM( G.SetPlay ) AS TotalSetPlay, 
SUM( G.Penalties ) AS TotalPen,
(SUM( G.OpenPlay ) + SUM( G.Corners ) + SUM( G.Throws ) + SUM( G.DFKicks ) + SUM( G.SetPlay ) + SUM( G.Penalties )) AS Total
FROM Matches AS M
INNER JOIN Teams AS T 
ON ( T.TeamID = M.TeamID1 OR T.TeamID = M.TeamID2 )
AND T.TeamID = $values[0]
INNER JOIN PlayerMatches AS PM 
ON PM.MatchID = M.MatchID
INNER JOIN Players AS P 
ON P.PlayerID = PM.PlayerID
AND P.TeamID != T.TeamID
INNER JOIN Goals AS G 
ON G.ID = PM.ID
GROUP BY M.MatchID);";

$query .= "CREATE TEMPORARY TABLE tGoalsScored (SELECT M.MatchID, 
SUM( G.OpenPlay ) AS TotalOpen, 
SUM( G.Corners ) AS TotalCorner, 
SUM( G.Throws ) AS TotalThrows, 
SUM( G.DFKicks ) AS TotalKicks , 
SUM( G.SetPlay ) AS TotalSetPlay, 
SUM( G.Penalties ) AS TotalPen,
(SUM( G.OpenPlay ) + SUM( G.Corners ) + SUM( G.Throws ) + SUM( G.DFKicks ) + SUM( G.SetPlay ) + SUM( G.Penalties )) AS Total
FROM Matches AS M
INNER JOIN Teams AS T 
ON ( T.TeamID = M.TeamID1 OR T.TeamID = M.TeamID2 )
AND T.TeamID = $values[0]
INNER JOIN PlayerMatches AS PM 
ON PM.MatchID = M.MatchID
INNER JOIN Players AS P 
ON P.PlayerID = PM.PlayerID
AND P.TeamID = T.TeamID
INNER JOIN Goals AS G 
ON G.ID = PM.ID
GROUP BY M.MatchID);";

$query .= "SELECT M.MatchID, 
T1.TeamName as Team1, 
T2.TeamName as Team2, 
tGC.Total as TotalConceded, 
tGS.Total as TotalScored,
CASE WHEN(tGS.Total>tGC.Total) THEN 3 
	 WHEN(tGS.Total=tGC.Total) THEN 1
	 WHEN(tGS.Total<tGC.Total) THEN 0 END as Points
FROM Matches as M
INNER JOIN tGoalsConceded as tGC 
ON tGC.MatchID = M.MatchID
INNER JOIN tGoalsScored as tGS
ON tGS.MatchID = M.MatchID
INNER JOIN Teams as T1
ON T1.TeamID = M.TeamID1
INNER JOIN Teams as T2
ON T2.TeamID = M.TeamID2";


mysqli_multi_query($conn, $query) or die("MySQL Error: " . mysqli_error($conn) . "<hr>\nQuery: $query");
mysqli_next_result($conn);
mysqli_next_result($conn);
  

$result = mysqli_store_result($conn);

$DataSet = new pData;

while($row = mysqli_fetch_array($result))
{
    echo "<tr>";
    echo "<td> {$row['MatchID']}</td>";
    echo "<td> {$row['Team1']}</td>";
    echo "<td> {$row['Team2']}</td>";
    echo "<td> {$row['TotalConceded']} </td>";
    echo "<td> {$row['TotalScored']} </td>";
    echo "<td> {$row['Points']} </td>";
    echo "</tr>";
    $DataSet->AddPoint($row["TotalScored"],"Serie2");  
   	$DataSet->AddPoint($row["Points"],"Serie1");  
}

 echo "</tbody>";
 echo "</table>";
 
 $DataSet->AddAllSeries();     
 $DataSet->SetAbsciseLabelSerie();
 
 $DataSet->SetSerieName("Points","Serie1");
 //$DataSet->SetSerieName("Goals Scored","Serie2");  

 $DataSet->SetXAxisName("Goals");  
 $DataSet->SetYAxisName("Points");  
  
 // Initialise the graph  
 $Test = new pChart(300,300);  
 $Test->drawGraphAreaGradient(0,0,0,-100,TARGET_BACKGROUND);  
  
 // Prepare the graph area  
 $Test->setFontProperties("Fonts/tahoma.ttf",8);  
 $Test->setGraphArea(55,30,270,230);  
 $Test->drawXYScale($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","Serie2",213,217,221,TRUE,45);  
 $Test->drawGraphArea(213,217,221,FALSE);  
 $Test->drawGraphAreaGradient(30,30,30,-50);  
 $Test->drawGrid(4,TRUE,230,230,230,20);  
  
 // Draw the chart  
 $Test->setShadowProperties(2,2,0,0,0,60,4);
 $Test->setLineStyle(0,0);
 $Test->drawXYGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","Serie2",0);  
 $Test->clearShadow();  
  
 // Draw the title  
 $Title = "Points Per Goal  ";  
 $Test->drawTextBox(0,280,300,300,$Title,0,255,255,255,ALIGN_RIGHT,TRUE,0,0,0,30);  
  
 // Draw the legend  
 $Test->setFontProperties("Fonts/pf_arma_five.ttf",6);
 $DataSet->RemoveSerie("Serie2");    
 $Test->drawLegend(160,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,255,255,255,FALSE);  
  
 $Test->Render("PPG.png");
?>

<div style="position:absolute;top:570px;right:280px;">
<img src="PPG.png"/>
</div>

<br><br>   
<button onClick="history.go(-1)">Back</button>
<button onClick="window.location='Home.php'">Home</button><br><br><br>

<button style="background-color:lightblue" onClick="window.location='http://www.cs.ox.ac.uk'">Department of CS</button>

</html> 