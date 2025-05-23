<div class="container mt-4">
  <h2 class="text-center my-4">Quản lý đơn hàng</h2>

  <div class="d-flex mb-3 align-items-center">
    <select id="statusFilter" class="form-select me-2" style="width: 200px;">
      <option value="">Tất cả trạng thái</option>
      <option value="pending">Chờ xử lý</option>
      <option value="shipping">Đang giao</option>
      <option value="completed">Hoàn thành</option>
      <option value="cancelled">Đã huỷ</option>
    </select>

    <input id="searchOrderInput" type="text" class="form-control me-2" placeholder="Tìm mã đơn / tên khách hàng...">
    <button id="searchOrderButton" class="btn btn-primary me-auto">Tìm</button>
  </div>

  <div id="orderTable"></div>
  <div class="text-center">
    <div id="orderPagination" class="mt-3"></div>
  </div>
</div>

<!-- Modal Xem chi tiết đơn hàng -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailTitle">Chi tiết đơn hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body" id="orderDetailContent">
        <!-- Chi tiết đơn sẽ được load ở đây -->
      </div>
    </div>
  </div>
</div>


<!-- Modal Ghi Chú -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"> <!-- canh giữa modal -->
    <div class="modal-content rounded-4 shadow-lg"> <!-- bo góc, bóng đổ -->
      <form id="noteForm" class="needs-validation" novalidate>
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold " id="noteModalLabel">Nhập ghi chú</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="noteOrderId">
          <input type="hidden" id="noteAction">
          <div class="mb-3">
            <label for="noteText" class="form-label fw-semibold">Ghi chú</label>
            <textarea 
              class="form-control form-control-lg" 
              id="noteText" 
              rows="4" 
              placeholder="Nhập ghi chú..." 
              required
              ></textarea>
            <div class="invalid-feedback">
              Vui lòng nhập ghi chú.
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn rounded-pill px-4 fw-semibold">Gửi</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
    const userRole = <?php echo $_SESSION['role']?>;
</script>
<script src="assets/js/order/order.js"></script>
