let currentPage = 1;
let totalPages = 1; // sẽ cập nhật từ backend
const maxVisible = 5; // số nút phân trang hiển thị tối đa

function loadPage(page) {
    if (page < 1 || (totalPages && page > totalPages)) return;

    fetch("controllers/quanly-block/quanly-block.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `page=${page}`
    })
    .then(res => {
        if (!res.ok) throw new Error("Failed to fetch");
        return res.json();
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }
        document.getElementById("block-list").innerHTML = data.html;
        totalPages = data.totalPages;
        currentPage = page;
        updatePaginationUI();
    })
    .catch(err => {
        console.error(err);
        alert("Không thể tải dữ liệu");
    });
}

function renderPagination() {
    const container = document.getElementById("pagination");
    container.innerHTML = "";

    container.appendChild(createPageButton("«", 1, {disabled: currentPage === 1}));
    container.appendChild(createPageButton("<", currentPage - 1, {disabled: currentPage === 1}));

    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = start + maxVisible - 1;
    if (end > totalPages) {
        end = totalPages;
        start = Math.max(1, end - maxVisible + 1);
    }

    for (let i = start; i <= end; i++) {
        container.appendChild(createPageButton(i, i, {active: i === currentPage}));
    }

    container.appendChild(createPageButton(">", currentPage + 1, {disabled: currentPage === totalPages}));
    container.appendChild(createPageButton("»", totalPages, {disabled: currentPage === totalPages}));
}

function createPageButton(text, page, {active = false, disabled = false} = {}) {
    const btn = document.createElement("button");
    btn.className = "btn btn-sm mx-1";

    if (active) {
        btn.classList.add("btn-success");
        btn.disabled = true;
    } else if (disabled) {
        btn.classList.add("btn-secondary");
        btn.disabled = true;
    } else {
        btn.classList.add("btn-outline-secondary");
        btn.disabled = false;
        btn.onclick = () => loadPage(page);
    }

    btn.innerText = text;
    return btn;
}

function updatePaginationUI() {
    renderPagination();
}

window.addEventListener("DOMContentLoaded", () => loadPage(1));