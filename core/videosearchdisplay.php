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

$cameraname = array();
$cameraoptionname = array();
$startdate = array();
$enddate = array();
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

$maxcamcount = 4;
$i = 0;
$j = 0;
$camtodisplay = NULL;
$camindex = -1;

//check if request is from main page
if ((!empty($_GET["req"]))|| (!empty($_POST["camtodisplay"])))
{
   while ($i < $maxcamcount)
   {
      if (empty($_POST["camera".strval($i+1)]))
      {
         $cameraname[$i] = NULL;
         $startdatetime[$i] = NULL;
         $starttime[$i] = NULL;
         $enddatetime[$i] = NULL;
         $endtime[$i] = NULL;
         $i = $i + 1;
         continue;
      }
      //echo "Camername ".$cameraname[$i]."\n";
      $cameraname[$i] = $_POST["camera".strval($i+1)];
      $cameranameoption[$j] = $_POST["camera".strval($i+1)];
      $startdatetime[$i] = $_POST["startdate"."camera".strval($i+1)];
      $startdate[$i] = strtok($_POST["startdate"."camera".strval($i+1)], " ");
      //echo "Start Date ".$startdate[$i]."\n";
      $starttime[$i] = strtok(" ");
      //echo "Start time ".$starttime[$i]."\n";
      $enddatetime[$i] = $_POST["enddate"."camera".strval($i+1)];
      $enddate[$i] = strtok($_POST["enddate"."camera".strval($i+1)], " ");
      //echo "End Date ".$enddate[$i]."\n";
      $endtime[$i] =  strtok(" ");
      //echo "End time ".$endtime[$i]."\n";

      if ($camindex == -1)
         $camindex = $i;
      $j = $j + 1;
      $i = $i + 1;
   }//end while
}//end if empty get req
else
   echo "Invalid Request";

$i=0;
$j=0;

$pagetodisplay = $_POST["pagetodisplay"];

$camtodisplay = (!empty($_POST["camtodisplay"])) ? $_POST["camtodisplay"]: $cameranameoption[0];
$camindex = (!empty($_POST["camindex"])) ? $_POST["camindex"]: $camindex;

   //Get the filedb parameter
   $clusterdir = $configarr["filedbfolder"]."/clusterdb/";

   //Check if start and end date and time 
   $startdatefolder = $clusterdir.$camtodisplay."/".$startdate[$camindex]."/";
   $starttimefolder =  $clusterdir.$camtodisplay."/".$startdate[$camindex]."/".$starttime[$camindex]."/";


   //get images from start date to either end date or the end of directory
   //whichever is earlier

   //Get the datefolder list and timefolder list
   $dblist = get_recursive_dir_list($clusterdir.$camtodisplay."/");
   if(empty($dblist))
   {
      echo "No data in ".$camtodisplay." folder \n";
      exit;
   }

   // display the result according to the start and end criteria
   //print_r($dblist);
   $k = 0;
   foreach ($dblist as $item)
   {
      //item date and time in range start and end date and time
      $itemdatetime = $item["date"]." ".$item["time"].":00";
      if (!check_datetime_in_range($startdatetime[$camindex].":00", $enddatetime[$camindex].":00", $itemdatetime))
      {
         unset($dblist[$k]);
      }
      $k = $k + 1;
   }
   $dblist = array_values($dblist);
   //print_r($dblist);
   //print_r($dblist);
   if(empty($dblist))
   {
      echo "No data in the specified range ".$camtodisplay." folder \n";
      exit;
   }
   //print_r($dblist);

//Each page will display 60 images determine the pages change below param to 60
$recordperpage = 60;

$totalpages = (int)(((int) count($dblist) / $recordperpage) + 1);

$prevdisabled = NULL;
$nextdisabled = NULL;
if ($pagetodisplay == 1)
   $prevdisabled = "disabled";

if ($pagetodisplay == $totalpages)
   $nextdisabled = "disabled";

$startindex = ($pagetodisplay - 1) * $recordperpage;
$endindex = $startindex + $recordperpage - 1;

//httpserverbase is not used
$httpserverbase = $configarr["httpserverbase"];
$imageserverdb = $configarr["imageserverdb"];

?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<head> 
<title>Video Search Result</title> 
  <link href="../ui/css/videosearch.css" rel="stylesheet"/> 

   <script type="text/javascript">
   function submitform(action, param )
   {
      var jscam = new Array(); 

      //get and set the camindex 
      <?php
         foreach($cameraname as $cam)
         {
            if (!empty($cam))
               print"jscam.push('$cam');";
         }
      ?>     
 
      for (var i =0; i<jscam.length;i++)
      {
         if (param == jscam[i])
            document.videosearchdisplay.camindex.value = i 
      }
         
      if (action == 'prev')
      {
         //make pagetodisplay as pagetodisplay - 1
         if(document.videosearchdisplay.pagetodisplay.value >  2)
         {
            document.videosearchdisplay.pagetodisplay.value = parseInt(document.videosearchdisplay.pagetodisplay.value, 10) - 1;
            document.videosearchdisplay.camtodisplay.value = param;
         }
         else
            return;
         
      }
      else if(action == 'next')
      {
         //make pagetodisplay as pagetodisplay + 1
         document.videosearchdisplay.pagetodisplay.value = parseInt(document.videosearchdisplay.pagetodisplay.value, 10) + 1;
         document.videosearchdisplay.camtodisplay.value = param;
      }
      else if(action == 'cam')
      {
         //make pagetodiaply as 1 and set camera to the selected camera
         document.videosearchdisplay.pagetodisplay.value = 1;
         document.videosearchdisplay.camtodisplay.value = param;
      }
      else 
      {
         //make pagetodiaply as 1 and set camera to the selected camera
         document.videosearchdisplay.pagetodisplay.value = action;
         document.videosearchdisplay.camtodisplay.value = param;
      }
      //submit the document
      document.videosearchdisplay.submit();
   }
   </script>
</head>
<body> 
<form name="videosearchdisplay" action="videosearchdisplay.php" method="POST">

<div align="center">
<select id="styledSelect" class="blueText" onChange="submitform('cam', document.getElementById('styledSelect').value)"> 
  <option value="<?=$camtodisplay?>">
     <?=$camtodisplay?>
   </option>

<?php
foreach ($cameranameoption as $val)
{
   if(($val == $camtodisplay) || empty($val))
      continue;
   print("<option value=".$val."> ".$val."</option>");
} //end foreach cameraname 

?>
</select>
</div>

<div id="prevnext" class="prevnext" align="center" font-family="Arial">
<input type="button" class="prevbutton" onclick="submitform('prev', '<?=$camtodisplay?>')" <?=$prevdisabled?> />

<?php
   //diplay the page numbers
   for ($k = 1; $k <= $totalpages; $k++)
   {
      if ($k != $pagetodisplay)
         print("<a href=\"javascript:submitform('$k', '$camtodisplay' )\">".$k."</a> ");
      else
         print("".$k." ");
   } 
?> 

<input type="button" class="nextbutton" onclick="submitform('next','<?=$camtodisplay?>')" <?=$nextdisabled?> />

<p>
<div id="grid">

<?php
//get the index where to start
while (($startindex <= $endindex) && ($startindex < count($dblist)))
{ 
   $item = $dblist[$startindex]; 
   $imageloc = $imageserverdb."/clusterdb/".$camtodisplay."/".$item["date"]."/".$item["time"]."/".$item["image"];
   print("<div class=\"image-with-text\" >");
   print(" <img src=\"$imageloc\"/> ");
   //get the date and video file name
   $date = substr($item["image"],0,4)."-".substr($item["image"],4,2)."-".substr($item["image"],6,2);
   $time = substr($item["image"],9,2).":".substr($item["image"],11,2).":".substr($item["image"],13,2);
   $filename = substr($item["image"],0,21);
   print("<p>");
   print($date." ".$time);
   print("</p>");
   print("<p>");
   print($filename);
   print("</p>");
   print("</div>");
   $startindex = $startindex + 1;
} //end while

?>
   </div>
   </p>
   <input name="pagetodisplay" type="hidden" value=<?=$pagetodisplay?> />
   <input name="camtodisplay" type="hidden" value="<?=$camtodisplay?>" />
   <input name="camindex" type="hidden" value="<?=$camindex?>" />

   <input id="camera1" name="camera1" value="<?=$cameraname[0]?>" type="hidden" />
   <input id="camera2" name="camera2" value="<?=$cameraname[1]?>" type="hidden" />
   <input id="camera3" name="camera3" value="<?=$cameraname[2]?>" type="hidden" />
   <input id="camera4" name="camera4" value="<?=$cameraname[3]?>" type="hidden" />

   <input id="startdatecamera1" name="startdatecamera1" value="<?=$startdatetime[0]?>" type="hidden" />
   <input id="enddatecamera1" name="enddatecamera1" value="<?=$enddatetime[0]?>" type="hidden" />

   <input id="startdatecamera2" name="startdatecamera2" value="<?=$startdatetime[1]?>"  type="hidden"/>
   <input id="enddatecamera2" name="enddatecamera2" value="<?=$enddatetime[1]?>"  type="hidden"/>

   <input id="startdatecamera3" name="startdatecamera3" value="<?=$startdatetime[2]?>"  type="hidden"/>
   <input id="enddatecamera3" name="enddatecamera3" value="<?=$enddatetime[2]?>"  type="hidden"/>

   <input id="startdatecamera4" name="startdatecamera4" value="<?=$startdatetime[3]?>"  type="hidden"/>
   <input id="enddatecamera4" name="enddatecamera4" value="<?=$enddatetime[3]?>"  type="hidden"/>

   </form>
   </body>
   </html>
