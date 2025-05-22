<?php
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    if (!isset($_GET['id_phieukiemdinh']) || empty($_GET['id_phieukiemdinh'])) {
        echo json_encode(["error" => "Thiếu ID phiếu kiểm định"]);
        exit();
    }

    $id_phieukiemdinh = intval($_GET['id_phieukiemdinh']);

    // Lấy thông tin phiếu kiểm định + kiểm định viên + loại chứng chỉ
    $query = "SELECT pkd.*, kdv.hoten AS ten_kdv, ct.name AS certification_type_name
            FROM phieukiemdinh pkd
            LEFT JOIN kiemdinhvien kdv ON pkd.id_kdv = kdv.manv
            LEFT JOIN certification_types ct ON pkd.certification_id = ct.id 
            WHERE pkd.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_phieukiemdinh);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Không tìm thấy phiếu kiểm định"]);
        exit();
    }

    $row = $result->fetch_assoc();

    // Lấy danh sách tiêu chí và trạng thái đánh giá (nếu có)
    $criteria_query = "SELECT c.code, c.description, p.status
                    FROM phieukiemdinh_chitiet p
                    JOIN certification_criteria c ON p.criteria_code = c.code
                    WHERE p.id_phieukiemdinh = ?";

    $cstmt = $conn->prepare($criteria_query);
    $cstmt->bind_param("i", $id_phieukiemdinh);
    $cstmt->execute();
    $cresult = $cstmt->get_result();

    $criteria = [];
    while ($crit = $cresult->fetch_assoc()) {
        $criteria[] = $crit;
    }

    // Gộp vào kết quả
    $row['criteria'] = $criteria;

    echo json_encode($row);
    exit();
?>