document.addEventListener("DOMContentLoaded", function () {
    loadFarmDetail(farmId);
    loadFarmCertificates(farmId);
});

function loadFarmDetail(id) {
    fetch(`controllers/farm-detail/get-farm-detail.php?id_nongtrai=${id}`)
        .then(res => res.json())
        .then(farm => {
            const container = document.getElementById("farmDetailContainer");
            if (!farm || !farm.id_nongtrai) {
                container.innerHTML = `<p class="text-danger">Không tìm thấy nông trại.</p>`;
                return;
            }

            const html = `
                <div class="row align-items-center g-4">
                    <div class="col-md-12">
                        <h3 class="text-success">${farm.tennongtrai}</h3>
                        <p><strong>Địa chỉ:</strong> ${farm.diachi}</p>
                        <p><strong>Chủ nông trại:</strong> ${farm.fname+" "+farm.lname || 'Đang cập nhật'}</p>
                    </div>
                </div>
            `;
            container.innerHTML = html;
        });
}

function loadFarmCertificates(id) {
    fetch(`controllers/farm-detail/get-farm-certificates.php?id_nongtrai=${id}`)
        .then(res => res.json())
        .then(data => {
            const certContainer = document.getElementById("certList");
            if (!data.length) {
                certContainer.innerHTML = `<p>Chưa có chứng chỉ nào.</p>`;
                return;
            }

            let html = `<div class="table-responsive"><table class="table table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Loại chứng chỉ</th>
                        <th>Ngày cấp</th>
                        <th>Ngày hết hạn</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.map((cert, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${cert.tenchungnhan}</td>
                            <td>${cert.issue_date}</td>
                            <td>${cert.expiry_date}</td>
                            <td>${cert.status}</td>
                        </tr>
                    `).join("")}
                </tbody>
            </table></div>`;

            certContainer.innerHTML = html;
        });
}