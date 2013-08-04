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

<p class="trebMS14">What is the ratio of goals scored to points for teams in the EPL? </p>

<p class="trebMS20">Teams in the EPL</p>

<table id="GK" cellspacing="0">
  <tbody>
    <tr>
        <th>TeamID<br> </th> 
     	<th>TeamName<br> </th> 
        </tr>
   <tr>

<?php 
 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpassword = 'root';

$conn = mysql_connect($dbhost, $dbuser, $dbpassword) or die ('Error connecting to mysql');

$dbname = 'testdb';

mysql_select_db($dbname);

$query  = "SELECT TeamID, TeamName FROM Teams";

$result = mysql_query($query);

$TeamIDList="<option values='1'> &nbsp</option>";

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
    echo "<tr>";
    echo "<td> {$row['TeamID']}</td>";
    echo "<td> {$row['TeamName']}</td>";
    echo "</tr>";
    
    $TeamIDList="{$TeamIDList} <option value='{$row['TeamID']}:{$row['TeamName']}'> {$row['TeamName']} </option> ";

}
 echo "</tbody>";
 echo "</table>";

	echo "<div style=\"position:absolute;top:370px;right:565px;\">";
	echo "<form action=\"PointsPerGoalModel.php\" method=\"post\" enctype=\"multipart/form-data\">";
	echo "Select Team to Analyze:";
    echo "<select name ='TeamName'> {$TeamIDList} </select><br><br>";
    echo "<input type='submit' value='Analyze'> </br> ";
    echo "</div>";
?>
 
 </form>
<br><br>   
<button onClick="history.go(-1)">Back</button>
<button onClick="window.location='Home.php'">Home</button><br><br><br>

<button style="background-color:lightblue" onClick="window.location='http://www.cs.ox.ac.uk'">Department of CS</button>

</html> 