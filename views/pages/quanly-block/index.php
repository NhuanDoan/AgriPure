<div class="container mt-4">
    <h2 class="text-center mb-4">LỊCH SỬ BLOCKCHAIN NÔNG SẢN</h2>
    <div class="table-responsive text-center">
        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th>Lô hàng</th>
                    <th>Độ tươi</th>
                    <th>Người kiểm</th>
                    <th>Ngày thu hoạch</th>
                    <th>Địa chỉ</th>
                    <th>Thời gian ghi block</th>
                    <th>QR</th>
                </tr>
            </thead>
            <tbody id="block-list">
                <!-- Dữ liệu block sẽ được load bằng JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Thanh phân trang dạng nút -->
    <nav class="d-flex justify-content-center mt-4">
        <div id="pagination" class="btn-group" role="group"></div>
    </nav>
</div>

<script src="assets/js/quanly-blockchain/quanly-blockchain.js"></script>
