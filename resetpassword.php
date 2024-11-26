<!DOCTYPE html>
<?php
    session_start();
    if(isset($_SESSION['email'])){
      header('Location: dashboard.php');
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <link rel="icon" href="profile.jpg" type="image/x-icon">
    <!-- Font Awesome Cdn link -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- Link untuk CSS Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <!-- Script untuk Bootstrap -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="style.css">
    <script>
      function validateForm(){
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        if(password !== confirmPassword){
          alert("Password dan Konfirmasi Password tidak sesuai!");
          return false;
        }
        return true;
      }
    </script>
</head>
<body>
<div class="container">
	<div class="screen">
		<div class="screen__content">
			<form class="login" onsubmit="return validateForm()" method="post" action="proses_reset.php">
                <h3 class="fw-bold text-center">Reset Password</h3>
                <?php
                    if(isset($_SESSION['log'])){
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal, </strong><?php echo $_SESSION['log']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        session_unset();
                    }
                ?>
				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" name="password" id="password" class="login__input" placeholder="Password" required>
				</div>
				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" name="confirm_password" id="confirm_password" class="login__input" placeholder="Konfirmasi Password" required>
				</div>
				<button class="button login__submit">
					<span class="button__text">Atur Ulang</span>
					<i class="button__icon fas fa-chevron-right"></i>
				</button>
			</form>
		</div>
		<div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
			<span class="screen__background__shape screen__background__shape3"></span>		
			<span class="screen__background__shape screen__background__shape2"></span>
			<span class="screen__background__shape screen__background__shape1"></span>
		</div>		
	</div>
</div>
</body>
</html>