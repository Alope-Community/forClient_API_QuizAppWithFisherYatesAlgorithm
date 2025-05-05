<?php
function fisherYatesShuffle($array) {
    $n = count($array);
    for ($i = $n - 1; $i > 0; $i--) {
        $j = rand(0, $i);
        
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }
    return $array;
}