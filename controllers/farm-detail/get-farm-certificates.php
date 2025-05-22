<?php
require_once '../../models/config.php';

$farm_id = isset($_GET['id_nongtrai']) ? intval($_GET['id_nongtrai']) : 0;
if ($farm_id <= 0) {
    echo json_encode([]);
    exit();
}

// Lấy danh sách chứng chỉ của nông trại kèm loại chứng chỉ
$sql = "
    SELECT 
        c.id,
        ct.name AS tenchungnhan,
        c.issue_date,
        c.expiry_date,
        c.status
    FROM certificates c
    JOIN certification_types ct ON c.certification_type_id = ct.id
    WHERE c.nongtrai_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farm_id);
$stmt->execute();
$result = $stmt->get_result();

$certificates = [];
while ($row = $result->fetch_assoc()) {
    $certificates[] = $row;
}

echo json_encode($certificates);
?>