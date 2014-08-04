#!/usr/bin/php -q
<?php
$wahoo_file = "20140726/activity_running_wahoo.tcx";
$garmin_file = "20140726/activity_running_garmin.tcx";
//$wahoo_xml = simplexml_load_file($wahoo_file, 'SimpleXMLElement',LIBXML_NOCDATA); 
$wahoo_xml = simplexml_load_file($wahoo_file); 
$garmin_xml = simplexml_load_file($garmin_file); 

// Functions Start
function flatten_array($array, $preserve_keys = 0, &$out = array()) {
    # Flatten a multidimensional array to one dimension, optionally preserving keys.
    #
    # $array - the array to flatten
    # $preserve_keys - 0 (default) to not preserve keys, 1 to preserve string keys only, 2 to preserve all keys
    # $out - internal use argument for recursion
    foreach($array as $key => $child)
        if(is_array($child))
            $out = flatten_array($child, $preserve_keys, $out);
        elseif($preserve_keys + is_string($key) > 1)
            $out[$key] = $child;
        else
            $out[] = $child;
    return $out;
}

function str_replace_json($search, $replace, $subject){ 
     return json_decode(str_replace($search, $replace,  json_encode($subject))); 

}


//Functions End
 
//$xml2 = simplexml_load_file( $file2 );	
//	foreach( $xml2->FOO as $foo ) {
//		$new = $xml1->addChild( 'FOO' , $foo );
//		foreach( $foo->attributes() as $key => $value ) {
//			$new->addAttribute( $key, $value );
//		}

    
$wahoo_xml_new = flatten_array($wahoo_xml->Activities->Activity->Lap->Track->Trackpoint);
$wahoo_xml_new = str_replace_json('Z', '.00Z', $wahoo_xml_new);
$garmin_xml_new = flatten_array($garmin_xml->Activities->Activity->Lap->Track->Trackpoint);


//$result = array();
//foreach( $garmin_xml_new->Time as $keyA => $valA ) {
  //foreach( $wahoo_xml_new->Time as $keyB => $valB ) {
     //if( $valA['id'] == $valB['id'] ) {
       //$result[$keyA] = $valA + $valB;

       // or if you do not care output keys, just
       // $result[] = $valA + $valB;
     //}
  //}
//}

var_dump($wahoo_xml_new);

$xml = new DOMDocument('1.0', 'utf-8');
header("Content-Type: text/plain");

$xml_tcd = $xml->createElement("TrainingCenterDatabase");
$xml->appendChild($xml_tcd);

// Start all the garmin info
$xml_tcd_act1 = $xml->createAttribute("xsi:schemaLocation");
$xml_tcd->appendChild($xml_tcd_act1);
$xml_tcd_val1 = $xml->createTextNode("http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2");
$xml_tcd_act1->appendChild($xml_tcd_val1);

$xml_tcd_act2 = $xml->createAttribute("xmlns:ns5");
$xml_tcd->appendChild($xml_tcd_act2);
$xml_tcd_val2 = $xml->createTextNode("http://www.garmin.com/xmlschemas/ActivityGoals/v1");
$xml_tcd_act2->appendChild($xml_tcd_val2);

$xml_tcd_act3 = $xml->createAttribute("xmlns:ns4");
$xml_tcd->appendChild($xml_tcd_act3);
$xml_tcd_val3 = $xml->createTextNode("http://www.garmin.com/xmlschemas/ProfileExtension/v1");
$xml_tcd_act3->appendChild($xml_tcd_val3);

$xml_tcd_act4 = $xml->createAttribute("xmlns:ns3");
$xml_tcd->appendChild($xml_tcd_act4);
$xml_tcd_val4 = $xml->createTextNode("http://www.garmin.com/xmlschemas/ActivityExtension/v2");
$xml_tcd_act4->appendChild($xml_tcd_val4);

$xml_tcd_act5 = $xml->createAttribute("xmlns:ns2");
$xml_tcd->appendChild($xml_tcd_act5);
$xml_tcd_val5 = $xml->createTextNode("http://www.garmin.com/xmlschemas/UserProfile/v2");
$xml_tcd_act5->appendChild($xml_tcd_val5);

$xml_tcd_act6 = $xml->createAttribute("xmlns");
$xml_tcd->appendChild($xml_tcd_act6);
$xml_tcd_val6 = $xml->createTextNode("http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2");
$xml_tcd_act6->appendChild($xml_tcd_val6);

$xml_tcd_act7 = $xml->createAttribute("xmlns:xsi");
$xml_tcd->appendChild($xml_tcd_act7);
$xml_tcd_val7 = $xml->createTextNode("http://www.w3.org/2001/XMLSchema-instance");
$xml_tcd_act7->appendChild($xml_tcd_val7);


// End garmin info

$xml_activities = $xml->createElement("Activites");
$xml_tcd->appendChild($xml_activities);

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
$xml_lap_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->attributes()->StartTime);
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
//$xml->save("test.xml");

//echo $garmin_xml->Activities->Activity->Lap->TotalTimeSeconds
//print_r($wahoo_xml_new2); 
foreach ($wahoo_xml_new as $key => $value) {
    echo "$key = $value\n";
}
?>
