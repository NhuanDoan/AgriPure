<?php
    session_start();
    include_once("../../models/config.php");
    header('Content-Type: application/json');

    if (isset($_GET['id_phieukiemdinh'])) {
        $id_phieukiemdinh = intval($_GET['id_phieukiemdinh']);
        
        // Fetching inspection report (phieukiemdinh) details
        $stmt = mysqli_prepare($conn, "SELECT * FROM phieukiemdinh pkd JOIN certification_types ct
        ON certification_id = ct.id WHERE pkd.id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id_phieukiemdinh);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Fetching the list of inspectors (kiemdinhvien)
            $manv = $_SESSION['id_kiemdinhvien'];
            $stmt_kdv = mysqli_prepare($conn, "SELECT manv, hoten FROM kiemdinhvien WHERE manv = ?");
            mysqli_stmt_bind_param($stmt_kdv, "i", $manv);
            mysqli_stmt_execute($stmt_kdv);
            $kiemdinhvien_result = mysqli_stmt_get_result($stmt_kdv);

            $kiemdinhvien_list = [];
            while ($kiemdinhvien = mysqli_fetch_assoc($kiemdinhvien_result)) {
                $kiemdinhvien_list[] = $kiemdinhvien;
            }
            mysqli_stmt_close($stmt_kdv);
            
            $row['kiemdinhvien_list'] = $kiemdinhvien_list;
            echo json_encode($row);
        } else {
            echo json_encode(["error" => "Không tìm thấy phiếu kiểm định"]);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["error" => "Thiếu ID phiếu kiểm định"]);
    }

    mysqli_close($conn);
    ?>
