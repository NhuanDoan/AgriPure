<?php
    session_start();
    header('Content-Type: application/json');
    require_once '../../models/config.php';

    $status = $_GET['status'] ?? '';
    $search = $_GET['search'] ?? '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    $role = $_SESSION['role'];
    $user_id = $_SESSION['user_id'];

    $offset = ($page - 1) * $limit;

    $params = [];
    $types = '';
    $whereClauses = [];
    $joinClause = ''; // Dùng cho role = 4

    // Lọc theo status
    if ($status && in_array($status, ['pending', 'shipping', 'completed', 'cancelled'])) {
        $whereClauses[] = "o.status = ?";
        $params[] = $status;
        $types .= 's';
    }

    // Tìm kiếm theo tên hoặc điện thoại
    if ($search) {
        $whereClauses[] = "(o.full_name LIKE ? OR o.id = ?)";
        $params[] = "%$search%";
        $params[] = $search;
        $types .= 'si';
    }

    // Lọc theo role
    if ($role == 4) {
        $whereClauses[] = "o.user_id = ?";
        $params[] = $user_id;
        $types .= 'i';
    } elseif ($role == 2) {
        $joinClause = "JOIN order_items oi ON oi.order_id = o.id
                    JOIN products p ON p.id = oi.product_id";
        $whereClauses[] = "p.user_id = ?";
        $params[] = $user_id;
        $types .= 'i';
    }

    // Tạo câu WHERE
    $whereSQL = '';
    if (!empty($whereClauses)) {
        $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
    }

    // Đếm tổng đơn hàng
    $sqlCount = "SELECT COUNT(DISTINCT o.id) FROM orders o $joinClause $whereSQL";
    $stmtCount = $conn->prepare($sqlCount);
    if (!empty($params)) {
        $stmtCount->bind_param($types, ...$params);
    }
    $stmtCount->execute();
    $stmtCount->bind_result($total);
    $stmtCount->fetch();
    $stmtCount->close();

    // Lấy danh sách đơn
    $sql = "SELECT DISTINCT o.id, o.full_name, o.phone, o.address, o.status, o.total_price, o.created_at, o.payment_method, o.payment_status
            FROM orders o
            $joinClause
            $whereSQL
            ORDER BY o.created_at DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    $typesWithLimit = $types . "ii";
    $paramsWithLimit = array_merge($params, [$limit, $offset]);
    $stmt->bind_param($typesWithLimit, ...$paramsWithLimit);

    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode([
        "total" => $total,
        "orders" => $orders
    ]);

    $stmt->close();
    $conn->close();
?>
