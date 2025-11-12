<?php
require_once __DIR__ ."/../controllers/CarController.php";
require_once __DIR__ ."/../connection/connection.php";
require_once __DIR__ . "/../services/ResponseService.php";

class CarApis{
    private static $db_connection = $connection;
    public static function get($id = null){
        try{
            $cars = CarController::getCars(CarApis::$db_connection, $id);
            echo ResponseService::response(200,$cars);
        }catch(Exception $ex){
            echo ResponseService::response(500 , "Server error, ". $ex->getMessage());
        }
    }
    public static function create(){
        $data = json_decode(file_get_contents("php://input"), true);
        // filter request api's job
        try{
            if(isset($data['carData'])){
                CarController::createCar(CarApis::$db_connection , $data['carData']);// controller 
            }else{
                echo ResponseService::response(401 , "Car data is missing");
            }
        }catch(Exception $ex){
            echo ResponseService::response(500 , "Server error, ". $ex->getMessage());
        }
    }

    public static function update($id){
        try{
            $updated_car = CarController::updateCar(CarApis::$db_connection,$id);
            echo ResponseService::response(200,$updated_car);
        }catch(Exception $ex){
            echo ResponseService::response(500 , "Server error, ". $ex->getMessage());
        }
    }
    
    public static function delete($id){
        try{
            $deleted_car = CarController::deleteCar(CarApis::$db_connection,$id);
            echo ResponseService::response(200,$deleted_car);
        }catch(Exception $ex){
            echo ResponseService::response(500 , "Server error, ". $ex->getMessage());
        }
    }
}


?>