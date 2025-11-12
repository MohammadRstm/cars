<?php

function validateCarData($carData){
    $requiredAttr = ["name" , "year" , "color"];
    $missing = [];
    foreach($requiredAttr as $req){
        if(!isset($carData[$req]) || empty($carData[$req])){
            $missing[] = $req;
        }
    };
    return $missing;
}

?>