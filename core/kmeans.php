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
    $xsum = 0.0;
    $ysum = 0.0;
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
  public $motionframe;
  function getDistance($p)
        {
         $x1 = $this->x - $p->x;
         $y1 = $this->y - $p->y;
         return sqrt($x1*$x1 + $y1*$y1);
        }
}

function distributeOverClusters($k, $arr)
{
 $maxX = 0.0;
 $maxY = 0.0;
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

function load_file_for_kmeans($filetoload, &$arr)
{
   $file = fopen($filetoload, "r") or exit("Unable to open file".$filetoload."\n");
   $i = count($arr);
   //Output a line of the file until the end is reached
   while(!feof($file))
   {
      $line = fgets( $file );
      if($line == "")
         continue;
  
      $arr[$i] = new Point();
      $arr[$i]->file = rtrim(strtok($line, " "));
      $arr[$i]->objectsize = rtrim(strtok(" "));
      $arr[$i]->x = rtrim(strtok(" "));
      $arr[$i]->y = rtrim(strtok(" "));
      $arr[$i]->motionframe = rtrim(strtok(" "));
      $i = $i + 1;
      //print "x = $x, y = $y \n";
   }
   fclose($file);
   return $arr;
}

?>
