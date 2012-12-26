<?php

include_once "uiutil.php";

//check if this is a post request
   //this is not a post request this  is redirected from main page
   //redirect to static html page
   $checkbox = array();
   $disabled = array();

   $reqdaction = (!empty($_GET["action"])) ? $_GET["action"] :"default";

   if( $reqdaction =="fullsubmit")
   {
       submit_config_param($reqdaction);
       echo "Will be directed to givmain.php";
       //header('Location: http://localhost/xampp/giv/ui/scripts/givmain.php');
   }
   
   else
   {
     if ( $reqdaction == "partsubmit")
        submit_config_param($reqdaction);

     if ($reqdaction == "ftpsub")
        submit_ftp_params();

     $configarr = read_config_param(); 
     //set the vaue of checkboxes
     $checkbox['cam1'] = (!empty($configarr["cameraname1"]))? "checked" : "";
     $disabled['cam1'] = NULL;
     if (!strcmp($checkbox['cam1'], "checked") == 0)
     {
        $disabled['cam1'] = "disabled"; 
     }
     $checkbox['cam2'] = (!empty($configarr["cameraname2"]))? "checked" : "";
     $disabled['cam2'] = NULL;
     if (!strcmp($checkbox['cam2'], "checked") == 0)
     {
        $disabled['cam2'] = "disabled";
     }
     $checkbox['cam3'] = (!empty($configarr["cameraname3"]))? "checked" : "";
     $disabled['cam3'] = NULL;
     if (!strcmp($checkbox['cam3'], "checked") == 0)
     {
        $disabled['cam3'] = "disabled";
     }
     $checkbox['cam4'] = (!empty($configarr["cameraname4"]))? "checked" : "";
     $disabled['cam4'] = NULL;
     if (!strcmp($checkbox['cam4'], "checked") == 0)
     {
        $disabled['cam4'] = "disabled";
     }

     
     //print_header header
?>
     



      <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> <html><head> <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>Giv_main</title> <link rel="stylesheet" media="all" type="text/css" href="../css/ui-lightness/jquery-ui-1.8.21.custom.css"> <script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script> <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script> <script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script> <script type="text/javascript" src="../js/jquery-ui-sliderAccess.js"></script> <script type="text/javascript" src="../js/givui.js"></script> </head>

      <body bgcolor="#F7F7F7" >
      <div align="center" style="font-family:'Arial';font-size:30px">
      <!-- img alt="Configuration" src="../images/configuration.png" -->
      Configuration
      <a href = "givmain.php"> <img  align="right" style="width: 40px; height: 40px; alt="Home" src="../images/home.png" >  </a>
      </div>
      <br/>


      <form method="post" name="ftp_form" action=camconfig.php?action=ftpsub >
      
      <center>
      <div id="ftp" align="center" style="background-color: #999999; color: white; font-family: Arial; width: 750px; height: 60px; align: center;"> 
      <b>FTP Details</b>
      <br/>
      
      IP: &nbsp; <input type="text" name="ftpip" value="<?=$configarr["ftpserver"]?>" size="15" style="height:16px;"/>
      Port: &nbsp; <input type="text" name="ftpport" value="<?=$configarr["ftpport"]?>" size="4"  style="height:16px;"/>
      Username: &nbsp;<input type="text" name="ftpuser" value="<?=$configarr["ftpusername"]?>" size="10" style="height:16px;"/>
      Password: &nbsp; <input type="text" name="ftppwd" value="<?=$configarr["ftppwd"]?>" size="10" style="height:16px;"/>
      <input type="submit" name="sub_ftp" value="Submit" />
      </div>
      </center>
      <br/>
 </form>

<form method="post" onsubmit="changeactionsubmitform('fullsubmit')" name="giv_form" enctype="multipart/form-data">
      <table style="text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="10">
      <tbody>
      <tr>
      <td style="width: 408px; height: 5px; background-color: #999999;"><span style="text-decoration: underline;">
  
      <input name="camera1cb" value="cam1" type="checkbox" <?=$checkbox["cam1"]?> onchange="changefield('cam1')" />
   
      </span><font style="color: white;" size="+1"><span style="font-family: Arial;">
      Camera 1</span></font></td>
<td style="width: 408px; height: 5px; font-family: Arial; background-color: #999999;"><font size="+1">

      <input  name="camera2cb" value="cam2" type="checkbox" <?=$checkbox["cam2"]?> onchange="changefield('cam2')" />

      <span style="color: white;">Camera 2</span></font></td>

      </tr>
      <tr>

      <td><img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 1" src="<?=$configarr["cameraimage1"]?>"</td>
      <td><img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 2" src="<?=$configarr["cameraimage2"]?>"</td>

      </tr>
      <tr>
      <td>

      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white; font-size: 14px; height:14px;">
      <td style="background-color: #999999;">
      Camera Name:  &nbsp; <input type="text" name="cameraname1" size="12" value="<?=$configarr["cameraname1"]?>" <?=$disabled['cam1']?> style="height:20px;"/>
      <br/>
      Image File: &nbsp; &nbsp; &nbsp; &nbsp; <input type="file" id="imagefile1" name="imagefile[]" onchange="changeactionsubmitform('partsubmit')"  size="12" style="height:27px;" <?=$disabled['cam1']?> /></td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999; font-size: 14px;" >
      <center>
      <button value="Mark Area On Image" name="showareacam1" onclick="window.open('camarea.php?camera=cameraname1&image=cameraimage1','cam1config','width=640,height=550')" type="button" style="font-family: Arial;"  <?=$disabled['cam1']?> > Mark Area On Image </button>
      </center>
      </td>
      </tr>
      </tbody>
      </table>
      </td>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white; font-size: 14px;">
      <td style="background-color: #999999;">
      Camera Name: &nbsp;  <input type="text" name="cameraname2" value="<?=$configarr["cameraname2"]?>"  <?=$disabled['cam2']?> size="12" style="height:20px;"/>
      <br/>
      Image File:  &nbsp; &nbsp; &nbsp; &nbsp; <input type="file" id="imagefile2" name="imagefile[]" onchange="changeactionsubmitform('partsubmit')" size="12" style="height:27px;" <?=$disabled['cam2']?> /></td>
      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999; font-size: 14px;">
      <center>
      <button value="Mark Area On Image" name="showareacam2" onclick="window.open('camarea.php?camera=cameraname2&image=cameraimage2','cam1config','width=640,height=550')" type="button" style="font-family: Arial;"  <?=$disabled['cam2']?> > Mark Area On Image </button>
      </center>
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

      <input name="camera3cb" value="cam3" type="checkbox"  <?=$checkbox["cam3"]?> onchange="changefield('cam3')"/>
      
      </span><font style="color: white;" size="+1"><span style="font-family: Arial;"> Camera 3</span></font></td>

      <td style="width: 408px; height: 5px; font-family: Arial; background-color: #999999;"><font size="+1">

      <input name="camera4cb" value="cam4" type="checkbox"  <?=$checkbox["cam4"]?> onchange="changefield('cam4')"/>

      <span style="color: white;">Camera 4</span></font></td>
      </tr>
      <tr>
      <td><img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 3" src="<?=$configarr["cameraimage3"]?>" />
      <td><img style="border: 0px solid ; width: 408px; height: 254px;" alt="Camera 4" src="<?=$configarr["cameraimage4"]?>" />

      </tr>
      <tr>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white; font-size: 14px;">
      <td style="background-color: #999999">
      Camera Name:  &nbsp; 

      <input type="text" name="cameraname3" value="<?=$configarr["cameraname3"]?>" <?=$disabled['cam3']?> size="12" style="height:20px;" />

      <br/>
      Image File:  &nbsp; &nbsp; &nbsp; &nbsp; 

      <input type="file" id="imagefile3" name="imagefile[]" onchange="changeactionsubmitform('partsubmit')" size="12" style="height:27px;" <?=$disabled['cam3']?> /></td>

      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999; font-size: 14px;">
      <center>
      <button value="Mark Area On Image" name="showareacam3" onclick="window.open('camarea.php?camera=cameraname3&image=cameraimage3','cam1config','width=640,height=550')" type="button" style="font-family: Arial;"  <?=$disabled['cam3']?> > Mark Area On Image </button>
      </center>
      </td>
      </tr>
      </tbody>
      </table>
      </td>
      <td>
      <table style="width: 100%; text-align: left; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
      <tbody>
      <tr style="font-family: Arial; color: white; font-size: 14px;">
      <td style="background-color: #999999;">
      Camera Name: &nbsp;

      <input type="text" name="cameraname4" size="12" value="<?=$configarr["cameraname4"]?>" <?=$disabled['cam4']?> style="height:20;"/>

      <br/>
      Image File: &nbsp; &nbsp; &nbsp; &nbsp; 

      <input type="file" id="imagefile4" name="imagefile[]" onchange="changeactionsubmitform('partsubmit')" size="12" style="height:27px;"<?=$disabled['cam4']?> /></td>

      </tr>
      <tr style="font-family: Arial; color: white;">
      <td style="background-color: #999999; font-size: 14px;">
      <center>
      <button value="Mark Area On Image" name="showareacam4" onclick="window.open('camarea.php?camera=cameraname4&image=cameraimage4','cam1config','width=640,height=550')" type="button" style="font-family: Arial;"  <?=$disabled['cam4']?> > Mark Area On Image </button>
      </center>
      </td>
      </tr>
      </tbody>
      </table>
      </td>
      </tr>
      </tbody>
      </table>

      <br/>
      <br/>

      <!--div  id="config" align="center">
      <input alt="Give Me" src="../images/configure.png" name="submit_giv"  type="image" />
      </div-->

      </form>
      </body>
      </html>


<?php   


   }

?>
