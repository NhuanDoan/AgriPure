function loadCriteria(certificationId, selectedCriteriaStatuses = {}) {
    fetch(`controllers/lapphieukiemdinh/get-criteria.php?certification_id=${certificationId}`)
        .then(response => response.json())
        .then(data => {
            const criteriaList = document.getElementById('criteria-list');
            criteriaList.innerHTML = '';
            data.forEach(criteria => {
                const div = document.createElement('div');
                div.classList.add('mb-3');

                const status = selectedCriteriaStatuses[criteria.code] || "";

                div.innerHTML = `
                    <label class="form-label"><strong>${criteria.description}</strong></label>
                    <div>
                        <input type="radio" name="criteria_status[${criteria.code}]" value="Đạt" 
                            ${status === "Đạt" ? "checked" : ""}> Đạt
                        <input type="radio" name="criteria_status[${criteria.code}]" value="Không đạt"
                            ${status === "Không đạt" ? "checked" : ""}> Không đạt
                    </div>
                `;
                criteriaList.appendChild(div);
            });
        });
}

document.addEventListener("DOMContentLoaded", function () {
    const id_phieukiemdinh = document.getElementById("id_phieukiemdinh").value;

    if (id_phieukiemdinh) {
        fetch(`controllers/lapphieukiemdinh/get-certification_and_criteria_status.php?id_phieukiemdinh=${id_phieukiemdinh}`)
            .then(response => response.json())
            .then(data => {
                if (data.certification_id) {
                    // Gọi hàm load với trạng thái tiêu chí đã lưu
                    loadCriteria(data.certification_id, data.criteria_status || {});
                } else {
                    console.error("Không lấy được certification_id");
                }
            });
    }
});
