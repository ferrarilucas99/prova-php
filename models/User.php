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
        $query = "
            SELECT 
                users.*,
                GROUP_CONCAT(colors.id) as colors
            FROM 
                users 
            LEFT JOIN 
                user_colors ON users.id = user_colors.user_id 
            LEFT JOIN 
                colors ON user_colors.color_id = colors.id
            GROUP BY 
                users.id;
        ";

        $statement = (($this->conn->query($query))->fetchAll(PDO::FETCH_OBJ));
        $users = array_map(function($user){
            if(!is_null($user->colors)){
                $user->colors = explode(',',$user->colors);
            }
            return $user;
        }, $statement);

        return $users;
    }

    public function insert($request)
    {
        $colors = $request['colors'];

        $query_string = $this->getQueryString($request, __FUNCTION__);
        $columns = $query_string['columns'];
        $values = $query_string['values'];
        
        $new_user = $this->conn->query("INSERT INTO users ($columns) VALUES ($values)");
        $id = $this->conn->query('SELECT last_insert_rowid()')->fetchColumn();

        if(!is_null($colors) && !empty($colors)){
            foreach($colors as $color_id){
                $this->conn->query("INSERT INTO user_colors ('user_id', 'color_id') VALUES ('$id', '$color_id')");
            }
        }

        return $new_user;
    }

    public function update($request, $id)
    {
        $colors = $request['colors'];

        $query_string = $this->getQueryString($request, __FUNCTION__)['query_string'];
        $update_user = $this->conn->query("UPDATE users SET $query_string WHERE `id` = $id");
        $delete_colors = $this->conn->query("DELETE FROM user_colors WHERE `user_id` = $id");

        if(!is_null($colors) && !empty($colors)){
            foreach($colors as $color_id){
                $this->conn->query("INSERT INTO user_colors ('user_id', 'color_id') VALUES ('$id', '$color_id')");
            }
        }

        return $update_user;
    }

    public function delete($id)
    {
        $delete = $this->conn->query("DELETE FROM users WHERE id = $id");
        $delete_colors = $this->conn->query("DELETE FROM user_colors WHERE `user_id` = $id");

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