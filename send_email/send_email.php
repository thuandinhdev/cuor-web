<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Nếu dùng Composer

// Thêm tệp PHPMailer nếu không dùng Composer
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'thuandinh@cuortech.com';
        $mail->Password   = 'ezhogbjxczdiryad';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Cấu hình người gửi và người nhận
        $mail->setFrom('thuandinh@cuortech.com', 'Your Name');
        $mail->addAddress($_POST['email']); // Gửi đến email của người dùng
        $mail->addAddress('thuandinh@cuortech.com'); // Gửi đến admin

        // Cấu hình nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'emai được gửi từ ';

        // Lấy nội dung email từ file
        $email_user_body = file_get_contents('mailbody_user.txt');
        $email_admin_body = file_get_contents('mailbody_admin.txt');

        $now = new DateTime();
        $formattedTime = $now->format('Y年n月j日 H:i:s');

        // Thay thế nội dung trong mẫu email
        $placeholders = [
            '__@@__DATETIME__@@__' => $formattedTime,
            '__@@__name__@@__' => htmlspecialchars($_POST['name']),
            '__@@__email__@@__' => htmlspecialchars($_POST['email']),
            '__@@__phone__@@__' => htmlspecialchars($_POST['phone']),
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
        $mail->addAddress('thuandinh@cuortech.com');
        $mail->Body    = $email_admin_body;
        $mail->AltBody = strip_tags($email_admin_body);

        $mail->send();

        // Chuyển hướng sau khi gửi email thành công
        header("Location: ../landing-cuor.html");
        exit();

    } catch (Exception $e) {
        echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
    }
}
?>
