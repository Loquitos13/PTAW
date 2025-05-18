<?php
session_start();
require_once '../restapi/Database.php';

$apiUrl = "http://localhost/PTAW/restapi/PrintGoAPI.php";

function executeCurlRequest($ch) {
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("CURL Error: $error");
    }
    curl_close($ch);

    json_decode($response);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON response: " . json_last_error_msg());
    }

    return $response;
}

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // Chama função para obter feedbacks (sem ler corpo)
        $result = getFeedbacks();
        echo json_encode([
            'status' => 'success',
            'data' => $result
        ]);
        exit;
    } elseif ($method === 'POST') {
        // Processa requisição POST normalmente
        $json = file_get_contents('php://input');
        if (empty($json)) {
            throw new Exception("Empty request body");
        }
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON: " . json_last_error_msg());
        }
        $result = addFeedback($data);
        echo json_encode([
            'status' => 'success',
            'data' => $result
        ]);
        exit;
    } else {
        throw new Exception("Unsupported HTTP method");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}

function addFeedback($data) {
    global $apiUrl;

    if (empty($data)) {
        throw new Exception("No data provided");
    }

    $ch = curl_init("$apiUrl/insertFeedback");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = executeCurlRequest($ch);
    $decoded = json_decode($response, true);

    if (!is_array($decoded) || !isset($decoded['status']) || $decoded['status'] !== 'success') {
        throw new Exception("Erro ao inserir feedback na API: " . ($decoded['message'] ?? 'Erro desconhecido'));
    }

    return $decoded;
}


function getFeedbacks() {
    global $apiUrl;

    $ch = curl_init("$apiUrl/feedbacks");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = executeCurlRequest($ch);

    return json_decode($response, true);
}
?>
