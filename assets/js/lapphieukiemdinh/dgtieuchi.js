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

            // Gắn sự kiện change cho tất cả radio mới tạo
            const radios = criteriaList.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    hideErrorMessage();
                });
            });
        });
}

function checkAllSelected() {
    const criteriaList = document.getElementById('criteria-list');
    const radioNames = new Set();

    criteriaList.querySelectorAll('input[type="radio"]').forEach(radio => {
        radioNames.add(radio.name);
    });

    let allSelected = true;
    radioNames.forEach(name => {
        if (!criteriaList.querySelector(`input[name="${name}"]:checked`)) {
            allSelected = false;
        }
    });

    return allSelected;
}

function showErrorMessage(message) {
    const errorMessage = document.getElementById('error-message');
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
}

function hideErrorMessage() {
    const errorMessage = document.getElementById('error-message');
    errorMessage.style.display = 'none';
    errorMessage.textContent = '';
}

document.addEventListener("DOMContentLoaded", function () {
    const id_phieukiemdinh = document.getElementById("id_phieukiemdinh").value;

    if (id_phieukiemdinh) {
        fetch(`controllers/lapphieukiemdinh/get-certification_and_criteria_status.php?id_phieukiemdinh=${id_phieukiemdinh}`)
            .then(response => response.json())
            .then(data => {
                if (data.certification_id) {
                    loadCriteria(data.certification_id, data.criteria_status || {});
                } else {
                    console.error("Không lấy được certification_id");
                }
            });
    }

    const submitBtn = document.getElementById('save');

    submitBtn.addEventListener('click', function (event) {
        if (!checkAllSelected()) {
            event.preventDefault();
            showErrorMessage("Vui lòng chọn trạng thái cho tất cả tiêu chí trước khi lưu!");
            return false;
        }

        hideErrorMessage();
        alert('Lưu thành công!');
        // TODO: Thay bằng code submit form hoặc gửi ajax
        // document.getElementById('lapphieukiemdinhForm').submit();
    });
});
