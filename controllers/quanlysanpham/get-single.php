<?php
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    // Lấy tham số id từ query string
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        echo json_encode(null); // hoặc bạn có thể trả về lỗi rõ hơn
        exit();
    }

    // Chuẩn bị câu truy vấn lấy chi tiết sản phẩm theo id
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    // Lấy kết quả
    $product = $res->fetch_assoc();

    if (!$product) {
        // Không tìm thấy sản phẩm, trả về null hoặc lỗi
        echo json_encode(null);
    } else {
        echo json_encode($product);
    }

    // Giải phóng tài nguyên
    $stmt->close();
    // $conn->close(); // nếu cần đóng kết nối

    exit();
?>
