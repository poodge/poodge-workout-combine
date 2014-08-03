#!/usr/bin/php -q
<?php
$wahoo_file = "20140726/activity_running_wahoo.tcx";
$garmin_file = "20140726/activity_running_garmin.tcx";
//$wahoo_xml = simplexml_load_file($wahoo_file, 'SimpleXMLElement',LIBXML_NOCDATA); 
$wahoo_xml = simplexml_load_file($wahoo_file); 
$garmin_xml = simplexml_load_file($garmin_file); 

//$xml2 = simplexml_load_file( $file2 );	
//	foreach( $xml2->FOO as $foo ) {
//		$new = $xml1->addChild( 'FOO' , $foo );
//		foreach( $foo->attributes() as $key => $value ) {
//			$new->addAttribute( $key, $value );
//		}

$wahoo_xml_new = str_replace('Z', '00Z', $wahoo_xml);
    
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

$xml_id_text = $xml->createTextNode($garmin_xml->Activities->Activity->Id);
$xml_id->appendChild($xml_id_text);

//Creating the lap start
$xml_lap = $xml->createElement("Lap");
$xml_sport->appendChild($xml_lap);
$xml_lap_start = $xml->createAttribute("Start");
$xml_lap->appendChild($xml_lap_start);
$xml_lap_val = $xml->createTextNode("Some val here");
$xml_lap_start->appendChild($xml_lap_val);

//Creating the totals
//Total time
$xml_tts = $xml->createElement("TotalTimeSeconds");
$xml_lap->appendChild($xml_tts);
$xml_tts_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->TotalTimeSeconds);
$xml_tts->appendChild($xml_tts_val);

//DistanceMeters
$xml_dm = $xml->createElement("DistanceMeters");
$xml_lap->appendChild($xml_dm);
$xml_dm_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->DistanceMeters);
$xml_dm->appendChild($xml_dm_val);

//DistanceMeters
$xml_ms = $xml->createElement("MaximumSpeed");
$xml_lap->appendChild($xml_ms);
$xml_ms_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->MaximumSpeed);
$xml_ms->appendChild($xml_ms_val);

//Calories
$xml_cal = $xml->createElement("Calories");
$xml_lap->appendChild($xml_cal);
$xml_cal_val = $xml->createTextNode($wahoo_xml->Activities->Activity->Lap->Calories);
$xml_cal->appendChild($xml_cal_val);

//AverageHeartRateBpm
$xml_ahr = $xml->createElement("AverageHeartRateBpm");
$xml_lap->appendChild($xml_ahr);
$xml_ahr_at = $xml->createElement("Value");
$xml_ahr->appendChild($xml_ahr_at);
$xml_ahr_val = $xml->createTextNode($wahoo_xml->Activities->Activity->Lap->AverageHeartRateBpm->Value);
$xml_ahr_at->appendChild($xml_ahr_val);

//MaximumHeartRateBpm
$xml_mhr = $xml->createElement("MaximumHeartRateBpm");
$xml_lap->appendChild($xml_mhr);
$xml_mhr_at = $xml->createElement("Value");
$xml_mhr->appendChild($xml_mhr_at);
$xml_mhr_val = $xml->createTextNode($wahoo_xml->Activities->Activity->Lap->MaximumHeartRateBpm->Value);
$xml_mhr_at->appendChild($xml_mhr_val);


//Creating static Fields
//Intesity!
$xml_int = $xml->createElement("Intensity");
$xml_lap->appendChild($xml_int);
$xml_int_val = $xml->createTextNode("Active");
$xml_int->appendChild($xml_int_val);

//TriggerMethod
$xml_tm = $xml->createElement("TriggerMethod");
$xml_lap->appendChild($xml_tm);
$xml_tm_val = $xml->createTextNode("Manual");
$xml_tm->appendChild($xml_tm_val);


$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
//$xml->loadXML($simpleXml->asXML());
//echo $xml->saveXML();
$xml->save("test.xml");
//echo $garmin_xml->Activities->Activity->Lap->TotalTimeSeconds
//print_r($arr); 
print_r($wahoo_xml); 
?>
