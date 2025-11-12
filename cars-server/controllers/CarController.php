<?php
require_once __DIR__ . "/../models/Car.php";
require_once __DIR__ . "/../services/ResponseService.php";
require_once __DIR__ . "/../services/carServices.php";


// can send http res but only for blogic stuff, records , not found...

class CarController{

    public static function getCars($id = null){
        try{
            $payload = CarServices::getCars($id);
            echo ResponseService::response(200, $payload);
        }catch(Exception $ex){
            echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
        }
    }

    public static function createCar($carData){
        try{
            $inserted_id = CarServices::createCar($carData);
            echo ResponseService::response(200, ["inserted_id" => $inserted_id]);
        }catch(Exception $ex){
            echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
        }
       
    }

    public static function updateCar($id , $updatedCarData){
        try{
            $is_Updated = CarServices::updateCar($id , $updatedCarData);
            echo ResponseService::response(200,["is_updated" => $is_Updated]);
        }catch(Exception $ex){
            echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
        }
    }

    public static function deleteCar($id){
        try{
            $is_deleted = CarServices::deleteCar($id);
            echo ResponseService::response(200,["is_deleted" => $is_deleted]);
        }catch(Exception $ex){
            echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
        }
    }
}
?>