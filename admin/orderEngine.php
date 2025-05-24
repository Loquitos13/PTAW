<?php
session_start();
require_once '../restapi/Database.php';
require_once '../restapi/QueryBuilder.php';
require_once '../restapi/ApiController.php';

// Conectar à base de dados
try {
    Database::connect();
} catch (Exception $e) {
    error_log("Erro de conexão à base de dados: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro de conexão à base de dados'
    ]);
    exit;
}

function getOrders() {
    try {
        $controller = new ApiController();
        return $controller->getOrders();
    } catch (Exception $e) {
        error_log("Erro ao obter encomendas: " . $e->getMessage());
        throw $e;
    }
}

// Definir cabeçalho de resposta
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    error_log("orderEngine.php: Método recebido: $method");

    if ($method === 'GET') {
        error_log("Processando pedido GET para orderEngine.php");
        
        $result = getOrders();
        
        error_log("Número de encomendas obtidas: " . count($result));
        
        if (empty($result)) {
            echo json_encode([]);
        } else {
            echo json_encode($result);
        }
        exit;
        
    } else {
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Método não permitido'
        ]);
        exit;
    }
        
} catch (Exception $e) {
    error_log("Erro em orderEngine.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500); 
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'details' => 'Verifique os logs do servidor para mais informações'
    ]);
    exit;
}
?>