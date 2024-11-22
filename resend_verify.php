<?php
    require('sendmail_verify.php');

    // Logic Progarm untuk regist
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $confirm_password = $_POST['confirm_password'];

        $verify_token = md5(rand());        //
        $email_template = "
            <h2>Kamu telah melakukan pendaftaran akun</h2>
            <h4>Verifikasi email kamu agar dapat login, klik tautan berikut !</h4>
            <a href='http://localhost/spk_gizi_balita/verify_email.php?token=$verify_token'>[ Klik Disini ]</a>
        ";

        $query = "SELECT * FROM tbl_users WHERE email = '$email'";
        $sql = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($sql);

        if($result){
            if($result['verify_status'] == 1){
                $_SESSION['status'] = "success, email sudah diverifikasi!";
                header('Location: index.php');
            } else{
                $query = "UPDATE tbl_users SET verify_token = '$verify_token' WHERE email = '$email'";
    
                if($conn->query($query) === TRUE){
                    sendmail_verify($email, $verify_token, $email_template);        //
                    $_SESSION['status'] = "success, link verifikasi telah dikirimkan";
                    header('Location: index.php');
                }
            }
        } else {
            $_SESSION['log'] = "email belum didaftarkan !";
            header('Location: daftar.php');
        }
        $conn->close();
    }
?>