<?php

function submit_config_param($action)
{ 
//print_r($_GET); 
   //Check for the param for 4 camera. not a good way
   // Also not much error checking

   //open config file for reading and then write the whole file back
   $file = fopen("../../conf/givmeconfig.txt", "r") or exit("Unable to open file!");
   while(!feof($file))
   {
     $line = fgets( $file );
     if($line == "")
        continue;
     $configarr[strtok($line, "=")] = rtrim(strtok("="));
   }
   fclose($file);

   $fileindex = 0;
   if (!empty($_POST["camera1cb"]))
   {
      $configarr["cameraname1"] = $_POST["cameraname1"];
      if(!empty($_FILES["imagefile"]["tmp_name"][$fileindex]))
      {
         move_uploaded_file($_FILES["imagefile"]["tmp_name"][$fileindex], "../usrimages/".$_FILES["imagefile"]["name"][$fileindex]);
         $configarr["cameraimage1"] = "../usrimages/".$_FILES["imagefile"]["name"][$fileindex];
      } //end if empty cam1
      else
      {
         if ($configarr["cameraimage1"] == "../images/noconfigcam1.png")
            $configarr["cameraimage1"] = "../images/defaultcam1.png";
         //else dont do anything

      } //end else cam1
      $fileindex = $fileindex + 1;
   } //endif empty cam1 checkbox
   else
   {
      $configarr["cameraname1"] = NULL;
      $configarr["cameraimage1"] = "../images/noconfigcam1.png";
   } //end else cam1 checkbox
   
   if (!empty($_POST["camera2cb"]))
   {
      $configarr["cameraname2"] = $_POST["cameraname2"];
      if(!empty($_FILES["imagefile"]["tmp_name"][$fileindex]))
      {
         move_uploaded_file($_FILES["imagefile"]["tmp_name"][$fileindex], "../usrimages/".$_FILES["imagefile"]["name"][$fileindex]);
         $configarr["cameraimage2"] = "../usrimages/".$_FILES["imagefile"]["name"][$fileindex];
         $fileindex = $fileindex + 1;
      } //end if empty file
      else
      {
         if ($configarr["cameraimage2"] == "../images/noconfigcam2.png")
            $configarr["cameraimage2"] = "../images/defaultcam2.png";
         //else dont do anything

      } //end else empty file
      $fileindex = $fileindex + 1;
   } //end if cam 2 check box
     else
   {
      $configarr["cameraname2"] = NULL;
      $configarr["cameraimage2"] = "../images/noconfigcam2.png";
   } //end else cam2 checkbox

   if (!empty($_POST["camera3cb"]))
   {
      $configarr["cameraname3"] = $_POST["cameraname3"];
      if(!empty($_FILES["imagefile"]["tmp_name"][$fileindex]))
      {
         move_uploaded_file($_FILES["imagefile"]["tmp_name"][$fileindex], "../usrimages/".$_FILES["imagefile"]["name"][$fileindex]);
         $configarr["cameraimage3"] = "../usrimages/".$_FILES["imagefile"]["name"][$fileindex];
         $fileindex = $fileindex + 1;
      } //end if emty files
      else
      {
         if ($configarr["cameraimage3"] == "../images/noconfigcam3.png")
            $configarr["cameraimage3"] = "../images/defaultcam3.png";
         //else dont do anything

      } //end else empty file
      $fileindex = $fileindex + 1;
   } //end if empty cam3 check box
     else
   {
      $configarr["cameraname3"] = NULL;
      $configarr["cameraimage3"] = "../images/noconfigcam3.png";
   } //end else cam3 checkbox
   if (!empty($_POST["camera4cb"]))
   {
      $configarr["cameraname4"] = $_POST["cameraname4"];
      if(!empty($_FILES["imagefile"]["tmp_name"][$fileindex]))
      {
         move_uploaded_file($_FILES["imagefile"]["tmp_name"][$fileindex], "../usrimages/".$_FILES["imagefile"]["name"][$fileindex]);
         $configarr["cameraimage4"] = "../usrimages/".$_FILES["imagefile"]["name"][$fileindex];
         $fileindex = $fileindex + 1;
      } //end if empty files
      else
      {
         if ($configarr["cameraimage4"] == "../images/noconfigcam4.png")
            $configarr["cameraimage4"] = "../images/defaultcam4.png";
         //else dont do anything

      } //end else empty files
      $fileindex = $fileindex + 1;
   } 
   else
   {
      $configarr["cameraname4"] = NULL;
      $configarr["cameraimage4"] = "../images/noconfigcam4.png";
   } //end else cam4 checkbox

   $configarr["cameracount"] = $fileindex;
   $configarr["ftpserver"] = $_POST["ftpip"];
   $configarr["ftpport"] = $_POST["ftpport"];
   $configarr["ftpusername"] = $_POST["ftpuser"];
   $configarr["ftppwd"] = $_POST["ftppwd"];

   //open the file for writing

   $file = fopen("../../conf/givmeconfig.txt", "w") or exit("Unable to open file!");
   foreach ($configarr as $key => $value)
   {
      fputs($file, $key."=".$value."\n");
   }
   fclose($file);
      
} //end function submit_confg_param

function read_config_param()
{
   $file = fopen("../../conf/givmeconfig.txt", "r") or exit("Unable to open file!");
   while(!feof($file))
   {
     $line = fgets( $file );
     if($line == "")
        continue;
     $configarr[strtok($line, "=")] = rtrim(strtok("="));
   }
   fclose($file);   
   return $configarr;
}

function header_print()
{
   $header = "<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> <html><head> <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>Giv_main</title> <link rel="stylesheet" media="all" type="text/css" href="../css/ui-lightness/jquery-ui-1.8.21.custom.css"> <script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script> <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script> <script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script> <script type="text/javascript" src="../js/jquery-ui-sliderAccess.js"></script> <script type="text/javascript" src="../js/givui.js"></script> </head>";
   print($header);
}

function submit_ftp_params()
{

   $configarr = read_config_param();
   $configarr["ftpserver"] = $_POST["ftpip"];
   $configarr["ftpport"] = $_POST["ftpport"];
   $configarr["ftpusername"] = $_POST["ftpuser"];
   $configarr["ftppwd"] = $_POST["ftppwd"];

   $file = fopen("../../conf/givmeconfig.txt", "w") or exit("Unable to open file!");
   foreach ($configarr as $key => $value)
   {
      fputs($file, $key."=".$value."\n");
   }
   fclose($file);



}
?> 
