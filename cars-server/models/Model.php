<?php
abstract class Model{

    protected static string $table;
    protected static string $primary_key = "id";

    public static function find(mysqli $connection, int $id){
        $sql = sprintf("SELECT * from %s WHERE %s = ?",
                       static::$table,
                       static::$primary_key);

        $query = $connection->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();               
        $result = $query->get_result();
        $data = $result->fetch_assoc();
        return $data ? new static($data) : null;
    }

    public static function findAll(mysqli $connection){
        $sql = sprintf("SELECT * FROM %s" , static::$table);
        $query = $connection->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        $cars = [];
        while($row = $result->fetch_assoc()){
            $cars[] = new static($row);
        }
        return $cars;
    }

    public static function create(mysqli $connection , $carData){

        // figure out columns & placeholders 
        $columns = array_keys($carData);
        $placeHolders = implode(',' , array_fill(0 , count($columns) , '?'));
        $columnList = implode($columns);

        // figure out column types
        $types = "";
        $values = [];
        foreach($carData as $value){
            if(is_int($value)){
                $types .= "i";
            }elseif (is_double($value) || is_float($value)){
            $types .= "d";
            }else{
                $types .= "s";
            }
            $values[] = $value;
        }

        $sql = sprintf("INSERT INTO %s (%s) VALUES(%s)" ,
         static::$table,
         $columnList,
         $placeHolders
        );

        $query = $connection->prepare($sql);
        $query->bind_param($types, ...$values);
        $query->execute();

        return $query->insert_id;
    }

    public static function update(mysqli $connection , $updatedData, $id){

        $columns = array_keys($updatedData);
        // figure out setters e.g SET name = ? , ....
        $setters = "";
        foreach($columns as $attribute){
            $setters .= $attribute ."=?,";
        }

        $settersList = substr($setters , 0 , -1);// remove trailing ,

        // figure out column types
        $types = "";
        $values = [];
        foreach($updatedData as $value){
            if(is_int($value)){
                $types .= "i";
            }elseif (is_double($value) || is_float($value)){
            $types .= "d";
            }else{
                $types .= "s";
            }
            $values[] = $value;
        }

        $sql = sprintf("UPDATE %s SET %s WHERE %s = ?" ,
         static::$table,
         $settersList,
         static::$primary_key
        );

        $query = $connection->prepare($sql);
        $query->bind_param($types, ...$values , $id);

        if(!$query->execute()){
            return false;
        }else{
            return true;
        }
    }

    public static function delete(mysqli $connection , $id){
        $sql = sprintf("DELETE FROM %s WHERE %s = ?" , static::$table , static::$primary_key);
        $query = $connection->prepare($sql);
        $query->bind_param("i" , $id);
        if(!$query->execute()){
            return false;
        }
        return true;
    }

    public function save(){}
    
}



?>
