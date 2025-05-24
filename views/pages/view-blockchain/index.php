<?php
    include_once("models/config.php");

    $block_id = $_GET['block_id'] ?? 0;
    $block_id = (int)$block_id;

    $sql = "SELECT b.id, p.name AS product_name, b.data, b.timestamp
            FROM blocks b
            JOIN products p ON b.product_id = p.id
            WHERE b.id = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Lỗi chuẩn bị câu SQL: " . $conn->error);
    }

    $stmt->bind_param("i", $block_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $block = $result->fetch_assoc();

    $stmt->close();

    if ($block) {
        $data = json_decode($block['data'], true);
        $block = array_merge($block, $data ?: []);
    }
?>


<div class="container mt-4">
    <h2 class="text-center mb-4">Chi tiết Block Nông Sản</h2>

    <?php if (!$block): ?>
        <p class="text-center text-danger">Không tìm thấy block với ID này.</p>
    <?php else: ?>
        <div class="card mx-auto" style="max-width: 700px;">
            <div class="card-body">
                <h5 class="card-title mb-4">Block ID: <?= htmlspecialchars($block['id']) ?></h5>

                <p><strong>Sản phẩm:</strong> <?= htmlspecialchars($block['product_name']) ?></p>
                <p><strong>Tên nông trại:</strong> <?= htmlspecialchars($block['garden_name'] ?? '') ?></p>
                <p><strong>Lô hàng:</strong> <?= htmlspecialchars($block['lot_number'] ?? '') ?></p>
                <p><strong>Độ tươi:</strong> <?= htmlspecialchars($block['freshness'] ?? '') ?></p>
                <p><strong>Người kiểm:</strong> <?= htmlspecialchars($block['checker'] ?? '') ?></p>
                <p><strong>Ngày thu hoạch:</strong> <?= !empty($block['harvest_date']) ? date('d/m/Y H:i', strtotime($block['harvest_date'])) : '' ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($block['address'] ?? '') ?></p>
                <p><strong>Thời gian ghi block:</strong> <?= !empty($block['timestamp']) ? date('d/m/Y H:i:s', strtotime($block['timestamp'])) : '' ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>
