<?php 
    session_start();

    if(isset($_SESSION['unique_id'])){
        include_once("../../models/config.php");

        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);

        $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (incoming_msg_id = '{$incoming_id}' AND outgoing_msg_id = '{$outgoing_id}') 
                OR (incoming_msg_id = '{$outgoing_id}' AND outgoing_msg_id = '{$incoming_id}') 
                ORDER BY msg_id";

        $query = mysqli_query($conn, $sql);
        $output = "";

        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] === $outgoing_id){
                    $rounded  =(strlen($row['msg'])<100)?'rounded-pill':'rounded-5';
                    $output .= '<div class="d-flex justify-content-end mb-2">
                                <div class="p-2 px-3 bg-primary text-white '.$rounded.' shadow-sm">
                                    '.$row['msg'].'
                                </div>
                            </div>';
                } else {
                    $rounded  =(strlen($row['msg'])<100)?'rounded-pill':'rounded-5';
                    $output .= '<div class="d-flex align-items-start mb-2">
                                    <img src="assets/images/upload/'.$row['img'].'" alt="" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                    <div class="p-2 px-3 bg-light '.$rounded.' shadow-sm">
                                        '.$row['msg'].'
                                    </div>
                                </div>';
                }
            }
        } else {
            $output .= '<p class="text-center">Chưa có tin nhắn nào.</p>';
        }
        echo $output;
    } else {
        header("location: ../../index.php?page=login");
    }
?>
