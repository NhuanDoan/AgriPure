const form = document.querySelector(".dangnhap form"),
errorText = document.querySelector(".error-text");

form.addEventListener("submit", function (e) {
e.preventDefault(); // Ngăn trang tải lại

let xhr = new XMLHttpRequest();
xhr.open("POST", "controllers/login/login.php", true);
xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
            let data = xhr.responseText.trim(); // Xử lý lỗi từ PHP
            if (data === "thành công") {
                alert("Đăng nhập thành công!!!!");
                setTimeout(() => {
                    window.location.href = "index.php";
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