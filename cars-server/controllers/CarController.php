<?php
include("../models/Car.php");
include("../connection/connection.php");
include("../services/ResponseService.php");

function getCars(){
    global $connection;
    if(isset($_GET["id"])){
        $id = $_GET["id"];
        $car = Car::find($connection, $id);
        echo ResponseService::response(200, $car->toArray());
    }else{
        // retrieve all cars
        $cars = Car::findAll($connection);
        $payload = [];
        foreach($cars as $car){
            $payload[] = $car->toArray();
        }
        echo ResponseService::response(200, $payload);
        return;
    }

    return;
}

//getCarById();
getCars();



?>