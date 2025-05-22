<?php
session_start();
include_once("../../models/config.php");

header("Content-Type: application/json");

// Kiểm tra kết nối cơ sở dữ liệu
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Kết nối cơ sở dữ liệu thất bại!"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $fullname = trim($_POST['fullname']);
    $farmname = trim($_POST['farmname']);
    $phone = trim($_POST['phone']);
    $date = trim($_POST['date']);
    $certification_id = $_POST['certification'];  // Thêm chứng nhận kiểm định
    $address = trim($_POST['address']);
    $status = "Chờ kiểm định viên";  // Hoặc trạng thái có thể thay đổi tùy yêu cầu
    $id_user = $_SESSION['user_id'];

    // Kiểm tra thông tin có đầy đủ không
    if (empty($fullname) || empty($phone) || empty($date) || empty($address) || empty($certification_id)) {
        echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit();
    }

    // Kiểm tra số điện thoại hợp lệ (Việt Nam)
    $pattern = "/^(03|05|07|08|09)[0-9]{8}$/";
    if (!preg_match($pattern, $phone)) {
        echo json_encode(["status" => "error", "message" => "Số điện thoại không hợp lệ!"]);
        exit();
    }

    // Kiểm tra ngày kiểm định không nhỏ hơn hôm nay
    if (strtotime($date) < strtotime(date("Y-m-d"))) {
        echo json_encode(["status" => "error", "message" => "Ngày kiểm định không được nhỏ hơn hôm nay!"]);
        exit();
    }

    // Kiểm tra nếu nông trại đã tồn tại trong cơ sở dữ liệu
    $query = "SELECT id_nongtrai FROM nongtrai WHERE tennongtrai = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $farmname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $farm_id = null;
    if ($result->num_rows > 0) {
        // Nông trại đã tồn tại, lấy id nông trại
        $farm = mysqli_fetch_assoc($result);
        $farm_id = $farm['id_nongtrai'];
    } else {
        // Nông trại chưa tồn tại, thêm vào cơ sở dữ liệu
        $insert_query = "INSERT INTO nongtrai (tennongtrai, diachi, sdt, id_user) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "sssi", $farmname, $address, $phone, $id_user);
        if (mysqli_stmt_execute($insert_stmt)) {
            $farm_id = mysqli_insert_id($conn); // Lấy id của nông trại vừa thêm vào
        } else {
            echo json_encode(["status" => "error", "message" => "Lỗi khi thêm nông trại: " . mysqli_error($conn)]);
            exit();
        }
        mysqli_stmt_close($insert_stmt);
    }

    mysqli_stmt_close($stmt);

    // Thêm phiếu kiểm định vào bảng phieukiemdinh
    $query_insert = "INSERT INTO phieukiemdinh (fullname, farmname, farm_id, phone, date, certification_id, address, status, id_user) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $query_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssississi", $fullname, $farmname, $farm_id, $phone, $date, $certification_id, $address, $status, $id_user);
    
    if (mysqli_stmt_execute($stmt_insert)) {
        echo json_encode(["status" => "success", "message" => "Đăng ký kiểm định nông sản thành công!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi khi đăng ký kiểm định: " . mysqli_error($conn)]);
    }

    mysqli_stmt_close($stmt_insert);
} else {
    echo json_encode(["status" => "error", "message" => "Yêu cầu không hợp lệ!"]);
}
?>
