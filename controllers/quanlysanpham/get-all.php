<?php
    session_start();
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $category = $_GET['category'] ?? '';
    $search = $_GET['search'] ?? '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $where = " WHERE p.user_id = ? ";
    $params = [$user_id];
    $types = "i";

    if ($category !== '') {
        $where .= " AND p.category_id = ? ";
        $params[] = $category;
        $types .= "i";
    }

    if ($search !== '') {
        $where .= " AND p.name LIKE ? ";
        $params[] = "%$search%";
        $types .= "s";
    }

    // Đếm tổng sản phẩm
    $sqlCount = "SELECT COUNT(*) as total FROM products p $where";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param($types, ...$params);
    $stmtCount->execute();
    $resCount = $stmtCount->get_result();
    $total = $resCount->fetch_assoc()['total'];
    $stmtCount->close();

    // Lấy danh sách sản phẩm
    $sql = "SELECT p.*, c.name AS category_name 
            FROM products p 
            LEFT JOIN categories_product c ON p.category_id = c.id
            $where
            ORDER BY p.id DESC 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    $types_with_limit = $types . "ii";
    $params_with_limit = array_merge($params, [$limit, $offset]);
    $stmt->bind_param($types_with_limit, ...$params_with_limit);

    $stmt->execute();
    $res = $stmt->get_result();

    $products = [];
    while ($row = $res->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode([
        'total' => $total,
        'products' => $products
    ]);
    exit();
?>
