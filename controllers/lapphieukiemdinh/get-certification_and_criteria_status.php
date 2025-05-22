<?php
include_once("../../models/config.php");
header('Content-Type: application/json');

$id = intval($_GET['id_phieukiemdinh'] ?? 0);

if ($id === 0) {
    echo json_encode(["error" => "Thiếu ID"]);
    exit;
}


// Lấy certification_id
$stmt = $conn->prepare("SELECT certification_id FROM phieukiemdinh WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cert = $result->fetch_assoc();

if (!$cert) {
    echo json_encode(["error" => "Không tìm thấy phiếu kiểm định"]);
    exit;
}

$certification_id = $cert['certification_id'];

// Lấy trạng thái tiêu chí đã đánh giá (nếu có)
$crit_stmt = $conn->prepare("SELECT criteria_code, status FROM phieukiemdinh_chitiet WHERE id_phieukiemdinh = ?");
$crit_stmt->bind_param("i", $id);
$crit_stmt->execute();
$crit_result = $crit_stmt->get_result();

$criteria_status = [];
while ($row = $crit_result->fetch_assoc()) {
    $criteria_status[$row['criteria_code']] = $row['status'];
}

echo json_encode([
    "certification_id" => $certification_id,
    "criteria_status" => $criteria_status
]);
