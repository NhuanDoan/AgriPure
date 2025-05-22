function submitCertificate() {
    const form = document.getElementById("certForm");
    const id_phieukiemdinh = form.querySelector('input[name="id_phieukiemdinh"]').value;
    const ngay_cap = form.querySelector('input[name="ngay_cap"]').value;

    if (!ngay_cap) {
        alert("Vui lòng chọn ngày cấp.");
        return;
    }

    fetch("controllers/lapchungchi/submit_certificate.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_phieukiemdinh, ngay_cap })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Cấp chứng chỉ thành công!");
            window.location.href = `index.php?page=xemchungchi&id_phieukiemdinh=${id_phieukiemdinh}`;
        } else {
            alert("Lỗi: " + data.message);
        }
    })
    .catch(error => {
        console.error("Lỗi:", error);
        alert("Có lỗi khi gửi dữ liệu.");
    });
}


// Hàm load thông tin phiếu kiểm định vào form
function loadPhieuKiemDinh(id) {
    // Gọi API để lấy dữ liệu phiếu kiểm định
    fetch(`controllers/chitietphieukiemdinh/get-chitietphieukiemdinh.php?id_phieukiemdinh=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Lấy thông tin từ response và điền vào form
            document.querySelector('input[name="farmname"]').value = data.farmname;
            document.querySelector('input[name="address"]').value = data.address;
            document.querySelector('input[name="phone"]').value = data.phone;
            document.querySelector('input[name="certification_type_name"]').value = data.certification_type_name;
        })
        .catch(error => {
            console.error("Lỗi:", error);
            alert("Có lỗi xảy ra khi tải thông tin phiếu kiểm định.");
        });
}
