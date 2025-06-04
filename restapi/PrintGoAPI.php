    <?php

    require 'Database.php';
    require 'QueryBuilder.php';
    require 'ApiController.php';
    require 'Router.php';

    Database::connect();

    header('Content-Type: application/json');

    $controller = new ApiController();

    $router = new Router();

    $router->add('GET', '/serverTimeStamp', fn() => $controller->getServerTimeStamp());

    $router->add('GET', '/users', fn() => $controller->getUsers());

    $router->add('GET', '/categories', fn() => $controller->getCategories());

    $router->add('GET', '/userById/$id', fn($id) => $controller->getUserByID($id));
    
    $router->add('GET', '/userInfoByID/$id', fn($id) => $controller->getUserInfoByID($id));

    $router->add('GET', '/userByEmail/$userEmail', fn($userEmail) => $controller->getUserByEmail($userEmail));

    $router->add('GET', '/adminByEmail/$adminEmail', fn($adminEmail) => $controller->getAdminByEmail($adminEmail));

    $router->add('GET', '/encomendas', fn() => $controller->getEncomendas());

    $router->add('GET', '/encomendasChart', fn() => $controller->getEncomendasChart());

    $router->add('GET', '/revenuePerMonth', fn() => $controller->getRevenuePerMonth());

    $router->add('GET', '/topProducts', fn() => $controller->getTopProducts());

    $router->add('GET', '/numberOfOrdersByDay/$day', fn($day) => $controller->numberOfOrdersFilteredByDay($day));

    $router->add('GET', '/numberOfOrdersByMonth/$month', fn($month) => $controller->numberOfOrdersFilteredByMonth($month));

    $router->add('GET', '/numberOfOrdersByYear/$year', fn($year) => $controller->numberOfOrdersFilteredByYear($year));

    $router->add('GET', '/revenueByDay/$day', fn($day) => $controller->revenueFilteredByDay($day));

    $router->add('GET', '/revenueByMonth/$month', fn($month) => $controller->revenueFilteredByMonth($month));

    $router->add('GET', '/revenueByYear/$year', fn($year) => $controller->revenueFilteredByYear($year));

    $router->add('GET', '/numberOfClientsByDay/$day', fn($day) => $controller->getNumberOfClientsByDay($day));

    $router->add('GET', '/numberOfClientsByMonth/$month', fn($month) => $controller->getNumberOfClientsByMonth($month));

    $router->add('GET', '/numberOfClientsByYear/$year', fn($year) => $controller->getNumberOfClientsByYear($year));

    $router->add('GET', '/recentOrdersByClient/$id', fn($id) => $controller->getRecentOrdersByClient($id));

    $router->add('GET', '/getAllOrdersByClient/$id', fn($id) => $controller->getNumberOfOrdersByClient($id));

    $router->add('GET', '/getUserProfile/$id', fn($id) => $controller->getClientProfile($id));

    $router->add('GET', '/carrinhos', fn() => $controller->getCarrinhos());

    $router->add('GET', '/encomendasByUserID/$id', fn($id) => $controller->getEncomendasByUserID($id));

    $router->add('GET', '/productsSoldByID/$productID', fn($productID) => $controller->getProductsSoldByID($productID));

    $router->add('POST', '/insertUser', fn() => $controller->insertUser());

    $router->add('POST', '/insertShoppingCart', fn() => $controller->insertShoppingCart());

    $router->add('GET', '/feedbackAVGProduct/$id_product', fn($id_product) => $controller->getFeedbackAVGProduct($id_product));

    $router->add('PUT', '/updateUser', fn() => $controller->updateUser());

    $router->add('DELETE', '/deleteUserByID/$id', fn($id) => $controller->deleteUserByID($id));

    $router->add('GET', '/getOrders', fn() => $controller->getOrders());

    $router->add('POST', '/insertProduct', fn() => $controller->insertProduct());

    $router->add('GET', '/feedbacks', fn() => $controller->getFeedbacks());

    $router->add('GET', '/classificationFeedback', fn() => $controller->getClassificationFeedback());

    $router->add('POST', '/insertFeedback', fn() => $controller->insertFeedback());

    $router->add('GET', '/products', fn() => $controller->getProducts());

    $router->add('GET', '/getCategoriesByID', fn() => $controller->getCategoriesByID());

    $router->add('GET', '/getSizesByCategories/$categorias', fn($categorias) => $controller->getSizesByCategories($categorias));

    $router->add('GET', '/getColorsByCategories/$categorias', fn($categorias) => $controller->getColorsByCategories($categorias));

    $router->add('GET', '/productByID/$id', fn($id) => $controller->getProductByID($id));

    $router->add('PUT', '/updateProduct', fn() => $controller->updateProduct());

    $router->add('DELETE', '/deleteProductByID/$id', fn($id) => $controller->deleteProductByID($id));

    $router->add('GET', '/filterProducts/$categoria/$precoMinimo/$precoMaximo/$cor/$tamanho', fn($categoria, $precoMinimo, $precoMaximo, $cor, $tamanho) => $controller->getFiltersProducts($categoria, $precoMinimo, $precoMaximo, $cor, $tamanho));

    $router->add('GET', '/getCarrinhoItens/$userID', fn($userID) => $controller->getCarrinhoItens($userID));

    $router->add('GET', '/orderById/$id', fn($id) => $controller->getOrderById($id));

    $router->add('GET', '/orderItems/$id', fn($id) => $controller->getOrderItems($id));

    $router->add('GET', '/orderPaymentInfo/$id', fn($id) => $controller->getOrderPaymentInfo($id));

    $router->add('POST', '/updateOrderStatus/$id/$status', fn($id, $status) => $controller->updateOrderStatus($id, $status));
    
    $router->add('GET', '/getDadosClientePorCarrinho/$userID', fn($userID) => $controller->getDadosClientePorCarrinho($userID));

    $router->add('GET' , '/getOrderID/$id' , fn($id) => $controller->getOrderID($id));

    $router->add('GET', '/searchProductsByTitle/$searchTerm', fn($searchTerm) => $controller->searchProductsByTitle($searchTerm));

    $router->add('GET', '/carrinhoByUserId/$id_cliente', fn($id_cliente) => $controller->getCarrinhoByUserId($id_cliente));
    
    $router->add('GET', '/carrinhoItensByCarrinhoId/$id_carrinho', fn($id_carrinho) => $controller->getCarrinhoItensByCarrinhoId($id_carrinho));
    
    $router->add('GET', '/checkCarrinhoItem/$id_carrinho/$id_produto/$tamanho/$cor', fn($id_carrinho, $id_produto, $tamanho, $cor) => $controller->checkCarrinhoItem($id_carrinho, $id_produto, $tamanho, $cor));

    $router->add('POST', '/insertCarrinhoItem', fn() => $controller->insertCarrinhoItem());
    
    $router->add('POST', '/updateItemFromCarrinhoItens', fn() => $controller->updateItemFromCarrinhoItens());
    
    $router->add('POST', '/deleteCartItem', fn() => $controller->deleteCartItem());
    
    $router->add('POST', '/insertPersonalizacao', fn() => $controller->insertPersonalizacao());

    $router->dispatch();
