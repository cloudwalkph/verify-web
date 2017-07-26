<?php

function get_total_hits_for_project($locations)
{
    $count = 0;

    foreach ($locations as $location) {
        $count += $location->hits()->count();
    }

    return $count;
}

function array_map_assoc( $callback , $array ){
    $r = array();
    foreach ($array as $key=>$value)
        $r[$key] = $callback($key,$value);
    return $r;
}