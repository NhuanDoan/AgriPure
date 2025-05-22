<?php
header('Content-Type: application/json');

// Kết nối đến cơ sở dữ liệu
include_once("../../models/config.php");

// Kiểm tra tham số certification_id
if (!isset($_GET['certification_id']) || !is_numeric($_GET['certification_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu hoặc sai định dạng certification_id."]);
    exit;
}

$certification_id = intval($_GET['certification_id']);

// Lấy các tiêu chí của chứng nhận
$sql = "SELECT code, description FROM certification_criteria WHERE certification_type_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $certification_id);
$stmt->execute();
$result = $stmt->get_result();

// Khởi tạo mảng tiêu chí
$criteria = [];
while ($row = $result->fetch_assoc()) {
    $criteria[] = $row;
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($criteria);
?>
