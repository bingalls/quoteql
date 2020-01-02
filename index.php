<?php

/**
 * index.php
 * @author Bruce Ingalls
 * @copyright 2019
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Dispatcher;
use App\Route;

ob_start();
$route = new Route($_SERVER['REQUEST_URI']);
$view = Dispatcher::invoke(
    $route->getCtrl(),
    $route->getAction(),
    $route->getVariables()
);

echo $view;
ob_end_flush();
