<?php 
    $localname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agripure";

    $conn = mysqli_connect($localname,$username,$password,$dbname);
    if(!$conn)
    {
        echo "Lỗi ".mysqli_connect_error();
    }

?>