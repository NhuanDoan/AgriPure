<?php 
    session_start();
    include_once("../../models/config.php");

    // Kiểm tra nếu chưa đăng nhập
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        die("Lỗi: Chưa đăng nhập.");
    }

    $user_id = intval($_SESSION['user_id']);
    $role = intval($_SESSION['role']);
    $searchTerm = isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : "";

    // Chuẩn bị truy vấn an toàn
    if ($role == 1) {
        if ($searchTerm === "") {
            $sql = "SELECT pkd.* FROM phieukiemdinh pkd
                    LEFT JOIN kiemdinhvien kdv ON pkd.id_kdv = kdv.manv
                    WHERE (kdv.manv = ?) OR (pkd.id_kdv IS NULL) 
                    ORDER BY pkd.created_at DESC";
            $stmt = $conn->prepare($sql);
            $manv = $_SESSION['id_kiemdinhvien'];
            $stmt->bind_param("i", $manv);
        } else {
            $sql = "SELECT pkd.* FROM phieukiemdinh pkd
                    LEFT JOIN kiemdinhvien kdv ON pkd.id_kdv = kdv.manv
                    WHERE ((kdv.manv = ?) OR (pkd.id_kdv IS NULL)) 
                    AND pkd.fullname LIKE ? 
                    ORDER BY pkd.created_at DESC";
            $stmt = $conn->prepare($sql);
            $manv = $_SESSION['id_kiemdinhvien'];
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("is", $manv, $searchTerm);
        }        
    } else {
        if ($searchTerm === "") {
            $sql = "SELECT * FROM phieukiemdinh WHERE id_user = ? ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
        } else {
            $sql = "SELECT * FROM phieukiemdinh WHERE id_user = ? AND fullname LIKE ? ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $searchTerm = "%$searchTerm%";
            $stmt->bind_param("is", $user_id, $searchTerm);
        }
    }

    $output = "";
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            include_once("data-phieukiemdinh.php");
        } else {
            $output .= "Không có phiếu kiểm định nào!!";
        }
    } else {
        $output .= "Lỗi truy vấn: " . $stmt->error;
    }

    echo $output;
    $stmt->close();
?>
