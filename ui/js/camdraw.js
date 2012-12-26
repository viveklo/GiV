<style type="text/css">
#canvas{background-image:url('../images/cam1.jpg');}
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

        x = x - canvas.offsetLeft - 10;
        y = y - canvas.offsetTop - 10;


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
            alert("Mark atleast 3 points");
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
<form name="configform" method="post" action="http://localhost/xampp/giv/ui/scripts/configui.php" onSubmit="return populate_validate();">
<table style="text-align: left; width: 764px; height: 497px;" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr>
<td style="height: 480px; width: 640px;">
<canvas id="canvas" width="640" height="480"></canvas> </td>
<td style="vertical-align: top; text-align: center;"><span style="font-family: Cooper Black;">Configure Interesting Area</span><br>
<br>
<button value="Show Area" name="Show Area" onclick="draw()" type="button"> Show Area </button><br>
<br>
<button value="Clear Area" name="Clear Area" onclick="reloadPage()" type="button"/> Clear Area </button><br>
<br>
<input value="camera1" name="camera" type="hidden"/> 
<button value="" name="area" type="submit"> Done </button></td>
</tr>
</tbody>
</table>
</form>
</body></html>
