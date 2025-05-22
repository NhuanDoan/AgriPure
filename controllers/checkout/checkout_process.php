<?php
    session_start();
    include_once('../../models/config.php');

    header('Content-Type: application/json');

    if (!isset($_SESSION['unique_id'])) {
        echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
        exit();
    }

    $input = json_decode(file_get_contents('php://input'), true);

    $user_id = $_SESSION['user_id'];
    $fullname = trim($input['fullname'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $address = trim($input['address'] ?? '');
    $payment_method = $input['payment_method'] ?? 'cod';
    $cart = $input['cart'] ?? [];

    if (empty($fullname) || empty($phone) || empty($address) || !in_array($payment_method, ['cod', 'vnpay'])) {
        echo json_encode(["success" => false, "message" => "Dữ liệu gửi lên không hợp lệ"]);
        exit();
    }

    if (count($cart) === 0) {
        echo json_encode(["success" => false, "message" => "Giỏ hàng không được để trống"]);
        exit();
    }

    $total_price = 0;
    $updated_cart = [];

    foreach ($cart as $item) {
        $product_id = (int)$item['id'];
        $quantity = (int)$item['quantity'];

        $result = $conn->query("SELECT price FROM products WHERE id = $product_id LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $price = (float)$row['price'];
            $total_price += $price * $quantity;

            $updated_cart[] = [
                'id' => $product_id,
                'quantity' => $quantity,
                'price' => $price
            ];
        } else {
            echo json_encode(["success" => false, "message" => "Sản phẩm không tồn tại"]);
            exit();
        }
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, address, phone, total_price, payment_method, payment_status, status, created_at)
                                VALUES (?, ?, ?, ?, ?, ?, 'unpaid', 'pending', NOW())");
        $stmt->bind_param("isssds", $user_id, $fullname, $address, $phone, $total_price, $payment_method);

        if (!$stmt->execute()) {
            throw new Exception("Không thể tạo đơn hàng");
        }

        $order_id = $stmt->insert_id;

        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($updated_cart as $item) {
            $stmt_item->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            if (!$stmt_item->execute()) {
                throw new Exception("Không thể lưu chi tiết đơn hàng");
            }
        }

        $conn->commit();

        if ($payment_method === 'vnpay') {
            echo json_encode(["success" => true, "redirect" => "vnpay_php/vnpay_pay.php?order_id=$order_id&total_price=$total_price"]);
        } else {
            echo json_encode(["success" => true, "order_id" => $order_id]);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
?>