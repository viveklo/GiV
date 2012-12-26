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

// Get theargument
var_dump($argv);
$processname = $argv[1];
$startdate = $argv[2];
$starttime = $argv[3];
$enddate = $argv[4];
$endtime = $argv[5];

echo $processname;

//Read the config file and get the parameters
$file = fopen("../conf/givmeconfig.txt", "r") or exit("Unable to open file!");
while(!feof($file))
{
  $line = fgets( $file );
  if($line == "")
     continue;
  $configarr[strtok($line, "=")] = rtrim(strtok("=")); 
}
fclose($file);

// Get the correct camera name from the config file
$camera = $configarr[$processname];
$polygon = explode(",", $configarr[$processname."polygon"]);

//Get the rest of parameters
$ftpserver = $configarr["ftpserver"];
$ftpport = $configarr["ftpport"];
$ftpusername = $configarr["ftpusername"];
$ftppwd = $configarr["ftppwd"];
$ftpfolder = $configarr["ftpfolder"];


//login to NVR
$connect_id = ftp_connect($ftpserver);
if ($connect_id) 
   $login_result = ftp_login($connect_id, $ftpusername, $ftppwd); 

// check connection or login
if ((!$connect_id) || (!$login_result)) {
   echo "Unable to connect the remote FTP Server!\n";
   exit;
}

//Set the passive mode. requried for windows ftp server
ftp_pasv ($connect_id, true);

// Get the directory list from ftp serevr
$contents = ftp_rawlist($connect_id, $camera);
var_dump ($contents);
$camlist = rawlist_2_nlist($contents);
if (empty ( $camlist ))
{
   echo "No files in ".$camera."\n";
   exit;
}

var_dump($camlist);

foreach  ($camlist as $datefolder)
{
   echo "datefolder ". $datefolder."\n";

   //Check if the datefolder is within start and end date..not and efficient way

   if (!check_datetime_in_range($startdate, $enddate, $datefolder))
   {
      // go to the next date
      continue;
   }

   $datefolder1 = $camera.'/'.$datefolder."/";
   $contents = ftp_rawlist($connect_id, $datefolder1);
   echo "Datefolder1 ".$datefolder1."\n";
   $timefolder = rawlist_2_nlist($contents);
   if (empty ( $timefolder ))
   {
      echo "No files in ".$datefolder1."\n";
      exit;
   }
   var_dump($timefolder);

   foreach ($timefolder as $timefold)
   { 
      //Init point array used for kmeans 
      $pointarr = array();

      if (!check_datetime_in_range($startdate." ".$starttime, $enddate." ".$endtime, $datefolder." ".$timefold.":00"))
      {
         // go to the next date
         continue;
      }

      //check if the folder is processed and present in clusterdb
      $timeclusterdir = $configarr["filedbfolder"]."/clusterdb/".$camera."/".$datefolder."/".$timefold;
      if ((file_exists($timeclusterdir) && is_dir($timeclusterdir)))
      {
         //This time is processed.. hence we will proceed to next time
         continue;
      }

      echo $timefold."\n";

      $timefold1 = $datefolder1.'/'.$timefold.'/';
      $contents = ftp_rawlist($connect_id, $timefold1);
      $videofiles = rawlist_2_nlist($contents);
      if (empty ( $videofiles ))
      {
         echo "No files in ".$timefold1."\n";
	 continue;
      }
      var_dump($videofiles);
      foreach ($videofiles as $file)
      {
         echo $file."\n";
	 //check if this file is processed 
	 //get processfolder param
	 $processdir = $configarr["filedbfolder"]."/rawdb/".$camera."/".$datefolder."/".$timefold."/";
	 $motionfile = $processdir.$file."_m.txt";
	 if (file_exists($motionfile))
	 {
	    echo $motionfile." file exits\n";
	 }
	 else
	 {
	    //make dir for the video to be downloaded from ftp server
	    mkdir($processdir, 0777,true);
	    //echo $motionfile." file does not exists\n";
	    //Get the file from NVR	    
            //local file name with directory
            $localfile = $processdir.$file;
	    if(ftp_get ( $connect_id, $localfile, $timefold1.$file, FTP_BINARY ))
	    {
               $localfilestd = $localfile."_m\.avi";
	       //Start processing the videos here through opencv
               // first get the format the video using ffmpeg in stad format
               exec("ffmpeg -y -i $localfile -acodec libmp3lame -vcodec msmpeg4 -b:a 192k -b:v 1000k -vf scale=640:480 -ar 44100 -r 10 ".$localfilestd);

               //rename the file back to original
               exec("mv ".$localfilestd." ".$localfile);
               
               // Get the image snapshot of the video we will need it later
               //exec("ffmpeg -ss 00:00:12  -y -i $localfile -f image2 ".$localfile.".jpg"); 
               //Set env variables and call opencv
               $parmlist[0] = $localfile;
	       opencv_call("/home/fedora/bnhs/giv/motempl", $parmlist);

               //the processed file and snapshot is in videotemp get it back to process dir
               //exec("mv ".$localfile."_m.txt ".$processdir);
               //exec("mv ".$localfile.".jpg ".$processdir);

               // remove the video file in videotemp
               exec("rm -f ".$localfile);

            } //end if ftp_get
	    else
	    {
	       echo "Could not ftp file ".$file."\n";
	    }
	 } //end if else file_exists

         //load the motempl file in an array to process using k means
         load_file_for_kmeans($processdir.$file."_m.txt", $pointarr);
      } //end for videofiles as file

      //Cluster Count 10% of no of point array count
      $clust_count = (int) (count($pointarr)) * 0.1;
      if ($clust_count <= 0)
      {
         echo "No clusters for kmeans \n";
         echo "Continue with next time folder...\n";
         continue;
      }
      var_dump($pointarr);
      $clust = distributeOverClusters($clust_count, $pointarr);
      if (count($clust) <= 0)
      {
         echo "No clusters after distributeOverClusters \n";
         echo "Continue with next time folder...\n";
         continue;
      }
      var_dump($clust);
  
      //find out the points inside polygon 
      $clust = find_points_in_polygon($clust, $polygon);
      if (count($clust) <= 0)
      {
         echo "No clusters after find_points_in_polygon \n";
         echo "Continue with next time folder...\n";
         continue;
      }

      populate_clusterdb ($clust, $camera);

      //empty the raw dir (process dir) since our work is done
      exec("rm -rf ".$processdir);
   } //end for timefolder as timefold
} //end for camlist as datefolder

//close ftp connection
ftp_close($connect_id);

// Get the filename to be proceesed
//Check if its already processed
// If processed go to the next file
//else 
// a. ftp to local machine
// b. Encode to the format (640x480, ms-avi) using ffmpeg
// c. process the file using opencv on the entire video - motion detection
// d. apply k-means to the file generated from step c.
// e. search from this file based on the marked area
// f. output the area


?>
