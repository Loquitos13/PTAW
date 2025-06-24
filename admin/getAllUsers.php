<?php
session_start();
require_once '../restapi/Database.php';

header('Content-Type: application/json');

try {
    $json = file_get_contents('php://input');
    
    if(empty($json)) {
        $json = '{}';
    }
    
    $data = json_decode($json, true);
    
    if(json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }
    
    $result = getAllUsers();
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

function getAllUsers() {
    $db = new Database();
    $connection = $db->getConnection();
    
    // Get all users that are not already team members
    $query = "SELECT c.id_cliente, c.nome_cliente, c.email_cliente
              FROM Clientes c
              WHERE c.id_cliente NOT IN (
                  SELECT DISTINCT tm.id_cliente 
                  FROM TeamMembers tm 
                  WHERE tm.status_member = 'active'
              )
              ORDER BY c.nome_cliente";
    
    $stmt = $connection->prepare($query);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $users = [];
    
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    $stmt->close();
    $connection->close();
    
    return $users;
}
?>