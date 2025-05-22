<div class="content my-4 product">
    <h2 class="text-center my-5">Danh sách sản phẩm</h2>

   <!-- Bộ lọc -->
    <div class="mb-3 row align-items-center">
        <div class="col-md-3 my-2">
            <select class="form-select" id="categorySelect">
                <option value="">Tất cả loại</option>
            </select>
        </div>
        <div class="col-md-8 my-2">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="Tìm sản phẩm...">
                <button class="btn btn-success" type="button" onclick="loadSanPham()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-1">
            <!-- Button trigger -->
            <a class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>

            <!-- Modal -->
            <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cartModalLabel">Giỏ hàng của bạn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body" id="cartItemsContainer">
                        <!-- JS sẽ load nội dung giỏ hàng ở đây -->
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <a href="index.php?page=checkout" class="btn btn-success">Thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Danh sách sản phẩm -->
    <div class="row" id="productList"></div>
    <div class="row mt-4">
        <div id="pagination" class="d-flex justify-content-center flex-wrap gap-1"></div>
    </div>
</div>

<script src="assets/js/product/product.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        loadCategories();
        loadSanPham();
    });
</script>
