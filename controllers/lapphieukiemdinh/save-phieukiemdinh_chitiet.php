<?php
    include_once("../../models/config.php");

    $response = ["status" => "error", "message" => "Dữ liệu không hợp lệ"];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["criteria_status"]) && isset($_POST["id_phieukiemdinh"])) {
        $id_phieukiemdinh = intval($_POST["id_phieukiemdinh"]);
        $criteria_statuses = $_POST["criteria_status"];

        foreach ($criteria_statuses as $code => $status) {
            // Kiểm tra xem đã tồn tại hay chưa
            $stmt_check = $conn->prepare("SELECT COUNT(*) FROM phieukiemdinh_chitiet WHERE id_phieukiemdinh = ? AND criteria_code = ?");
            $stmt_check->bind_param("is", $id_phieukiemdinh, $code);
            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($count > 0) {
                // Update
                $stmt_update = $conn->prepare("UPDATE phieukiemdinh_chitiet SET status = ? WHERE id_phieukiemdinh = ? AND criteria_code = ?");
                $stmt_update->bind_param("sis", $status, $id_phieukiemdinh, $code);
                $stmt_update->execute();
                $stmt_update->close();
            } else {
                // Insert mới
                $stmt_insert = $conn->prepare("INSERT INTO phieukiemdinh_chitiet (id_phieukiemdinh, criteria_code, status) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("iss", $id_phieukiemdinh, $code, $status);
                $stmt_insert->execute();
                $stmt_insert->close();
            }
        }

        $response = ["status" => "success"];
    }

    echo json_encode($response);
?>
