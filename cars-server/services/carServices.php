<?php
require_once(__DIR__ . "/../models/Car.php");
require_once __DIR__ . "/../connection/connection.php";


class CarServices{

    public static function getCars($id = null){
        global $connection;
        if($id){
            $car = Car::find($connection, $id);
            if(!$car) throw new Exception("Car doesn't exist");
            $payload = $car->toArray();
        }else{
            $cars = Car::findAll($connection);
            if(!is_array($cars)){// only one car in db
                $payload = $cars->toArray();
            }else{
                $assocCars = [];
                foreach($cars as $car){
                    $assocCars[] = $car->toArray();
                }
                if(empty($assocCars)) throw new Exception("No cars found");
                $payload = $assocCars;
            }
        }
        return $payload;
    }
    public static function createCar($carData){
        global $connection;
        $current_year = date("Y");
        if($carData['year'] < 0 || $carData['year'] > $current_year)
            throw new Exception("Year Invalid");

        $inserted_id = Car::create($connection , $carData);

        if(!$inserted_id || $inserted_id < 0)
            throw new Exception("New car failed to get inserted");
        else
            return (int)$inserted_id;
    }

    public static function updateCar($id , $updatedCarData){
        global $connection;
        $current_year = date("Y");
        if($updatedCarData['year'] < 0 || $updatedCarData['year'] > $current_year)
            throw new Exception("Year Invalid");

        $car = CarServices::getCars($id);
        if(!$car){
            throw new Exception("Car not found");
        }

        $updated = Car::update($connection , $updatedCarData , $id);

        if(!$updated)
            throw new Exception("failed to update car");
        else
            return (bool)$updated;
    }

    public static function deleteCar($id){
        global $connection;
        $car = CarServices::getCars($id);
        if(!$car){
            throw new Exception("Car not found");
        }

        $deleted = Car::delete($connection ,  $id);
        if(!$deleted)
            throw new Exception("failed to delete car");
        else
            return (bool)$deleted;
    }
}



?>