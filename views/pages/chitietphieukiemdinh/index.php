<?php

    if (!isset($_SESSION['unique_id'])) {
        header("Location: index.php?page=login");
        exit();
    }

    if (!isset($_GET['id_phieukiemdinh']) || empty($_GET['id_phieukiemdinh'])) {
        header("Location: index.php?page=xemphieukiemdinh");
        exit();
    }
    $id_phieukiemdinh = $_GET['id_phieukiemdinh'];
?>

<div class="content">
    <section class="details-area border border-success-subtle p-4">
        <h2 class="text-center">Chi Tiết Phiếu Kiểm Định</h2>
        <table class="table table-bordered mt-3" id="phieuKiemDinhTable"></table>
        <div class="text-center">
            <?php 
                if($_SESSION['role'] == 1)
                {
                    echo '<button class="btn btn-outline-primary" id="btnLapphieu">Lập Phiếu Kiểm Định</button>';
                    echo '<a href="index.php?page=kiemdinh" class="btn btn-outline-secondary m-1">Quay Lại</a>';
                } else {
                    echo '<a href="index.php?page=xemphieukiemdinh" class="btn btn-outline-secondary m-1">Quay Lại</a>';
                }
            ?>
            
            <?php 
                 if($_SESSION['role'] == 1)
                 {
                     echo '<a href="index.php?page=lapchungchi&id_phieukiemdinh='.$id_phieukiemdinh.'" class="btn btn-outline-primary m-1">Lập chứng chỉ</a>';
                     echo '<a href="index.php?page=xemchungchi&id_phieukiemdinh='.$id_phieukiemdinh.'" class="btn btn-outline-primary m-1">Xem chứng chỉ</a>';
                 } else
                 {
                    echo '<a href="index.php?page=xemchungchi&id_phieukiemdinh='.$id_phieukiemdinh.'" class="btn btn-outline-primary m1-1">Xem chứng chỉ</a>';
                 }
            ?>
            
        </div>
    </section>
</div>

<script src="assets/js/chitietphieukiemdinh/chitietphieukiemdinh.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        loadChiTietPhieuKiemDinh(<?php echo $id_phieukiemdinh; ?>);
    });
    
    document.getElementById("btnLapphieu").addEventListener("click", function() {
        window.location.href = "index.php?page=lapphieukiemdinh&id_phieukiemdinh=<?php echo $id_phieukiemdinh; ?>";
    });
</script>