<?php
    session_start();
    include_once("../../models/config.php");
    require 'block.php';

    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập."]);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id   = $_POST['product_id']   ?? '';
        $farm_id      = $_POST['farm_id']      ?? '';
        $lot_number   = $_POST['lot_number']   ?? '';
        $freshness    = $_POST['freshness']    ?? '';
        $checker      = $_POST['checker']      ?? '';
        $harvest_date = $_POST['harvest_date'] ?? date('Y-m-d H:i:s');
        $address      = $_POST['address']      ?? '';

        if (!$product_id || !$farm_id || !$lot_number || !$freshness || !$checker || !$address) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin."]);
            exit();
        }

        // Lấy tên sản phẩm
        $stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($product_name);
        $stmt->fetch();
        $stmt->close();

        if (!$product_name) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Sản phẩm không tồn tại."]);
            exit();
        }

        // Lấy tên nông trại
        $stmt = $conn->prepare("SELECT tennongtrai FROM nongtrai WHERE id_nongtrai = ?");
        $stmt->bind_param("i", $farm_id);
        $stmt->execute();
        $stmt->bind_result($garden_name);
        $stmt->fetch();
        $stmt->close();

        if (!$garden_name) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Nông trại không tồn tại."]);
            exit();
        }

        // Định dạng ngày
        $harvest_date = date('Y-m-d H:i:s', strtotime($harvest_date));

        // Lấy block cuối cùng
        $stmt = $conn->prepare("SELECT * FROM blocks WHERE product_id = ? ORDER BY index_block DESC LIMIT 1");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $lastBlock = $result->fetch_assoc();

        $index        = $lastBlock ? $lastBlock['index_block'] + 1 : 0;
        $previousHash = $lastBlock ? $lastBlock['hash'] : "0";
        $timestamp    = $harvest_date;
        $stage        = "Thu hoạch";

        $data = json_encode([
            "product_id"   => $product_id,
            "product_name" => $product_name,
            "garden_name"  => $garden_name,
            "lot_number"   => $lot_number,
            "freshness"    => $freshness,
            "harvest_date" => $harvest_date,
            "checker"      => $checker,
            "address"      => $address,
        ], JSON_UNESCAPED_UNICODE);

        $block = new Block($index, $timestamp, $stage, $data, $previousHash);
        $block->mineBlock();

        // Lưu vào DB
        $insertStmt = $conn->prepare("INSERT INTO blocks (product_id,farm_id, index_block, timestamp, stage, data, previous_hash, hash, nonce)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param(
            "iiisssssi",
            $product_id,
            $farm_id,
            $block->index,
            $block->timestamp,
            $block->stage,
            $block->data,
            $block->previousHash,
            $block->hash,
            $block->nonce
        );

        if ($insertStmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Thêm block mới thành công!"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Lỗi lưu block: " . $conn->error]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ."]);
    }
?>
