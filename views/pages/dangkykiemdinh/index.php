
<div class="content">
    <div class="container mt-4">
        <h2 class="text-center">ĐĂNG KÝ KIỂM ĐỊNH NÔNG SẢN</h2>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-sm-12 col-md-10">
                <section class="form bg-success bg-gradient px-4 py-5 my-4 rounded" style="--bs-bg-opacity: .2;">
                    <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <div class="error-text alert alert-danger" style="display:none;"></div>
                        
                        <div class="form-group my-2">
                            <label for="phone">Số điện thoại</label>
                            <input class="form-control" type="text" name="phone" placeholder="Nhập số điện thoại" required>
                        </div>
                        

                        <div class="form-group my-2">
                            <label for="fullname">Họ và tên</label>
                            <input class="form-control" type="text" name="fullname" placeholder="Nhập họ và tên" required>
                        </div>

                        <div class="form-group my-2">
                            <label for="farmname">Tên nông trại</label>
                            <input class="form-control" type="text" name="farmname" placeholder="Nhập tên nông trại" required>
                        </div>
                        
                        
                        <div class="form-group my-2">
                            <label for="date">Ngày kiểm định</label>
                            <input class="form-control" type="date" name="date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="form-group my-2">
                            <label for="certification">Chứng nhận kiểm định</label>
                            <select class="form-control" name="certification" id="certification" onchange="loadCriteria()" required>
                                <option value="" disabled selected>Chọn loại chứng nhận</option>
                            </select>
                        </div>
                        
                        <!-- Hiển thị tiêu chí khi chọn chứng chỉ -->
                        <div id="criteria-list" class="my-3"></div>
                        
                        <h5 class="mt-4">Nhập địa chỉ nông trại hoặc chọn trên map</h5>
            
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" class="btn btn-primary mb-3" onclick="getCurrentLocation()">Vị trí của bạn</button>
                            <div id="map" class="w-75" style="height: 40vh;"></div>
                        </div>
            
                        <div class="form-group my-2">
                            <label for="address">Địa chỉ</label>
                            <input class="form-control" type="text" name="address" id="address" required>
                        </div>
                        
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="form-group my-4 text-center">
                            <input class="btn btn-outline-primary" type="submit" name="submit" value="Gửi đăng ký">
                        </div>
                    </form>
                </section>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>

<script src="assets/js/dangkykiemdinh/dangkykiemdinh.js"></script>
<script src="assets/js/dangkykiemdinh/map.js"></script>
