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
        // $colors = $this->conn->query("SELECT * FROM colors");
        $query = "
            SELECT 
                colors.*,
                GROUP_CONCAT(users.id) as users
            FROM 
                colors
            LEFT JOIN 
                user_colors ON colors.id = user_colors.color_id 
            LEFT JOIN 
                users ON user_colors.user_id = users.id
            GROUP BY 
                colors.id;
        ";

        $statement = (($this->conn->query($query))->fetchAll(PDO::FETCH_OBJ));
        $colors = array_map(function($color){
            if(!is_null($color->users)){
                $color->users = explode(',',$color->users);
            }
            return $color;
        }, $statement);

        return $colors;
    }

    public function insert($request)
    {
        $users = $request['users'];

        $query_string = $this->getQueryString($request, __FUNCTION__);
        $columns = $query_string['columns'];
        $values = $query_string['values'];
        
        $new_color = $this->conn->query("INSERT INTO colors ($columns) VALUES ($values)");
        $id = $this->conn->query('SELECT last_insert_rowid()')->fetchColumn();

        if(!is_null($users) && !empty($users)){
            foreach($users as $user_id){
                $this->conn->query("INSERT INTO user_colors ('user_id', 'color_id') VALUES ('$user_id', '$id')");
            }
        }

        return $new_color;
    }

    public function update($request, $id)
    {
        $users = $request['users'];

        $query_string = $this->getQueryString($request, __FUNCTION__)['query_string'];
        $update_user = $this->conn->query("UPDATE colors SET $query_string WHERE `id` = $id");
        $delete_users = $this->conn->query("DELETE FROM user_colors WHERE `color_id` = $id");

        if(!is_null($users) && !empty($users)){
            foreach($users as $user_id){
                $this->conn->query("INSERT INTO user_colors ('user_id', 'color_id') VALUES ('$user_id', '$id')");
            }
        }

        return $update_user;
    }

    public function delete($id)
    {
        $delete = $this->conn->query("DELETE FROM colors WHERE id = $id");
        $delete_users = $this->conn->query("DELETE FROM user_colors WHERE `color_id` = $id");

        return $delete;
    }

    public function getQueryString($query_array, $action)
    {
        unset($query_array['users']);
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