function loadCertificates(type, value) {
    let query = type && value ? `?${type}=${value}` : "";
    fetch(`controllers/xemchungchi/get-certificates.php${query}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("certList");
            tbody.innerHTML = "";

            if (data.length === 0) {
                tbody.innerHTML = "<tr><td colspan='7'>Không có chứng chỉ nào.</td></tr>";
                return;
            }

            data.forEach((cert, index) => {
                tbody.innerHTML += `
                    <tr>
                        <td scope="row">${index + 1}</td>
                        <td>${cert.tennongtrai}</td>
                        <td>${cert.phone}</td>
                        <td>${cert.certification_name}</td>
                        <td>${cert.issue_date}</td>
                        <td>${cert.expiry_date}</td>
                        <td>${cert.status}</td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error("Lỗi khi tải chứng chỉ:", err);
            document.getElementById("certList").innerHTML = "<tr><td colspan='7'>Lỗi tải dữ liệu.</td></tr>";
        });
}