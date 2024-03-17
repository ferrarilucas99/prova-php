<?php

namespace Models;

use Classes\Connection;
use PDO;

class Color
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Connection();
    }

    public function get()
    {
        $colors = $this->conn->query("SELECT * FROM colors");

        return $colors->fetchAll(PDO::FETCH_OBJ);
    }

    public function insert($request)
    {
        $query_string = $this->getQueryString($request, __FUNCTION__);
        $columns = $query_string['columns'];
        $values = $query_string['values'];
        
        $new_color = $this->conn->query("INSERT INTO colors ($columns) VALUES ($values)");

        return $new_color;
    }

    public function update($request, $id)
    {
        $query_string = $this->getQueryString($request, __FUNCTION__)['query_string'];
        
        $update_user = $this->conn->query("UPDATE colors SET $query_string WHERE `id` = $id");

        return $update_user;
    }

    public function delete($id)
    {
        $delete = $this->conn->query("DELETE FROM colors WHERE id = $id");

        return $delete;
    }

    public function getQueryString($query_array, $action)
    {
        $count = 0;
        $total = count($query_array);
        $columns = '';
        $values = '';
        $query_string = '';

        foreach($query_array as $key => $value){
            $count++;
            if($action == 'insert'){
                $columns .= $count < $total ? "'$key', " : "'$key'";
                $values .= $count < $total ? "'$value', " : "'$value'";
            }else{
                $comma = $count < $total ? ', ' : '';
                $query_string .= "`$key`  =  '$value'  $comma";
            }
        }

        return [
            'columns' => $columns,
            'values' => $values,
            'query_string' => $query_string
        ];
    }
}