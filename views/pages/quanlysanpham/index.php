<?php
    if (!isset($_SESSION['unique_id']) || $_SESSION['role'] != 2) {
        header("Location: index.php");
        exit();
    }
?>
<div class="content my-4">
    <h2 class="text-center mb-4">Quản lý sản phẩm</h2>

    <div class="row mb-3">
        <div class="col-md-3 my-2">
            <select id="categoryFilter" class="form-select">
                <option value="">Tất cả loại</option>
            </select>
        </div>
        <div class="col-md-5 my-2">
            <div class="input-group">
                <input type="text" id="searchInput" class="form-control" placeholder="Tìm sản phẩm...">
                <button class="btn btn-outline-success" type="button" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-2 text-center my-2">
            <button class="btn btn-success" onclick="showAddModal()">+ Thêm sản phẩm</button>
        </div>
    </div>


    <div id="productTable" class="table-responsive text-center"></div>
    <div id="pagination" class="d-flex justify-content-center mt-3"></div>

    <!-- Modal thêm/sửa -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="productForm" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="id" id="productId">
                    <div class="col-md-6">
                        <label>Tên sản phẩm</label>
                        <input type="text" name="name" id="productName" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Giá</label>
                        <input type="number" name="price" id="productPrice" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Loại</label>
                        <select name="category_id" id="productCategory" class="form-select" required></select>
                    </div>
                    <div class="col-md-6">
                        <label>Ảnh</label>
                        <input type="file" name="image" id="productImage" class="form-control">
                        <div id="imagePreview" class="mt-1"></div>
                    </div>
                    <div class="col-12">
                        <label>Mô tả</label>
                        <textarea name="description" id="productDesc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/quanlysanpham/manage-product.js"></script>
