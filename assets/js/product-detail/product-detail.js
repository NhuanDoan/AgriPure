document.addEventListener("DOMContentLoaded", function() {
    loadProductDetail(productId);
});

function loadProductDetail(id) {
    fetch(`controllers/product-detail/get-product-detail.php?id_product=${id}`)
    .then(res => res.json())
    .then(product => {
        const container = document.getElementById("productDetailContainer");

        if (!product || !product.id) {
            container.innerHTML = `<p class="text-danger">Không tìm thấy sản phẩm.</p>`;
            return;
        }

        const farmInfo = product.tennongtrai && product.id_nongtrai
            ? `<p>Nông trại: 
                <a href="index.php?page=farm-detail&id_nongtrai=${product.id_nongtrai}" 
                   class="fw-semibold text-success text-decoration-underline">
                   ${product.tennongtrai}
                </a></p>`
            : "";

        const label = product.is_certified == 1
            ? `<span class="badge bg-success bg-gradient mb-2">Pure</span>`
            : "";

        const html = `
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center">
                    <img src="assets/images/imgsanpham/${product.image_url}" 
                         class="img-fluid shadow-sm" 
                         style="max-height: 400px; object-fit: contain; border-radius: 12px;" 
                         alt="${product.name}">
                </div>
                <div class="col-md-6">
                    <h3 class="text-primary">${label} ${product.name}</h3>
                    <h4 class="text-danger">${parseInt(product.price).toLocaleString()}đ</h4>
                    ${farmInfo}
                    <p>${product.description}</p>
                    <button class="btn btn-success">Mua ngay</button>
                </div>
            </div>
        `;

        container.innerHTML = html;
    })
    .catch(err => {
        document.getElementById("productDetailContainer").innerHTML = `<p class="text-danger">Lỗi: ${err.message}</p>`;
    });
}