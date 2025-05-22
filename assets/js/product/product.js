let currentPage = 1;

function loadSanPham(page = 1) {
    currentPage = page;
    const category = document.getElementById("categorySelect").value;
    const search = document.getElementById("searchInput").value.trim();
    const limit = 24;

    let url = "controllers/product/get-product.php";
    const params = new URLSearchParams();
    if (category) params.append("category", category);
    if (search) params.append("search", search);
    params.append("limit", limit);
    params.append("page", page);

    url += "?" + params.toString();

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById("productList");
            if (!Array.isArray(data.products) || data.products.length === 0) {
                list.innerHTML = `<div class="col-12 text-danger text-center">Không có sản phẩm nào phù hợp.</div>`;
                return;
            }

            let html = "";
            data.products.forEach(p => {
                let label = "";
                if (p.is_certified == 1) {
                    label = `<span class="badge pure-badge">Pure</span>`;
                }

                html += `
                    <div class="col-4 col-lg-2 mb-3">
                        <div class="card position-relative h-100 d-flex flex-column" style="border-radius: 0; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.15); transition: box-shadow 0.3s ease;">
                        ${label}
                        <a href="index.php?page=product-detail&id_product=${p.id}">
                            <img src="assets/images/imgsanpham/${p.image_url}" class="card-img-top" alt="${p.name}" style="border-radius: 0;">
                        </a>
                        <div class="card-body text-center flex-grow-1">
                            <h5 class="card-title">${p.name}</h5>
                            <p class="card-text text-danger fw-bold">${parseInt(p.price).toLocaleString()}đ</p>
                        </div>
                        <button onclick='addToCart(${JSON.stringify(p)})' class="btn btn-outline-success w-100 rounded-0">Thêm vào giỏ</button>
                        </div>
                    </div>`;
            });

            list.innerHTML = html;
            renderPagination(data.total, limit);
        })
        .catch(err => {
            document.getElementById("productList").innerHTML = `<div class="col-12 text-danger">Lỗi: ${err.message}</div>`;
        });
}

// Gọi loadSanPham mỗi khi chọn loại — reset về trang 1
document.getElementById("categorySelect").addEventListener("change", function () {
    loadSanPham(1);
});

// Khi tìm kiếm — reset về trang 1
document.getElementById("searchInput").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        loadSanPham(1);
    }
});


function loadCategories() {
    fetch("controllers/product/get-categories.php")
        .then(res => {
            if (!res.ok) throw new Error(`Lỗi mạng: ${res.status}`);
            return res.json();
        })
        .then(categories => {
            const select = document.getElementById("categorySelect");
            select.innerHTML = `<option value="">Tất cả loại</option>`; // reset lại danh sách option mỗi lần load

            categories.forEach(cat => {
                const option = document.createElement("option");
                option.value = cat.id;
                option.textContent = cat.name;
                select.appendChild(option);
            });
        })
        .catch(err => {
            console.error("Lỗi load categories:", err);
        });
}

function renderPagination(totalItems, limit) {
    const totalPages = Math.ceil(totalItems / limit);
    const container = document.getElementById("pagination");
    if (!container) return;

    const maxVisible = 5; // số trang hiển thị tối đa
    let html = "";

    // Trang đầu & trước
    html += `<button class="btn btn-sm mx-1 ${currentPage === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadSanPham(1)">«</button>`;
    html += `<button class="btn btn-sm mx-1 ${currentPage === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadSanPham(${currentPage - 1})"><</button>`;

    // Xác định range trang hiển thị
    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = start + maxVisible - 1;

    if (end > totalPages) {
        end = totalPages;
        start = Math.max(1, end - maxVisible + 1);
    }

    // Các số trang
    for (let i = start; i <= end; i++) {
        html += `<button class="btn btn-sm mx-1 ${i === currentPage ? 'btn-success' : 'btn-outline-secondary'}" onclick="loadSanPham(${i})">${i}</button>`;
    }

    // Trang tiếp theo & cuối
    html += `<button class="btn btn-sm mx-1 ${currentPage === totalPages ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadSanPham(${currentPage + 1})">></button>`;
    html += `<button class="btn btn-sm mx-1 ${currentPage === totalPages ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadSanPham(${totalPages})">»</button>`;

    container.innerHTML = html;
}
  
  function updateQuantity(index, newQuantity) {
    newQuantity = parseInt(newQuantity);
    if (isNaN(newQuantity) || newQuantity < 1) return;
  
    let cart = getCart();
    if (index < 0 || index >= cart.length) return;
  
    cart[index].quantity = newQuantity;
    saveCart(cart);
    renderCartItems();
  }
  
  function removeFromCart(index) {
    let cart = getCart();
    if (index < 0 || index >= cart.length) return;
  
    cart.splice(index, 1);
    saveCart(cart);
    renderCartItems();
  }
  


function getCart() {
    return JSON.parse(sessionStorage.getItem("cart") || "[]");
  }
  
  function saveCart(cart) {
    sessionStorage.setItem("cart", JSON.stringify(cart));
  }
  
  function addToCart(product) {
    let cart = getCart();
    let existing = cart.find(item => item.id === product.id);
  
    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ ...product, quantity: 1 });
    }
  
    saveCart(cart);
    alert("Đã thêm vào giỏ hàng!");
    renderCartItems(); // cập nhật modal nếu đang mở
  }
  
function renderCartItems() {
    const container = document.getElementById("cartItemsContainer");
    const cart = getCart();

    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `<p class="text-center">Giỏ hàng trống.</p>`;
        return;
    }

    let html = "<ul class='list-group'>";
    cart.forEach((item, index) => {
        html += `
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center w-100">
                <!-- Tên và giá -->
                <div style="flex: 2;">
                    <strong>${item.name}</strong><br>
                    <small class="text-muted">${parseInt(item.price).toLocaleString()}đ</small>
                </div>

                <!-- Nút tăng/giảm -->
                <div class="input-group input-group-sm mx-3" style="width: 120px;">
                    <button class="btn btn-outline-secondary" onclick="changeQuantity(${index}, -1)">-</button>
                    <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                    <button class="btn btn-outline-secondary" onclick="changeQuantity(${index}, 1)">+</button>
                </div>

                <!-- Tổng giá -->
                <div style="flex: 1;" class="text-end text-success fw-bold">
                    ${(item.price * item.quantity).toLocaleString()}đ
                </div>

                <!-- Nút xóa -->
                <div class="ms-3">
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </li>`;
    });
    html += "</ul>";

    // Tổng tiền
    let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    html += `<p class="mt-3 text-end fw-bold">Tổng cộng: ${total.toLocaleString()}đ</p>`;

    container.innerHTML = html;
}


function changeQuantity(index, delta) {
    let cart = getCart();
    if (index < 0 || index >= cart.length) return;
  
    const newQuantity = cart[index].quantity + delta;
    if (newQuantity < 1) return; // Không cho giảm dưới 1
  
    cart[index].quantity = newQuantity;
    saveCart(cart);
    renderCartItems();
  }
  

  
const cartModal = document.getElementById('cartModal');
cartModal.addEventListener('show.bs.modal', renderCartItems);