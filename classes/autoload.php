<?php

spl_autoload_register(function ($class) {
    $dir = explode('\\', $class)[0];
    $prefix = $dir.'\\';
    $base_dir = realpath(__DIR__ . "/../$dir/");
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    
    $file = $base_dir . '/' . $relative_class . '.php';

    if (file_exists($file)) {
        require $file;
    }
});