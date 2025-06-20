<?php
// Coloque este arquivo na mesma pasta do seu ApiController.php
require_once 'QueryBuilder.php';

header('Content-Type: application/json');

$queryBuilder = new QueryBuilder();

try {
    error_log("=== DEBUG UPDATE ADMIN ===");
    
    // Simular dados de entrada
    $data = [
        'id_admin' => 1,
        'pass_admin' => 'testesara'
    ];
    
    error_log("Input data: " . json_encode($data));
    
    $key_first_element = array_key_first($data);
    $value_first_element = $data[$key_first_element];
    
    error_log("First key: $key_first_element");
    error_log("First value: $value_first_element");
    
    unset($data[$key_first_element]);
    
    error_log("Data after unset: " . json_encode($data));
    
    // Hash da senha
    if (isset($data['pass_admin'])) {
        $data['pass_admin'] = password_hash($data['pass_admin'], PASSWORD_DEFAULT);
        error_log("Password hashed: " . $data['pass_admin']);
    }
    
    // Teste da query
    $result = $queryBuilder->table('Admins')
        ->update($data)
        ->where($key_first_element, '=', $value_first_element)
        ->execute();
    
    error_log("Query result: " . ($result ? 'SUCCESS' : 'FAILED'));
    
    $response = [
        'success' => true,
        'message' => 'Admin updated successfully',
        'debug' => [
            'first_key' => $key_first_element,
            'first_value' => $value_first_element,
            'update_data' => $data,
            'query_result' => $result
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>