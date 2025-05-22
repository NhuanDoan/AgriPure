<?php
    require_once '../../models/config.php'; // file chứa kết nối CSDL

    $farm_id = isset($_GET['id_nongtrai']) ? intval($_GET['id_nongtrai']) : 0;
    if ($farm_id <= 0) {
        echo json_encode([]);
        exit();
    }

    $sql = "SELECT * FROM nongtrai nt LEFT JOIN users us
            ON nt.id_user = us.user_id
            WHERE id_nongtrai = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $farm_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $farm = $result->fetch_assoc();
        echo json_encode($farm);
    } else {
        echo json_encode([]);
    }
?>