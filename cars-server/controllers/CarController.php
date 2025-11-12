<?php
require_once __DIR__ . "/../models/Car.php";
require_once __DIR__ . "/../services/ResponseService.php";
require_once __DIR__ . "/../services/carServices.php";
require_once __DIR__ . "/../utils/validateCarData.php";


// can send http res but only for blogic stuff, records , not found...

class CarController{

    public static function getCars($id = null){
        if($id !== null && !is_numeric($id) || $id < 1)
            echo ResponseService::response(400,["error" => "Car id in the wrong format"]);
        try{
            $payload = CarServices::getCars($id);
            echo ResponseService::response(200, $payload);
        }catch(Exception $ex){
            echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
        }
    }

    public static function createCar(){
        $data = json_decode(file_get_contents("php://input"), true);
        if(!empty($data) && isset($data['carData'])){
            $carData = $data['carData'];
            $missing = validateCarData($carData);
            
            if(!empty($missing)){
                echo ResponseService::response(400 ,["error" => "Some car attributes are missing " . implode(',',$missing)]);
                return;
            }
            try{
                $inserted_id = CarServices::createCar($carData);
                echo ResponseService::response(200, ["inserted_id" => $inserted_id]);
            }catch(Exception $ex){
                echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
            }
        }else{
            echo ResponseService::response(400 ,["error" => "No car data provided"]);
            return;
        }       
    }

    public static function updateCar($id){
        $data = json_decode(file_get_contents("php://input"), true);

        if($id === null || !is_numeric($id) || $id < 1){
            echo ResponseService::response(400,["error" => "Car id not provided or in the wrong format"]);
            return;
        }
        if(!empty($data) && isset($data['updatedCarData'])){
            $updatedCarData = $data['updatedCarData'];
            $missing = validateCarData($updatedCarData);
            if(!empty($missing)){
                echo ResponseService::response(400 ,["error" => "Some car attributes are missing " . implode(',',$missing)]);
                return;
            }

            try{
                $is_Updated = CarServices::updateCar($id , $updatedCarData);
                echo ResponseService::response(200,["is_updated" => $is_Updated]);
            }catch(Exception $ex){
                echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
            }
            
        }else{
            echo ResponseService::response(400 ,["error" => "No car data provided"]);
        }
    }

    public static function deleteCar($id){
        if($id === null || !is_numeric($id) || $id < 1){
            echo ResponseService::response(400,["error" => "Car id not provided or in the wrong format"]);
            return;
        }
        try{
            $is_deleted = CarServices::deleteCar($id);
            echo ResponseService::response(200,["is_deleted" => $is_deleted]);
        }catch(Exception $ex){
            echo ResponseService::response(500 , ["error" => "Server error : ". $ex->getMessage()]);
        }
    }
}
?>