let currentOrderPage = 1;
    const orderLimit = 10;

    document.addEventListener("DOMContentLoaded", () => {
        loadOrders();

        document.getElementById("statusFilter").addEventListener("change", () => {
        currentOrderPage = 1;
        loadOrders();
        });

        document.getElementById("searchOrderButton").addEventListener("click", () => {
        currentOrderPage = 1;
        loadOrders();
        });

        document.getElementById("searchOrderInput").addEventListener("keypress", e => {
        if (e.key === "Enter") {
            currentOrderPage = 1;
            loadOrders();
        }
        });
    });

    function loadOrders(page = currentOrderPage) {
        currentOrderPage = page;

        const status = document.getElementById("statusFilter").value;
        const search = document.getElementById("searchOrderInput").value.trim();

        const params = new URLSearchParams();  // Đưa lên trước
        if (status) params.append("status", status);
        if (search) params.append("search", search);
        params.append("limit", orderLimit);
        params.append("page", page);

        let url = "controllers/order/get-order.php?" + params.toString();

        fetch(url)
            .then(res => res.json())
            .then(data => {
            renderOrderTable(data.orders);
            renderOrderPagination(data.total, orderLimit);
            })
            .catch(e => {
            document.getElementById("orderTable").innerHTML = `<div class="text-danger">Lỗi: ${e.message}</div>`;
            });
    }

    function renderOrderTable(orders) {
        if (!orders.length) {
            document.getElementById("orderTable").innerHTML = `<div class="text-center text-muted">Không có đơn hàng</div>`;
            return;
        }

        let html = `<table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light ">
                <tr>
                    <th>STT</th>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>`;

        let stt = (currentOrderPage - 1) * orderLimit + 1;
        orders.forEach(order => {
            html += `
            <tr>
                <td>${stt++}</td>
                <td>${order.id}</td>
                <td>${order.full_name}</td>
                <td>${order.phone}</td>
                <td>${new Date(order.created_at).toLocaleDateString()}</td>
                <td>${Number(order.total_price).toLocaleString()}đ</td>
                <td>${formatStatus(order.status)}</td>
                <td>
                <button class="btn btn-sm btn-outline-info me-1" onclick="showOrderDetail(${order.id})">Xem</button>
                ${order.status === 'pending'
                    ? `
                        <button class="btn btn-sm btn-outline-danger me-1" onclick="cancelOrder(${order.id})">Huỷ</button>
                        ${userRole === 2 ? `<button class="btn btn-sm btn-outline-success" onclick="confirmOrder(${order.id})">Xác nhận</button>` : ''}
                    `
                    : ''
                }

                </td>
            </tr>`;
        });

        html += `</tbody></table>`;
        document.getElementById("orderTable").innerHTML = html;
    }


    function formatStatus(status) {
        switch(status) {
        case 'pending': return '<span class="badge bg-warning text-dark">Chờ xử lý</span>';
        case 'shipping': return '<span class="badge bg-primary">Đang giao</span>';
        case 'completed': return '<span class="badge bg-success">Hoàn thành</span>';
        case 'cancelled': return '<span class="badge bg-danger">Đã huỷ</span>';
        default: return status;
        }
    }

    function formatPaymentStatus(status) {
        switch(status) {
            case 'unpaid':
            return '<span class="badge bg-danger">Chưa thanh toán</span>';
            case 'paid':
            return '<span class="badge bg-success">Đã thanh toán</span>';
            default:
            return `<span class="badge bg-light text-dark">${status}</span>`;
        }
    }

    function renderOrderPagination(totalItems, limit) {
        const totalPages = Math.ceil(totalItems / limit);
        const container = document.getElementById("orderPagination");
        if (!container) return;

        const maxVisible = 5;
        let html = "";

        html += `<button class="btn btn-sm mx-1 ${currentOrderPage === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadOrders(1)">«</button>`;
        html += `<button class="btn btn-sm mx-1 ${currentOrderPage === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadOrders(${currentOrderPage - 1})"><</button>`;

        let start = Math.max(1, currentOrderPage - Math.floor(maxVisible / 2));
        let end = start + maxVisible - 1;
        if (end > totalPages) {
        end = totalPages;
        start = Math.max(1, end - maxVisible + 1);
        }

        for (let i = start; i <= end; i++) {
        html += `<button class="btn btn-sm mx-1 ${i === currentOrderPage ? 'btn-success' : 'btn-outline-secondary'}" onclick="loadOrders(${i})">${i}</button>`;
        }

        html += `<button class="btn btn-sm mx-1 ${currentOrderPage === totalPages ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadOrders(${currentOrderPage + 1})">></button>`;
        html += `<button class="btn btn-sm mx-1 ${currentOrderPage === totalPages ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadOrders(${totalPages})">»</button>`;

        container.innerHTML = html;
    }

    // Hiển thị chi tiết đơn hàng trong modal
    function showOrderDetail(id) {
        fetch(`controllers/order/get-single.php?order_id=${id}`)
        .then(res => res.json())
        .then(order => {
            if (!order) throw new Error("Không tìm thấy đơn hàng");

            let html = `
                <p><strong>Mã đơn:</strong> ${order.id}</p>
                <p><strong>Khách hàng:</strong> ${order.full_name}</p>
                <p><strong>Ngày đặt:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                <p><strong>Trạng thái:</strong> ${formatStatus(order.status)}</p>
                <p><strong>Địa chỉ giao hàng:</strong> ${order.address}</p>
                <p><strong>Thanh toán:</strong> ${formatPaymentStatus(order.payment_status)}</p>
                <p><strong>Ghi chú:</strong> ${order.note || '-'}</p>
                <hr>
                <h5>Chi tiết sản phẩm:</h5>
                <table class="table table-sm table-bordered text-center">
                    <thead>
                    <tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th></tr>
                    </thead>
                    <tbody>`;

            order.items.forEach(item => {
            html += `<tr>
                <td>${item.product_name}</td>
                <td>${item.quantity}</td>
                <td>${Number(item.price).toLocaleString()}đ</td>
                <td>${(item.price * item.quantity).toLocaleString()}đ</td>
            </tr>`;
            });

            html += `</tbody></table>`;

            document.getElementById("orderDetailContent").innerHTML = html;

            const modal = new bootstrap.Modal(document.getElementById("orderDetailModal"));
            modal.show();
        })
        .catch(e => alert("Lỗi tải đơn hàng: " + e.message));
    }


   // Khởi tạo modal Bootstrap 1 lần duy nhất
    const noteModal = new bootstrap.Modal(document.getElementById('noteModal'));

    function openNoteModal(orderId, action) {
        document.getElementById('noteOrderId').value = orderId;
        document.getElementById('noteAction').value = action;
        const noteText = document.getElementById('noteText');
        noteText.value = '';

        const title = action === 'cancel' ? 'Nhập ghi chú huỷ đơn' : 'Nhập ghi chú xác nhận đơn';
        document.getElementById('noteModalLabel').textContent = title;

        const submitBtn = document.querySelector('#noteForm button[type="submit"]');
        if (action === 'cancel') {
            submitBtn.textContent = 'Huỷ đơn';
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-danger');

            // Style textarea cho huỷ
            document.getElementById('noteModalLabel').classList.remove('text-white','text-success');
            document.getElementById('noteModalLabel').classList.add('text-danger');
        } else {
            submitBtn.textContent = 'Xác nhận';
            submitBtn.classList.remove('btn-danger');
            submitBtn.classList.add('btn-success');

            // Style textarea cho xác nhận
            document.getElementById('noteModalLabel').classList.remove( 'text-white','text-danger');
            document.getElementById('noteModalLabel').classList.add('text-success');
        }

        // const noteModal = new bootstrap.Modal(document.getElementById('noteModal'));
        noteModal.show();
    }

    // Hai hàm gọi mở modal khi nhấn nút
    function cancelOrder(id) {
        openNoteModal(id, 'cancel');
    }

    function confirmOrder(id) {
        openNoteModal(id, 'confirm');
    }

    // Xử lý submit form ghi chú
    document.getElementById('noteForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const orderId = document.getElementById('noteOrderId').value;
        const action = document.getElementById('noteAction').value;
        const note = document.getElementById('noteText').value.trim();

        if (!note) {
            alert('Vui lòng nhập ghi chú');
            return;
        }

        const endpoint = action === 'confirm' ? 'controllers/order/confirm.php' : 'controllers/order/cancel.php';

        const formData = new URLSearchParams();
        formData.append('order_id', orderId);
        formData.append('note', note);

        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(res => res.text()) // đổi sang text để xem raw response
        .then(text => {
            console.log('Server response:', text);
            return JSON.parse(text); // thử parse thủ công để xem lỗi
        })
        .then(data => {
            alert(data.message);
            if (data.success) {
                noteModal.hide();
                loadOrders(); // tải lại danh sách đơn hàng
            }
        })
        .catch(err => alert('Lỗi kết nối: ' + err.message));
    });
