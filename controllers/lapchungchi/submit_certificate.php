<?php
    session_start();
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    $data = json_decode(file_get_contents("php://input"), true);
    $id_phieukiemdinh = $data['id_phieukiemdinh'] ?? null;
    $issue_date = $data['ngay_cap'] ?? null;

    if (!$id_phieukiemdinh || !$issue_date) {
        echo json_encode(["success" => false, "message" => "Thiếu thông tin phiếu kiểm định hoặc ngày cấp."]);
        exit();
    }

    // Lấy thông tin từ phiếu kiểm định
    $stmt = $conn->prepare("
        SELECT 
            pk.id AS phieukiemdinh_id,
            nt.id_nongtrai AS nongtrai_id,
            ct.id AS certification_type_id,
            nt.tennongtrai,
            ct.name AS certification_type_name
        FROM 
            phieukiemdinh pk
        JOIN 
            nongtrai nt ON pk.farm_id = nt.id_nongtrai
        JOIN 
            certification_types ct ON pk.certification_id = ct.id
        WHERE pk.id = ?
    ");
    $stmt->bind_param("i", $id_phieukiemdinh);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Phiếu kiểm định không hợp lệ."]);
        exit();
    }

    $info = $result->fetch_assoc();
    $nongtrai_id = $info['nongtrai_id'];
    $certification_type_id = $info['certification_type_id'];

    // Kiểm tra chứng chỉ hiện tại (trùng loại + còn hiệu lực)
    $stmtCheck = $conn->prepare("
        SELECT id FROM certificates 
        WHERE nongtrai_id = ? AND certification_type_id = ? AND expiry_date >= CURDATE()
    ");
    $stmtCheck->bind_param("ii", $nongtrai_id, $certification_type_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    if ($resultCheck->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Nông trại đã có chứng chỉ còn hiệu lực cho loại này."]);
        exit();
    }

    // Tính ngày hết hạn (ví dụ: 1 năm)
    $expiry_date = date('Y-m-d', strtotime($issue_date . ' +1 year'));
    $status = "Đạt";
    $id_kdv = $_SESSION['id_kiemdinhvien'];

    // Ghi chứng chỉ mới
    try {
        $stmtInsert = $conn->prepare("
            INSERT INTO certificates (nongtrai_id, certification_type_id, id_pkd, id_kdv, issue_date, expiry_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInsert->bind_param("iiiisss", $nongtrai_id, $certification_type_id,$id_phieukiemdinh, $id_kdv, $issue_date, $expiry_date, $status);
        $stmtInsert->execute();

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Lỗi: " . $e->getMessage()]);
    }
?>
