#!/bin/bash

# Variables
garminfile=activity_running_garmin.tcx
wahoofile=activity_running_wahoo.tcx
uploadfile=activity_running_upload.tcx

# Heading
head -15 $garminfile > $uploadfile
grep -A 10 Calories $wahoofile >> $uploadfile

for i in `grep "<Time" $garminfile | awk -F\> '{print $2}' | sed -e s/'<\/Time'//g`
do
grep -A 1 '<Time>'${i} $garminfile >> $uploadfile
grep -A 3 '<Time>'${i} $wahoofile | grep -v '<Time>' >> $uploadfile
grep -A 10 '<Time>'${i} $garminfile | grep -A 7 '<Extensions>' >> $uploadfile
done

# footer
tail -41 $garminfile >> $uploadfile
