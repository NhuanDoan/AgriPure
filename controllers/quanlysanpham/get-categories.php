<?php
    header('Content-Type: application/json');
    include_once "../../models/config.php"; // file config dùng mysqli_connect

    $sql = "SELECT id, name FROM categories_product ORDER BY name";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Lỗi truy vấn: " . mysqli_error($conn)]);
        exit();
    }

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    echo json_encode($categories);
    exit();
?>
