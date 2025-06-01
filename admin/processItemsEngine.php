
<?php
require_once '../restapi/Database.php';

$apiUrl = "http://estga-dev.ua.pt/~ptaw-2025-gr4/restapi/PrintGoAPI.php";

function executeCurlRequest($ch)
{
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL Error: $error");
    }

    curl_close($ch);

    // Log the response for debugging
    error_log("API Response: " . $response);
    error_log("HTTP Code: " . $httpCode);

    // Validate JSON response
    $decodedResponse = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON response: " . json_last_error_msg());
    }
    
    // Set HTTP response code
    if ($httpCode >= 400) {
        http_response_code($httpCode);
        throw new Exception("API returned HTTP $httpCode");
    }
    
    return $decodedResponse;
}

header('Content-Type: application/json');

try {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    
    if ($requestMethod === 'POST') {
        $json = file_get_contents('php://input');

        if (empty($json)) {
            throw new Exception("Empty request body");
        }

        error_log("Received JSON: " . $json);

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON: " . json_last_error_msg());
        }

        // Verificar dados necessários
        if (!isset($data['order_id']) || !isset($data['tracking_number']) || !isset($data['carrier'])) {
            throw new Exception("Dados incompletos. Necessário: order_id, tracking_number e carrier");
        }

        error_log("Processando encomenda: " . $data['order_id']);
        $response = processOrder($data);
        echo json_encode($response);
    } else {
        throw new Exception("Method not allowed. Use POST instead.");
    }
} catch (Exception $e) {
    error_log("Error in processItemsEngine.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function processOrder($data)
{
    global $apiUrl;
    
    // Preparar os dados para a API
    $orderId = $data['order_id'];
    
    // Usar o endpoint existente para atualizar o status
    $url = $apiUrl . "/updateOrderStatus/{$orderId}/Enviado";
    error_log("Making request to: " . $url);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'tracking_number' => $data['tracking_number'],
        'carrier' => $data['carrier'],
        'notify_customer' => $data['notify_customer'] ?? 0
    ]));
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = executeCurlRequest($ch);
    
    // Verificar resposta e preparar retorno
    if (isset($response['success']) && $response['success']) {
        return [
            'success' => true,
            'message' => 'Encomenda processada com sucesso!',
            'data' => $response
        ];
    } else {
        throw new Exception(isset($response['message']) ? $response['message'] : 'Erro desconhecido ao processar a encomenda.');
    }
}
?>