<?php
$base_url = "http://estga-dev.ua.pt/~ptaw-2025-gr4";

require_once '../restapi/Database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//para o servidor usar: $base_url/restapi/PrintGoAPI.php
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['product_image'])) {

        $idProduto = isset($_POST['id_produto']) ? $_POST['id_produto'] : null;

        // --- Processamento da imagem principal ---
        if ($_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro no upload da imagem.");
        }

        $uploadDir = __DIR__ . '/../imagens/img_products/';
        $fileName = $idProduto . '_' . basename($_FILES['product_image']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
            throw new Exception("Erro ao guardar a imagem.");
        }

        // --- Processamento do modelo 3D (opcional) ---
        $model3dName = null;
        if (isset($_FILES['modelo3d_produto']) && $_FILES['modelo3d_produto']['error'] === UPLOAD_ERR_OK) {

            $model3dDir = __DIR__ . '/../public/modelo3D/';
            $model3dName = uniqid() . '_' . basename($_FILES['modelo3d_produto']['name']);
            $model3dFile = $model3dDir . $model3dName;

            if (!move_uploaded_file($_FILES['modelo3d_produto']['tmp_name'], $model3dFile)) {
                throw new Exception("Erro ao guardar o modelo 3D.");
            }
        }

        $data = $_POST;
        $data['imagem_principal'] = $fileName;
        if ($model3dName) {
            $data['modelo3d_produto'] = $model3dName;
        }

        if (isset($data['variantes'])) {
            $data['variantes'] = json_decode($data['variantes'], true);
        } elseif (isset($data['cores'])) {
            $data['variantes'] = array_map(function($cor) {
                return ['id_cor' => $cor['hex_cor'] ?? $cor];
            }, json_decode($data['cores'], true));
        }

        if (isset($data['dimensoes'])) {
            $data['dimensoes'] = json_decode($data['dimensoes'], true);
        }

        $data['status_produto'] = in_array($data['status_produto'], ['active', 'inactive', '1', '0']) ? 
        $data['status_produto'] : 'active';

        error_log('DADOS A ENVIAR PARA API: ' . json_encode($data));
        $result = addProduct($data);
        error_log('RESPOSTA DA API: ' . json_encode($result));

        echo json_encode([
            'status' => 'success',
            'data' => $result,
            'imagem' => $fileName,
            'modelo3d' => $model3dName
        ]);
        exit;
    } else {
        throw new Exception("Pedido inválido ou sem imagem.");
    }
}

function addProduct($data) {
    global $apiUrl;

    if(empty($data)) {
        throw new Exception("No user data provided");
    }

    $ch = curl_init("$apiUrl/insertProduct");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = executeCurlRequest($ch);
    $decoded = json_decode($response, true);

    if (!$decoded || (isset($decoded['success']) && !$decoded['success'])) {
        throw new Exception("Erro ao inserir produto na API: " . ($decoded['message'] ?? 'Erro desconhecido'));
    }

    return $decoded;

}
?>