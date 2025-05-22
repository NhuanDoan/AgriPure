<?php 
    session_start();
    include_once("../../models/config.php");

    $outgoing_id = $_SESSION['unique_id'];
    $role = intval($_SESSION['role']);
    $searchTerm = isset($_POST['searchTerm']) ? mysqli_real_escape_string($conn, $_POST['searchTerm']) : "";

    // Truy vấn lấy danh sách unique_id người dùng đã từng nhắn tin
    $subquery = "
        SELECT DISTINCT 
            IF(incoming_msg_id = '{$outgoing_id}', outgoing_msg_id, incoming_msg_id) AS user_id
        FROM messages
        WHERE incoming_msg_id = '{$outgoing_id}' OR outgoing_msg_id = '{$outgoing_id}'
    ";

    // Nếu không tìm kiếm
    if ($searchTerm === "") {
        $sql = "
            SELECT * FROM users 
            WHERE unique_id IN ($subquery) AND unique_id != '{$outgoing_id}'
        ";
    } else {
        $sql = "
            SELECT * FROM users 
            WHERE unique_id IN ($subquery) 
            AND unique_id != '{$outgoing_id}' 
            AND lname LIKE '%{$searchTerm}%'
        ";
    }

    $output = "";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        include_once("data-search-chat.php");
    } else {
        $output .= "Không có người dùng nào phù hợp!";
    }

    echo $output;
?>
