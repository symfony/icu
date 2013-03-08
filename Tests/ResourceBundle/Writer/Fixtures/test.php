<?php

$bundle = new \ResourceBundle('en', __DIR__);

function recursive_iterator_to_array($it)
{
    $array = array();
    foreach ($it as $key => $value) {
        $array[$key] = $value instanceof \ResourceBundle ? recursive_iterator_to_array($value) : $value;
    }
    return $array;
}

var_dump(recursive_iterator_to_array($bundle));
