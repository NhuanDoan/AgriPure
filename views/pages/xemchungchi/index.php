<?php 
    if(!isset($_SESSION['id_nongtrai']))
    {
        $id_nongtrai = -1;
    }

?>


<div class="content my-3">
    <h2 class="text-center my-4">Danh sách chứng chỉ đã cấp</h2>
    <table border="1"  class="table text-center table-success">
        <thead >
            <tr>
                <th scope="col" class="text-success">STT</th>
                <th scope="col" class="text-success">Tên nông trại</th>
                <th scope="col" class="text-success">Số điện thoại</th>
                <th scope="col" class="text-success">Loại chứng chỉ</th>
                <th scope="col" class="text-success">Ngày cấp</th>
                <th scope="col" class="text-success">Ngày hết hạn</th>
                <th scope="col" class="text-success">Trạng thái</th>
            </tr>
        </thead>
        <tbody id="certList">
            <tr><td colspan="7">Đang tải dữ liệu...</td></tr>
        </tbody>
    </table>
    <div class="button text-center">
        <?php 
            if(isset($_GET['id_phieukiemdinh']))
            {
                echo '<a href="index.php?page=chitietphieukiemdinh&id_phieukiemdinh='.$_GET['id_phieukiemdinh'].'"
                class="btn btn-outline-secondary">Quay lại</a>';
            }

            if(isset($_GET['id_nongtrai']) || isset($_GET['id_kdv']) )
            {
                echo '<a href="index.php"
                class="btn btn-outline-secondary">Quay lại</a>';
            }
        ?>
    </div>
    
</div>

<script src="assets/js/xemchungchi/xemchungchi.js"></script>

<?php 
    if(isset($_GET['id_phieukiemdinh']))
    {
        echo '<script>
                loadCertificates("id_phieukiemdinh", '.$_GET['id_phieukiemdinh'].'); 
            </script>';
    }
    if(isset($_GET['id_kdv'])){
        echo '<script>
                loadCertificates("id_kdv", '.$_GET['id_kdv'].'); 
            </script>';
    }
    if(isset($_GET['id_nongtrai']) ){
        echo '<script>
                loadCertificates("id_nongtrai", '.$id_nongtrai.'); 
            </script>';
    }

?>
