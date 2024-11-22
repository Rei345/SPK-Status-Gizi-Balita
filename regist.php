<?php
    require('sendmail_verify.php');

    // Logic Progarm untuk regist
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $confirm_password = $_POST['confirm_password'];
        
        $query = "SELECT * FROM tbl_users WHERE email = '$email'";
        $sql = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($sql);
        
        $verify_token = md5(rand());        //
        $email_template = "
            <h2>Kamu telah melakukan pendaftaran akun</h2>
            <h4>Verifikasi email kamu agar dapat login, klik tautan berikut !</h4>
            <a href='http://localhost/spk_gizi_balita/verify_email.php?token=$verify_token'>[ Klik Disini ]</a>
        ";

        if($result){
            $_SESSION['log'] = "email sudah digunakan !";
            header('Location: daftar.php');
        } else {
            $query = "INSERT INTO tbl_users(email, username, password, verify_token, verify_status, reset_pascode) VALUES ('$email', '$username', '$password', '$verify_token', '0', '0')";

            if($conn->query($query) === TRUE){
                sendmail_verify($email, $verify_token, $email_template);        //
                $_SESSION['status'] = "warning, silahkan cek email anda, <a href='verify_ulang.php'> Tidak terima email?</a>";
                header('Location: index.php');
            }
        }
        $conn->close();
    }
?>