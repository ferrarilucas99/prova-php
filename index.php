<?php

require_once __DIR__.'/classes/autoload.php';

use Controllers\AppController;

$controller = new AppController();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controller->{$action}();