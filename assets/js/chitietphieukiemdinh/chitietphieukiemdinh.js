function loadChiTietPhieuKiemDinh(id) {
    fetch(`controllers/chitietphieukiemdinh/get-chitietphieukiemdinh.php?id_phieukiemdinh=${id}`)
        .then(response => response.text())
        .then(text => {
            console.log("Raw response:", text);
            if (!text.trim()) throw new Error("Phản hồi rỗng từ server");
            let data = JSON.parse(text);

            const table = document.getElementById("phieuKiemDinhTable");

            if (data.error) {
                table.innerHTML = `<tr><td colspan="2" class="text-danger">${data.error}</td></tr>`;
                return;
            }

            let html = `
                <tr><th>Họ tên</th><td>${data.fullname || 'Chưa rõ'}</td></tr>
                <tr><th>SĐT</th><td>${data.phone || 'Chưa rõ'}</td></tr>
                <tr><th>Nông trại</th><td>${data.farmname || 'Chưa rõ'}</td></tr>
                <tr><th>Ngày kiểm định</th><td>${data.date || 'Chưa rõ'}</td></tr>
                <tr><th>Loại chứng chỉ</th><td>${data.tenchungchi || data.certification_type_name || 'Chưa rõ'}</td></tr>
                <tr><th>Địa chỉ</th><td>${data.address || 'Chưa rõ'}</td></tr>
                <tr><th>Kiểm định viên</th><td>${data.ten_kdv || 'Chưa rõ'}</td></tr>
                <tr><th>Đánh giá</th><td>${data.danhgia || 'Chưa có'}</td></tr>
                <tr><th>Bình luận</th><td>${data.binhluan || 'Không có'}</td></tr>
            `;

            if (Array.isArray(data.criteria) && data.criteria.length > 0) {
                html += `<tr><th colspan="2" class="table-info text-center">Chi tiết tiêu chí</th></tr>`;
                data.criteria.forEach(c => {
                    html += `<tr><td>${c.description}</td><td>${c.status}</td></tr>`;
                });
            }

            table.innerHTML = html;

            // Giả sử data.status là trạng thái phiếu kiểm định
            if (data.status === "Đã kiểm định") {
                const link = document.getElementById("btnLapphieu");
                if (link) {
                    link.textContent = "Cập nhật phiếu kiểm định";
                }
            }
            
        })
        .catch(error => {
            document.getElementById("phieuKiemDinhTable").innerHTML = 
                `<tr><td colspan="2" class="text-danger">Lỗi khi tải dữ liệu: ${error.message}</td></tr>`;
            console.error("Lỗi khi fetch dữ liệu phiếu kiểm định:", error);
        });
}
