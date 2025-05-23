<?php

    header('Content-Type: application/json');
    require_once '../../models/config.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Phương thức không hợp lệ"]);
        exit;
    }

    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $note = trim($_POST['note'] ?? '');

    if ($order_id <= 0) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID đơn hàng không hợp lệ"]);
        exit;
    }

    // Kiểm tra trạng thái đơn hàng
    $sql = "SELECT status FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $order_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $status);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$status) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Không tìm thấy đơn hàng"]);
        exit;
    }

    if ($status !== 'pending') {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Chỉ huỷ được đơn đang chờ xử lý"]);
        exit;
    }

    // Cập nhật trạng thái và ghi chú
    $sql = "UPDATE orders SET status = 'cancelled', note = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $note, $order_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($success) {
        echo json_encode(["success" => true, "message" => "Huỷ đơn thành công"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Có lỗi khi huỷ đơn"]);
    }

?>
