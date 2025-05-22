<?php
    session_start();
    header('Content-Type: application/json');
    include_once("../../models/config.php");

    $name = $_POST['name'] ?? '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $description = $_POST['description'] ?? '';
    $image_url = '';

    if (empty($name) || $price <= 0 || !$category_id) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đủ và đúng thông tin']);
        exit();
    }

    // Xử lý upload ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ định dạng ảnh JPG, PNG, GIF']);
            exit();
        }
        
        $targetDir = "../../assets/images/imgsanpham/";
        $filename = uniqid() . "-" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image_url = $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi upload ảnh']);
            exit();
        }
    }

    $sql = "INSERT INTO products (name, price, category_id, description, image_url, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdissi", $name, $price, $category_id, $description, $image_url, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    exit();
?>
