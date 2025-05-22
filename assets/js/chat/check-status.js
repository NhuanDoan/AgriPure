const statusText = document.getElementById("status-text");
const incomingId = document.querySelector(".incoming_id").value;

// Gọi hàm mỗi 5 giây
setInterval(() => {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "controllers/chat/check_status.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      const newStatus = xhr.responseText.trim();
      statusText.textContent = newStatus;
      statusText.className = newStatus === "Online" ? "text-success" : "text-danger";
    }
  };
  xhr.send("user_id=" + incomingId);
}, 5000); // 5 giây cập nhật 1 lần