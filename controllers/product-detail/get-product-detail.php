<?php
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    $id = isset($_GET['id_product']) ? intval($_GET['id_product']) : 0;
    if ($id <= 0) {
        echo json_encode([]);
        exit();
    }

    $sql = "
    SELECT 
        p.*, 
        c.name AS category_name,
        nt.id_nongtrai,
        nt.tennongtrai,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM certificates ct 
                WHERE ct.nongtrai_id = nt.id_nongtrai AND ct.status = 'Đạt'
            )
            THEN 1 ELSE 0
        END AS is_certified
    FROM products p
    LEFT JOIN categories_product c ON p.category_id = c.id
    LEFT JOIN nongtrai nt ON nt.id_user = p.user_id
    WHERE p.id = ?
    LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $product = $result->fetch_assoc();

    echo json_encode($product ? $product : []);
    exit();
?>