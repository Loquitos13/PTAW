<?php
require_once '../restapi/Database.php';

$apiUrl = "http://localhost/PTAW/restapi/PrintGoAPI.php";

function executeCurlRequest($ch) {
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL Error: $error");
    }

    curl_close($ch);

    json_decode($response);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON response: " . json_last_error_msg());
    }

    http_response_code($httpCode);
    return $response;
}

header('Content-Type: application/json');

try {
    $json = file_get_contents('php://input');

    if (empty($json)) {
        throw new Exception("Empty request body");
    }

    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }

    $response = deleteProductData($data);
    echo $response;

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Request failed',
        'message' => $e->getMessage()
    ]);
}

function deleteProductData($data) {
    global $apiUrl;

    if (empty($data)) {
        throw new Exception("No data provided");
    }

    $id = $data['id'] ?? null;
        if (!$id) {
            throw new Exception("Missing 'id' in request data");
        }

    $ch = curl_init("$apiUrl//deleteProductByID/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = executeCurlRequest($ch);
    $decoded = json_decode($response, true);

    if (!is_array($decoded) || (isset($decoded['success']) && $decoded['success'] !== 'Product created')) {
        throw new Exception("Erro ao apagar produto na API: " . ($decoded['message'] ?? 'Erro desconhecido'));
    }

    return $decoded;
}

?>