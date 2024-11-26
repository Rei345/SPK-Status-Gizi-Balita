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
    <title>Lupa password</title>
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
</head>
<body>
<div class="container">
	<div class="screen">
		<div class="screen__content">
			<form class="login" method="post" action="resend_verify.php">
                <h3 class="fw-bold text-center">Verifikasi Email</h3>   
				<div class="login__field">
					<i class="login__icon fa fa-envelope"></i>
					<input type="email" name="email" class="login__input" placeholder="Email" required>
				</div>			
				<button class="button login__submit">
					<span class="button__text">Kirim Ulang</span>
					<i class="button__icon fas fa-chevron-right"></i>
				</button>
                <p class="mt-2 fw-bold">Sudah punya akun? <a href="index.php">Masuk</a></p> 
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