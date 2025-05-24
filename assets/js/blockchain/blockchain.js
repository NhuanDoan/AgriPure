let map, marker;

function initMap(lat = 10.762622, lng = 106.660172) {
    if (map) return;

    map = L.map('map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker([lat, lng], { draggable: false }).addTo(map)
        .bindPopup("Vị trí hiện tại của bạn")
        .openPopup();
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                if (!map) {
                    initMap(lat, lng);
                } else {
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng])
                        .bindPopup("Vị trí hiện tại của bạn")
                        .openPopup();
                }

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                getAddress(lat, lng);
            },
            (err) => {
                alert("Lỗi lấy vị trí: " + err.message);
                if (!map) initMap();
            }
        );
    } else {
        alert("Trình duyệt không hỗ trợ Geolocation.");
        if (!map) initMap();
    }
}

function getAddress(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=vi`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const address = data.display_name?.replace(/\d{5,6},?\s?/g, "") || "Không tìm thấy địa chỉ";
            document.getElementById('address').value = address;
        })
        .catch(() => {
            document.getElementById('address').value = "Lỗi khi lấy địa chỉ";
        });
}

document.addEventListener('DOMContentLoaded', function () {
    getCurrentLocation();

    const form = document.getElementById('blockForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('controllers/blockchain/add-block.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message || 'Đã thêm block mới!');
                form.reset();
                getCurrentLocation();
            } else {
                alert('Có lỗi: ' + (data.message || 'Không xác định'));
            }
        })
        .catch(err => {
            alert('Gửi form thất bại: ' + err.message);
        });
    });
});