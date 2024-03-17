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
        $colors = $request['colors'];

        $query_string = $this->getQueryString($request, __FUNCTION__);
        $columns = $query_string['columns'];
        $values = $query_string['values'];
        
        $new_user = $this->conn->query("INSERT INTO users ($columns) VALUES ($values)");
        $new_id = $this->conn->query('SELECT last_insert_rowid()')->fetchColumn();

        if(!is_null($colors) && !empty($colors)){
            foreach($colors as $color){
                $this->conn->query("INSERT INTO user_colors ('user_id', 'color_id') VALUES ('$new_id', '$color')");
            }
        }

        return $new_user;
    }

    public function update($request, $id)
    {
        $query_string = $this->getQueryString($request, __FUNCTION__)['query_string'];
        
        $update_user = $this->conn->query("UPDATE users SET $query_string WHERE `id` = $id");

        return $update_user;
    }

    public function delete($id)
    {
        $delete = $this->conn->query("DELETE FROM users WHERE id = $id");

        return $delete;
    }

    public function getQueryString($query_array, $action)
    {
        unset($query_array['colors']);
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