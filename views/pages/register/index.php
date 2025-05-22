
<div class="content" >
  <div class="row">
    <h1 class="text-center">Đăng ký tài khoản</h1>
  </div>
  <div class="row">
    <div class="col-md-2 col-sm-12"></div>
    <div class="wrapper col-md-8">
      <section class="form bg-success-subtle bg-gradient px-4 py-5 my-4 rounded dangky" style="--bs-bg-opacity: .2;">
        <!-- action gửi dữ liệu về controller/signup.php -->
         <!-- Không dùng action do đã sử dụng js để gửi form bằng ajax -->
        <form method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="error-text alert alert-danger" style="display:none;" role="alert"></div>
          <div class="row my-2">
            <div class="form-group col-6">
              <label for="" class="">Họ đệm</label>
              <input class="form-control" type="text" name="fname" placeholder="Nhập họ đệm" required>
            </div>
            <div class="form-group col-6">
              <label for="">Tên</label>
              <input class="form-control" type="text" name="lname" placeholder="Nhập tên" required>
            </div>
          </div>
          <div class="form-group  my-2">
            <label for="">Số điện thoại</label>
            <input class="form-control" type="text" name="sdt" placeholder="Nhập số điện thoại" required>
          </div>
          <div class="form-group  my-2">
            <label for="">Mật khẩu</label>
            <div class="input-group">
              <input class="form-control" type="password" name="password" placeholder="Nhập mật khẩu" required aria-describedby="basic-eye">
              <span class="input-group-text" id="basic-eye"><i class="fas fa-eye"></i></span>

            </div>
          </div>
          <div class="form-group  my-2">
            <label for="">Ảnh đại diện</label>
            <input class="form-control" type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
          </div>
          <!-- Người dùng sẽ chọn loại tài khoản là nông dân hay là khách hàng -->
           <!-- Nếu chọn nông dân sẽ hiển thị thêm form điền thông tin nông trại -->
          <div class="form-group my-2">
            <label for="role">Tài khoản</label>
            <select class="form-control" name="role" id="role-select" required>
              <option value="" disabled selected>Chọn loại tài khoản</option>
              <option value="4">Khách hàng</option>
              <option value="2">Nông dân</option>
            </select>
          </div>

          <div id="farmer-fields" style="display: none;">
            <div class="form-group my-2">
              <label for="farm_name">Tên nông trại</label>
              <input class="form-control" type="text" name="farm_name" id="farm_name" placeholder="Nhập tên nông trại">
            </div>
            <label class="mt-4">Nhập địa chỉ nông trại hoặc chọn trên map</label>
            <div class="d-flex flex-column align-items-center">
                <button type="button" class="btn btn-primary mb-3" onclick="getCurrentLocation()">Vị trí của bạn</button>
                <div id="map" class="w-75" style="height: 40vh;"></div>
            </div>

            <div class="form-group my-2">
                <label for="address">Địa chỉ</label>
                <input class="form-control" type="text" name="farm_address" id="farm_address" required placeholder="Nhập địa chỉ nông trại">
            </div>
            <!-- Tọa độ x y của điểm trên bản đồ -->
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
          </div>
          
          <div class="form-group  my-5 text-center">
            <input class="btn btn-outline-primary" type="submit" name="submit" value="Đăng ký tài khoản">
          </div>
          
        </form>
        <div class="link my-2"> Bạn đã có tài khoản?
          <a href="index.php?page=login">Đăng nhập ngay</a>
        </div>
      </section>
      
    </div>
    <div class="col-md-2"></div>
  </div>
</div>

<script src="assets/js/map/map.js"></script>
<script type="text/javascript" src="assets/js/login/pass-show-hide.js"></script>
<script type="text/javascript" src="assets/js/register/dangky.js"></script>