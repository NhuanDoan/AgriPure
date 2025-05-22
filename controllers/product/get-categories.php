<?php
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    $result = $conn->query("SELECT id, name FROM categories_product ORDER BY name");

    if (!$result) {
        echo json_encode(["error" => "Lỗi truy vấn: " . $conn->error]);
        exit();
    }

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode($categories);
    exit();
?>
