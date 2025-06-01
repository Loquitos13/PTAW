<?php
require_once '../restapi/Database.php';

$apiUrl = "http://estga-dev.ua.pt/~ptaw-2025-gr4/restapi/PrintGoAPI.php";

function executeCurlRequest($ch)
{
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlInfo = curl_getinfo($ch);
    
    // Log detailed curl info
    error_log("cURL Info: " . json_encode($curlInfo));
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL Error: $error");
    }

    curl_close($ch);

    // Log the response for debugging
    error_log("API Response: " . $response);
    error_log("HTTP Code: " . $httpCode);

    // Check if response is empty
    if (empty($response)) {
        throw new Exception("Empty response from API. HTTP Code: $httpCode");
    }

    // Check for HTML error pages (403, 404, 500, etc.)
    if (strpos($response, '<!DOCTYPE') === 0 || strpos($response, '<html') !== false) {
        // This is an HTML error page, not JSON
        if ($httpCode === 403) {
            throw new Exception("API endpoint returned 403 Forbidden. Check if the API file exists and has correct permissions.");
        } elseif ($httpCode === 404) {
            throw new Exception("API endpoint not found (404). Check if PrintGoAPI.php exists in the restapi directory.");
        } else {
            throw new Exception("API returned HTML error page. HTTP Code: $httpCode");
        }
    }

    // Try to decode JSON
    $decodedResponse = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        error_log("Raw response: " . $response);
        
        // If HTTP code indicates success (2xx), create a success response
        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'message' => 'Operation completed successfully',
                'raw_response' => $response,
                'http_code' => $httpCode
            ];
        } else {
            throw new Exception("API Error - HTTP $httpCode: $response");
        }
    }
    
    // Set HTTP response code for client errors
    if ($httpCode >= 400) {
        http_response_code($httpCode);
        $errorMessage = isset($decodedResponse['message']) ? $decodedResponse['message'] : 'Unknown error';
        throw new Exception("API returned HTTP $httpCode: $errorMessage");
    }
    
    return $decodedResponse;
}

// Ensure clean JSON output
ob_start();
header('Content-Type: application/json; charset=utf-8');

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
        if (!isset($data['order_id']) || !isset($data['numero_seguimento']) || !isset($data['transportadora'])) {
            throw new Exception("Dados incompletos. Necessário: order_id, numero_seguimento e transportadora");
        }

        error_log("Processando encomenda: " . $data['order_id']);
        
        // Clean any previous output
        if (ob_get_length()) {
            ob_clean();
        }
        
        $response = processOrder($data);
        echo json_encode($response);
        
    } else {
        throw new Exception("Method not allowed. Use POST instead.");
    }
} catch (Exception $e) {
    error_log("Error in processItemsEngine.php: " . $e->getMessage());
    
    // Clean any previous output
    if (ob_get_length()) {
        ob_clean();
    }
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function processOrder($data)
{
    global $apiUrl;
    
    try {
        // Preparar os dados para a API
        $orderId = intval($data['order_id']);
        
        if ($orderId <= 0) {
            throw new Exception("ID da encomenda inválido");
        }
        
        $requestData = [
            'numero_seguimento' => trim($data['numero_seguimento']),
            'transportadora' => trim($data['transportadora']),
            'status' => 'Enviado',
            'notify_customer' => isset($data['notify_customer']) ? intval($data['notify_customer']) : 0
        ];
        
        error_log("Request data: " . json_encode($requestData));
        
        // First, try to check if the API endpoint is accessible
        error_log("Checking API endpoint accessibility...");
        
        // Try a simple GET request first to check if the endpoint exists
        $testCh = curl_init($apiUrl);
        curl_setopt($testCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($testCh, CURLOPT_TIMEOUT, 10);
        curl_setopt($testCh, CURLOPT_NOBODY, true); // HEAD request
        curl_setopt($testCh, CURLOPT_FOLLOWLOCATION, true);
        
        curl_exec($testCh);
        $testHttpCode = curl_getinfo($testCh, CURLINFO_HTTP_CODE);
        curl_close($testCh);
        
        error_log("API endpoint test - HTTP Code: " . $testHttpCode);
        
        if ($testHttpCode === 403) {
            throw new Exception("API endpoint is forbidden (403). Check file permissions and server configuration.");
        } elseif ($testHttpCode === 404) {
            throw new Exception("API endpoint not found (404). Verify that PrintGoAPI.php exists in the restapi directory.");
        }
        
        // Try different API approaches
        $approaches = [
            // Try POST with action parameter
            [
                'method' => 'POST',
                'url' => $apiUrl,
                'data' => array_merge($requestData, ['action' => 'updateOrderStatus', 'order_id' => $orderId])
            ],
            // Try PUT with order ID in URL
            [
                'method' => 'PUT',
                'url' => $apiUrl . "?action=updateOrderStatus&id=" . $orderId,
                'data' => $requestData
            ],
            // Try POST with order ID in URL
            [
                'method' => 'POST', 
                'url' => $apiUrl . "?id=" . $orderId . "&action=updateStatus",
                'data' => $requestData
            ]
        ];
        
        $lastException = null;
        
        // Try each approach
        foreach ($approaches as $approach) {
            try {
                error_log("Trying approach: " . json_encode($approach));
                
                $ch = curl_init($approach['url']);
                
                if ($approach['method'] === 'POST') {
                    curl_setopt($ch, CURLOPT_POST, true);
                } else {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $approach['method']);
                }
                
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($approach['data']));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ]);
                
                $response = executeCurlRequest($ch);
                
                // If we get here, the request was successful
                error_log("Successful response from: " . $approach['url']);
                error_log("Response: " . json_encode($response));
                
                return [
                    'success' => true,
                    'message' => 'Encomenda processada com sucesso!',
                    'data' => [
                        'order_id' => $orderId,
                        'numero_seguimento' => $requestData['numero_seguimento'],
                        'transportadora' => $requestData['transportadora'],
                        'status' => 'Enviado',
                        'method_used' => $approach['method'],
                        'url_used' => $approach['url'],
                        'api_response' => $response
                    ]
                ];
                
            } catch (Exception $e) {
                error_log("Approach failed: " . $e->getMessage());
                $lastException = $e;
                continue; // Try next approach
            }
        }
        
        // If all API approaches failed, try direct database update as fallback
        error_log("All API approaches failed, trying direct database update...");
        
        try {
            $db = new Database();
            $pdo = $db->getConnection();
            
            $stmt = $pdo->prepare("
                UPDATE encomendas 
                SET status_encomenda = 'Enviado', 
                    numero_seguimento = ?, 
                    transportadora = ?,
                    data_atualizacao = NOW()
                WHERE id = ?
            ");
            
            $result = $stmt->execute([
                $requestData['numero_seguimento'],
                $requestData['transportadora'],
                $orderId
            ]);
            
            if ($result && $stmt->rowCount() > 0) {
                error_log("Direct database update successful for order: " . $orderId);
                
                return [
                    'success' => true,
                    'message' => 'Encomenda processada com sucesso via base de dados!',
                    'data' => [
                        'order_id' => $orderId,
                        'numero_seguimento' => $requestData['numero_seguimento'],
                        'transportadora' => $requestData['transportadora'],
                        'status' => 'Enviado',
                        'method' => 'direct_database'
                    ]
                ];
            } else {
                throw new Exception("Falha ao atualizar na base de dados - nenhuma linha afetada");
            }
            
        } catch (Exception $dbError) {
            error_log("Database update also failed: " . $dbError->getMessage());
            throw new Exception("Falha na API e na base de dados: API - " . ($lastException ? $lastException->getMessage() : 'Unknown error') . "; DB - " . $dbError->getMessage());
        }
        
    } catch (Exception $e) {
        error_log("Error in processOrder: " . $e->getMessage());
        throw new Exception("Erro ao processar encomenda: " . $e->getMessage());
    }
}

// Clean up output buffer
if (ob_get_length()) {
    ob_end_flush();
}
?>