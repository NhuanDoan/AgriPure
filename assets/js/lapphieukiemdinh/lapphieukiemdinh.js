document.addEventListener("DOMContentLoaded", function () {
    const id_phieukiemdinh = document.getElementById("id_phieukiemdinh").value;

    // Load thông tin phiếu kiểm định và danh sách kiểm định viên
    if (id_phieukiemdinh) {
        fetch(`controllers/lapphieukiemdinh/get-phieukiemdinh.php?id_phieukiemdinh=${id_phieukiemdinh}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    document.getElementById("fullname").value = data.fullname;
                    document.getElementById("phone").value = data.phone;
                    document.getElementById("farmname").value = data.farmname;
                    document.getElementById("date").value = data.date;
                    document.getElementById("address").value = data.address;
                    document.getElementById("binhluan").value = data.binhluan || "";
                    document.getElementById("danhgia").value = data.danhgia || "";
                    document.getElementById("chungnhan").value = data.name || "";

                    const selectKiemDinhVien = document.getElementById("id_kdv");
                    selectKiemDinhVien.innerHTML = '';
                    data.kiemdinhvien_list.forEach(kdv => {
                        // Không dùng selected trong tất cả option, chỉ chọn nếu có logic riêng
                        selectKiemDinhVien.innerHTML += `<option value="${kdv.manv}">${kdv.hoten}</option>`;
                    });
                }
            })
            .catch(error => console.error("Lỗi lấy dữ liệu phiếu kiểm định:", error));
    }

    // Hàm kiểm tra tiêu chí đã chọn đủ chưa
    function checkAllSelected() {
        const criteriaList = document.getElementById('criteria-list');
        const radioNames = new Set();

        criteriaList.querySelectorAll('input[type="radio"]').forEach(radio => {
            radioNames.add(radio.name);
        });

        let allSelected = true;
        radioNames.forEach(name => {
            if (!criteriaList.querySelector(`input[name="${name}"]:checked`)) {
                allSelected = false;
            }
        });

        return allSelected;
    }

    // Xử lý khi bấm nút lưu
    document.getElementById("save").addEventListener("click", function () {
        if (!checkAllSelected()) {
            alert("Vui lòng chọn trạng thái cho tất cả tiêu chí trước khi lưu!");
            return; // dừng xử lý gửi form
        }

        // Gửi dữ liệu phiếu kiểm định (binhluan, danhgia, id_kdv)
        let dataPhieu = new FormData();
        dataPhieu.append("id_phieukiemdinh", id_phieukiemdinh);
        dataPhieu.append("binhluan", document.getElementById("binhluan").value.trim());
        dataPhieu.append("danhgia", document.getElementById("danhgia").value.trim());
        dataPhieu.append("id_kdv", document.getElementById("id_kdv").value);

        fetch("controllers/lapphieukiemdinh/update-phieukiemdinh.php", {
            method: "POST",
            body: dataPhieu
        })
        .then(response => response.json())
        .then(response => {
            if (response.status !== "success") {
                alert(response.message || "Lỗi cập nhật phiếu kiểm định!");
                throw new Error("Lỗi cập nhật phiếu kiểm định");
            }

            // Nếu thành công, tiếp tục gửi dữ liệu tiêu chí
            const form = document.getElementById("lapphieukiemdinhForm");
            const formData = new FormData(form);

            return fetch('controllers/lapphieukiemdinh/save-phieukiemdinh_chitiet.php', {
                method: 'POST',
                body: formData
            });
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                alert("Cập nhật tiêu chí đánh giá thành công!");
                window.location.href = `index.php?page=chitietphieukiemdinh&id_phieukiemdinh=${id_phieukiemdinh}`;
            } else {
                alert(data.message || "Có lỗi xảy ra khi lưu tiêu chí!");
            }
        })
        .catch(error => {
            console.error("Lỗi gửi yêu cầu:", error);
            alert("Có lỗi xảy ra, vui lòng thử lại.");
        });
    });
});
