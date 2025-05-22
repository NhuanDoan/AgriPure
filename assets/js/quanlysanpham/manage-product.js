let currentPage = 1;
const limit = 10; // 10 sản phẩm/trang

document.addEventListener("DOMContentLoaded", () => {
  loadCategories();
  loadProducts();

  document.getElementById("categoryFilter").addEventListener("change", () => {
    currentPage = 1;
    loadProducts();
  });


  document.getElementById('searchButton').addEventListener('click', function () {
    currentPage = 1;
    loadProducts();
  });

  document.getElementById("searchInput").addEventListener("keypress", e => {
    if (e.key === "Enter") {
      currentPage = 1;
      loadProducts();
    }
  });

  document.getElementById("productForm").addEventListener("submit", handleFormSubmit);
  document.getElementById("productImage").addEventListener("change", previewImage);
});

// Load loại sản phẩm để filter & form select
function loadCategories() {
  fetch("controllers/quanlysanpham/get-categories.php")
    .then(res => res.json())
    .then(categories => {
      const filter = document.getElementById("categoryFilter");
      const formSelect = document.getElementById("productCategory");
      filter.innerHTML = `<option value="">Tất cả loại</option>`;
      formSelect.innerHTML = `<option value="">Chọn loại</option>`;
      categories.forEach(c => {
        filter.innerHTML += `<option value="${c.id}">${c.name}</option>`;
        formSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
      });
    });
}

// Load sản phẩm theo filter, tìm kiếm, phân trang
function loadProducts(page = currentPage) {
  currentPage = page;
  const category = document.getElementById("categoryFilter").value;
  const search = document.getElementById("searchInput").value.trim();

  let url = "controllers/quanlysanpham/get-all.php?";
  const params = new URLSearchParams();
  if (category) params.append("category", category);
  if (search) params.append("search", search);
  params.append("limit", limit);
  params.append("page", page);
  url += params.toString();

  fetch(url)
    .then(res => res.json())
    .then(data => {
      renderProductTable(data.products);
      renderPagination(data.total, limit);
    })
    .catch(e => {
      document.getElementById("productTable").innerHTML = `<div class="text-danger">Lỗi: ${e.message}</div>`;
    });
}

// Hiển thị bảng sản phẩm
function renderProductTable(products) {
  if (!products.length) {
    document.getElementById("productTable").innerHTML = `<div class="text-center text-muted">Không có sản phẩm</div>`;
    return;
  }

  let html = `<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên</th>
        <th>Loại</th>
        <th>Giá</th>
        <th>Mô tả</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>`;
  let stt=1;
  products.forEach(p => {
    html += `
      <tr>
        <td>${stt++}</td>
        <td><img src="assets/images/imgsanpham/${p.image_url}" alt="${p.name}" style="max-width: 80px; max-height: 60px; object-fit: cover;"></td>
        <td>${p.name}</td>
        <td>${p.category_name || ''}</td>
        <td>${parseInt(p.price).toLocaleString()}đ</td>
        <td class="text-start">${p.description ? p.description.substring(0, 50) + '...' : ''}</td>
        <td class="text-center">
          <button class="btn btn-sm btn-primary m-1" onclick="showEditModal(${p.id})">Sửa</button>
          <button class="btn btn-sm btn-danger m-1" onclick="deleteProduct(${p.id})">Xoá</button>
        </td>
      </tr>`;
  });

  html += `</tbody></table>`;

  document.getElementById("productTable").innerHTML = html;
}

// Render phân trang giống như trước
function renderPagination(totalItems, limit) {
  const totalPages = Math.ceil(totalItems / limit);
  const container = document.getElementById("pagination");
  if (!container) return;

  const maxVisible = 5;
  let html = "";

  html += `<button class="btn btn-sm mx-1 ${currentPage === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadProducts(1)">«</button>`;
  html += `<button class="btn btn-sm mx-1 ${currentPage === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadProducts(${currentPage - 1})"><</button>`;

  let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
  let end = start + maxVisible - 1;
  if (end > totalPages) {
    end = totalPages;
    start = Math.max(1, end - maxVisible + 1);
  }

  for (let i = start; i <= end; i++) {
    html += `<button class="btn btn-sm mx-1 ${i === currentPage ? 'btn-success' : 'btn-outline-secondary'}" onclick="loadProducts(${i})">${i}</button>`;
  }

  html += `<button class="btn btn-sm mx-1 ${currentPage === totalPages ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadProducts(${currentPage + 1})">></button>`;
  html += `<button class="btn btn-sm mx-1 ${currentPage === totalPages ? 'btn-secondary disabled' : 'btn-outline-secondary'}" onclick="loadProducts(${totalPages})">»</button>`;

  container.innerHTML = html;
}

// Mở modal thêm sản phẩm
function showAddModal() {
  clearForm();
  document.getElementById("modalTitle").innerText = "Thêm sản phẩm";
  const modal = new bootstrap.Modal(document.getElementById("productModal"));
  modal.show();
}

// Mở modal sửa sản phẩm
function showEditModal(id) {
  fetch(`controllers/quanlysanpham/get-single.php?id=${id}`)
    .then(res => res.json())
    .then(p => {
      if (!p) throw new Error("Sản phẩm không tồn tại");

      document.getElementById("modalTitle").innerText = "Sửa sản phẩm";
      document.getElementById("productId").value = p.id;
      document.getElementById("productName").value = p.name;
      document.getElementById("productPrice").value = p.price;
      document.getElementById("productCategory").value = p.category_id;
      document.getElementById("productDesc").value = p.description || "";
      document.getElementById("imagePreview").innerHTML = `<img src="assets/images/imgsanpham/${p.image_url}" style="max-width: 150px; max-height: 120px; object-fit: contain;">`;
      document.getElementById("productImage").value = ""; // reset file input

      const modal = new bootstrap.Modal(document.getElementById("productModal"));
      modal.show();
    })
    .catch(e => alert("Lỗi tải sản phẩm: " + e.message));
}

// Xoá sản phẩm
function deleteProduct(id) {
  if (!confirm("Bạn có chắc muốn xoá sản phẩm này?")) return;

  fetch(`controllers/quanlysanpham/delete.php?id=${id}`, { method: "DELETE" })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Xoá thành công");
        loadProducts();
      } else {
        alert("Lỗi: " + (data.message || "Xoá thất bại"));
      }
    })
    .catch(e => alert("Lỗi: " + e.message));
}

// Xử lý submit form thêm/sửa
function handleFormSubmit(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const id = formData.get("id");
  const url = id ? "controllers/quanlysanpham/update.php" : "controllers/quanlysanpham/add.php";

  fetch(url, {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(id ? "Cập nhật thành công" : "Thêm thành công");
        form.reset();
        const modalEl = document.getElementById("productModal");
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        loadProducts();
      } else {
        alert("Lỗi: " + (data.message || "Thao tác thất bại"));
      }
    })
    .catch(e => alert("Lỗi: " + e.message));
}

// Xem trước ảnh upload
function previewImage() {
  const fileInput = document.getElementById("productImage");
  const preview = document.getElementById("imagePreview");
  preview.innerHTML = "";
  if (fileInput.files && fileInput.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      preview.innerHTML = `<img src="${e.target.result}" style="max-width: 150px; max-height: 120px; object-fit: contain;">`;
    };
    reader.readAsDataURL(fileInput.files[0]);
  }
}

// Xoá dữ liệu form
function clearForm() {
  document.getElementById("productForm").reset();
  document.getElementById("productId").value = "";
  document.getElementById("imagePreview").innerHTML = "";
}

