<?php

$targetDir = "../uploads/";

if (isset($_FILES['modelFile']) && isset($_FILES['image'])) {
    
    $file = $_FILES['modelFile'];
    $image = $_FILES['image'];

    $targetFile = $targetDir . basename($file['name']);
    $targetImage = $targetDir . basename($image['name']);

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {

        $pathToFile = $targetDir . htmlspecialchars($file['name']);

        if (move_uploaded_file($image['tmp_name'], $targetImage)) {

            $pathToImage = $targetDir . htmlspecialchars($image['name']);

            echo json_encode([
                'status' => 'success',
                'pathToFile' => $pathToFile,
                'pathToImage' => $pathToImage,
            ]);

        } else {

            echo json_encode([
                'status' => 'error',
                'pathToFile' => $pathToFile,
                'pathToImage' => "Error uploading Image",
            ]);

        }

    } else {

        echo json_encode([
            'status' => 'error',
            'pathToFile' => "Error uploading 3DModel",
            'pathToImage' => "Error uploading Image",
        ]);

    }

} else {

        echo json_encode([
            'status' => 'error',
            'pathToFile' => "No file uploaded",
            'pathToImage' => "No image uploaded",
        ]);
}
?>
