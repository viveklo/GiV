<?php

//check if any camera is configured

//read the givmeconfig.txt file
$file = fopen("../../conf/givmeconfig.txt", "r") or exit("Unable to open file!");
while(!feof($file))
{
  $line = fgets( $file );
  if($line == "")
     continue;
  $configarr[strtok($line, "=")] = rtrim(strtok("="));
}
fclose($file);

//check camera count
if ($configarr["cameracount"] == "0")
{
   //no camera is configure, redirect to configure page
   header('Location: http://localhost/xampp/giv/ui/scripts/camconfig.php'); 
}

else
{
   $camera['cam1']['cb'] = (!empty($configarr["cameraname1"])) ? "checked":"disabled";
   $camera['cam2']['cb'] = (!empty($configarr["cameraname2"])) ? "checked":"disabled";
   $camera['cam3']['cb'] = (!empty($configarr["cameraname3"])) ? "checked":"disabled";
   $camera['cam4']['cb'] = (!empty($configarr["cameraname4"])) ? "checked":"disabled";

   $camera['cam1']['cameraname'] = $configarr["cameraname1"];
   $camera['cam2']['cameraname'] = $configarr["cameraname2"];
   $camera['cam3']['cameraname'] = $configarr["cameraname3"];
   $camera['cam4']['cameraname'] = $configarr["cameraname4"];

   $camera['cam1']['image'] = $configarr["cameraimage1"];
   $camera['cam2']['image'] = $configarr["cameraimage2"];
   $camera['cam3']['image'] = $configarr["cameraimage3"];
   $camera['cam4']['image'] = $configarr["cameraimage4"];
}

?>

      <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> <html><head> <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>Giv_main</title> <link rel="stylesheet" media="all" type="text/css" href="../css/ui-lightness/jquery-ui-1.8.21.custom.css"> <script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script> <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script> <script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script> <script type="text/javascript" src="../js/jquery-ui-sliderAccess.js"></script> <script type="text/javascript" src="../js/givui.js"></script> </head>

      <body bgcolor="#F7F7F7">
      <div align="center"  style="font-family:'Arial';font-size:45px">
      <!--img style="width: 136px; height: 48px;" alt="GiV" src="../images/Giv.png" -->
      GiV
      <a href = "managedb.php"> <img  align="right" style="width: 40px; height: 40px; alt="Database Management" src="../images/db.png" >  </a>
      <a href = "camconfig.php"> <img  align="right" style="width: 40px; height: 40px; alt="Settings" src="../images/setting.jpg" >  </a>
      </div>

      <form method="post" action="../../core/videosearchdisplay.php?req=mainpage" name="giv_form"><br>
      <table style="text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="10">
      <tbody>
      <tr>
      <td style="width: 408px; height: 5px; background-color: #999999;"><span style="text-decoration: underline;">

      <input name="camera1" value="<?=$camera['cam1']['cameraname']?>" type="checkbox" <?=$camera['cam1']['cb']?>/>

      </span><font style="color: white;" size="+1"><span style="font-family: Arial;">Camera 1</span></font>
      </td>
      <td style="width: 408px; height: 5px; font-family: Arial; background-color: #999999;"><font size="+1">

      <input name="camera2" value="<?=$camera['cam2']['cameraname']?>" type="checkbox" <?=$camera['cam2']['cb']?>/>

      <span style="color: white;">Camera 2</span></font></td>

      </tr>
      <tr>
      <td>
      
      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 1" src="<?=$camera['cam1']['image']?>">

      </td>
      <td>

      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 2" src="<?=$camera['cam2']['image']?>">

      </td>
      </tr>
      <tr>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">From Date and Time: 

      <input id="startdatecamera1" name="startdatecamera1">

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp; 

      <input id="enddatecamera1" name="enddatecamera1">

      </td>
      </tr>
      </tbody>
      </table>
      </td>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">From Date and Time: 

      <input id="startdatecamera2" name="startdatecamera2">

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp; 

      <input id="enddatecamera2" name="enddatecamera2"></td>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>



      <table style="text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="10">
      <tbody>
      <tr>
      <td style="width: 408px; height: 5px; background-color: #999999;"><span style="text-decoration: underline;">

      <input name="camera3" value="<?=$camera['cam3']['cameraname']?>" type="checkbox" <?=$camera['cam3']['cb']?>/>
      </span><font style="color: white;" size="+1"><span style="font-family: Arial;">Camera 3</span></font>
      </td>
      <td style="width: 408px; height: 5px; font-family: Arial; background-color: #999999;"><font size="+1">

      <input name="camera4" value="<?=$camera['cam4']['cameraname']?>" type="checkbox" <?=$camera['cam4']['cb']?>/>

      <span style="color: white;">Camera 4</span></font></td>

      </tr>
      <tr>
      <td>

      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 3" src="<?=$camera['cam3']['image']?>">

      </td>
      <td>

      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 4" src="<?=$camera['cam4']['image']?>">

      </td>
      </tr>
      <tr>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">From Date and Time:

      <input id="startdatecamera3" name="startdatecamera3">

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp;

      <input id="enddatecamera3" name="enddatecamera3">

      </td>
      </tr>
      </tbody>
      </table>
      </td>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">From Date and Time:

      <input id="startdatecamera4" name="startdatecamera4">

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp;

      <input id="enddatecamera4" name="enddatecamera4"></td>

      <input value=1 name="pagetodisplay" type="hidden"/>

      </tr>
      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>

      <br/>
      <div align="center" style="font-family:'Arial';font-size:45px">
      <input  name="submit_giv" type="submit" value="Giv Me" style="background:none;border:0; font-size:35px" />  

      </div>
</form>
</body></html>

