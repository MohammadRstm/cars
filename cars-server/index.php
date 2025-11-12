<?php
require_once(__DIR__ . "/routes/carApis.php");
// All requests reach index first, then depending on their rout they get routed to the correct api
// the api calls the appropraite controller function, the controller communicated to the models through
// services , finally models communicate with the database

$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($request, $base_dir) === 0) {
    $request = substr($request, strlen($base_dir));
}

if ($request == '') {
    $request = '/';
}

$routSegments = explode('/' ,substr($request , 1));// car / create / 1 

if($routSegments && !empty($routSegments)){
    if($routSegments[0] === 'car'){// segment 1
        CarApis::$routSegment[1]();// $routSegment[1] -> create 
    }else if ($routSegments[0] === 'user'){
        // user apis
    }
}







?>




