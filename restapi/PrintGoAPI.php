<?php

require 'Database.php';
require 'QueryBuilder.php';
require 'ApiController.php';
require 'Router.php';

Database::connect();

header('Content-Type: application/json');

$controller = new ApiController();

$router = new Router();


$router->add('GET', '/users', fn() => $controller->getUsers());

$router->add('GET', '/user/$id', fn($id) => $controller->getUserByID($id));

$router->add('GET', '/encomendas', fn() => $controller->getEncomendas());

$router->add('GET', '/carrinhos', fn() => $controller->getCarrinhos());

$router->add('GET', '/encomendasByUserID/$id', fn($id) => $controller->getEncomendasByUserID($id));

$router->add('POST', '/insertUser', fn() => $controller->insertUser());

$router->add('PUT', '/updateUser', fn() => $controller->updateUser());

$router->add('DELETE', '/deleteUserByID/$id', fn($id) => $controller->deleteUserByID($id));

$router->dispatch();


