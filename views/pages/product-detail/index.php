<?php
    if (!isset($_SESSION['unique_id'])) {
        header("Location: index.php?page=login");
        exit();
    }

    $product_id = isset($_GET['id_product']) ? intval($_GET['id_product']) : 0;
    if ($product_id <= 0) {
        echo "Sản phẩm không tồn tại.";
        exit();
    }
?>

<div class="content my-4 product-detail">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9">
                <h2 class="text-center my-4">Thông tin sản phẩm</h2>
            </div>
            <div id="productDetailContainer" class="col-md-10 border border-success-subtle rounded p-4 bg-light">
                <!-- Thông tin sản phẩm sẽ được load bằng JavaScript -->
            </div>
        </div>
        <div class="my-4 text-center">
            <button class="btn btn-outline-dark" onclick="history.back()">
                &larr; Quay lại
            </button>
        </div>
    </div>
    
</div>

<script>
    const productId = <?= json_encode($product_id) ?>;
</script>
<script src="assets/js/product-detail/product-detail.js"></script>

