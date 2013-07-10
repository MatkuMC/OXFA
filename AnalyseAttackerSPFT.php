<html> 
<img src="Oxford.png"/> <br>
<h2> Successful Passes (Final Third) - Analysis</h2>
<style>
#SPFT
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:50%;
border-collapse:collapse;
}
#SPFT td, #SPFT th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#SPFT th 
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}
#SPFT tr:nth-child(even) {
	color:#000000;
    background-color: #EAF2D3;
}
</style>

<table id="SPFT">
  <tbody>
    <tr>
        <th>PlayerID<br> </th> 
     	<th>Surname<br> </th> 
        <th>Forename<br></th>
        <th>TotalSP<br></th>
        <th>Total<br></th>
     	<th>TotalUSP<br></th>
     	<th>PercentageSP<br></th>
     	<th>PercentageUSP<br></th>
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
SUM( SP.FinalThird ) AS TotalSP, 
SUM( SP.Total ) AS Total, 
SUM( USP.FinalThird ) AS TotalUSP, (
(
SUM( SP.FinalThird ) / ( SUM( SP.Total ) )
) *100
) AS PercentageSP, (
(
SUM( USP.FinalThird ) / ( SUM( SP.Total ) )
) *100
) AS PercentageUSP
FROM Players AS P
INNER JOIN PlayerMatches AS PM ON PM.PlayerID = P.PlayerID
INNER JOIN SuccessfulPasses AS SP ON SP.ID = PM.ID
INNER JOIN UnsuccessfulPasses AS USP ON USP.ID = PM.ID
INNER JOIN MatchInfo AS MI ON MI.ID = PM.ID
INNER JOIN Positions AS PO ON PO.PositionID = MI.PositionID
AND PO.Position = 'Attacker'
GROUP BY P.PlayerID
HAVING Total > 400
ORDER BY PercentageSP DESC
LIMIT 0, 10";

$result = mysql_query($query);
 
$DataSet = new pData;
$DataSet2 = new pData;

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
    echo "<tr>";
    echo "<td> {$row['PlayerID']}</td>";
    $DataSet->AddPoint($row['PlayerID'],"XLabel");
    $DataSet2->AddPoint($row['PlayerID'],"XLabel");
    
    echo "<td> {$row['Surname']}</td>";
    echo "<td> {$row['Forename']} </td>";
    
    echo "<td> {$row['TotalSP']} </td>";
    $DataSet->AddPoint($row["TotalSP"],"Serie1");
    
    echo "<td> {$row['Total']} </td>";
    $DataSet->AddPoint($row["Total"],"Serie2");
    
    echo "<td> {$row['TotalUSP']} </td>";
    $DataSet->AddPoint($row["TotalUSP"],"Serie3");
    
    echo "<td> {$row['PercentageSP']} </td>";
    $DataSet2->AddPoint($row["PercentageSP"],"Serie1");
    
    echo "<td> {$row['PercentageUSP']} </td>";
    $DataSet2->AddPoint($row["PercentageUSP"],"Serie2");
    
    echo "</tr>";

}

//Handle the first data set...
$DataSet->AddAllSeries(); 
$DataSet->SetAbsciseLabelSerie("XLabel"); 
$DataSet->RemoveSerie("XLabel");
 
$DataSet->SetSerieName("Total SP","Serie1");  
$DataSet->SetSerieName("Total","Serie2");  
$DataSet->SetSerieName("Total USP","Serie3");

$DataSet->SetXAxisName("Player ID"); 

//Handle the second data set..
$DataSet2->AddAllSeries(); 
$DataSet2->SetAbsciseLabelSerie("XLabel"); 
$DataSet2->RemoveSerie("XLabel");
 
$DataSet2->SetSerieName("Percentage SP","Serie1");  
$DataSet2->SetSerieName("Percentage USP","Serie2");  

$DataSet2->SetXAxisName("Player ID");

//Draw the graph for the first data set...

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
 $Test->drawLegend(596,30,$DataSet->GetDataDescription(),255,255,255);  
 $Test->setFontProperties("Fonts/tahoma.ttf",10);  
 $Test->drawTitle(50,22,"Successful Passes in Final Third",50,50,50,585);  
 $Test->Render("SPFT.png");  
 
 //Draw the graph for the second data set..
 
 // Initialise the graph  
 $Test = new pChart(700,250);  
 $Test->setFontProperties("Fonts/tahoma.ttf",8);  
 $Test->setGraphArea(50,30,680,200);  
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);  
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,250);  
 $Test->drawGraphArea(255,255,255,TRUE);  
 $Test->drawScale($DataSet2->GetData(),$DataSet2->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);     
 $Test->drawGrid(4,TRUE,230,230,230,50);  
  
 // Draw the 0 line  
 $Test->setFontProperties("Fonts/tahoma.ttf",6);  
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);  
  
 // Draw the bar graph  
 $Test->drawBarGraph($DataSet2->GetData(),$DataSet2->GetDataDescription(),TRUE);  
  
 // Finish the graph  
 $Test->setFontProperties("Fonts/tahoma.ttf",8);  
 $Test->drawLegend(396,30,$DataSet2->GetDataDescription(),255,255,255);  
 $Test->setFontProperties("Fonts/tahoma.ttf",10);  
 $Test->drawTitle(50,22,"Percentage Successful Passes",50,50,50,585);  
 $Test->Render("PSPFT.png");
 
?>

   </tbody>
</table>

<br>

<div style="position:absolute;top:170px;right:1px;">
<img src="SPFT.png"/>
</div>

<div style="position:absolute;top:470px;right:1px;">
<img src="PSPFT.png"/>
</div>

<button onClick="history.go(-1)">Back</button>
<button onClick="window.location='Home.php'">Home</button>
</html>