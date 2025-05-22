<?php 
    while($row = mysqli_fetch_assoc($result)){
        $output .= '<a href="index.php?page=chitietphieukiemdinh&id_phieukiemdinh=' . $row['id'] . '" class="text-decoration-none">
           <div class="users">
                <div class="card px-2 my-2">
                    <div class="card-body text-center details row d-flex align-items-center">
                         <div class="col-3">
                            <p class="card-title">' . htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8') . '</p>
                         </div>
                         <div class="col-3">
                            <p class="card-title">' . htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') . '</p>
                         </div>
                         <div class="col-3">
                            <p class="card-title">' . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . '</p>
                         </div>
                         <div class="col-3">
                           <p class="card-title">' . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . '</p>
                         </div>
                    </div>
                </div>
            </div>
        </a>';
    }
?>
