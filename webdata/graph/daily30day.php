<?php
 
/* Include all the classes */
include("pchart/class/pData.class.php");
include("pchart/class/pDraw.class.php");
include("pchart/class/pImage.class.php");
//Include DB connection Infor
include("../includes/db_config.php");

if(isset($_GET['port'])){
$varService = $_GET['port'];
}else{
$varService = 0;
}
 
/* Create your dataset object */
$myData = new pData(); 
 
$db = mysql_connect($servername, $username, $password); //location of server, db username, db pass
mysql_select_db($dbname, $db);

$Requete = "SELECT amount, time, type FROM stats WHERE service = '".$varService."' and type = 'daily' AND time > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 710 HOUR) ORDER BY time"; //table name
$Result = mysql_query($Requete, $db);
 
/*This fetches the data from the mysql database, and adds it to pchart as points*/
while($row = mysql_fetch_array($Result))
{
    //$Sample_Number = $row["Sample_Number"]; //Not using this data
    //$myData->addPoints($Sample_Number,"Sample_Number");
     
    $Time = $row["time"];
    $myData->addPoints($Time,"time");
     
    $Amt = $row["amount"];
    $myData->addPoints($Amt,"amount");
     
  //  $Type = $row["type"];
  //  $myData->addPoints($Type,"type");
}
 
$myData-> setSerieOnAxis("amount", 0); //assigns the data to the frist axis
$myData-> setAxisName(0, "# of Connections"); //adds the label to the first axis
 
//$myData-> setSerieOnAxis("type", 1);
//$myData-> setAxisName(1, "Service");
 
$myData-> setAxisPosition(1,AXIS_POSITION_LEFT); //moves the second axis to the far left
 
$myData->setAbscissa("time"); //sets the time data set as the x axis label
 
 
$myPicture = new pImage(950,320,$myData); /* Create a pChart object and associate your dataset */
$rectsettings = array("R"=>224, "G"=>224, "B"=>224, "Dash"=>1, "DashR"=>200, "DashG"=>200, "DashB"=>200);
$myPicture->drawFilledRectangle(0,0,950,320,$rectsettings); 
$myPicture->setFontProperties(array("FontName"=>"pchart/fonts/verdana.ttf","FontSize"=>8)); /* Choose a nice font */
 
$myPicture->setGraphArea(75,20,850,200); /* Define the boundaries of the graph area */
 
$Settings = array("R"=>250, "G"=>250, "B"=>250, "Dash"=>1, "DashR"=>0, "DashG"=>0, "DashB"=>0);
 
$myPicture->drawScale(array("LabelRotation"=>300)); /* Draw the scale, keep everything automatic */
$myPicture->drawFilledRectangle(75,20,850,200,array("R"=>46,"G"=>46,"B"=>46,"Surrounding"=>-200,"Alpha"=>10));
/*The combination makes a cool looking graph*/
$myPicture->drawPlotChart();
$myPicture->drawLineChart();
 
 
//$myPicture->drawLegend(90,20); //adds the legend
 
$myPicture->autoOutput(); /* Build the PNG file and send it to the web browser */
 
?>
