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
include_once("point_in_polygon.php"); 
class Cluster
{
  public $points;
  public $avgPoint;
  function calculateAverage($maxX, $maxY)
  {
    if (count($this->points)==0)
    {
        $this->avgPoint->x = rand(0, $maxX);
        $this->avgPoint->y =  rand(0,$maxY);
        //we didn't get any clues at all :( lets just randomize and hope for better...
        return;
    }
     foreach($this->points as $p)
        {
         $xsum += $p->x;
         $ysum += $p->y;
        }

      $count = count($this->points);
      $this->avgPoint->x =  $xsum / $count;
      $this->avgPoint->y =  $ysum / $count;
  }
}

class Point
{
  public $x;
  public $y;
  public $file;
  public $objectsize;
  function getDistance($p)
        {
         $x1 = $this->x - $p->x;
         $y1 = $this->y - $p->y;
         return sqrt($x1*$x1 + $y1*$y1);
        }
}

function distributeOverClusters($k, $arr)
{
 foreach($arr as $p)
        { if ($p->x > $maxX)
                $maxX = $p->x;
          if ($p->y > $maxY)
                $maxY = $p->y;
        }
  $clusters = array();
  for($i = 0; $i < $k; $i++)
        {
         $clusters[] = new Cluster();
         $tmpP = new Point();
         $tmpP->x=rand(0,$maxX);
         $tmpP->y=rand(0,$maxY);
         $clusters[$i]->avgPoint = $tmpP;
        }
  #deploy points to closest center.
  #recalculate centers
  for ($a = 0; $a < 200; $a++) # run it 200 times
  {
        foreach($clusters as $cluster)
                $cluster->points = array(); //reinitialize
        foreach($arr as $pnt)
        {
           $bestcluster=$clusters[0];
           $bestdist = $clusters[0]->avgPoint->getDistance($pnt);

           foreach($clusters as $cluster)
                {
                        if ($cluster->avgPoint->getDistance($pnt) < $bestdist)
                        {
                                $bestcluster = $cluster;
                                $bestdist = $cluster->avgPoint->getDistance($pnt);
                        }
                }
                $bestcluster->points[] = $pnt;//add the point to the best cluster.
        }
        //recalculate the centers.
        foreach($clusters as $cluster)
                $cluster->calculateAverage($maxX, $maxY);

  }
  return $clusters;
}

$file = fopen("wildobjects_process.txt", "r") or exit("Unable to open file!");
$i = 0;
//Output a line of the file until the end is reached
while(!feof($file))
{
  $line = fgets( $file );
  if($line == "")
     continue;
  //print "$line";
  
  $arr[$i] = new Point();
  $arr[$i]->file = strtok($line, " ");
  $arr[$i]->objectsize = strtok(" ");
  $arr[$i]->x = strtok(" ");
  $arr[$i]->y = strtok(" ");
  $i = $i + 1;
  //print "x = $x, y = $y \n";
}
fclose($file);
//print_r ($arr);
//var_dump(distributeOverClusters(20, $arr));
//$clust[]= array();
$clust_no = (int)($i * 0.1);

$clust = distributeOverClusters($clust_no, $arr);
var_dump($clust);
echo "--------------------------------------------- \n";
// Area based search on cluster. if atleast one element is within boundary
//keep the cluster else delete the cluster
// define the area
$polygon1 = array("250 50","440 50", "520 170", "560 170", "560 480", "250 480", "250 50"); 
$found_val = 0;
$index = 0;
$point_inside = 0;
$pointLocation = new pointLocation();
//$status = $pointLocation->pointInPolygon("77 420", $polygon1);
//echo "Points \n ",$status;
foreach ($clust as $pts)
{  
   foreach($pts->points as $val2)
   {
      $locate_point1 = $val2->x." ".$val2->y;
      $locate_point = rtrim($locate_point1);
      $status = $pointLocation->pointInPolygon($locate_point, $polygon1);
      //echo "\n Locate point..", $locate_point;
      //echo "Point ",$locate_point," "; 
      //echo $pointLocation->pointInPolygon($locate_point, $polygon1);
      //echo "\n";
      
      if (strcmp($status, "inside") == 0)
      {
        $point_inside = 1;
	//echo "Points ",$status," ", $locate_point, "\n";
      }
   }
   if ($point_inside == 0)
       unset($clust[$index]);

   $index = $index +1;
   $point_inside = 0; 
} 

array_values($clust);
var_dump($clust);

$file1 = fopen("wildobjects_process2.txt", "w+") or exit("Unable to open file!");
foreach ($clust as $pts)
{
   foreach($pts->points as $val2)
   {
      
      fwrite($file1, $val2->file." ".$val2->objectsize." ".$val2->x." ".$val2->y);
   }
}
fclose($file1); 


/*unset($clust[0]);
array_values($clust);
echo "--------------------------------------------- \n";
var_dump($clust); */
    
         //echo "(",$val2->x,", ", $val2->y,") ","\n"; */
      
//var_dump($clust[0]);

//var_dump($clust[0]->points[0]->x);


// $keys = array_keys(265, $arr); 
//echo $keys;
//print_r($keys); 
//print_r($clust[0]->Cluster[0]);
//echo $arr

/* $p = new Point();
$p->x = 2;
$p->y = 2;
$p2 = new Point();
$p2->x = 3;
$p2->y = 2;
$p3 = new  Point();
$p3->x = 8;
$p3->y = 2;
$arr[] = $p;
$arr[] = $p2;
$arr[] = $p3;
var_dump(distributeOverClusters(2, $arr)); */

/* $arr[0] = new Point();
$arr[0]->x = 2;
$arr[0]->y = 2;
$arr[1] = new Point();
$arr[1]->x = 3;
$arr[1]->y = 2;
$arr[2] = new  Point();
$arr[2]->x = 8;
$arr[2]->y = 2;
var_dump(distributeOverClusters(2, $arr)); */

?>

		
