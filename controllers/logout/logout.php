<?php 
    session_start();

    if (isset($_SESSION['unique_id'])) {
        include_once("../../models/config.php");

        $logout_id = $_GET['logout_id'] ?? null;

        // Kiểm tra logout_id có tồn tại và có trùng với phiên hiện tại không
        if ($logout_id && $logout_id === $_SESSION['unique_id']) {
            $status = "Offline";
            
            // Sử dụng prepare để an toàn hơn
            $stmt = $conn->prepare("UPDATE users SET status = ? WHERE unique_id = ?");
            $stmt->bind_param("si", $status, $logout_id);
            
            if ($stmt->execute()) {
                session_unset();
                session_destroy();
                header("Location: ../../index.php?page=login");
                exit();
            } else {
                echo "Lỗi khi cập nhật trạng thái đăng xuất!";
            }

            $stmt->close();
        } else {
            // Không đúng người hoặc không có logout_id
            header("Location: ../../index.php");
            exit();
        }
    } else {
        header("Location: ../../index.php?page=login");
        exit();
    }
?>
