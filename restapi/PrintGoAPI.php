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

$router->add('GET', '/userById/$id', fn($id) => $controller->getUserByID($id));

$router->add('GET', '/userByEmail/$userEmail', fn($userEmail) => $controller->getUserByEmail($userEmail));

$router->add('GET', '/adminByEmail/$adminEmail', fn($adminEmail) => $controller->getAdminByEmail($adminEmail));

$router->add('GET', '/encomendas', fn() => $controller->getEncomendas());

$router->add('GET', '/carrinhos', fn() => $controller->getCarrinhos());

$router->add('GET', '/encomendasByUserID/$id', fn($id) => $controller->getEncomendasByUserID($id));

$router->add('POST', '/insertUser', fn() => $controller->insertUser());

$router->add('PUT', '/updateUser', fn() => $controller->updateUser());

$router->add('DELETE', '/deleteUserByID/$id', fn($id) => $controller->deleteUserByID($id));

$router->add('GET', '/getOrders', fn() => $controller->getOrders());

$router->add('GET', '/feedbacks', fn() => $controller->getFeedbacks());

$router->add('GET', '/products', fn() => $controller->getProducts());

$router->add('GET', '/productByID/$id', fn($id) => $controller->getProductByID($id));

$router->add('POST', '/insertProduct', fn() => $controller->insertProduct());

$router->add('PUT', '/updateProduct', fn() => $controller->updateProduct());

$router->add('DELETE', '/deleteProductByID/$id', fn($id) => $controller->deleteProductByID($id));

$router->dispatch();


