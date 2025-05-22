// const form = document.querySelector(".dangky form"),
// continuBtn = form.querySelector("input[type='submit']"),
// errorText = form.querySelector(".error-text");

// form.onsubmit = (e)=>{
//     e.preventDefault();
// }

// continuBtn.onclick =()=>{
//     let xhr = new XMLHttpRequest();
//     xhr.open("POST",'../php/signup.php', true);
//     xhr.onload = ()=>{
//         if(xhr.readyState === XMLHttpRequest.DONE)
//         {
//             if(xhr.status === 200)
//             {
//                 let data = xhr.response;
//                 if(data === "success"){
//                     location.href="users.php";
//                 } else {
//                     errorText.style.display = "block";
//                     errorText.textContent = data;
//                 }
//             }
//         }
//     }
//     let formData = new FormData(form);
//     xhr.send(formData);
// }

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".dangky form"),
        errorText = document.querySelector(".error-text");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Ngăn trang tải lại

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "controllers/register/signup.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.responseText.trim(); // Xử lý lỗi từ PHP
                    if (data === "thành công") {
                        alert("Đăng ký thành công!!!!");
                        setTimeout(() => {
                            window.location.href = "index.php?page=login";
                        }, 500);
                    } else {
                        errorText.style.display = "block";
                        errorText.textContent = data;
                    }
                } else {
                    errorText.style.display = "block";
                    errorText.textContent = "Lỗi kết nối đến server!";
                }
            }
        };

        let formData = new FormData(form);
        xhr.send(formData);
    });


    // Ẩn hiện form điền thông tin cho tài khoản nông dân
    const roleSelect = document.getElementById("role-select");
    const farmerFields = document.getElementById("farmer-fields");
    const farmName = document.getElementById("farm_name");
    const farmAddress = document.getElementById("farm_address");

    // Nếu mở trang mà đã chọn "nông dân"
    if (roleSelect.value === "2") {
        farmerFields.style.display = "block";
        farmName.required = true;
        farmAddress.required = true;
        initMap();
    }

    // Khi thay đổi vai trò
    roleSelect.addEventListener("change", function () {
        if (this.value === "2") {
            farmerFields.style.display = "block";
            farmName.required = true;
            farmAddress.required = true;
            initMap(); // ← Khởi tạo map tại đây
        } else {
            farmerFields.style.display = "none";
            farmName.required = false;
            farmAddress.required = false;
        }
    });
});

