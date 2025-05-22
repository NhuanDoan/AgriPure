<?php
    if (!isset($_SESSION['unique_id'])) {
        header("Location: index.php?page=login");
        exit();
    }
?>
<div class="content container">
    <section class="details-area border border-info p-4">
        <h2 class="text-center">Lập Phiếu Kiểm Định</h2>
        <form id="lapphieukiemdinhForm">
            <input type="hidden" name="id_phieukiemdinh" id="id_phieukiemdinh" value="<?php echo $_GET['id_phieukiemdinh'] ?? ''; ?>">

            <div class="mb-3">
                <label class="form-label">Họ tên:</label>
                <input type="text" id="fullname" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại:</label>
                <input type="text" id="phone" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Tên nông trại:</label>
                <input type="text" id="farmname" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày kiểm định:</label>
                <input type="date" id="date" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Loại chứng nhận kiểm định:</label>
                <input type="text" id="chungnhan" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Địa chỉ:</label>
                <input type="text" id="address" class="form-control" readonly>
            </div>
            <div id="criteria-list">
                <!-- JavaScript sẽ load vào đây -->
            </div>
            <script src="assets/js/lapphieukiemdinh/dgtieuchi.js"></script>
            <div class="mb-3">
                <label class="form-label">Bình luận:</label>
                <input type="text" id="binhluan" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Đánh giá:</label>
                <input type="text" id="danhgia" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Kiểm định viên:</label>
                <select id="id_kdv" name="id_kdv"  class="form-select"></select>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-outline-primary" id="save">Lưu</button>
                <a href="index.php?page=chitietphieukiemdinh&id_phieukiemdinh=<?php echo $_GET['id_phieukiemdinh'] ?>" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </section>
</div>
<script src="assets/js/lapphieukiemdinh/lapphieukiemdinh.js"></script>
