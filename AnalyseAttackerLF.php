<html> 
<img src="Oxford.png"/> <br>

<style>
#goals
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:30%;
border-collapse:collapse;
}
#goals td, #goals th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#goals th
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}
#goals tr:nth-child(even) {
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

<p class="trebMS20"> Goals Scored (Left Foot) - Analysis</p>

<table id="goals">
  <tbody>
    <tr>
        <th>PlayerID<br> </th> 
     	<th>Surname<br> </th> 
        <th>Forename<br></th>
        <th>TotalLFGoals<br></th>
        <th>TotalLFOn<br></th>
        <th>TotalLFOff<br></th>
        </tr>
   <tr>
<?php

include('pChart/pData.class');  
include('pChart/pChart.class'); 
 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpassword = 'root';

$conn = mysql_connect($dbhost, $dbuser, $dbpassword) or die ('Error connecting to mysql');

$dbname = 'testdb';

mysql_select_db($dbname);

$query  = "SELECT P.PlayerID, P.Surname, P.Forename, 
SUM( LF.Goals ) as TotalLFGoals, 
SUM( LF.ShotsOn) as TotalLFOn,
SUM( LF.ShotsOff) as TotalLFOff
FROM Players AS P
INNER JOIN PlayerMatches AS PM ON PM.PlayerID = P.PlayerID
INNER JOIN MatchInfo AS MI ON MI.ID = PM.ID
INNER JOIN Positions AS PO ON PO.PositionID = MI.PositionID
AND PO.Position = 'Attacker'
INNER JOIN LeftFoot AS LF ON LF.ID = PM.ID
GROUP BY P.PlayerID
ORDER BY TotalLFGoals DESC,TotalLFOn DESC,TotalLFOff DESC
LIMIT 0 , 10";

$result = mysql_query($query);
 
$DataSet = new pData;

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
    echo "<tr>";
    echo "<td> {$row['PlayerID']}</td>";
    $DataSet->AddPoint($row['PlayerID'],"XLabel");
   
    echo "<td> {$row['Surname']}</td>";
    echo "<td> {$row['Forename']} </td>";
    
    echo "<td> {$row['TotalLFGoals']} </td>";
    $DataSet->AddPoint($row["TotalLFGoals"],"Serie1");
    
    echo "<td> {$row['TotalLFOn']} </td>";
    $DataSet->AddPoint($row["TotalLFOn"],"Serie2");
    
    echo "<td> {$row['TotalLFOff']} </td>";
    $DataSet->AddPoint($row["TotalLFOff"],"Serie3");
    
    echo "</tr>";

}

$DataSet->AddAllSeries(); 
$DataSet->SetAbsciseLabelSerie("XLabel"); 
$DataSet->RemoveSerie("XLabel");
 
$DataSet->SetSerieName("Total LF Goals","Serie1");
$DataSet->SetSerieName("Total LF ShotsOn","Serie2"); 
$DataSet->SetSerieName("Total LF ShotsOff","Serie3");   

$DataSet->SetXAxisName("Player ID"); 

// Initialise the graph  
 $Test = new pChart(700,250);  
 $Test->setFontProperties("Fonts/tahoma.ttf",8);  
 $Test->setGraphArea(50,30,680,200);  
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);  
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,250);  
 $Test->drawGraphArea(255,255,255,TRUE);  
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);     
 $Test->drawGrid(4,TRUE,230,230,230,50);  
  
 // Draw the 0 line  
 $Test->setFontProperties("Fonts/tahoma.ttf",6);  
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);  
  
 // Draw the bar graph  
 $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);  
  
 // Finish the graph  
 $Test->setFontProperties("Fonts/tahoma.ttf",8);  
 $Test->drawLegend(596,100,$DataSet->GetDataDescription(),255,255,255);  
 $Test->setFontProperties("Fonts/tahoma.ttf",10);  
 $Test->drawTitle(50,22,"Left Foot",50,50,50,585);  
 $Test->Render("LF.png");  
?>

   </tbody>
</table>

<br>

<div style="position:absolute;top:170px;right:80px;">
<img src="LF.png"/>
</div>

<button onClick="history.go(-1)">Back</button>
<button onClick="window.location='Home.php'">Home</button>
</html>