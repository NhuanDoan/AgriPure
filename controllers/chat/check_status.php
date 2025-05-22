<?php
    include_once("../../models/config.php");

    if (isset($_POST['user_id'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $sql = mysqli_query($conn, "SELECT status FROM users WHERE unique_id='{$user_id}'");
        if ($row = mysqli_fetch_assoc($sql)) {
            echo $row['status']; // Trả về 'Online' hoặc 'Offline now'
        }
    }
?>
