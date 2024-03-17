<?php

namespace Classes;

use ReflectionMethod;

class Router {
    private $routes = [];

    public function addRoute($method, $path, $callback) {
        $this->routes[] = [$method, $path, $callback];
    }

    public function handleRequest($method, $path) {
        foreach ($this->routes as $route) {
            list($route_method, $route_path, $callback) = $route;
            if ($method == $route_method && preg_match("#^$route_path$#", $path, $matches)) {
                array_shift($matches);
                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                } else {
                    list($controller_name, $method_name) = explode('@', $callback);
                    $controller_name = 'Controllers\\'.$controller_name;
                    $controller = new $controller_name();
                    
                    call_user_func_array([$controller, $method_name], $matches);
                }
                return;
            }
        }

        echo "404 Not Found";
    }
}