const searchBar = document.querySelector(".search input"),
    searchIcon = document.querySelector(".search button"),
    userslist = document.querySelector(".users-list");

function searchPhieuKiemDinh(searchTerm = "") {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "controllers/xemphieukiemdinh/search-phieukiemdinh.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            userslist.innerHTML = xhr.response;
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("searchTerm=" + encodeURIComponent(searchTerm));
}

searchIcon.onclick = () => {
    let searchTerm = searchBar.value.trim();
    searchPhieuKiemDinh(searchTerm);
};

// Tự động gọi khi vừa tải trang
document.addEventListener("DOMContentLoaded", () => {
    searchPhieuKiemDinh("");
});
