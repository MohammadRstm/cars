<?php
require_once __DIR__ ."/../controllers/CarController.php";
require_once __DIR__ . "/../services/ResponseService.php";
require_once __DIR__. "./../utils/validateCarData.php";

// can send http responses but only for auth, presmission & data validation

class CarApis{
    public static function get($id = null){
        CarController::getCars($id);
    }

    public static function create(){
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data) && isset($data['carData'])){
            $carData = $data['carData'];
            $missing = validateCarData($carData);
            if(empty($missing))
                CarController::createCar($data['carData']);
            else 
                echo ResponseService::response(401 ,["error" => "Some car attributes are missing " . implode(',',$missing)]);
        }else{
            echo ResponseService::response(401 ,["error" => "No car data provided"]);
        }
    }

    public static function update($id){
        $data = json_decode(file_get_contents("php://input"), true);

        if(!isset($id) || empty($id)){
            echo ResponseService::response(401,["error" => "Car id must be provided to update"]);
            return;
        }

        if(!empty($data) && isset($data['updatedCarData'])){
            $missing = validateCarData($data['updatedCarData']);
            if(empty($missing))
                CarController::updateCar($id , $data['updatedCarData']);
            else
                echo ResponseService::response(401 ,["error" => "Some car attributes are missing " . implode(',',$missing)]);
        }else{
            echo ResponseService::response(401 ,["error" => "No car data provided"]);
        }
    }
    
    public static function delete($id){
        if(!isset($id) || empty($id)){
            echo ResponseService::response(401,["error" => "Car id must be provided to delete"]);
            return;
        }
        CarController::deleteCar($id);
        
    }
}


?>