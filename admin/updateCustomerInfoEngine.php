
<?php
require_once '../restapi/Database.php';

$apiUrl = "http://estga-dev.ua.pt/~ptaw-2025-gr4/restapi/PrintGoAPI.php";

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
    // Receber dados do cliente
    $json = file_get_contents('php://input');
    
    if(empty($json)) {
        throw new Exception("Empty request body");
    }
    
    $data = json_decode($json, true);
    
    if(json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }
    
    // Validar dados necessários
    if (!isset($data['order_id']) || !isset($data['customer_info'])) {
        throw new Exception("Missing required fields: order_id and customer_info");
    }
    
    // Validar customer_info
    if (!isset($data['customer_info']['nome']) || 
        !isset($data['customer_info']['email']) || 
        !isset($data['customer_info']['morada'])) {
        throw new Exception("Missing required customer information");
    }

    $response = updateCustomerInfo($data);
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}


function updateCustomerInfo($data) {
    global $apiUrl;
    
    // Formato CORRETO da URL (igual aos outros arquivos)
    $endpoint = "$apiUrl/updateCustomerInfo";
    
    $ch = curl_init($endpoint);
    
    // Configurar requisição POST
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Configurar headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    // Executar a requisição
    $responseJson = executeCurlRequest($ch);
    $responseData = json_decode($responseJson, true);
    
    // Verificar se a atualização foi bem-sucedida
    if (isset($responseData['success']) && $responseData['success'] === true) {
        return [
            'success' => true,
            'message' => 'Informações do cliente atualizadas com sucesso',
            'data' => $responseData['data'] ?? []
        ];
    }
    
    return [
        'success' => false,
        'message' => $responseData['message'] ?? 'Erro ao atualizar informações do cliente...',
        'error_details' => $responseData['error'] ?? null
    ];
}
?>