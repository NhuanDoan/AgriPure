<?php
    include_once("models/config.php");

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit();
    }
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $user_id = $_SESSION['user_id'];

    // Lấy sản phẩm
    $sql = "SELECT id, name FROM products WHERE user_id = ? ORDER BY name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) $products[] = $row;
    $stmt->close();

    // Lấy nông trại
    $sqlFarm = "SELECT id_nongtrai, tennongtrai FROM nongtrai WHERE id_user = ? ORDER BY tennongtrai ASC";
    $stmtFarm = $conn->prepare($sqlFarm);
    $stmtFarm->bind_param("i", $user_id);
    $stmtFarm->execute();
    $resultFarm = $stmtFarm->get_result();
    $farmList = [];
    while ($row = $resultFarm->fetch_assoc()) $farmList[] = $row;
    $stmtFarm->close();
?>

<div class="content">
    <div class="container mt-4">
        <h2 class="text-center mb-4">THÊM THÔNG TIN BLOCKCHAIN NÔNG SẢN</h2>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-8">
                <section class="form bg-success bg-gradient px-4 py-5 my-4 rounded" style="--bs-bg-opacity: .2;">
                    <form id="blockForm" autocomplete="off">
                        <div class="form-group my-3">
                            <label for="product_id" class="form-label">Chọn sản phẩm</label>
                            <select class="form-select" name="product_id" id="product_id" required>
                                <option value="" selected disabled>Chọn sản phẩm</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= htmlspecialchars($product['id']) ?>"><?= htmlspecialchars($product['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group my-3">
                            <label for="farm_id" class="form-label">Chọn nông trại</label>
                            <select class="form-select" name="farm_id" id="farm_id" required>
                                <option value="" selected disabled>Chọn nông trại</option>
                                <?php foreach ($farmList as $farm): ?>
                                    <option value="<?= htmlspecialchars($farm['id_nongtrai']) ?>"><?= htmlspecialchars($farm['tennongtrai']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group my-3">
                            <label for="lot_number" class="form-label">Số lô hàng</label>
                            <input class="form-control" type="text" name="lot_number" id="lot_number" placeholder="Nhập số lô hàng" required>
                        </div>

                        <div class="form-group my-3">
                            <label for="freshness" class="form-label">Độ tươi sản phẩm</label>
                            <select class="form-select" name="freshness" id="freshness" required>
                                <option value="" selected disabled>Chọn độ tươi</option>
                                <option value="Tươi mới">Tươi mới</option>
                                <option value="Vừa thu hoạch">Vừa thu hoạch</option>
                                <option value="Bình thường">Bình thường</option>
                                <option value="Giảm chất lượng">Giảm chất lượng</option>
                            </select>
                        </div>

                        <div class="form-group my-3">
                            <label for="checker" class="form-label">Người kiểm hàng</label>
                            <input class="form-control" type="text" name="checker" id="checker" placeholder="Nhập tên người kiểm hàng" required>
                        </div>

                        <div class="form-group my-2">
                            <label for="harvest_date" class="form-label">Ngày</label>
                            <input class="form-control" type="datetime-local" name="harvest_date" id="harvest_date"
                                value="<?= date('Y-m-d\TH:i') ?>" required>
                        </div>

                        <div class="d-flex flex-column align-items-center mb-3">
                            <div id="map" class="w-75" style="height: 40vh;"></div>
                        </div>

                        <div class="form-group my-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input class="form-control" type="text" name="address" id="address" placeholder="Nhập địa chỉ" value="" required>
                        </div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="form-group my-4 text-center">
                            <input class="btn btn-outline-primary px-4 py-2" type="submit" value="Thêm block mới">
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/blockchain/blockchain.js"></script>
