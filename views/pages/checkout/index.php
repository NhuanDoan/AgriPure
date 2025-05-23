<?php
    include_once('models/config.php'); 

    if (!isset($_SESSION['unique_id'])) {
        header("Location: index.php?page=login");
        exit();
    }

    $userid = $_SESSION['unique_id'];

    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id='{$userid}'");

    $user = null;
    if ($sql && mysqli_num_rows($sql) > 0) {
        $user = mysqli_fetch_assoc($sql);
    }
?>

<div class="container py-4">
    <h2 class="mb-4 text-center text-primary fw-bold">Thanh toán</h2>

    <div class="row gx-5">
        <!-- Giỏ hàng -->
        <div class="col-md-5 mb-4">
            <div class="border rounded p-3 bg-light h-100">
                <h4 class="mb-3 fw-semibold">Giỏ hàng của bạn</h4>
                <div id="cartPreview" style="min-height: 400px;"></div>
            </div>
        </div>

        <!-- Thông tin thanh toán -->
        <div class="col-md-7">
            <form id="checkoutForm" onsubmit="return prepareCheckout()" class="needs-validation" novalidate>
                <div class="border rounded p-4 bg-white shadow-sm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ tên</label>
                        <input type="text" name="fullname" class="form-control" required
                            value="<?= htmlspecialchars(trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''))) ?>">
                        <div class="invalid-feedback">Vui lòng nhập họ tên.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="tel" name="phone" class="form-control" required pattern="[0-9]{9,15}"
                            value="<?= htmlspecialchars($user['sdt'] ?? '') ?>">
                        <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ.</div>
                    </div>
                    <h5 class="mt-4">Nhập địa chỉ giao hàng hoặc chọn trên map</h5>
                    <div class="d-flex flex-column align-items-center">
                        <button type="button" class="btn btn-primary mb-3" onclick="getCurrentLocation()">Vị trí của bạn</button>
                        <div id="map" class="w-75" style="height: 40vh;"></div>
                    </div>
                   
                    <div class="form-group my-2">
                        <label for="address">Địa chỉ giao hàng</label>
                        <input class="form-control" type="text" name="address" id="address" required>
                    </div>
                    
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <input type="hidden" name="cart" id="cartData">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hình thức thanh toán</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="" hidden>Chọn hình thức thanh toán</option>
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="vnpay">VNPay</option>
                        </select>
                        <div class="invalid-feedback">Vui lòng chọn hình thức thanh toán.</div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-semibold">Đặt hàng</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function prepareCheckout() {
        const cart = sessionStorage.getItem('cart') || '[]';
        document.getElementById("cartData").value = cart;
        return true;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const cart = JSON.parse(sessionStorage.getItem("cart") || "[]");
        const list = document.getElementById("cartPreview");
        let total = 0;

        if (cart.length === 0) {
            list.innerHTML = "<p>Giỏ hàng trống.</p>";
            return;
        }

        let html = "<ul class='list-group'>";
        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            html += `<li class='list-group-item d-flex justify-content-between align-items-center'>
                <div>${item.name} x ${item.quantity}</div>
                <div>${subtotal.toLocaleString()}đ</div>
            </li>`;
        });
        html += `<li class='list-group-item d-flex justify-content-between fw-bold bg-secondary text-white'>
            <div>Tổng:</div><div>${total.toLocaleString()}đ</div>
        </li></ul>`;
        list.innerHTML = html;

        // Bootstrap form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    });

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("checkoutForm");

        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Ngăn submit truyền thống

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            // Lấy dữ liệu từ form
            const formData = new FormData(form);
            const cart = JSON.parse(sessionStorage.getItem('cart') || '[]');

            // Gộp dữ liệu thành object
            const data = {
                fullname: formData.get("fullname"),
                phone: formData.get("phone"),
                address: formData.get("address"),
                latitude: formData.get("latitude"),
                longitude: formData.get("longitude"),
                payment_method: formData.get("payment_method"),
                cart: cart
            };

            // Gửi qua PHP
            fetch("controllers/checkout/checkout_process.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    sessionStorage.removeItem("cart");
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        alert("Đặt hàng thành công!");
                        window.location.href = "index.php?page=order&order_id=" + response.order_id;
                    }
                } else {
                    alert("Lỗi: " + response.message);
                }
            })
            .catch(error => {
                console.error("Lỗi khi gửi đơn hàng:", error);
                alert("Có lỗi xảy ra khi gửi đơn hàng.");
            });
        });
    });
</script>

<script src="assets/js/dangkykiemdinh/map.js"></script>
