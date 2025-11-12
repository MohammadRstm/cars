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

$routSegments = explode('/' ,trim($request , '/'));// car / create / 1 
$controller = ucfirst($routSegments[0] ?? '');// get first segment e.g Car
$method = $routSegments[1] ?? '';// what method to call in api
$params = array_slice($routSegments,2);// rout parameters in an array

$className = $controller . 'Apis';// what api to call

if(class_exists($className) && method_exists($className, $method)){
    $className::$method(...$params);// call the api & its method
}else{
    ResponseService::response(404,["error" => "Rout not found"]);
}

// the upper code is much cleaner than this one
// the below works but I'll have to keep adding else ifs if I add another api breaking the open/close principle

// two issues to fix in the upper code
// routes with extra segments like this "http://localhost/cars-fullstack/cars-server/car/get/4/ldf/sdfnsdf"
// will get accepted and routed , in this case "getCars" will be called normally because php methods can accept extra params
// another issue if user types http://localhost/cars-fullstack/cars-server/car/_constructor" the contructor will be called

// if($routSegments && !empty($routSegments)){
//     $method = $routSegments[1] ?? null;
//     if($routSegments[0] === 'car'){
//         if($method && method_exists(CarApis,$method)) CarApis::$method();
//     }else if ($routSegments[0] === 'user'){
//         // user apis
//     }
// }







?>




