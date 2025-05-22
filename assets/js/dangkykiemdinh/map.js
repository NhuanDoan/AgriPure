// Khởi tạo bản đồ
var map = L.map('map').setView([10.762622, 106.660172], 13); // Vị trí mặc định TP.HCM

// Thêm tile layer từ OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Thêm marker rỗng
var marker = L.marker([10.762622, 106.660172], {draggable: true}).addTo(map)
    .bindPopup("Nhấp vào bản đồ để chọn vị trí")
    .openPopup();

// Hàm lấy địa chỉ từ tọa độ
function getAddress(lat, lng) {
    var url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=vi`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                let address = data.display_name.replace(/\d{5,6},?\s?/g, ""); // Loại bỏ mã bưu chính (5-6 chữ số)
                document.getElementById('address').value = address;
            } else {
                document.getElementById('address').value = "Không tìm thấy địa chỉ";
            }
        })
        .catch(error => {
            console.error("Lỗi khi lấy địa chỉ: ", error);
            document.getElementById('address').value = "Lỗi khi lấy địa chỉ";
        });
}

// Hàm lấy vị trí hiện tại
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                // Cập nhật marker và bản đồ
                marker.setLatLng([lat, lng])
                    .bindPopup("Vị trí hiện tại của bạn")
                    .openPopup();
                map.setView([lat, lng], 15);

                // Cập nhật thông tin tọa độ
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Lấy địa chỉ từ tọa độ
                getAddress(lat, lng);
            },
            function(error) {
                alert("Không thể lấy vị trí của bạn. Hãy kiểm tra cài đặt quyền riêng tư!");
            }
        );
    } else {
        alert("Trình duyệt của bạn không hỗ trợ lấy vị trí!");
    }
}

// Sự kiện chọn vị trí trên bản đồ
map.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    marker.setLatLng([lat, lng])
        .bindPopup("Vị trí bạn chọn")
        .openPopup();

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    getAddress(lat, lng);
});
