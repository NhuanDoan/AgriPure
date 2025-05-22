<?php
    $id_phieukiemdinh = $_GET['id_phieukiemdinh'];
?>

<div class="content">
    <div class="row">
        <div class="search">
            <h2 class="text-center">Cấp chứng chỉ nông trại</h2>
            <div class="users-list">
                <form id="certForm">
                    <input type="hidden" name="id_phieukiemdinh" value="<?= $id_phieukiemdinh ?>">

                    <div class="mb-3">
                        <label>Tên nông trại:</label>
                        <input type="text" class="form-control" name="farmname" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Số điện thoại:</label>
                        <input type="text" class="form-control" name="phone" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Địa chỉ:</label>
                        <input type="text" class="form-control" name="address" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Tiêu chuẩn:</label>
                        <input type="text" class="form-control" name="certification_type_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Ngày cấp chứng chỉ:</label>
                        <input type="date" class="form-control" name="ngay_cap" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-success" onclick="submitCertificate()">Cấp chứng chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        loadPhieuKiemDinh(<?php echo $id_phieukiemdinh; ?>);
    });
    
</script>
<script src="assets/js/lapchungchi/lapchungchi.js"></script>
