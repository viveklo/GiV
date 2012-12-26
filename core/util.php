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

// ftp utilitiy to convert ftp raw list to file list

function rawlist_2_nlist($list) 
{ 
    $newlist = array();
    reset($list); 
    while (list(,$row) = each($list)) 
    {
        $buf="";
        if ($row[0]=='d'||$row[0]=='-'){ 
        $buf = substr($row,51);
	if (($buf == ".") || ($buf == ".."))
	   continue;
           $newlist[]=$buf;
        }
    }

return $newlist; 
}

// utility to call opencv function
function opencv_call ($function, $arglist)
{
   // set opencv env variables
   $savedld = getenv("LD_LIBRARY_PATH");
   $newld = "/usr/local/lib/";
   $newld .=":$savedld";
   putenv("LD_LIBRARY_PATH=$newld");
   $savedpkg = getenv("PKG_CONFIG_PATH");
   $newpkg = "/usr/local/lib/pkgconfig/";
   $newpkg .= ":savedpkg";
   putenv("PKG_CONFIG_PATH");
   foreach ($arglist as $val)
   {
      $function = $function." ".$val;
   }
   exec($function);
}

function check_datetime_in_range($start_date, $end_date, $date_from_user) 
{ 

   // Convert to timestamp 
   $start_ts = strtotime($start_date); 
   $end_ts = strtotime($end_date); 
   $user_ts = strtotime($date_from_user); 

  // Check that user date is between start & end 
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts)); 

} 


function check_time_in_range($start_time, $end_time, $time_from_user) 
{

   // time is in hour (no mins and secs) and hence range comparison
   // is mere comparison of nos
  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));

} 

function populate_clusterdb ($clust, $camera)
{
   $file = fopen("../conf/givmeconfig.txt", "r") or exit("Unable to open file!");
      while(!feof($file))
      {
        $line = fgets( $file );
        if($line == "")
           continue;
        $configarr[strtok($line, "=")] = rtrim(strtok("="));
      }
   fclose($file);

   foreach ($clust as $pts)
   {
      foreach($pts->points as $val2)
      {
         // file name is in format fildb/cam/date/time/20120424_18571649.avi
         // extract the file naame 20120424_18571649.avi from the above string
         $file_with_ext = substr($val2->file, -21);
         $file_without_ext = substr($val2->file, -21, -4);
         //get the date folder
         //extract date-time(hrs) from the file name 20120424_18571649.avi
         $datefoldername = substr($file_with_ext, 0, 4)."-".substr($file_with_ext, 4, 2)."-".substr($file_with_ext, 6, 2);
         $timefoldername = substr($file_with_ext, 9, 2);

         $clusterdir = $configarr["filedbfolder"]."/clusterdb/".$camera."/".$datefoldername."/".$timefoldername."/";
         if (!(file_exists($clusterdir) && is_dir($clusterdir)))
            mkdir($clusterdir,0777,true);

         $file = fopen($clusterdir.$file_with_ext."_c.txt", "a") or exit("Unable to open file".$file_with_ext."_c.txt"."\n");

         fwrite($file, $file_with_ext." ".$val2->objectsize." ".$val2->x." ".$val2->y." ".$val2->motionframe."\n");

         fclose($file);
         //copy the image file from filedb to clusterdb
         exec("cp ".$val2->file."_".$val2->motionframe.".jpg ".$clusterdir);
      }
   }
}

// list of files in directory
function get_recursive_dir_list($dir)
{
   $result = array();
   $i = 0;
   
   //check if the specified dir exists
   if (!(file_exists($dir) && is_dir($dir)))
   {
      //print an error saying video not found for this start date
      echo "The specified directory ".$dir."does not exists\n";
      return $result;
   }
   //open handle to dir
   if($camhdl = opendir($dir))
   {
      while(false !== ($datefld = readdir($camhdl)))
      {
         if ($datefld != "." && $datefld != "..")
         {
            //$result[i] = $datefld;
            if($datehdl = opendir($dir."/".$datefld))
            {
               while(false !== ($timefld = readdir($datehdl)))
               {
                  if ($timefld != "." && $timefld != "..")
                  {
                     if($timehdl = opendir($dir."/".$datefld."/".$timefld)) 
                     {
                        //$i = 0;
                        while(false !== ($videofile = readdir($timehdl)))
                        {
                           if($videofile != "." && $videofile != "..")
                           {
                              //read the videofile_.txt to 
                              if(pathinfo($videofile, PATHINFO_EXTENSION) == "txt")
                              {
                                 //get the video file name
                                 $file = fopen($dir."/".$datefld."/".$timefld."/".$videofile, "r") or exit("Unable to open file!");    
                                 while(!feof($file))
                                 {
                                    $line = fgets( $file );
                                    if($line == "")
                                       continue;
                                    $filename = rtrim(strtok($line, " "));
                                    $result[$i]["cameradir"] = $dir;
                                    $result[$i]["date"] = $datefld;
                                    $result[$i]["time"] = $timefld;
                                    $result[$i]["file"] = $filename;
                                    strtok(" "); //objectsize
                                    strtok(" "); // x
                                    strtok(" "); //y
                                    $frame = rtrim(strtok(" "));
                                    $result[$i]["image"] = $filename."_".$frame.".jpg"; 
                                    $i = $i + 1;
                                 } //end while feof
                              }//end if pathinfo
                           }
                        } //end while videofile
                        closedir($timehdl);
                     } //end if timehdl
                  } //end if $timefld
               } //end while timefld
               closedir($datehdl);
            } //end if $datehdl
         }//end if $datefld
      }//while datefld
      closedir($camhdl);
   } //end if camhdl
   //sort the array before returning based on time and file name

   if(empty($result))
      return $result;

   foreach ($result as $key => $row) {
      $time[$key]  = $row["time"];
      $filname[$key] = $row["file"];
   }//foreach
   array_multisort($time, SORT_ASC, $result);
   return $result;
}

function display_search_page($cameralist, $currentcam,$list, $current_page, $record_perpage)
{
$totalpages = ((int) count($list) / $record_perpage) + 1;
$startindex = ($current_page - 1) * record_perpage;
$endindex = $startindex + record_perpage - 1;
//Read the config file
$file = fopen("../conf/givmeconfig.txt", "r") or exit("Unable to open file!");
   while(!feof($file))
   {
     $line = fgets( $file );
     if($line == "")
        continue;
     $configarr[strtok($line, "=")] = rtrim(strtok("="));
   }
fclose($file);
$httpserverbase = $configarr["httpserverbase"];
$httpserverdb = $configarr["httpserverdb"];

//display the option field
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<head> 
<title>Video Search Result</title> 
  <link href="../ui/css/videosearch.css" rel="stylesheet"> 

   <script type="text/javascript">
   function submitform(action, param )
   {
      if (action == 'prev')
      {
         //make pagetodisplay as pagetodisplay - 1
      }
      else if(action = 'next')
      {
         //make pagetodisplay as pagetodisplay + 1
      }
      else if(action = 'cam')
      {
         //make pagetodiaply as 1 and set camera to the selected camera
      }
      //submit the document
      document.videosearch.submit();
   }
   </scipt>
</head>
<body> 
<form name="videosearch" action="http://localhost/xampp/giv/core/videodearch.php" method="POST">

<input type="hidden" name="pagetodisplay" value="notset" />
<input type="hidden" name="cameraname" value="notset" />

<select id="styledSelect" class="blueText" onChange="submitform('cam, document.getElementById('styledSelect'))> 
  <option value="<?=$currentcam?>">
<?php
foreach ($camlist as $cam)
{
   if ($cam == $currentcam)
      continue;
   ?> <option value="<?=$cam?>">
<?php
} //end foreach camlist ?>

<div id="prevnext" align="center">
<input type="button" class="button" onclick="submitform('prev', null)" />

<?php
   //diplay the page numbers
   for ($k = 1; $k <= $totalpages; $k++)
   { ?>
      <a href="submitform('<?=$k?>')" </a>

<?php
   } ?> 

<input type="button" class="button" onclick="submitform('next',null)" >

<div id="grid">

<?php
//get the index where to start

while ($startindex <= $endindex || $startindex < count($list))
{ 
   $item = $list[$startindex]; ?>

   <img src="<?=$httpserverdb."/".$currentcam."/".$item["date"]."/".$item["time"]."/".$item["image"]?>" height="100" width="100"/>

<?php    
   $startindex = $startindex + 1;
} //end while

}


?> 

