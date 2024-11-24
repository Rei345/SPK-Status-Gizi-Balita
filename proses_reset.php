<?php
    require('koneksi.php');
    session_start();

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $code = $_SESSION['code'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "SELECT * FROM tbl_users WHERE reset_pascode = '$code'";
        $sql = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($sql);

        if($result){
            $query = "UPDATE tbl_users SET password = '$password', reset_pascode = '0' WHERE reset_pascode = '$code'";
            if($conn->query($query) === TRUE){
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['status'] = "success, sandi telah diatur ulang !";
                header('Location: index.php');
            } else {
                $_SESSION['status'] = "danger, proces gagal !";
                header('Location: index.php');
            }
        } else {
            $_SESSION['status'] = "danger, proces gagal !";
            header('Location: lupapassword.php');
        }
        $conn->close();
    }
?>