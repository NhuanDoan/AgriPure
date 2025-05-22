<?php
    if (!isset($_SESSION['unique_id'])) {
        header("Location: index.php?page=login");
        exit();
    }

    $farm_id = isset($_GET['id_nongtrai']) ? intval($_GET['id_nongtrai']) : 0;
    if ($farm_id <= 0) {
        echo "Nông trại không tồn tại.";
        exit();
    }
?>

<div class="content my-4 farm-detail">
    <div class="container">
        <div class="row justify-content-center">
            <div id="farmDetailContainer" class="col-md-10 border rounded bg-light p-4 mb-4">
                <!-- Thông tin nông trại sẽ được load tại đây -->
            </div>

            <div id="certificatesContainer" class="col-md-10 border rounded bg-white p-4">
                <h5 class="mb-3">Chứng chỉ đạt được</h5>
                <div id="certList">
                    <!-- Danh sách chứng chỉ -->
                </div>
            </div>
            <div class="col-12 my-3 text-center">
                <button class="btn btn-outline-dark" onclick="history.back()">&larr; Quay lại</button>
            </div>
        </div>
    </div>
</div>

<script>
    const farmId = <?= $farm_id ?>;
</script>
<script src="assets/js/farm-detail/farm-detail.js"></script>
