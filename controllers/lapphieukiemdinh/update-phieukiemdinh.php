<?php 
    include_once("../../models/config.php");

    header('Content-Type: application/json');
    
    // Kiểm tra phương thức gửi dữ liệu
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ"]);
        exit;
    }
    
    // Kiểm tra dữ liệu đầu vào
    $id_phieukiemdinh = isset($_POST["id_phieukiemdinh"]) ? intval($_POST["id_phieukiemdinh"]) : 0;
    $binhluan = isset($_POST["binhluan"]) ? trim($_POST["binhluan"]) : "";
    $danhgia = isset($_POST["danhgia"]) ? trim($_POST["danhgia"]) : "";
    $id_kdv = isset($_POST["id_kdv"]) ? intval($_POST["id_kdv"]) : 0;
    $status = "Đã kiểm định";
    
    if ($id_phieukiemdinh == 0 ) {
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu bắt buộc id_pkd"]);
        exit;
    }
    if($id_kdv == 0){
        echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu bắt buộc id_kdv"]);
        exit;
    }
    
    // Cập nhật vào database
    $sql = "UPDATE phieukiemdinh SET binhluan = ?, danhgia = ?, id_kdv = ?, status= ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssisi", $binhluan, $danhgia, $id_kdv, $status, $id_phieukiemdinh);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "message" => "Cập nhật phiếu kiểm định thành công!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi cập nhật dữ liệu!", "error" => mysqli_error($conn)]);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>
