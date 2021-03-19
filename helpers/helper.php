<?php

namespace dcms\pin\helpers;

final class Helper{
    // Search in array of objects
    public static function search_object_in_array($search, $arr){
        $key = array_search($search, array_column($arr, 'meta_key'));
        return $arr[$key];
    }

    // object meta to array meta
    public static function meta_to_array($user_meta){
        $arr = [];

        $arr['user_id'] = $user_meta[0]->user_id;
        foreach ($user_meta as $item) {
            $arr[$item->meta_key] = $item->meta_value;
        }

        return $arr;
    }
}
