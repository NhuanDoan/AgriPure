<?php
    include_once("../../models/config.php");
    header("Content-Type: application/json");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $phone = trim($_POST['phone'] ?? '');

        if (empty($phone)) {
            echo json_encode(["status" => "error", "message" => "Số điện thoại không được để trống"]);
            exit();
        }

        // Join bảng user với nongtrai theo user_id
        $query = "SELECT n.tennongtrai, n.diachi, u.fname, u.lname
                FROM users u
                JOIN nongtrai n ON u.user_id = n.id_user
                WHERE u.sdt = ? LIMIT 1";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $phone);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode(["status" => "success", "data" => $row]);
        } else {
            echo json_encode(["status" => "not_found"]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["status" => "error", "message" => "Yêu cầu không hợp lệ"]);
    }
?>
