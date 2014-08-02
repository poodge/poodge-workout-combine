#!/usr/bin/php -q
<?php
$wahoo_file = "20140726/activity_running_wahoo.tcx";
$garmin_file = "20140726/activity_running_garmin.tcx";
//$wahoo_xml = simplexml_load_file($wahoo_file, 'SimpleXMLElement',LIBXML_NOCDATA); 
$wahoo_xml = simplexml_load_file($wahoo_file); 
$garmin_xml = simplexml_load_file($garmin_file); 


    
//echo $wahoo_xml->Activities->Activity->Id;
//echo $wahoo_xml->Activities->Activity->Lap->TotalTimeSeconds;
//foreach ($wahoo_xml->Activities->Activity->Lap->Track->Trackpoint->Time as $value){ 
// echo $value;
//}
//echo '<pre>';
//echo "<br>";
//echo "<TrainingCenterDatabase";
//echo 'xsi:schemaLocation="http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2 http://www.garmin.com/xmlschemas/TrainingCenterDatabasev2.xsd"';

//$xml_tcd = $xml->createElement("TrainingCenterDatabase\
 //xsi:schemaLocation="http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2\
//http://www.garmin.com/xmlschemas/TrainingCenterDatabasev2.xsd"\
  //xmlns:ns5="http://www.garmin.com/xmlschemas/ActivityGoals/v1"\
  //xmlns:ns3="http://www.garmin.com/xmlschemas/ActivityExtension/v2"\
  //xmlns:ns2="http://www.garmin.com/xmlschemas/UserProfile/v2"\
  //xmlns="http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2"\
  //xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns4="http://www.garmin.com/xmlschemas/ProfileExtension/v1");
//$xml_tcd->appendChild( $xml_activities);

$xml = new DOMDocument('1.0', 'utf-8');
header("Content-Type: text/plain");
$xml_activities = $xml->createElement("Activites");
$xml->appendChild($xml_activities);

//Creating the activity
$xml_sport = $xml->createElement("Activity");
$xml_activities->appendChild($xml_sport);
$xml_sport_act = $xml->createAttribute("Sport");
$xml_sport->appendChild($xml_sport_act);
$xml_sport_val = $xml->createTextNode("Running");
$xml_sport_act->appendChild($xml_sport_val);

$xml_id = $xml->createElement("ID");
$xml_sport->appendChild($xml_id);

$xml_id_text = $xml->createTextNode($wahoo_xml->Activities->Activity->Id);
$xml_id->appendChild($xml_id_text);

//Creating the lap start
$xml_lap = $xml->createElement("Lap");
$xml_sport->appendChild($xml_lap);
$xml_lap_start = $xml->createAttribute("Start");
$xml_lap->appendChild($xml_lap_start);
$xml_lap_val = $xml->createTextNode("Some val here");
$xml_lap_start->appendChild($xml_lap_val);

//Creating the totals
$xml_total_time = $xml->createTextNode(



$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
//$xml->loadXML($simpleXml->asXML());
//echo $xml->saveXML();
$xml->save("test.xml");

//print_r($wahoo_xml); 
?>
