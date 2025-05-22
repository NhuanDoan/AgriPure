<?php
    // filename: vnpay_response.php
    require_once("./config.php");

    // Hàm ghi log đơn giản
    function write_log($message) {
        $logFile = __DIR__ . '/vnpay_response.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
    }

    $valid = false;
    $orderUpdated = false;
    $errorMsg = '';
    $resultMsg = '';
    $data = []; // Dữ liệu để hiển thị

    if (!empty($_GET)) {
        $vnp_SecureHash = isset($_GET['vnp_SecureHash']) ? $_GET['vnp_SecureHash'] : '';

        $inputData = [];
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) === "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        // Tạo chuỗi dữ liệu để hash
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        // Tạo chữ ký
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            $valid = true;

            // Lấy dữ liệu an toàn
            $data['order_id'] = isset($_GET['vnp_TxnRef']) ? htmlspecialchars($_GET['vnp_TxnRef']) : '';
            $data['amount'] = isset($_GET['vnp_Amount']) ? (float)$_GET['vnp_Amount'] / 100 : 0;
            $data['order_info'] = isset($_GET['vnp_OrderInfo']) ? htmlspecialchars($_GET['vnp_OrderInfo']) : '';
            $data['response_code'] = isset($_GET['vnp_ResponseCode']) ? htmlspecialchars($_GET['vnp_ResponseCode']) : '';
            $data['transaction_no'] = isset($_GET['vnp_TransactionNo']) ? htmlspecialchars($_GET['vnp_TransactionNo']) : '';
            $data['bank_code'] = isset($_GET['vnp_BankCode']) ? htmlspecialchars($_GET['vnp_BankCode']) : '';
            $data['pay_date'] = isset($_GET['vnp_PayDate']) ? htmlspecialchars($_GET['vnp_PayDate']) : '';

            if ($data['response_code'] === '00') {
                // Cập nhật trạng thái đơn hàng
                include_once('../models/config.php'); // Đảm bảo đường dẫn đúng
                $order_id = (int)$data['order_id'];
                $amount = $data['amount'];

                $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', status = 'pending' WHERE id = ? AND total_price = ?");
                $stmt->bind_param("id", $order_id, $amount);
                if ($stmt->execute()) {
                    $orderUpdated = true;
                    $resultMsg = "Giao dịch thành công! Đã cập nhật trạng thái đơn hàng.";
                    write_log("Order #$order_id updated successfully.");
                } else {
                    $resultMsg = "Giao dịch thành công nhưng lỗi cập nhật đơn hàng.";
                    write_log("Order #$order_id update failed: " . $stmt->error);
                }
            } else {
                $resultMsg = "Giao dịch không thành công! Mã phản hồi: " . $data['response_code'];
                write_log("Transaction failed. Response code: " . $data['response_code']);
            }
        } else {
            $errorMsg = "Chữ ký không hợp lệ!";
            write_log("Invalid signature detected.");
        }
    } else {
        $errorMsg = "Không nhận được dữ liệu phản hồi!";
        write_log("No GET data received.");
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Phản hồi thanh toán - VNPAY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/bootstrap.min.css" rel="stylesheet"/>
    <script src="assets/jquery-1.11.3.min.js"></script>
    <meta http-equiv="refresh" content="15;url=../index.php?page=product" />
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 40px;
        }
        .panel {
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .panel-heading {
            background-color: #0275d8 !important;
            color: white !important;
            border-radius: 10px 10px 0 0;
        }
        .result-success {
            color: green;
            font-weight: bold;
        }
        .result-fail {
            color: red;
            font-weight: bold;
        }
        .result-pending {
            color: orange;
            font-weight: bold;
        }
        label {
            font-weight: 600;
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
    <body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h3 class="panel-title">Kết quả giao dịch VNPAY</h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="result-fail"><?php echo $errorMsg; ?></p>
                <?php else: ?>
                    <div class="form-group">
                        <label>Mã đơn hàng:</label> <?php echo $data['order_id']; ?>
                    </div>
                    <div class="form-group">
                        <label>Số tiền:</label> <?php echo number_format($data['amount'], 0, ',', '.') . ' VNĐ'; ?>
                    </div>
                    <div class="form-group">
                        <label>Nội dung thanh toán:</label> <?php echo $data['order_info']; ?>
                    </div>
                    <div class="form-group">
                        <label>Mã phản hồi:</label> <?php echo $data['response_code']; ?>
                    </div>
                    <div class="form-group">
                        <label>Mã GD tại VNPAY:</label> <?php echo $data['transaction_no']; ?>
                    </div>
                    <div class="form-group">
                        <label>Ngân hàng:</label> <?php echo $data['bank_code']; ?>
                    </div>
                    <div class="form-group">
                        <label>Thời gian thanh toán:</label> <?php echo $data['pay_date']; ?>
                    </div>
                    <div class="form-group">
                        <label>Kết quả:</label><br>
                        <?php if ($valid && $data['response_code'] === '00'): ?>
                            <span class="result-success"><?php echo $resultMsg; ?></span>
                        <?php elseif ($valid): ?>
                            <span class="result-fail"><?php echo $resultMsg; ?></span>
                        <?php else: ?>
                            <span class="result-fail"><?php echo $errorMsg; ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="form-group text-center">
                    <a href="../index.php?page=product" class="btn btn-primary btn-lg">Quay lại trang sản phẩm</a>
                </div>
                <p class="text-center text-muted">
                    <small>Trang sẽ tự động chuyển về trang sản phẩm sau <span id="countdown">15</span> giây...</small>
                </p>
            </div>
        </div>
        <footer class="text-center text-muted" style="margin-top: 20px;">
            <p>&copy; VNPAY <?php echo date("Y") ?></p>
        </footer>
    </div>

    <script>
        let seconds = 15;
        const countdownEl = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
            clearInterval(interval);
            // Chuyển hướng về trang sản phẩm
            window.location.href = '../index.php?page=product';
            }
        }, 1000);
    </script>


    </body>
</html>
