<?php
    if (!isset($_SESSION['unique_id'])) {
        header("Location: index.php?page=login");
        exit();
    }
?>


<div class="content">
    <div class="container mt-4">
        <h2 class="text-center mb-4">ĐĂNG KÝ KIỂM ĐỊNH NÔNG SẢN</h2>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10">
                <section class="form bg-success bg-gradient px-4 py-5 my-4 rounded" style="--bs-bg-opacity: .2;">
                    <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="error-text alert alert-danger" style="display:none;"></div>
                        
                        <!-- Số điện thoại (editable) -->
                        <div class="form-group my-3">
                            <label for="phone" class="form-label">
                                Số điện thoại
                                <small class="text-muted fst-italic">(Nhập số điện thoại của nông trại cần đăng ký)</small>
                            </label>
                            <input class="form-control" type="tel" name="phone" id="phone" placeholder="Nhập số điện thoại" required pattern="[0-9]{10,15}" title="Vui lòng nhập số điện thoại hợp lệ">
                            <div class="form-text mt-1">
                                Nếu chưa có tài khoản nông trại, vui lòng <a href="index.php?page=register" target="_blank">đăng ký tại đây</a> và chọn vai trò <strong>nông trại</strong>.
                            </div>
                        </div>

                        <!-- Họ và tên (readonly) -->
                        <div class="form-group my-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input class="form-control" type="text" name="fullname" id="fullname" placeholder="Họ và tên" readonly>
                        </div>

                        <!-- Tên nông trại (readonly) -->
                        <div class="form-group my-3">
                            <label for="farmname" class="form-label">Tên nông trại</label>
                            <input class="form-control" type="text" name="farmname" id="farmname" placeholder="Tên nông trại" readonly>
                        </div>

                        <!-- Ngày kiểm định (readonly) -->
                        <div class="form-group my-3">
                            <label for="date" class="form-label">Ngày kiểm định</label>
                            <input class="form-control" type="date" name="date" id="date" value="<?= date('Y-m-d') ?>">
                        </div>

                        <!-- Chứng nhận kiểm định (disabled) -->
                        <div class="form-group my-3">
                            <label for="certification" class="form-label">Chứng nhận kiểm định</label>
                            <select class="form-select" name="certification" id="certification">
                                <option value="" selected disabled>Chọn loại chứng nhận</option>
                            </select>
                        </div>

                        <!-- Hiển thị tiêu chí khi chọn chứng chỉ -->
                        <div id="criteria-list" class="my-3"></div>

                        <h5 class="mt-4">Nhập địa chỉ nông trại hoặc chọn trên map</h5>

                        <div class="d-flex flex-column align-items-center mb-3">
                            <button type="button" class="btn btn-primary mb-3" onclick="getCurrentLocation()">Vị trí của bạn</button>
                            <div id="map" class="w-75" style="height: 40vh;"></div>
                        </div>

                        <!-- Địa chỉ (editable) -->
                        <div class="form-group my-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input class="form-control" type="text" name="address" id="address" placeholder="Nhập địa chỉ" required>
                        </div>
                        
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="form-group my-4 text-center">
                            <input class="btn btn-outline-primary px-4 py-2" type="submit" name="submit" value="Gửi đăng ký">
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>


<script src="assets/js/dangkykiemdinh/dangkykiemdinh.js"></script>
<script src="assets/js/dangkykiemdinh/map.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', initMap);
</script>
