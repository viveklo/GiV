<?php

//check if any camera is configured

//read the givmeconfig.txt file
$file = fopen("../..//conf/givmeconfig.txt", "r") or exit("Unable to open file!");
while(!feof($file))
{
  $line = fgets( $file );
  if($line == "")
     continue;
  $configarr[strtok($line, "=")] = rtrim(strtok("="));
}
fclose($file);

if (!empty($_POST["action"]))
{
   if($_POST["action"] == "delete")
   {
      $camindex = $_POST["camindex"];     
      $cameraname = $configarr["cameraname".$_POST["camindex"]];     
      $startdate =  $_POST["startdate"."camera".strval($camindex)];
      $enddate =  $_POST["enddate"."camera".strval($camindex)];

      //convert to timestamp
      $startts = strtotime($startdate);
      $endts = strtotime($enddate);

      //set the db folder
      $clusterdir = $configarr["filedbfolder"]."/clusterdb/";
      while($startts <= $endts)
      {
         //construct the folder to delete
         $flddel = $clusterdir.$cameraname."/".$startdate;
         //remove the folder
         exec("rm -rf $flddel");
         //increment start date
         $startts = strtotime("+1 day", $startts);
         $startdate = date("Y-m-d" ,$startts);
      }//end while
      //echo "<script type='text/javascript'>alert('Deleted');</script>";
   }//end if post action
   else
      echo "Invalid Request";
}
      
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
      <div align="center" style="font-family:'Arial';font-size:30px">
      <!--img style="width: 136px; height: 48px;" alt="GiV" src="../images/Giv.png" -->
      Manage Database
      <a href = "givmain.php"> <img  align="right" style="width: 40px; height: 40px; alt="Home" src="../images/home.png" >  </a>
      
      </div>

      <form method="post" name="delete_form"><br>
      <table style="text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="10">
      <tbody>
      <tr>
      <td style="width: 408px; height: 5px; background-color: #999999;"><span style="text-decoration: underline;">


      </span><font style="color: white;" size="+1"><span style="font-family: Arial;">Camera 1</span></font>
      </td>
      <td style="width: 408px; height: 5px; font-family: Arial; background-color: #999999;"><font size="+1">


      <span style="color: white;">Camera 2</span></font></td>

      </tr>
      <tr>
      <td>
      
      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 1" src="<?=$camera['cam1']['image']?>" <?=$camera['cam1']['cb']?> >

      </td>
      <td>

      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 2" src="<?=$camera['cam2']['image']?>" <?=$camera['cam2']['cb']?> >

      </td>
      </tr>
      <tr>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">From Date and Time: 

      <input id="mdbstartdatecamera1" name="startdatecamera1" <?=$camera['cam1']['cb']?> >

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp; 

      <input id="mdbenddatecamera1" name="enddatecamera1"<?=$camera['cam1']['cb']?> />

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;" align="center">

      <input type="button" value="Delete Record" id="mdbcam1button" <?=$camera['cam1']['cb']?> />

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

      <input id="mdbstartdatecamera2" name="startdatecamera2" <?=$camera['cam2']['cb']?> >

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp; 

      <input id="mdbenddatecamera2" name="enddatecamera2"></td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;" align="center">

      <input type="button" value="Delete Record" id="mdbcam2button" />

      </td>
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

      </span><font style="color: white;" size="+1"><span style="font-family: Arial;">Camera 3</span></font>
      </td>
      <td style="width: 408px; height: 5px; font-family: Arial; background-color: #999999;"><font size="+1">


      <span style="color: white;">Camera 4</span></font></td>

      </tr>
      <tr>
      <td>

      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 3" src="<?=$camera['cam3']['image']?>" <?=$camera['cam3']['cb']?> >

      </td>
      <td>

      <img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 4" src="<?=$camera['cam4']['image']?>" <?=$camera['cam4']['cb']?> >

      </td>
      </tr>
      <tr>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">From Date and Time:

      <input id="mdbstartdatecamera3" name="startdatecamera3" <?=$camera['cam3']['cb']?> >

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp;

      <input id="mdbenddatecamera3" name="enddatecamera3" <?=$camera['cam3']['cb']?> >

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;" align="center">

      <input type="button" value="Delete Record" id="mdbcam3button" <?=$camera['cam3']['cb']?> />

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

      <input id="mdbstartdatecamera4" name="startdatecamera4" <?=$camera['cam4']['cb']?>>

      </td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;">To Date and Time: &nbsp; &nbsp; &nbsp;

      <input id="mdbenddatecamera4" name="enddatecamera4" <?=$camera['cam4']['cb']?>></td>

      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999;" align="center">

      <input type="button" value="Delete Record" id="mdbcam4button" <?=$camera['cam4']['cb']?>/>

      </td>
      </tr>

      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>

</form>
</body></html>

