<?php

$_SERVER['REQUEST_URI'] = '/prenacersistem/login';
$_SERVER['SCRIPT_NAME'] = '/prenacersistem/index.php';
$_SERVER['REQUEST_METHOD'] = 'POST';

require 'core/Router.php';
$router = new Router();
$router->post('/login', function() { echo 'LOGIN FOUND'; });
$router->resolve();
