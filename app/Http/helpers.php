<?php

function get_total_hits_for_project($locations)
{
    $count = 0;

    foreach ($locations as $location) {
        $count += $location->hits()->count();
    }

    return $count;
}