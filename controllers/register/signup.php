<?php 
    session_start(); 
    include_once("../../models/config.php");
     
    
    $role = isset($_POST['role']) ? (int)$_POST['role'] : 4; // mặc định là khách hàng

    $farm_name = '';
    $farm_address = '';

    if ($role === 2) {
        $farm_name = mysqli_real_escape_string($conn, $_POST['farm_name']);
        $farm_address = mysqli_real_escape_string($conn, $_POST['farm_address']);
    }


    $fname = mysqli_real_escape_string($conn,$_POST['fname']);
    $lname = mysqli_real_escape_string($conn,$_POST['lname']);
    $sdt = mysqli_real_escape_string($conn,$_POST['sdt']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    if(!empty($fname) && !empty($lname) && !empty($sdt) && !empty($password))
    {   
        $pattern = "/^(03|05|07|08|09)[0-9]{8}$/";
        if(filter_var($sdt, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => $pattern]]))
        {
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE sdt = '{$sdt}'");
            if(mysqli_num_rows($sql)>0){
                echo ''.$sdt.' đã được đăng ký!!';
            } else {
                if(isset($_FILES['image'])){
                    $img_name = $_FILES['image']['name'];
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];

                    $img_explode = explode('.',$img_name);
                    $img_ext = end($img_explode);

                    $extensions = ["jpeg","png","jpg"];

                    if(in_array($img_ext,$extensions) === true)
                    {
                        $type = ["image/jpeg","image/jpg","image/png"];
                        if(in_array($img_type,$type) === true){
                            $time = time();
                            $new_image_name = $time.$img_name;
                            if(move_uploaded_file($tmp_name,"../../assets/images/upload/".$new_image_name)){
                                $ran_id = rand(time(), 1000000000);
                                $status = "Online";
                                $encrypt_pass = md5($password);
                                $stmt = $conn->prepare("INSERT INTO users (unique_id, fname, lname, sdt, password, img, status, role) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                                $stmt->bind_param("sssssssi", $ran_id, $fname, $lname, $sdt, $encrypt_pass, $new_image_name, $status, $role)   ; 
                                if($stmt->execute()){
                                    $select_sql2 = mysqli_query($conn,"SELECT * FROM users WHERE sdt ='{$sdt}'");
                                    if(mysqli_num_rows($select_sql2)>0){
                                        $result = mysqli_fetch_assoc($select_sql2);
                                        $user_id = $result['user_id'];
                                        if($role === 2){
                                            $stmtFarm = $conn->prepare("INSERT INTO nongtrai (id_user, tennongtrai, diachi) VALUES (?, ?, ?)");
                                            $stmtFarm->bind_param("iss", $user_id, $farm_name, $farm_address);
                                            $stmtFarm->execute();
                                            $stmtFarm->close();
                                        }
                                        echo 'thành công';
                                    } else {
                                        echo 'Số điện thoại không tồn tại!!!';
                                    }
                                } else 
                                {
                                    echo "Lỗi: " . $stmt->error;
                                }
                                $stmt->close();
                            } else {
                                echo 'Có vài lỗi xảy ra. Vui lòng thử lại!!!';
                            }
                        }
                    } else {
                        echo 'Vui lòng chọn hình ảnh có định dạng: jpg, png, jpeg';
                    }
                }
            }
        } else {
            echo ''.$sdt.' không hợp lệ!!(Số điện thoại phải là
            10 chữ số, bắt đầu bằng 03, 05, 07, 08, 09)';
        }
    } else {
        echo  'Nhập đầy đủ thông tin!!!!';
    }

?>
