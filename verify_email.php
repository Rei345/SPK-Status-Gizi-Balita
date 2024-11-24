<?php
    require('koneksi.php');
    session_start();

    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $verify_query = "SELECT verify_token, verify_status FROM tbl_users WHERE verify_token = '$token' LIMIT 1";
        $verify_sql = mysqli_query($conn, $verify_query);
        $result = mysqli_fetch_assoc($verify_sql);

        if($result){
            if($result['verify_status'] == '0'){
                $clicked_token = $result['verify_token'];
                $update_query = "UPDATE tbl_users SET verify_status = '1' WHERE verify_token = '$clicked_token'";
                $update_sql = mysqli_query($conn, $update_query);
                if($update_sql){
                    $_SESSION['status'] = "success, email berhasil diverifikasi !";
                    header('Location: index.php');
                }
            } else {
                $_SESSION['status'] = "success, email sudah diverifikasi sebelumnya !";
                header('Location: index.php');
            }
        } else {
            $_SESSION['status'] = "danger, Token tidak berlaku !";
            header('Location: index.php');
        }
    } else {
        $_SESSION['status'] = "danger, Token tidak berlaku !";
        header('Location: index.php');
    }
?>