<?php
session_start();
require_once '../restapi/Database.php';
$base_url = "/~ptaw-2025-gr4"; 

$apiUrl = "http://estga-dev.ua.pt/~ptaw-2025-gr4/src/restapi";
if (!function_exists('curl_init')) {
    error_log("CURL is not available on this server");
}

function executeCurlRequest($ch) {
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_STDERR, $verbose);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        error_log("CURL Verbose info: " . $verboseLog);
        curl_close($ch);
        throw new Exception("CURL Error: $error. Info: " . json_encode($info));
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode >= 400) {
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        error_log("CURL Verbose info: " . $verboseLog);
        curl_close($ch);
        throw new Exception("API returned error code: $httpCode. Response: $response");
    }
    
    curl_close($ch);

    if (!empty($response)) {
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON response: " . json_last_error_msg() . ". Raw: " . substr($response, 0, 200));
        }
        return $decoded;
    }
    
    return [];
}

function getOrders() {
    global $apiUrl;
    $requestUrl = rtrim($apiUrl, '/') . '/getOrders';
    error_log("Making request to: $requestUrl");
    
    $ch = curl_init($requestUrl);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    return executeCurlRequest($ch);
}

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        error_log("Processing GET request to orderEngine.php");
        $result = getOrders();
        echo json_encode($result); 
        exit;
    } 
        
} catch (Exception $e) {
    error_log("Error in orderEngine.php: " . $e->getMessage());
    http_response_code(500); 
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}
?>
