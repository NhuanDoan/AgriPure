<?php
    include_once("../../models/config.php");
    include_once("../../qr-demo/phpqrcode/qrlib.php");

    session_start();

    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(["error" => "Bạn chưa đăng nhập!"]);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $limit = 10;  // số bản ghi mỗi trang, bạn chỉnh theo ý muốn
    $page = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    // Đếm tổng số bản ghi
    $count_sql = "SELECT COUNT(*) AS total FROM blocks b
                JOIN products p ON b.product_id = p.id
                WHERE p.user_id = ?";
    $stmt_count = $conn->prepare($count_sql);
    $stmt_count->bind_param("i", $user_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    $row_count = $result_count->fetch_assoc();
    $totalRecords = (int)$row_count['total'];
    $totalPages = ceil($totalRecords / $limit);

    // Lấy dữ liệu theo phân trang
    $sql = "SELECT b.id, p.name AS product_name, n.tennongtrai AS farm_name, b.data, b.timestamp
            FROM blocks b
            JOIN products p ON b.product_id = p.id
            JOIN nongtrai n ON b.farm_id = n.id_nongtrai
            WHERE p.user_id = ?
            ORDER BY b.timestamp DESC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $index = $offset + 1;
    $html = "";

    while ($block = $result->fetch_assoc()) {
        $data = json_decode($block['data'], true);
        $block = array_merge($block, $data ?: []);

        $link_block = 'http://localhost/AgriPure_demo/index.php?page=view-blockchain&block_id=' . $block['id'];

        ob_start();
        QRcode::png($link_block, null, QR_ECLEVEL_L, 3);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();

        $html .= "<tr>
            <td>{$index}</td>
            <td>" . htmlspecialchars($block['product_name']) . "</td>
            <td>" . htmlspecialchars($block['lot_number'] ?? '') . "</td>
            <td>" . htmlspecialchars($block['freshness'] ?? '') . "</td>
            <td>" . htmlspecialchars($block['checker'] ?? '') . "</td>
            <td>" . (!empty($block['harvest_date']) ? date('d/m/Y H:i', strtotime($block['harvest_date'])) : '') . "</td>
            <td>" . htmlspecialchars($block['address'] ?? '') . "</td>
            <td>" . (!empty($block['timestamp']) ? date('d/m/Y H:i:s', strtotime($block['timestamp'])) : '') . "</td>
            <td><img src='data:image/png;base64,{$imageString}' alt='QR Code' /></td>
        </tr>";
        $index++;
    }

    // Trả về JSON dữ liệu
    echo json_encode([
        'html' => $html,
        'totalPages' => $totalPages
    ]);
    exit();
?>
