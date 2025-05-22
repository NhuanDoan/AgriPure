<?php 
    if(isset($_SESSION['unique_id']))
    {
        header("Location: index.php");
    }
?>
<div class="content" >
  <div class="row">
    <h1 class="text-center">Đăng nhập tài khoản</h1>
  </div>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="wrapper col-md-8 col-sm-12">
      <section class="form bg-success-subtle bg-gradient px-4 py-5 my-4 rounded dangnhap" style="--bs-bg-opacity: .2;">
        <form action="#" method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="error-text alert alert-danger" style="display:none;" role="alert"></div>
          <div class="form-group  my-2">
            <label for="">Số điện thoại</label>
            <input class="form-control" type="text" name="sdt" placeholder="Nhập số điện thoại" required>
          </div>
          <div class="form-group  my-2">
            <label for="">Mật khẩu</label>
            <div class="input-group">
                <input class="form-control" type="password" name="password" placeholder="Nhập mật khẩu" required required aria-describedby="basic-eye">
                <span class="input-group-text" id="basic-eye"><i class="fas fa-eye"></i></span>
            </div>
            
          </div>
          <div class="form-group  my-5 text-center">
            <input class="btn btn-outline-danger" type="submit" name="submit" value="Đăng nhập">
          </div>
          <div class="link my-2"> Bạn chưa có tài khoản?
          <a href="index.php?page=register">Đăng ký ngay</a>
        </div>
        </form>
      </section>
    </div>
    <div class="col-md-2"></div>
  </div>
</div>

<script type="text/javascript" src="assets/js/login/pass-show-hide.js"></script>
<script type="text/javascript" src="assets/js/login/dangnhap.js"></script>