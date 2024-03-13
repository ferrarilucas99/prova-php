<?php

namespace Models;

use Classes\Connection;
use PDO;

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Connection();
    }

    public function get()
    {
        $users = $this->conn->query("SELECT * FROM users");

        return $users->fetchAll(PDO::FETCH_OBJ);
    }

    public function insert($request)
    {
        $query_string = $this->getQueryInsertString($request);
        $columns = $query_string['columns'];
        $values = $query_string['values'];
        
        $new_user = $this->conn->query("INSERT INTO users ($columns) VALUES ($values)");

        return $new_user;
    }

    public function update($request)
    {
        $id = $request['id'];
        $query_string = $this->getQueryUpdateString($request);
        
        $update_user = $this->conn->query("UPDATE users SET $query_string WHERE `id` = $id");

        return $update_user;
    }

    public function delete($id)
    {
        $delete = $this->conn->query("DELETE FROM users WHERE id = $id");

        return $delete;
    }

    public function getQueryInsertString($query_array)
    {
        $count = 0;
        $total = count($query_array);
        $columns = '';
        $values = '';

        foreach($query_array as $key => $value){
            if($key != 'action'){
                $count++;
                $columns .= $count < $total - 1 ? "'$key', " : "'$key'";
                $values .= $count < $total - 1 ? "'$value', " : "'$value'";
            }
        }

        return [
            'columns' => $columns,
            'values' => $values,
        ];
    }

    public function getQueryUpdateString($query_array)
    {
        $count = 0;
        $total = count($query_array);
        $query_string = '';

        foreach($query_array as $key => $value){
            if($key != ('action') && $key != '_method' && $key != 'id'){
                $count++;
                $comma = $count < $total -3 ? ', ' : '';
                $query_string .= "`$key`  =  '$value'  $comma";
            }
        }

        return $query_string;
    }
}