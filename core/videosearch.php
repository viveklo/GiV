<?php
// @(#) $Id$
// +-----------------------------------------------------------------------+
// | Copyright (C) 2008, http://yoursite                                   |
// +-----------------------------------------------------------------------+
// | This file is free software; you can redistribute it and/or modify     |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation; either version 2 of the License, or     |
// | (at your option) any later version.                                   |
// | This file is distributed in the hope that it will be useful           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of        |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the          |
// | GNU General Public License for more details.                          |
// +-----------------------------------------------------------------------+
// | Author: pFa                                                           |
// +-----------------------------------------------------------------------+
//

//Incldue files
include_once("util.php"); 
include_once("kmeans.php"); 
include_once("videodisplay.php"); 

// Get theargument
//var_dump($argv);
$cameraname = array();
$startdate = array();
$enddate = array();
$fileindexprev = array();
$fileindexnext = array();

//read the givmeconfig.txt file
$file = fopen("../conf/givmeconfig.txt", "r") or exit("Unable to open file!");
while(!feof($file))
{
  $line = fgets( $file );
  if($line == "")
     continue;
  $configarr[strtok($line, "=")] = rtrim(strtok("="));
}
fclose($file);

$cameracount = $configarr["cameracount"];
$cameracount = $configarr["cameracount"];
$maxcamcount = 4;


$i = 0;
$j = 0;
while ($j < $maxcamcount)
{
    if (empty($_POST["camera".strval($j+1)])
    {
       $j = $j + 1;
       continue;
    }
    echo "Camername ".$cameraname[$i]."\n";
    $cameraname[$i] = $_POST["camera".strval($i+1)];
    $startdate[$i] = strtok($_POST["startdate"."camera".strval($i+1)], " ");
    echo "Start Date ".$startdate[$i]."\n";
    $starttime[$i] = strtok(" ");
    echo "Start time ".$starttime[$i]."\n";
    $enddate[$i] = strtok($_POST["enddate"."camera".strval($i+1)], " ");
    echo "End Date ".$enddate[$i]."\n";
    $endtime[$i] =  strtok(" ");
    echo "End time ".$endtime[$i]."\n";
    $fileindexprev[$i] = $_POST["fileindexprev"."camera".strval($i+1)];
    echo "Fileindex prev ".$fileindexprev[$i]."\n";
    $fileindexnext[$i] = $_POST["fileindexnext"."camera".strval($i+1)];
    echo "Fileindex next ".$fileindexnext[$i]."\n";
    $i = $i + 1;
    $j = $j + 1;
}

$j= 0;
$i = 0;

foreach ($cameraname as $cam)
{
   //Get the filedb parameter
   $clusterdir = $configarr["filedbfolder"]."/clusterdb/";
   
   //Check if start and end date and time exists/processed for this camera
   $startdatefolder = $clusterdir.$cam."/".$startdate[$i]."/";
   //check if startdatefolder exists
  /* if (!(file_exists($startdatefolder) && is_dir($startdatefolder)))
   {
      //print an error saying video not found for this start date
      echo "No videos exists for the start date ".$startdatefolder[$i]."\n";
      $i = $i + 1;
      continue;  
   } */
 
   //ok the datefolder exists now check time folder
   $starttimefolder =  $clusterdir.$cam."/".$startdate[$i]."/".$starttime[$i]."/";
  /* if (!(file_exists($starttimefolder) && is_dir($starttimefolder)))
   {
      //print an error saying video not found for this start date
      echo "No videos exists for the start time ".$starttimefolder[i]."\n";
      $i = $i + 1;
      continue;
   }*/
   //end date should have already been verfied we will display result
   //from start date to either end date or the end of directory
   //whichever is earlier
   
   //Get the datefolder list and timefolder list 
   $dblist = get_recursive_dir_list($clusterdir.$cam."/");
   if(empty($dblist))
   {
      echo "No data in ".$cam." folder \n";
      $i = $i + 1;
      continue;
   }
   // display the result according to the start and end criteria
   print_r($dblist);

   $k = 0;
   foreach ($dblist as $item)
   {
      //item date and time in range start and end date and time
      $itemdatetime = $item["date"]." ".$item["time"].":00";
      if (!check_datetime_in_range($startdate[$i], $enddate[$i], $itemdatetime))
      {
         unset($dblist[$k]);
      }
      $k = $k + 1;
   }
   array_values($dblist);
   if(empty($dblist))
   {
      echo "No data in the specified range ".$cam." folder \n";
      $i = $i + 1;
      continue;
   }
   print_r($dblist);
} //foreach $cameraname

//Each page will display 60 images determine the pages change below param to 60
$recordperpage = 3;
$totalpages = ((int) count($dblist) / $recordperpage) + 1;
$totalpages = $pages + $lastpage;

if ($fileindex == -1)
   $pagetodisplay = 1; 

display_search_page($dblist, $totalpages, $pagetodisplay, $recordperpage);


 
/*foreach $camername as $cam
{
   //go through the loop 

   //go to clusterdb check if start date and end date directory exits

   //check if start and end time exits

   //set search date as start date
   //set search time as start time
   // while search date <= end date
   //    go to search date dierctory
   //     go to search time directory
   //     retrieve the files and retrieve information 

     //display info based on fileindexnext and fileindexprev
} */
     
?>
