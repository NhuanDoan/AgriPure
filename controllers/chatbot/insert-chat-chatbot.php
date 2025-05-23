<?php
    session_start(); // Bắt đầu session để lưu trữ lịch sử trò chuyện

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (isset($_SESSION['unique_id'])) {
        include_once("../../models/config.php"); // Đường dẫn đến file config.php của bạn

        // Lấy thông tin từ request POST
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = trim(mysqli_real_escape_string($conn, $_POST['message']));

        // Khởi tạo lịch sử trò chuyện trong session nếu chưa tồn tại
        if (!isset($_SESSION['conversation_history'])) {
            $_SESSION['conversation_history'] = [];
        }

        // Nếu tin nhắn không rỗng
        if (!empty($message)) {
            // --- 1. Lưu tin nhắn người dùng vào cơ sở dữ liệu ---
            $sql_user_msg = mysqli_prepare($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($sql_user_msg, "iis", $incoming_id, $outgoing_id, $message);
            mysqli_stmt_execute($sql_user_msg);
            mysqli_stmt_close($sql_user_msg); // Đóng statement

            // --- 2. Thêm tin nhắn người dùng vào lịch sử trong session ---
            $_SESSION['conversation_history'][] = ['role' => 'user', 'parts' => [['text' => $message]]];

            // --- 3. Lấy phản hồi từ chatbot Gemini, truyền toàn bộ lịch sử cuộc trò chuyện ---
            $bot_response = chatbotReply($message, $_SESSION['conversation_history']);

            // --- 4. Lưu tin nhắn chatbot vào cơ sở dữ liệu ---
            $sql_bot_msg = mysqli_prepare($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($sql_bot_msg, "iis", $outgoing_id, $incoming_id, $bot_response); // incoming_id của bot là outgoing_id của người dùng, outgoing_id của bot là incoming_id của người dùng
            mysqli_stmt_execute($sql_bot_msg);
            mysqli_stmt_close($sql_bot_msg); // Đóng statement

            // --- 5. Thêm tin nhắn bot vào lịch sử trong session ---
            $_SESSION['conversation_history'][] = ['role' => 'model', 'parts' => [['text' => $bot_response]]];

            // --- 6. Giới hạn độ dài lịch sử cuộc trò chuyện để tránh quá tải API và chi phí ---
            // Mỗi cặp (user + model) là 2 phần tử. Giới hạn 10 cặp = 20 phần tử.
            $max_history_length = 20;
            if (count($_SESSION['conversation_history']) > $max_history_length) {
                // Giữ lại N phần tử cuối cùng
                $_SESSION['conversation_history'] = array_slice($_SESSION['conversation_history'], -$max_history_length);
            }

            // Tùy chọn: trả về phản hồi cho client nếu cần (ví dụ: qua Ajax)
            // echo $bot_response;

        } else {
            // Tin nhắn rỗng, có thể gửi một phản hồi lỗi hoặc không làm gì
            // echo "Tin nhắn không được để trống.";
        }

    } else {
        // Nếu không có unique_id trong session, chuyển hướng về trang đăng nhập
        header("Location: ../../index.php?page=login");
        exit(); // Đảm bảo dừng script sau khi chuyển hướng
    }

    /**
     * Hàm gọi API Gemini để lấy phản hồi từ chatbot.
     * Bao gồm lịch sử cuộc trò chuyện để duy trì ngữ cảnh.
     *
     * @param string $message Tin nhắn hiện tại của người dùng.
     * @param array $conversation_history Lịch sử các tin nhắn trước đó (user và model).
     * @return string Phản hồi từ chatbot hoặc thông báo lỗi.
     */
    function chatbotReply($message, $conversation_history = []) {
        // **Cảnh báo quan trọng: KHÔNG ĐẶT API KEY TRỰC TIẾP TRONG MÃ CLIENT-SIDE HOẶC MÃ CÓ THỂ TRUY CẬP ĐƯỢC CÔNG KHAI!**
        // Thay thế bằng API key của bạn. Lý tưởng nhất, API key nên được lưu trữ trong biến môi trường
        // và gọi thông qua một proxy server-side riêng biệt.
        $api_key = 'AIzaSyAaUGInePFgFtFGvXrNxG6T0IDZg8LyMA0'; // <-- Thay thế bằng API key Gemini của bạn

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . urlencode($api_key);

        // Xây dựng nội dung yêu cầu gửi đến Gemini API
        $contents = [];

        // --- Lời nhắc hệ thống (System Instructions) để "huấn luyện" bot ---
        // Định nghĩa vai trò, phong cách và giới hạn của bot.
        // Đặt ở đầu tiên để bot hiểu ngữ cảnh chung.
        $contents[] = [
            "role" => "user",
            "parts" => [
                ["text" => "Bạn là một trợ lý chatbot thân thiện, hữu ích và chuyên về lĩnh vực nông sản sạch.
                            Bạn luôn trả lời một cách lịch sự, rõ ràng, và cung cấp thông tin chính xác về các sản phẩm, quy trình trồng trọt, chứng nhận an toàn, cách bảo quản và sử dụng nông sản.
                            Nếu câu hỏi không liên quan đến nông sản sạch, các sản phẩm của cửa hàng hoặc không phù hợp, hãy nhẹ nhàng từ chối và khuyến khích người dùng hỏi về chủ đề nông sản sạch hoặc các thông tin liên quan đến website.
                            Ví dụ:
                            Người dùng: Hôm nay thời tiết thế nào?
                            Bot: Xin lỗi, tôi chỉ có thể hỗ trợ các câu hỏi liên quan đến nông sản sạch và các sản phẩm của cửa hàng. Bạn có muốn tìm hiểu về nguồn gốc cà chua hữu cơ, cách bảo quản rau xanh tươi lâu, hay thông tin về chương trình khuyến mãi không?
                            Người dùng: Kể một câu chuyện cười.
                            Bot: Tôi không được lập trình để kể chuyện cười, nhưng tôi có thể cung cấp thông tin chi tiết về các loại nông sản sạch, hay giải đáp thắc mắc của bạn về quy trình đặt hàng. Bạn có câu hỏi nào không?"]
            ]
        ];
        // Nếu sử dụng "user" role cho system instruction, thường cần một phản hồi trống từ "model" để cân bằng cuộc trò chuyện.
        $contents[] = [
            // "role" => "model",
            // "parts" => [["text" => ""]]
        ];


        // --- Thêm lịch sử cuộc trò chuyện vào contents để duy trì ngữ cảnh ---
        // Mỗi phần tử trong $conversation_history đã có định dạng chuẩn ('role' và 'parts').
        foreach ($conversation_history as $item) {
            $contents[] = $item;
        }

        // --- Thêm tin nhắn hiện tại của người dùng vào cuối contents ---
        $contents[] = [
            "role" => "user",
            "parts" => [
                ["text" => $message]
            ]
        ];

        // Tạo payload JSON cho yêu cầu API
        $data = [
            "contents" => $contents,
            // Cấu hình an toàn để lọc nội dung không phù hợp (tùy chọn)
            "safetySettings" => [
                [
                    "category" => "HARM_CATEGORY_HARASSMENT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_HATE_SPEECH",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_DANGEROUS_CONTENT",
                    "threshold" => "BLOCK_NONE"
                ],
            ],
            // Cấu hình tạo sinh (tùy chọn)
            "generationConfig" => [
                "temperature" => 0.7, // Độ sáng tạo của câu trả lời (0.0 - 1.0)
                "maxOutputTokens" => 500, // Giới hạn độ dài câu trả lời
            ]
        ];

        $jsonData = json_encode($data);

        // --- Gửi yêu cầu đến Gemini API bằng cURL ---
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Trả về kết quả dưới dạng chuỗi
        curl_setopt($ch, CURLOPT_POST, true);         // Gửi yêu cầu POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Dữ liệu gửi đi
        curl_setopt($ch, CURLOPT_HTTPHEADER, [         // Thiết lập header
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Tắt kiểm tra SSL cho môi trường dev, KHÔNG NÊN DÙNG TRONG PRODUCTION

        $response = curl_exec($ch); // Thực thi cURL
        $error = curl_error($ch);    // Lấy lỗi (nếu có)
        curl_close($ch);             // Đóng cURL

        if ($error) {
            error_log("Lỗi cURL khi gọi Gemini API: " . $error);
            return "Xin lỗi, hiện tại tôi không thể kết nối đến dịch vụ AI. Vui lòng thử lại sau.";
        }

        $result = json_decode($response, true); // Giải mã phản hồi JSON

        // Kiểm tra và trả về phản hồi từ chatbot
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        } elseif (isset($result['error']['message'])) {
            error_log("Lỗi từ Gemini API: " . $result['error']['message']);
            return "Xin lỗi, đã có lỗi từ phía dịch vụ AI: " . $result['error']['message'];
        } else {
            // Ghi log phản hồi đầy đủ để debug nếu không có 'text' hoặc lỗi rõ ràng
            error_log("Không thể lấy nội dung từ phản hồi API. Phản hồi đầy đủ: " . print_r($result, true));
            return "Xin lỗi, tôi không hiểu rõ. Bạn có thể diễn đạt lại không?";
        }
    }

?>