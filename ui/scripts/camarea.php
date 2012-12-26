<?php

include_once "uiutil.php";

$cameraname = (!empty($_GET["camera"])) ? $_GET["camera"] :NULL;
$imagename = (!empty($_GET["image"])) ? $_GET["image"] :NULL;
$action = (empty($cameraname)) ? $_GET["action"] : NULL;
$configarr = read_config_param();

if (!empty($cameraname))
{
   //get camerimage from config
   $camera = $configarr[$cameraname];
   $image = $configarr[$imagename];
}
if (!empty($action))
{
   $currentcamera =  $_POST["camera"];
   $configarr[$currentcamera."polygon"] = $_POST["area"];

   //write to file
   $file = fopen("../../conf/givmeconfig.txt", "w") or exit("Unable to open file!");
   foreach ($configarr as $key => $value)
   {
      fputs($file, $key."=".$value."\n");
   }
   fclose($file);
   print("<script> window.close(); </script>");
} //empty action

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type"><title>Camera 1 Configuration</title>

<style type="text/css">
#canvas{background-image:url('<?=$image?>');}
</style>
<script type="text/javascript">

      var cordinates = [];
      var count = 0;

      document.addEventListener("DOMContentLoaded", init, false);

      function init()
      {
        var canvas = document.getElementById("canvas");
        canvas.addEventListener("mousedown", getPosition, false);
      }

      function getPosition(event)
      {
        var x = new Number();
        var y = new Number();
        var canvas = document.getElementById("canvas");

        if (event.x != undefined && event.y != undefined)
        {
          x = event.x;
          y = event.y;
        }
        else // Firefox method to get the position
        {
          x = event.clientX + document.body.scrollLeft +
              document.documentElement.scrollLeft;
          y = event.clientY + document.body.scrollTop +
              document.documentElement.scrollTop;
        }

        x = x - canvas.offsetLeft;
        y = y - canvas.offsetTop;


       // alert("count");
   
        if(count < 24)
        {
	   //draw the point
	   var canvas=document.getElementById("canvas") 
	   var ctx = canvas.getContext('2d'); 
	   ctx.beginPath();
	   ctx.arc(x, y, 6, 0, 2 * Math.PI, false);
	   ctx.fillStyle = "#f00";
	   ctx.fill();
	   ctx.fill();
	   
	   //store the coordinates
           cordinates[count] = x;
           count = count + 1;
           cordinates[count] = y;
           count = count + 1;  
        }
        else
        {
           alert ("Not more than 12 points allowed");
        }  

        //alert("x: " + x + "  y: " + y);
      }


      function draw()
      {
         var canvas=document.getElementById("canvas") 
         var ctx = canvas.getContext('2d'); 
         ctx.fillStyle = '#f00'; 
         //alert(cordinates.length);
         ctx.beginPath(); 
         ctx.moveTo(cordinates[0], cordinates[1]); 
	 index = 2;
	 while (index <cordinates.length)
	 {
	    ctx.lineTo( cordinates[index] , cordinates[index+1] );
	    index = index+2;
	 }
         ctx.lineWidth = 4;
         ctx.strokeStyle = "#FFA500";
         ctx.closePath(); 
         ctx.stroke();
      } 

      function clear()
      {
         var canvas = document.getElementById("canvas");
         var context = canvas.getContext("2d");
	 context.save(); 
	  
	 // Use the identity matrix while clearing the canvas 
	 context.setTransform(1, 0, 0, 1, 0, 0); 
	 context.clearRect(0, 0, canvas.width, canvas.height); 
	canvas.width = canvas.width; 
	  
	 // Restore the transform 
	 context.restore(); 
	// context.beginPath(); 
        // context.clearRect(0, 0, canvas.width, canvas.height);
	// canvas.width = canvas.width; 
         cordinates = [];
         count = 0;
      }
      function reloadPage()
      {
          window.location.reload()
      }
      
      function populate_validate()
      {
         if (count < 5)
         {
            alert("Mark atleast 5 points");
	    return false;
         }

         //format of passed string "x y,x1 y1,x2 y2"
	 document.configform.area.value = cordinates[0].toString();
	 document.configform.area.value = document.configform.area.value + " " + cordinates[1].toString();
         index = 2;
	 while (index <cordinates.length-1)
	 {
            document.configform.area.value = document.configform.area.value + ","+ cordinates[index].toString() + " " + cordinates[index+1].toString();
            index = index + 2
	 }
	 //alert(document.configform.area.value);
       } 
</script>

</head>
<body>
<form name="configform" method="post" action="camarea.php?action=submit" onSubmit="return populate_validate();">
<canvas id="canvas" width="640" height="480"></canvas>

<center>
<div id="show" align="center" style="background-color: #F6AF3A; color: white; font-family: Cooper Black; ">
<button value="Show Area" name="Show Area" onclick="draw()" type="button"> Show Area </button>
<button value="Clear Area" name="Clear Area" onclick="reloadPage()" type="button"/> Clear Area </button>
<input value="<?=$cameraname?>" name="camera" type="hidden"/> 
<button value="" name="area" type="submit"> Done </button>
</div>
</center>

</form>
</body></html>
