document.addEventListener("DOMContentLoaded", function () {
    const id_phieukiemdinh = document.getElementById("id_phieukiemdinh").value;
    
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
                    selectKiemDinhVien.innerHTML = ''; // Xóa hết option cũ
                    data.kiemdinhvien_list.forEach(kdv => {
                        selectKiemDinhVien.innerHTML += `<option value="${kdv.manv}" selected>${kdv.hoten}</option>`;
                    });
                    // selectKiemDinhVien.disabled = true;
                }
            })
            .catch(error => console.error("Lỗi lấy dữ liệu phiếu kiểm định:", error));
    }
});

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("save").addEventListener("click", function () {
        let data = new FormData();
        const id_phieukiemdinh = document.getElementById("id_phieukiemdinh").value;
        data.append("id_phieukiemdinh", id_phieukiemdinh);
        data.append("binhluan", document.getElementById("binhluan").value.trim());
        data.append("danhgia", document.getElementById("danhgia").value.trim());
        data.append("id_kdv", document.getElementById("id_kdv").value);

        fetch("controllers/lapphieukiemdinh/update-phieukiemdinh.php", {
            method: "POST",
            body: data
        })
        .then(response => response.json())
        .then(response => {
            console.log("Server Response:", response);
            alert(response.message);
            if (response.status === "success") {
                window.location.href = "index.php?page=chitietphieukiemdinh&id_phieukiemdinh="+id_phieukiemdinh;
            }
        })
        .catch(error => {
            console.error("Lỗi gửi yêu cầu!", error);
            alert("Lỗi gửi yêu cầu! Kiểm tra console.");
        });
    });

    document.getElementById("save").addEventListener("click", function () {
        const form = document.getElementById("lapphieukiemdinhForm");
        const formData = new FormData(form);
    
        fetch('controllers/lapphieukiemdinh/save-phieukiemdinh_chitiet.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                alert("Cập nhật tiêu chí đánh giá thành công!");
                window.location.href = `index.php?page=chitietphieukiemdinh&id_phieukiemdinh=${formData.get('id_phieukiemdinh')}`;
            } else {
                alert(data.message || "Có lỗi xảy ra");
            }
        });
    });
});

