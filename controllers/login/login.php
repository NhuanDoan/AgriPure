<?php 
session_start(); 
include_once("../../models/config.php");

$sdt = trim($_POST['sdt'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!empty($sdt) && !empty($password)) {
    // Băm mật khẩu nhập vào để so sánh với DB
    $hashed_password = md5($password);

    // Truy vấn kiểm tra tài khoản
    if ($stmt = $conn->prepare("SELECT * FROM users WHERE sdt = ? AND password = ?")) {
        $stmt->bind_param("ss", $sdt, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            $status = "Online";
            $stmt2 = $conn->prepare("UPDATE users SET status=? WHERE unique_id=?");
            $stmt2->bind_param("si", $status, $row['unique_id']);
            $stmt2->execute();

            $_SESSION['unique_id'] = $row['unique_id'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];
            if($row['role'] == 1)
            {
                $stmt3 = $conn->prepare("SELECT * FROM kiemdinhvien WHERE user_id=?");
                $stmt3->bind_param("i", $row['user_id']);
                $stmt3->execute();
                $result1 = $stmt3->get_result();
                $row1 = $result1->fetch_assoc();
                $_SESSION['id_kiemdinhvien'] = $row1['manv'];
            } else if($row['role'] == 2)
            {
                $stmt3 = $conn->prepare("SELECT * FROM nongtrai WHERE id_user=?");
                $stmt3->bind_param("i", $row['user_id']);
                $stmt3->execute();
                $result1 = $stmt3->get_result();
                $row1 = $result1->fetch_assoc();
                $_SESSION['id_nongtrai'] = $row1['id_nongtrai'];
            }

            echo "thành công";
        } else {
            echo "Số điện thoại hoặc mật khẩu không đúng!";
        }

        $stmt->close();
    } else {
        echo "Lỗi hệ thống: Không thể thực hiện truy vấn.";
    }
} else {
    echo "Vui lòng nhập đầy đủ thông tin!";
}
?>
