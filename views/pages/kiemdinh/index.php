<?php 
    if(!isset($_SESSION['unique_id']))
    {
       header("Location: index.php?page=login");
    }
?>


    <div class="content" >
        <h2 class="text-center">Danh sách phiếu kiểm định</h2>
        <div class="row chat">
            <div class="search">
                <span class="text">Chọn phiếu kiểm định cần xem</span>
                <div class="input-group my-2">
                    <input class="form-control" type="text" name="searchTerm" placeholder="Nhập tên nông dân cần kiểm định nông trại" aria-describedby="basic-search">
                    <button class="input-group-text" id="basic-search"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="users-list">

                </div>
                <script type="text/javascript" src="assets/js/xemphieukiemdinh/xemphieukiemdinh.js"></script>
            </div>
        </div>
    </div>