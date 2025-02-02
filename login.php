<!DOCTYPE html>
<html lang="en">
<head>
	<title>เข้าสู่ระบบ</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	<div class="limiter" align=center>
		<div class="container-login100" align=center>
			<div class="wrap-login100" align=center>				
					<span class="login100-form-title">
						เข้าสู่ระบบ
					</span>
					<form class="login100-form validate-form" action="/MobileDim/login/process-login.php"method="POST" align=center>
					<div class="wrap-input100 validate-input" data-validate = "input email">
						<input class="input100" type="email" name="email_account" placeholder="email" required>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password_account" placeholder="Password" required>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" name="login_user" type="submit">เข้าสู่ระบบ</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							ลืมรหัสผ่าน
						</span>
						<a class="txt2" href="form-register.php">
							ชื่อผู้ใช้ / รหัสผ่าน?
						</a>
					</div>

					<!--<div class="text-center p-t-136">
						<a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>-->
					</form>
			</div>
		</div>
	</div>
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>