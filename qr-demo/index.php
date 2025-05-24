<?php
    include "phpqrcode/qrlib.php";

    // Nội dung mã QR
    $data = 'https://3d81-2401-d800-9c7-c6f7-4d83-f66c-b546-5bb.ngrok-free.app/agripure_demo/index.php';

    // Xuất trực tiếp mã QR ra trình duyệt
    header('Content-Type: image/png');
    QRcode::png($data);
?>