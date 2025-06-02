<?php
session_start();
require_once '../restapi/Database.php';

$apiUrl = "http://estga-dev.ua.pt/~ptaw-2025-gr4/restapi/PrintGoAPI.php";

function executeCurlRequest($ch) {
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("CURL Error: $error");
    }
    
    curl_close($ch);
    
    json_decode($response);
    if(json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON response: " . json_last_error_msg());
    }
    
    return $response;
}

header('Content-Type: application/json');

try {
    $json = file_get_contents('php://input');
    
    if(empty($json)) {
        throw new Exception("Empty request body");
    }
    
    $data = json_decode($json, true);
    
    if(json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }
    
    $result = addShoppingCart($data);
    echo json_encode([
        'status' => 'success',
        'data' => $result
    ]);
    
} catch(Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function addShoppingCart($id_cliente) {
    global $apiUrl;

    if(empty($id_cliente)) {
        throw new Exception("No user data provided");
    }

    $ch = curl_init("$apiUrl/insertShoppingCart");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($id_cliente));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = executeCurlRequest($ch);

    $cartData = json_decode($response, true);

     return [
         'status' => 'success',
         'message' => 'Shopping Cart created',
         'id_cart' => $cartData['id_cart']
     ];

}

