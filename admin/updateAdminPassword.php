<?php
session_start();
require_once '../restapi/Database.php';

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
    
    $result = updateAdminPassword($data);
    echo json_encode([
        'status' => 'success',
        'message' => 'Password updated successfully',
        'data' => $result
    ]);
    
} catch(Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function updateAdminPassword($data) {
    if(empty($data['id_admin'])) {
        throw new Exception("Admin ID is required");
    }
    
    if(empty($data['current_password'])) {
        throw new Exception("Current password is required");
    }
    
    if(empty($data['new_password'])) {
        throw new Exception("New password is required");
    }
    
    $adminId = intval($data['id_admin']);
    $currentPassword = $data['current_password'];
    $newPassword = $data['new_password'];
    
    // Direct database connection
    $db = new Database();
    $connection = $db->getConnection();
    
    // First, verify current password
    $query = "SELECT pass_admin FROM Admins WHERE id_admin = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    
    if (!$admin) {
        $stmt->close();
        $connection->close();
        throw new Exception("Admin not found");
    }
    
    // Check if current password matches (plain text comparison for school project)
    if ($admin['pass_admin'] !== $currentPassword) {
        $stmt->close();
        $connection->close();
        throw new Exception("Current password is incorrect");
    }
    
    $stmt->close();
    
    // Update password (store as plain text for school project)
    $updateQuery = "UPDATE Admins SET pass_admin = ? WHERE id_admin = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("si", $newPassword, $adminId);
    
    if (!$updateStmt->execute()) {
        $updateStmt->close();
        $connection->close();
        throw new Exception("Failed to update password");
    }
    
    $affectedRows = $updateStmt->affected_rows;
    $updateStmt->close();
    $connection->close();
    
    return ['updated' => true, 'affected_rows' => $affectedRows];
}
?>