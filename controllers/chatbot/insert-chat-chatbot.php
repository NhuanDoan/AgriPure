<?php 
    session_start();
    if (isset($_SESSION['unique_id'])) {
        include_once("../../models/config.php");
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = trim(mysqli_real_escape_string($conn, $_POST['message']));

        if (!empty($message)) {
            // Lưu tin nhắn người dùng
            $sql = mysqli_prepare($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($sql, "iis", $incoming_id, $outgoing_id, $message);
            mysqli_stmt_execute($sql);
            $bot_response = chatbotReply($message);
            $sql = mysqli_prepare($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($sql, "iis", $outgoing_id, $incoming_id, $bot_response);
            mysqli_stmt_execute($sql);
        }

    } else {
        header("Location: ../../index.php?page=login");
        exit();
    }

    function chatbotReply($message) {
        $api_key = 'AIzaSyAaUGInePFgFtFGvXrNxG6T0IDZg8LyMA0'; // Thay thế bằng API key của bạn
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . urlencode($api_key);

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $message]
                    ]
                ]
            ]
        ];

        $jsonData = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return "Lỗi cURL: " . $error;
        }

        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        } else {
            return "Không thể lấy nội dung từ phản hồi API.";
        }
    }

?>
