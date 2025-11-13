<?php
require_once __DIR__ . "/routes/apis.php";

// we need to fix the routing 
// then fix the insert, & update in models to work in all models like tf

$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($request, $base_dir) === 0) {
    $request = substr($request, strlen($base_dir));
}

if ($request == '') {
    $request = '/';
}

$routSegments = explode('/' ,trim($request , '/'));// car / create / 1 
$request = $routSegments[0] ?? '';// get first segment e.g car
$reqMethod = $routSegments[1] ?? '';// what method to call 
$params = array_slice($routSegments,2);// rout parameters in an array


if(!empty($request) && !empty($reqMethod)){
    if(isset($apis[$request])){
        $controller_name = $apis[$request]['controller'];
        $method = $apis[$request]['reqMethod'][$reqMethod];
        require_once "./controllers/". $controller_name .".php";
        if(method_exists($controller_name , $method)){
            $controller_name::$method(...$params);
        }else{
            echo ResponseService::response(500, "Error: Method {$method} not found in {$controller_name}");
        }
    }else{
        echo ResponseService::response(404, "Route Not Found");
    }
}








?>




