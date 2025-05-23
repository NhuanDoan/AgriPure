<?php
    require_once("./config.php"); 
    $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
    $total_price = isset($_GET['total_price']) ? (float)$_GET['total_price'] : 0;

    if ($order_id <= 0 || $total_price <= 0) {
        die('Thông tin đơn hàng không hợp lệ');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tạo mới đơn hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            padding-top: 30px;
            background-color: #f8f9fa;
        }
        .panel {
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .panel-heading {
            background-color: #0275d8 !important;
            color: #fff !important;
            border-radius: 10px 10px 0 0;
        }
        .btn-main {
            background-color: #0275d8;
            color: #fff;
        }
        .btn-main:hover {
            background-color: #025aa5;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading text-center">
            <h3 class="panel-title">Tạo mới đơn hàng</h3>
        </div>
        <div class="panel-body">
            <form action="vnpay_create_payment.php" id="frmCreateOrder" method="post">        
                <div class="form-group">
                    <label for="order_id">Mã đơn hàng</label>
                    <input class="form-control" id="order_id" name="order_id" type="number" value="<?php echo htmlspecialchars($order_id); ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="amount">Số tiền (VNĐ)</label>
                    <input class="form-control" id="amount" name="amount" type="number" value="<?php echo htmlspecialchars($total_price); ?>" readonly />
                </div>
                <div class="form-group">
                    <h4>Phương thức thanh toán</h4>
                    <div class="radio">
                        <label><input type="radio" checked name="bankCode" value=""> Cổng thanh toán VNPAYQR</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="bankCode" value="VNPAYQR"> Ứng dụng hỗ trợ VNPAYQR</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="bankCode" value="VNBANK"> Thẻ ATM/Tài khoản nội địa</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="bankCode" value="INTCARD"> Thẻ quốc tế</label>
                    </div>
                </div>
                <div class="form-group">
                    <h4>Ngôn ngữ giao diện</h4>
                    <div class="radio">
                        <label><input type="radio" checked name="language" value="vn"> Tiếng Việt</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="language" value="en"> English</label>
                    </div>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-main btn-lg">Thanh toán</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="text-center">
        <p class="text-muted">&copy; VNPAY <?php echo date("Y"); ?></p>
    </footer>
</div>
</body>
</html>
