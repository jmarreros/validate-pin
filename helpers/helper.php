<?php

// Search in array of objects
function search_object_in_array($search, $arr){
    $key = array_search($search, array_column($arr, 'meta_key'));
    return $arr[$key];
}