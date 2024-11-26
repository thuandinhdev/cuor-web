<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Nếu dùng Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['hidden_field'])) {
        die("Spam detected!");
    }
    $secretKey = "6LcoVooqAAAAAJ4Ym5saGmeKgVUj1aTcLuD0AiCI";
    $responseKey = $_POST['g-recaptcha-response'];

    if (empty($responseKey)) {
        header("Location: ../index.html");
    }

    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $responseKey
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ]
    ];

    $context = stream_context_create($options);
    $verifyResponse = file_get_contents($url, false, $context);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        header("Location: ../index.html");
    }

    $mail = new PHPMailer(true);
    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'thuandinhdev@gmail.com';
        $mail->Password   = 'lfozzfwqwsfvuvia';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Cấu hình người gửi và người nhận
        $mail->setFrom('cuor@cuortech.com', 'CUOR');
        $mail->addAddress($_POST['email']);

        // Cấu hình nội dung email
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = htmlspecialchars('CUOR website');

        // Lấy nội dung email từ file
        $email_user_body = file_get_contents('mailbody_user.txt');
        $email_admin_body = file_get_contents('mailbody_admin.txt');

        $now = new DateTime();
        $formattedTime = $now->format('Y-m-d H:i:s');

        // Thay thế nội dung trong mẫu email
        $placeholders = [
            '__@@__DATETIME__@@__' => $formattedTime,
            '__@@__first_name__@@__' => htmlspecialchars($_POST['first_name']),
            '__@@__last_name__@@__' => htmlspecialchars($_POST['last_name']),
            '__@@__email__@@__' => htmlspecialchars($_POST['email']),
            '__@@__phone__@@__' => htmlspecialchars($_POST['phone']),
            '__@@__subject__@@__' => htmlspecialchars($_POST['subject']),
            '__@@__message__@@__' => htmlspecialchars($_POST['message']),
        ];

        foreach ($placeholders as $key => $value) {
            $email_user_body = str_replace($key, $value, $email_user_body);
            $email_admin_body = str_replace($key, $value, $email_admin_body);
        }

        // Thiết lập nội dung email cho người dùng
        $mail->Body    = $email_user_body;
        $mail->AltBody = strip_tags($email_user_body); // Nội dung không chứa HTML

        // Gửi email cho người dùng
        $mail->send();

        // Gửi email cho admin
        $mail->clearAddresses(); // Xóa danh sách người nhận cũ
        $mail->addAddress('cuor@cuortech.com'); // Gửi đến admin
        $mail->Body    = $email_admin_body;
        $mail->AltBody = strip_tags($email_admin_body);

        $mail->send();


        header("Location: ../index.html");
        exit();

    } catch (Exception $e) {
        echo "Erro: {$mail->ErrorInfo}";
    }
}
?>
