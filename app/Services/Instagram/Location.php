<?php

namespace App\Services\Instagram;


use App\TargetLocation;

class Location {

    /**
     * @param $x
     * @param $y
     * @return array
     * Check if the post's co-ordinates falls under the points that we have saved for a country
     */
    public function pointInPolygon($x, $y) {
        $latLng = TargetLocation::all();
        foreach ($latLng as $item) {

            $polyX = $item->region_data[0];
            $polyY = $item->region_data[1];

            $polySides = count($polyX);
            $j = $polySides-1 ;
            $oddNodes = 0;
            //Loops through the number of side of polygon, and checks the given point is inside the polygon or not..
            for ($i=0; $i<$polySides; $i++) {
                if (($polyY[$i]<$y && $polyY[$j]>=$y) ||  ($polyY[$j]<$y && $polyY[$i]>=$y)) {
                    if ($polyX[$i]+($y-$polyY[$i])/($polyY[$j]-$polyY[$i])*($polyX[$j]-$polyX[$i])<$x) {
                        $oddNodes=!$oddNodes;
                    }
                }
                $j=$i;
            }

            if ($oddNodes) {
                return [$oddNodes, $item];
            }
        }

        [0, null];

    }

    /**
     * @param $x
     * @param $y
     * @param $item
     * @return array
     * Checks if the point is inside the country or not
     */
    public function pointInParticularLocation($x, $y, $item){
        if (!$item) {
            return [0];
        }

        $polyX = $item->region_data[0];
        $polyY = $item->region_data[1];

        $polySides = count($polyX);
        $j = $polySides-1 ;
        $oddNodes = 0;

        // loop through the count up to sides of polugon...
        for ($i=0; $i<$polySides; $i++) {
            // check if the location we targeted is inside the polygon or not..
            if (($polyY[$i]<$y && $polyY[$j]>=$y) ||  ($polyY[$j]<$y && $polyY[$i]>=$y)) {
                if ($polyX[$i]+($y-$polyY[$i])/($polyY[$j]-$polyY[$i])*($polyX[$j]-$polyX[$i])<$x) {
                    $oddNodes=!$oddNodes;
                }
            }
            $j=$i;
        }

        // it means found...
        if ($oddNodes) {
            return [$oddNodes, $item];
        }

        return [0, null];
    }
}
