<?php
    include_once 'models/config.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<p>Giỏ hàng trống.</p>";
    exit;
}

$product_ids = implode(",", array_keys($cart));
$sql = "SELECT * FROM products WHERE id IN ($product_ids)";
$result = mysqli_query($conn, $sql);
$total = 0;
?>

<h2>Giỏ hàng</h2>
<table>
    <tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Tổng</th></tr>
    <?php while ($row = mysqli_fetch_assoc($result)): 
        $quantity = $cart[$row['id']]['quantity'];
        $subtotal = $row['price'] * $quantity;
        $total += $subtotal;
    ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= number_format($row['price']) ?>đ</td>
            <td><?= $quantity ?></td>
            <td><?= number_format($subtotal) ?>đ</td>
        </tr>
    <?php endwhile; ?>
</table>

<h3>Tổng tiền: <?= number_format($total) ?>đ</h3>
<a href="checkout.php" class="btn btn-success">Thanh toán</a>
