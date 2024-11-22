<?php
    require('sendmail_verify.php');

    function generateRandomString($length){
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = '';

        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength -1)];
        }
        return $randomString;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['email'];
        $code_reset = generateRandomString(6);
        $email_template = "
        <h2>Berikut Kode Reset Password</h2>
        <br/>
        <h4>$code_reset</h4>
        <br/>
        <p>Kode tersebut hanya berlaku satu kali, silahkan digunakan sebaik mungkin!</p>
        ";

        $query = "SELECT * FROM tbl_users WHERE email = '$email'";
        $sql = mysqli_query($conn, $query);
        $result = mysqli_fetch_assoc($sql);

        if($result){
            if($result['reset_pascode'] != '0'){
                $_SESSION['status'] = "danger, Kode sudah dikirimkan !";
                header('Location: lupapassword.php');
            } else {
                $query = "UPDATE tbl_users SET reset_pascode  = '$code_reset' WHERE email = '$email'";

                if($conn->query($query) === TRUE){
                    sendmail_verify($email, $code_reset, $email_template);
                    $_SESSION['status'] = "success, Kode Reset telah dikirim";
                    header('Location: input_resetcode.php');
                } else {
                    $_SESSION['status'] = "danger, Email belum terdaftar";
                    header('Location: index.php');
                }
            }
        } else {
            $_SESSION['status'] = "danger, Email belum terdaftar";
            header('Location: lupapassword.php');
        }
        $conn->close();
    }
?>