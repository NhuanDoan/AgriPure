<?php 
    while($row = mysqli_fetch_assoc($query)){
        $sql2 = "SELECT * FROM messages WHERE 
        ((incoming_msg_id = {$row['unique_id']} AND outgoing_msg_id = '{$outgoing_id}') 
        OR (outgoing_msg_id = {$row['unique_id']} AND incoming_msg_id = '{$outgoing_id}')) 
        ORDER BY msg_id DESC LIMIT 1";


        $query2 = mysqli_query($conn,$sql2);
        $row2 = mysqli_fetch_assoc($query2);
        (mysqli_num_rows($query2)>0)?$result = $row2['msg'] : $result = "Không có tin nhắn nào cả";
        (strlen($result)>28)? $msg = substr($result,0,28).'...':$msg = $result;

        if(isset($row2['outgoing_msg_id'])){
            ($outgoing_id == $row2['outgoing_msg_id']) ? $you ="Bạn: ": $you="";
        } else {
            $you = "";
        }
        ($row['status'] == "Offline")? $offline = "offline": $offline="";
        ($outgoing_id == $row['unique_id'])?$hid_me = "hide":$hid_me = "";
        $output .= '<a href="index.php?page=chat&user_id='.$row['unique_id'].'" class="text-decoration-none">
           <div class="users">
                <div class="card px-2 my-2">
                    <div class="card-body text-center deltails row d-flex align-items-center">
                        <div class="col-3">
                            <img class="user-avatar rounded-circle" src="assets/images/upload/'.$row['img'].'" alt="card image" style="width: 50px; height: 50px; object-fit: cover;">
                        </div>
                       <div class="col-3">
                            <p class="card-title">'.$row['lname'].'</p>
                       </div>
                       <div class="col-4">
                            <p class="card-text">'.$you.$msg.'</p>
                       </div>
                       <div class="col-2">
                            <div class="status-dot '.$offline.' "><i class="fa-solid fa-circle"></i></div>
                       </div>
                    </div>
                    
                </div>
        </a>';
    }
?>