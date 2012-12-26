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


/*if(empty($_POST)) 
    echo "No POST variables"; 
else 
    print_r($_POST); */

$camera = $_POST["camera"];
//$area_pts = explode(" ",$_POST["area"]);

// write parameters as is in config file
$cameraconfig = "../../conf/".$camera."config.txt";

$param = "polygon=".$_POST["area"];
$file = fopen($cameraconfig, "w") or exit("Unable to open ".$cameraconfig." \n"); 

fwrite($file, $param);

fclose($file);

 header('Location: http://localhost/xampp/giv/ui/html/givmain.html');

?>
