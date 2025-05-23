let map, marker;

// Hàm khởi tạo bản đồ
function initMap() {
    if (map) return; // Đã khởi tạo rồi thì không làm lại

    map = L.map('map').setView([10.762622, 106.660172], 13); // Mặc định là TP.HCM

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    marker = L.marker([10.762622, 106.660172], {draggable: true}).addTo(map)
        .bindPopup("Nhấp vào bản đồ để chọn vị trí")
        .openPopup();

    // Sự kiện click để chọn vị trí
    map.on('click', function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        marker.setLatLng([lat, lng])
            .bindPopup("Vị trí bạn chọn")
            .openPopup();

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        getAddress(lat, lng);
    });
}

// Lấy địa chỉ từ tọa độ
function getAddress(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=vi`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                const address = data.display_name.replace(/\d{5,6},?\s?/g, "");
                document.getElementById('address').value = address;
            } else {
                document.getElementById('address').value = "Không tìm thấy địa chỉ";
            }
        })
        .catch(error => {
            console.error("Lỗi khi lấy địa chỉ:", error);
            document.getElementById('address').value = "Lỗi khi lấy địa chỉ";
        });
}

// Lấy vị trí hiện tại
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                marker.setLatLng([lat, lng])
                    .bindPopup("Vị trí hiện tại của bạn")
                    .openPopup();
                map.setView([lat, lng], 15);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                getAddress(lat, lng);
            },
            function (error) {
                console.log('Lỗi lấy vị trí:', error);
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("Người dùng từ chối truy cập vị trí.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Thông tin vị trí không khả dụng. Vui lòng thử lại hoặc kiểm tra thiết bị.");
                        break;
                    case error.TIMEOUT:
                        alert("Yêu cầu lấy vị trí hết thời gian. Vui lòng thử lại.");
                        break;
                    default:
                        alert("Không thể lấy vị trí của bạn. Hãy kiểm tra quyền truy cập vị trí!");
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 1000,
                maximumAge: 0
            }
        );
    } else {
        alert("Trình duyệt không hỗ trợ Geolocation.");
    }
}

