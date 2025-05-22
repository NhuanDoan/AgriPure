<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once("../../models/config.php");
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = trim(mysqli_real_escape_string($conn, $_POST['message']));

        if(!empty($message)){
            $sql = mysqli_prepare($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($sql, "iis", $incoming_id, $outgoing_id, $message);
            mysqli_stmt_execute($sql);
        }
    } else {
        header("Location: ../../index.php?page=login");
        exit();
    }
?>