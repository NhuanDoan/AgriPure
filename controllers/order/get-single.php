<?php
    header('Content-Type: application/json');
    require_once '../../models/config.php';

    $id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "ID đơn hàng không hợp lệ"]);
        exit;
    }

    // Lấy thông tin đơn hàng
    $sql = "SELECT id, full_name, address, phone, total_price, payment_method, payment_status, status, created_at, note
            FROM orders
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order) {
        http_response_code(404);
        echo json_encode(["error" => "Không tìm thấy đơn hàng"]);
        exit;
    }

    // Lấy danh sách sản phẩm trong đơn hàng
    $sql = "SELECT oi.product_id, p.name AS product_name, oi.quantity, oi.price
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();

    $order['items'] = $items;

    echo json_encode($order);
?>