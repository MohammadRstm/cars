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

        $data = $query->get_result()->fetch_assoc();

        return $data ? new static($data) : null;
    }

    public static function findAll(mysqli $connection){
        $sql = sprintf("SELECT * FROM %s" , static::$table);
        $query = $connection->prepare($sql);
        $query->execute();

        $cars = [];
        while($row = $query->get_result()->fetch_assoc()){
            $cars[] = new static($row);
        }
        return $cars;
    }

    public static function create(mysqli $connection , $carData){
        $sql = sprintf("INSERT INTO %s (name,year,color) VALUES(? , ? , ?)" , static::$table);
        $query = $connection->prepare($sql);
        $query->bind_param("sis", $carData["name"] , $carData["year"],$carData["color"]);
        $query->execute();

        return $query->insert_id;
    }

    public static function update(mysqli $connection , $updatedData){
        $sql = sprintf("UPDATE %s SET name = ? , year = ? , color = ? WHERE id = ?" , static::$table);
        $query = $connection->prepare($sql);
        $query->bind_param("sisi", $updatedData["name"] , $updatedData["year"],$updatedData["color"],$updatedData["id"]);
        if(!$query->execute()){
            return false;
        }else{
            return true;
        }
    }

    public static function delete(mysqli $connection , $id){
        $sql = sprintf("DELETE FROM %s WHERE id = ?" , static::$table);
        $query = $connection->prepare($sql);
        $query->bind_param("i" , $id);
        if(!$query->execute()){
            return false;
        }
        return true;
    }

}



?>
