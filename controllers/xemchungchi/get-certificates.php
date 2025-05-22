<?php
    include_once("../../models/config.php");
    header('Content-Type: application/json');

    // Nhận các tham số GET
    $id_phieukiemdinh = $_GET['id_phieukiemdinh'] ?? null;
    $id_nongtrai = $_GET['id_nongtrai'] ?? null;
    $id_kdv = $_GET['id_kdv'] ?? null;

    // Câu SQL gốc
    $sql = "
        SELECT 
            c.id, 
            n.tennongtrai, 
            ct.name AS certification_name, 
            c.issue_date, 
            c.expiry_date, 
            c.status,
            pkd.phone
        FROM 
            certificates c
        JOIN 
            nongtrai n ON c.nongtrai_id = n.id_nongtrai
        JOIN 
            certification_types ct ON c.certification_type_id = ct.id
        JOIN
            phieukiemdinh pkd ON c.id_pkd = pkd.id
        JOIN 
            kiemdinhvien kdv ON c.id_kdv = kdv.manv
    ";

    // Xử lý điều kiện WHERE nếu có tham số lọc
    $conditions = [];
    $params = [];
    $types = "";

    if ($id_phieukiemdinh) {
        $conditions[] = "c.id_pkd = ?";
        $params[] = $id_phieukiemdinh;
        $types .= "i";
    }
    if ($id_nongtrai) {
        $conditions[] = "n.id_nongtrai = ?";
        $params[] = $id_nongtrai;
        $types .= "i";
    }
    if ($id_kdv) {
        $conditions[] = "c.id_kdv = ?";
        $params[] = $id_kdv;
        $types .= "i";
    }

    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY c.issue_date DESC";

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $certificates = [];
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }

    echo json_encode($certificates);
?>