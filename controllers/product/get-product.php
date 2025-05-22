<?php
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    $category = isset($_GET['category']) ? intval($_GET['category']) : null;
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 24;
    $offset = ($page - 1) * $limit;

    // --- Câu truy vấn đếm tổng sản phẩm (phục vụ phân trang) ---
    $countQuery = "SELECT COUNT(*) AS total
                FROM products p
                WHERE 1";
    $countParams = [];
    $countTypes = "";

    if ($category) {
        $countQuery .= " AND p.category_id = ?";
        $countTypes .= "i";
        $countParams[] = $category;
    }
    if ($search) {
        $countQuery .= " AND p.name LIKE ?";
        $countTypes .= "s";
        $countParams[] = "%" . $search . "%";
    }

    $countStmt = $conn->prepare($countQuery);
    if (!empty($countParams)) {
        $countStmt->bind_param($countTypes, ...$countParams);
    }
    $countStmt->execute();
    $total = $countStmt->get_result()->fetch_assoc()['total'] ?? 0;

    // --- Truy vấn sản phẩm có kèm is_certified ---
    $dataQuery = "
        SELECT 
            p.*, 
            c.name AS category_name,
            CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM certificates ct 
                    JOIN nongtrai nt2 ON ct.nongtrai_id = nt2.id_nongtrai
                    WHERE nt2.id_user = p.user_id AND ct.status = 'Đạt'
                )
                THEN 1 ELSE 0
            END AS is_certified
        FROM products p
        LEFT JOIN categories_product c ON p.category_id = c.id
        WHERE 1";

    $dataParams = [];
    $dataTypes = "";

    if ($category) {
        $dataQuery .= " AND p.category_id = ?";
        $dataTypes .= "i";
        $dataParams[] = $category;
    }
    if ($search) {
        $dataQuery .= " AND p.name LIKE ?";
        $dataTypes .= "s";
        $dataParams[] = "%" . $search . "%";
    }

    $dataQuery .= " LIMIT ? OFFSET ?";
    $dataTypes .= "ii";
    $dataParams[] = $limit;
    $dataParams[] = $offset;

    $dataStmt = $conn->prepare($dataQuery);
    $dataStmt->bind_param($dataTypes, ...$dataParams);
    $dataStmt->execute();
    $result = $dataStmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode([
        "products" => $products,
        "total" => $total,
    ]);
    exit();
?>
