#!/usr/bin/php -q
<?php
$wahoo_file = "20140729/activity_running_wahoo.tcx";
$garmin_file = "20140729/activity_running_garmin.tcx";
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

function searchForHR($id, $array) {
   foreach ($array as $val) {
       $time = substr($id, 0, 19) . "Z";
       //echo "whaoo time is $val->Time   garmin time is $time";
       if ($time == $val->Time) {
           $hr = ($val->HeartRateBpm->Value);
	   if (!$hr) {
             $hr = "0";
           }
           return $hr;
       }
   }
   return null;
}
//Functions End
 

//Flattening Arrays and making wahoo time match garmin
$wahoo_xml_new = flatten_array($wahoo_xml->Activities->Activity->Lap->Track->Trackpoint);
//$wahoo_xml_new = str_replace_json('Z', '.00Z', $wahoo_xml_new);
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


$xml = new DOMDocument('1.0', 'utf-8');
header("Content-Type: text/plain");

$xml_tcd = $xml->createElement("TrainingCenterDatabase");
$xml->appendChild($xml_tcd);

// Start all the garmin info
$xml_tcd_act1 = $xml->createAttribute("xsi:schemaLocation");
$xml_tcd->appendChild($xml_tcd_act1);
$xml_tcd_val1 = $xml->createTextNode("http://www.garmin.com/xmlschemas/TrainingCenterDatabase/v2 http://www.garmin.com/xmlschemas/TrainingCenterDatabasev2.xsd");
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
$xml_lap_start = $xml->createAttribute("StartTime");
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

//Track and Trackpoint
$xml_t = $xml->createElement("Track");
$xml_lap->appendChild($xml_t);


//Creating Loop for getting all the times
$seq=0;
foreach ($garmin_xml_new as $row) {
 $xml->preserveWhiteSpace = false;
 $xml->formatOutput = true;
 $speed = $row->Extensions->TPX->Speed;
 $cadence = $row->Extensions->TPX->RunCadence;
 $time = $row->Time;
 $distance = $row->DistanceMeters;
 $hr = searchForHR($time, $wahoo_xml_new);
 //$hr = 0;
 if (isset($speed)) {
  $xml_tp = $xml->createElement("TrackPoint");
  $xml_t->appendChild($xml_tp);
   //Getting Time
   $xml_time = $xml->createElement("Time");
   $xml_tp->appendChild($xml_time);
   $xml_time_val = $xml->createTextNode($time);
   $xml_time->appendChild($xml_time_val);

   //Getting Distance
   $xml_dis = $xml->createElement("DistanceMeters");
   $xml_tp->appendChild($xml_dis);
   $xml_dis_val = $xml->createTextNode($distance);
   $xml_dis->appendChild($xml_dis_val);

   //Getting HeartRate
   $xml_hr = $xml->createElement("HeartRateBpm");
   $xml_tp->appendChild($xml_hr);
   $xml_hr_val = $xml->createElement("Value");
   $xml_hr->appendChild($xml_hr_val);
   $xml_hr_val_val = $xml->createTextNode($hr);
   $xml_hr_val->appendChild($xml_hr_val_val);
   
   
   //Setting up some more static info
   $xml_ext = $xml->createElement("Extensions");
   $xml_tp->appendChild($xml_ext);
   $xml_tpx = $xml->createElement("TPX");
   $xml_ext->appendChild($xml_tpx);
   $xml_tpx_act = $xml->createAttribute("xmlns");
   $xml_tpx->appendChild($xml_tpx_act);
   $xml_tpx_val = $xml->createTextNode("http://www.garmin.com/xmlschemas/ActivityExtension/v2");
   $xml_tpx_act->appendChild($xml_tpx_val);
   
   //Getting Speed
   $xml_speed = $xml->createElement("Speed");
   $xml_tpx->appendChild($xml_speed);
   $xml_speed_val = $xml->createTextNode($speed);
   $xml_speed->appendChild($xml_speed_val);

   //Getting Cadence
   $xml_cad = $xml->createElement("RunCadence");
   $xml_tpx->appendChild($xml_cad);
   $xml_cad_val = $xml->createTextNode($cadence);
   $xml_cad->appendChild($xml_cad_val);

 }
  //echo "Speed is $speed, time is $time, cadence is $cadence, and distance is $distance HR is $hr ";
}

//Getting the rest of the data
$xml_tot_ext = $xml->createElement("Extensions");
$xml_lap->appendChild($xml_tot_ext);
$xml_lx1 = $xml->createElement("LX");
$xml_tot_ext->appendChild($xml_lx1);
$xml_lx1_act = $xml->createAttribute("xmlns");
$xml_lx1->appendChild($xml_lx1_act);
$xml_lx1_val = $xml->createTextNode("http://www.garmin.com/xmlschemas/ActivityExtension/v2");
$xml_lx1_act->appendChild($xml_lx1_val);
  $xml_maxruncad = $xml->createElement("MaxRunCadence");
  $xml_lx1->appendChild($xml_maxruncad);
  $xml_maxruncad_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->Extensions->LX[0]->MaxRunCadence);
  $xml_maxruncad->appendChild($xml_maxruncad_val);


$xml_lx2 = $xml->createElement("LX");
$xml_tot_ext->appendChild($xml_lx2);
$xml_lx2_act = $xml->createAttribute("xmlns");
$xml_lx2->appendChild($xml_lx2_act);
$xml_lx2_val = $xml->createTextNode("http://www.garmin.com/xmlschemas/ActivityExtension/v2");
$xml_lx2_act->appendChild($xml_lx2_val);
  $xml_avgruncad = $xml->createElement("AvgRunCadence");
  $xml_lx2->appendChild($xml_avgruncad);
  $xml_avgruncad_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->Extensions->LX[1]->AvgRunCadence);
  $xml_avgruncad->appendChild($xml_avgruncad_val);
  
$xml_lx3 = $xml->createElement("LX");
$xml_tot_ext->appendChild($xml_lx3);
$xml_lx3_act = $xml->createAttribute("xmlns");
$xml_lx3->appendChild($xml_lx3_act);
$xml_lx3_val = $xml->createTextNode("http://www.garmin.com/xmlschemas/ActivityExtension/v2");
$xml_lx3_act->appendChild($xml_lx3_val);
  $xml_avgspeed = $xml->createElement("AvgSpeed");
  $xml_lx3->appendChild($xml_avgspeed);
  $xml_avgspeed_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->Extensions->LX[2]->AvgSpeed);
  $xml_avgspeed->appendChild($xml_avgspeed_val);
  
$xml_lx4 = $xml->createElement("LX");
$xml_tot_ext->appendChild($xml_lx4);
$xml_lx4_act = $xml->createAttribute("xmlns");
$xml_lx4->appendChild($xml_lx4_act);
$xml_lx4_val = $xml->createTextNode("http://www.garmin.com/xmlschemas/ActivityExtension/v2");
$xml_lx4_act->appendChild($xml_lx4_val);
  $xml_steps = $xml->createElement("Steps");
  $xml_lx4->appendChild($xml_steps);
  $xml_steps_val = $xml->createTextNode($garmin_xml->Activities->Activity->Lap->Extensions->LX[3]->Steps);
  $xml_steps->appendChild($xml_steps_val);

//Finish with static info
$xml_cre = $xml->createElement("Creator");
$xml_sport->appendChild($xml_cre);
$xml_cre_act = $xml->createAttribute("xsi:type");
$xml_cre->appendChild($xml_cre_act);
$xml_cre_val = $xml->createTextNode("Device_t");
$xml_cre_act->appendChild($xml_cre_val);
  $xml_name = $xml->createElement("Name");
  $xml_cre->appendChild($xml_name);
  $xml_name_val = $xml->createTextNode("Garmin Forerunner 310XT");
  $xml_name->appendChild($xml_name_val);
  $xml_unit = $xml->createElement("UnitId");
  $xml_cre->appendChild($xml_unit);
  $xml_unit_val = $xml->createTextNode("3826588013");
  $xml_unit->appendChild($xml_unit_val);
  $xml_pi = $xml->createElement("ProductID");
  $xml_cre->appendChild($xml_pi);
  $xml_pi_val = $xml->createTextNode("1018");
  $xml_pi->appendChild($xml_pi_val);
  $xml_ver = $xml->createElement("Version");
  $xml_cre->appendChild($xml_ver);
    $xml_vma = $xml->createElement("VersionMajor");
    $xml_ver->appendChild($xml_vma);
    $xml_vma_val = $xml->createTextNode("4");
    $xml_vma->appendChild($xml_vma_val);
    $xml_vmi = $xml->createElement("VersionMinor");
    $xml_ver->appendChild($xml_vmi);
    $xml_vmi_val = $xml->createTextNode("50");
    $xml_vmi->appendChild($xml_vmi_val);
    $xml_bma = $xml->createElement("BuildMajor");
    $xml_ver->appendChild($xml_bma);
    $xml_bma_val = $xml->createTextNode("0");
    $xml_bma->appendChild($xml_bma_val);
    $xml_bmi = $xml->createElement("BuildMinor");
    $xml_ver->appendChild($xml_bmi);
    $xml_bmi_val = $xml->createTextNode("0");
    $xml_bmi->appendChild($xml_bmi_val);


$xml_aut = $xml->createElement("Author");
$xml_tcd->appendChild($xml_aut);
$xml_aut_act = $xml->createAttribute("xsi:type");
$xml_aut->appendChild($xml_aut_act);
$xml_aut_val = $xml->createTextNode("Application_t");
$xml_aut_act->appendChild($xml_aut_val);
  $xml_aut_name = $xml->createElement("Name");
  $xml_aut->appendChild($xml_aut_name);
  $xml_aut_name_val = $xml->createTextNode("Garmin Connect API");
  $xml_aut_name->appendChild($xml_aut_name_val);
    $xml_bu = $xml->createElement("Build");
    $xml_aut->appendChild($xml_bu);
    $xml_bu_ver = $xml->createElement("Version");
    $xml_bu->appendChild($xml_bu_ver);
      $xml_bu_vma = $xml->createElement("VersionMajor");
      $xml_bu_ver->appendChild($xml_bu_vma);
      $xml_bu_vma_val = $xml->createTextNode("14");
      $xml_bu_vma->appendChild($xml_bu_vma_val);
      $xml_bu_vmi = $xml->createElement("VersionMinor");
      $xml_bu_ver->appendChild($xml_bu_vmi);
      $xml_bu_vmi_val = $xml->createTextNode("7");
      $xml_bu_vmi->appendChild($xml_bu_vmi_val);
      $xml_bu_bma = $xml->createElement("BuildMajor");
      $xml_bu_ver->appendChild($xml_bu_bma);
      $xml_bu_bma_val = $xml->createTextNode("0");
      $xml_bu_bma->appendChild($xml_bu_bma_val);
      $xml_bu_bmi = $xml->createElement("BuildMinor");
      $xml_bu_ver->appendChild($xml_bu_bmi);
      $xml_bu_bmi_val = $xml->createTextNode("0");
      $xml_bu_bmi->appendChild($xml_bu_bmi_val);

  $xml_lang = $xml->createElement("LangID");
  $xml_aut->appendChild($xml_lang);
  $xml_lang_val = $xml->createTextNode("en");
  $xml_lang->appendChild($xml_lang_val);
  
  $xml_part = $xml->createElement("PartNumber");
  $xml_aut->appendChild($xml_part);
  $xml_part_val = $xml->createTextNode("006-D2449-00");
  $xml_part->appendChild($xml_part_val);

$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
//$xml->loadXML($simpleXml->asXML());
//echo $xml->saveXML();
$xml->save("test.tcx");

//echo $garmin_xml->Activities->Activity->Lap->TotalTimeSeconds
//print_r($garmin_xml); 


?>
