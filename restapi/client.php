<?php

require_once 'Database.php';
require_once 'QueryBuilder.php';

$queryBuilder = new QueryBuilder();

$apiUrl = "http://localhost:8081/PTAW/restapi/PrintGoAPI.php";

function executeCurlRequest($ch) {

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode >= 400) {

        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("HTTP $httpCode Error: $error | Response: $response");

    }

    if (curl_errno($ch)) {

        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL Error: $error");
        
    }

    curl_close($ch);
    return $response;

}

function getUsers() {

    global $apiUrl;

    $ch = curl_init("$apiUrl/users");

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    return json_decode(executeCurlRequest($ch), true);
}

function getUserByID($id) {

    global $apiUrl;

    $ch = curl_init("$apiUrl/user/$id");

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    return json_decode(executeCurlRequest($ch), true);
}

function getEncomendasByUserID($id) {

    global $apiUrl;

    $ch = curl_init("$apiUrl/encomendasByUserID/$id");

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    return json_decode(executeCurlRequest($ch), true);
}

function addUser($userData) {

    global $apiUrl;

    $ch = curl_init("$apiUrl/insertUser");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    return json_decode(executeCurlRequest($ch), true);
}

function updateUser($userData) {

    global $apiUrl;

    $ch = curl_init("$apiUrl/updateUser");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    return json_decode(executeCurlRequest($ch), true);
}

function deleteUserByID($userID) {

    global $apiUrl;

    $ch = curl_init("$apiUrl/deleteUser/$userID");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userID));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    return json_decode(executeCurlRequest($ch), true);
}


try {

    $deleteUserByID = deleteUserByID(3);
    print_r($deleteUserByID);

    /*$updateUser = [
        'id_cliente' => 6,
        'nome_cliente' => 'UserTest',
        'email_cliente' => 'usertestasdasd@mail.com'
    ];

    $updateUserResult = updateUser($updateUser);
    print_r($updateUserResult);*/

    /*$newUser = [
        'nome_cliente' => 'Test User2',
        'email_cliente' => 'test2@example.com',
        'pass_cliente' => 'secure_password',
        'contacto_cliente' => '123456789',
        'morada_cliente' => 'Test Address',
        'nif_cliente' => '987654324',
        'imagem_cliente' => 'test_image.jpg',
        'data_criacao_cliente' => '2025-04-16'
    ];

    $addedUserResult = addUser($newUser);
    print_r($addedUserResult);*/


    /*$users = getUsers();
    print_r($users);*/

    /*$userByID = getUserByID(1);
    print_r($userByID);*/

    /*$encomendasByUserID = getEncomendasByUserID(1);
    print_r($encomendasByUserID);*/

} catch (Exception $e) {

    echo "Error: " . $e->getMessage();

}
