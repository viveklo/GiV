//formating in givmain.php
$(function(){$('#startdatecamera1').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});
$(function(){$('#enddatecamera1').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});
$(function(){$('#startdatecamera2').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});
$(function(){$('#enddatecamera2').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});
$(function(){$('#startdatecamera3').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});
$(function(){$('#enddatecamera3').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});
$(function(){$('#startdatecamera4').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});
$(function(){$('#enddatecamera4').datetimepicker({dateFormat:"yy-mm-dd", showMinute:false, timeFormat:'hh'})});

//formating in managedb.php
$(function(){$('#mdbstartdatecamera1').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false})});
$(function(){$('#mdbenddatecamera1').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false })});
$(function(){$('#mdbstartdatecamera2').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false })});
$(function(){$('#mdbenddatecamera2').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false })});
$(function(){$('#mdbstartdatecamera3').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false })});
$(function(){$('#mdbenddatecamera3').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false })});
$(function(){$('#mdbstartdatecamera4').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false })});
$(function(){$('#mdbenddatecamera4').datetimepicker({dateFormat:"yy-mm-dd", showTimepicker:false })});

function changefield(cam) {
   if(cam == 'cam1')
   {
      document.giv_form.cameraname1.disabled = !document.giv_form.camera1cb.checked;
      document.getElementById('imagefile1').disabled = !document.giv_form.camera1cb.checked;
      document.giv_form.showareacam1.disabled = !document.giv_form.camera1cb.checked;
      document.giv_form.clearareacam1.disabled = !document.giv_form.camera1cb.checked;
   }
   else if(cam == 'cam2')
   {
      document.giv_form.cameraname2.disabled = !document.giv_form.camera2cb.checked;
      document.getElementById('imagefile2').disabled = !document.giv_form.camera2cb.checked;
      document.giv_form.showareacam2.disabled = !document.giv_form.camera2cb.checked;
      document.giv_form.clearareacam2.disabled = !document.giv_form.camera2cb.checked;
   }
   else if(cam == 'cam3')
   {
      document.giv_form.cameraname3.disabled = !document.giv_form.camera3cb.checked;
      document.getElementById('imagefile3').disabled = !document.giv_form.camera3cb.checked;
      document.giv_form.showareacam3.disabled = !document.giv_form.camera3cb.checked;
      document.giv_form.clearareacam3.disabled = !document.giv_form.camera3cb.checked;
   }
   else if (cam == 'cam4')
   {
      document.giv_form.cameraname4.disabled = !document.giv_form.camera4cb.checked;
      document.getElementById('imagefile4').disabled = !document.giv_form.camera4cb.checked;
      document.giv_form.showareacam4.disabled = !document.giv_form.camera4cb.checked;
      document.giv_form.clearareacam4.disabled = !document.giv_form.camera4cb.checked;
   } 
   else
   {
      alert("changefield - Cannot disable fields");
   }
}

function changeactionsubmitform(fullpartsubmit)
{
     document.giv_form.action = "../scripts/camconfig.php?action=" + fullpartsubmit;
     document.giv_form.submit();
}


$(document).ready(function(){
  $("#mdbcam1button").click(function(){
    $.post("managedb.php",
    {
      action:"delete",
      camindex:1,
      startdatecamera1:$('#mdbstartdatecamera1').val(),
      enddatecamera1:$('#mdbenddatecamera1').val()
    },
    function(data,status){
      //alert("Data: " + data + "\nStatus: " + status);
      alert("Delete Record: " + status);
    });
  });
});

$(document).ready(function(){
  $("#mdbcam2button").click(function(){
    $.post("managedb.php",
    {
      action:"delete",
      camindex:2,
      startdatecamera2:$('#mdbstartdatecamera2').val(),
      enddatecamera2:$('#mdbenddatecamera2').val()
    },
    function(data,status){
      //alert("Data: " + data + "\nStatus: " + status);
      alert("Delete Record: " + status);
    });
    //);
  });
});

$(document).ready(function(){
  $("#mdbcam3button").click(function(){
    $.post("managedb.php",
    {
      action:"delete",
      camindex:3,
      startdatecamera3:$('#mdbstartdatecamera3').val(),
      enddatecamera3:$('#mdbenddatecamera3').val()
    },
    function(data,status){
      //alert("Data: " + data + "\nStatus: " + status);
      alert("Delete Record: " + status);
    });
    //);
  });
});

$(document).ready(function(){
  $("#mdbcam4button").click(function(){
    $.post("managedb.php",
    {
      action:"delete",
      camindex:4,
      startdatecamera4:$('#mdbstartdatecamera4').val(),
      enddatecamera4:$('#mdbenddatecamera4').val()
    },
    function(data,status){
      //alert("Data: " + data + "\nStatus: " + status);
      alert("Delete Record: " + status);
    });
    //);
  });
});


