document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const errorText = document.querySelector(".error-text");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "controllers/dangkykiemdinh/dangkykiemdinh.php", true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        alert(response.message);
                        setTimeout(() => {
                            window.location.href = "index.php?page=xemphieukiemdinh";
                        }, 1000);
                    } else {
                        showError(response.message);
                    }
                } else {
                    showError("Lỗi kết nối đến server! Vui lòng thử lại.");
                }
            }
        };

        let formData = new FormData(form);
        xhr.send(formData);
    });

    function showError(message) {
        errorText.style.display = "block";
        errorText.textContent = message;
    }



    const phoneInput = document.querySelector("input[name='phone']");
    const farmnameInput = document.querySelector("input[name='farmname']");
    const addressInput = document.querySelector("input[name='address']");
    const fullnameInput = document.querySelector("input[name='fullname']");
    // const errorText = document.querySelector(".error-text");

    phoneInput.addEventListener("blur", function () {
        const phone = phoneInput.value.trim();
        if (phone.length === 0) {
            farmnameInput.value = "";
            addressInput.value = "";
            return;
        }

        // Gọi API lấy thông tin nông trại theo số điện thoại
        fetch("controllers/dangkykiemdinh/get_farm_info.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `phone=${encodeURIComponent(phone)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                farmnameInput.value = data.data.tennongtrai || "";
                addressInput.value = data.data.diachi || "";
                fullnameInput.value= (data.data.fname + " " + data.data.lname) || "";
                errorText.style.display = "none";
            } else if (data.status === "not_found") {
                // Không tìm thấy, xóa giá trị input để người dùng nhập mới
                farmnameInput.value = "";
                addressInput.value = "";
                fullnameInput.value = "";
                errorText.style.display = "none";
            } else {
                errorText.style.display = "block";
                errorText.textContent = data.message || "Lỗi khi tải thông tin nông trại.";
            }
        })
        .catch(err => {
            errorText.style.display = "block";
            errorText.textContent = "Lỗi kết nối đến server. nông trại";
            console.error(err);
        });
    });
});

// Tải danh sách chứng nhận kiểm định
document.addEventListener("DOMContentLoaded", () => {
    fetch("controllers/dangkykiemdinh/get_certifications.php")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("certification");
            data.forEach(cert => {
                const option = document.createElement("option");
                option.value = cert.id;
                option.text = cert.name;
                select.appendChild(option);
            });
        })
        .catch(err => {
            console.error("Lỗi khi tải chứng nhận:", err);
        });
});

function loadCriteria() {
    const select = document.getElementById("certification");
    const certId = select.value;
    const criteriaList = document.getElementById("criteria-list");

    // Reset trước
    criteriaList.innerHTML = "Đang tải tiêu chí...";

    // Gọi API lấy tiêu chí theo ID chứng nhận
    fetch(`controllers/dangkykiemdinh/get_criteria.php?certification_id=${certId}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                criteriaList.innerHTML = "<em>Không có tiêu chí nào cho chứng nhận này.</em>";
                return;
            }

            let html = "<label class='form-label fw-bold'>Tiêu chí kiểm định:</label><ul class='list-group'>";
            data.forEach(item => {
                html += `<li class='list-group-item'>${item.description}</li>`;
            });
            html += "</ul>";
            criteriaList.innerHTML = html;
        })
        .catch(err => {
            console.error("Lỗi khi tải tiêu chí:", err);
            criteriaList.innerHTML = "<span class='text-danger'>Không thể tải tiêu chí.</span>";
        });
}



