const searchBar = document.querySelector(".search input"),
      searchIcon = document.querySelector(".search button"),
      userslist = document.querySelector(".users-list");

// Hàm load danh sách người dùng (có thể dùng lại được)
function loadUserList() {
    const searchTerm = searchBar.value.trim();
    const xhr = new XMLHttpRequest();

    xhr.open("POST", "controllers/chat-search/search.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            userslist.innerHTML = xhr.response;
        } else {
            userslist.innerHTML = "<p>Có lỗi xảy ra!</p>";
        }
    };

    xhr.onerror = () => {
        userslist.innerHTML = "<p>Lỗi kết nối máy chủ!</p>";
    };

    xhr.send("searchTerm=" + encodeURIComponent(searchTerm));
}

// Bấm nút tìm kiếm
searchIcon.onclick = loadUserList;

// Lần đầu vào trang thì load ngay
window.addEventListener("load", loadUserList);

// Cứ mỗi 5 giây load lại (để cập nhật nếu có người mới nhắn tin)
setInterval(loadUserList, 5000);
