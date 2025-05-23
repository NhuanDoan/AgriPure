<?php
header('Content-Type: application/json');
require_once '../../models/config.php'; // file config phải khai báo $conn = mysqli_connect(...)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Phương thức không hợp lệ"]);
    exit;
}

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$note = $_POST['note'] ?? '';

if ($order_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ID đơn hàng không hợp lệ"]);
    exit;
}

// Lấy trạng thái đơn hiện tại
$sql = "SELECT status FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Lỗi chuẩn bị truy vấn"]);
    exit;
}
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->bind_result($status);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(["success" => false, "message" => "Không tìm thấy đơn hàng"]);
    $stmt->close();
    exit;
}
$stmt->close();

// Kiểm tra trạng thái
if ($status !== 'pending') {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Chỉ xác nhận/hủy được đơn đang chờ xử lý"]);
    exit;
}

// Cập nhật trạng thái và ghi chú
$sql = "UPDATE orders SET status = 'shipping', note = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Lỗi chuẩn bị truy vấn cập nhật"]);
    exit;
}
$stmt->bind_param("si", $note, $order_id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Đơn hàng đã được xác nhận"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Có lỗi khi xác nhận đơn"]);
}
?>
