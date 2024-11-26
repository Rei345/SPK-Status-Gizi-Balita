<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require('koneksi.php');
    session_start();
    
    function sendmail_verify($email, $verify_token, $email_template){
        //Load Composer's autoloader
        require 'vendor/autoload.php';
        $mail = new PHPMailer(true);

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
        $mail->addAddress($email, 'User');     //Add a recipient
        $mail->addReplyTo('no-reply@gmail.com', 'Information');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Verifikasi Email';
        $mail->Body    = $email_template;

        $mail->send();
        echo 'Email Terkirim';                                                                                                                                      
    }
?>