<?php
    // Kết nối tới cơ sở dữ liệu
    include_once("../../models/config.php");

    // Lấy danh sách chứng chỉ từ cơ sở dữ liệu
    $sql = "SELECT * FROM certification_types";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Khởi tạo mảng để lưu trữ kết quả
    $certifications = [];
    while ($row = $result->fetch_assoc()) {
        $certifications[] = $row;
    }

    // Trả về danh sách chứng chỉ dưới dạng JSON
    echo json_encode($certifications);
?>
