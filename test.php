<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'reinhardsitompul03@gmail.com';                     //SMTP username
    $mail->Password   = 'grylnyqzhtbllwge';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'Reinhard');
    $mail->addAddress('mlbbprogramers@gmail.com', 'User');     //Add a recipient
    $mail->addReplyTo('no-reply@gmail.com', 'Information');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $email_template = "
        <h2>Kamu telah melakukan pendaftaran akun</h2>
        <h4>Verifikasi email kamu agar dapat login, klik tautan berikut !</h4>
        <a href='#'>[ Klik Disini ]</a>
        ";
    $mail->Subject = 'Verifikasi Email';
    $mail->Body    = $email_template;

    $mail->send();
    echo 'Email Terkirim';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}