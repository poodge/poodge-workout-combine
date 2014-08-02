#!/bin/bash

# Variables
garminfile=activity_biking_garmin.tcx
wahoofile=activity_biking_wahoo.tcx
uploadfile=activity_biking_upload.tcx

# Heading
head -15 $garminfile > $uploadfile
grep -A 10 Calories $wahoofile >> $uploadfile

for i in `grep Time $garminfile | grep -v "/TotalTimeSeconds" | grep -v Lap` 
do
grep_output=`echo $i | grep "Start"`
echo $grep_output
if [ "$grep_output" == "" ]; then
	echo "not lap start";
else
	echo "lapstart";
fi
#echo $i | grep "Lap Start"| awk -F\> '{print $2}' | sed -e s/'<\/Time'//g`
#grep -A 1 '<Time>'${i} $garminfile >> $uploadfile
#grep -A 3 '<Time>'${i} $wahoofile | grep -v '<Time>' >> $uploadfile
#grep -A 10 '<Time>'${i} $garminfile | grep -A 7 '<Extensions>' >> $uploadfile
done

# footer
#tail -41 $garminfile >> $uploadfile
