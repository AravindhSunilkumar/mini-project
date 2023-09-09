<?php
session_start();
include("connection.php");
$message1 = '';
$message2 = '';

if (isset($_SESSION["message1"])) {
	$message1 = $_SESSION["message1"];
	// Store the message content in a JavaScript variable
	$message1 = htmlspecialchars($message1, ENT_QUOTES);
}

if (isset($_SESSION["message2"])) {
	$message2 = $_SESSION["message2"];
	// Store the message content in a JavaScript variable
	$message2 = htmlspecialchars($message2, ENT_QUOTES);
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Login & Signup Form | CodingNepal</title>
	<!--<link rel="stylesheet" href="css/CSS.css">-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="./img/tooth.png" type="image/png" />
	<style>
		@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Poppins', sans-serif;
		}

		html,
		body {
			display: grid;
			background-image: url('./img/signup.jpg');
			place-items: center;
			background-size: cover;
			background-position: center center;
			background-repeat: no-repeat;
			animation: moveBackground 30s infinite;
		}

		@keyframes moveBackground {
			0% {
				background-position: center center;
			}

			50% {
				background-position: center top;
			}

			100% {
				background-position: center center;
			}
		}


		::selection {
			background: #fa4299;
			color: #fff;
		}

		.wrapper {
			overflow: hidden;
			max-width: 390px;
			margin-top: 98px;
			margin-right: -390px;
			background: transparent;
			padding: 30px;
			border-radius: 5px;
			box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
		}

		.wrapper .title-text {
			display: flex;
			width: 200%;
		}

		.wrapper .title {
			width: 50%;
			font-size: 35px;
			font-weight: 600;
			text-align: center;
			transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		}

		.wrapper .slide-controls {
			position: relative;
			display: flex;
			height: 50px;
			width: 100%;
			overflow: hidden;
			margin: 30px 0 10px 0;
			justify-content: space-between;
			border: 1px solid lightgrey;
			border-radius: 5px;
		}

		.slide-controls .slide {
			height: 100%;
			width: 100%;
			color: #fff;
			font-size: 18px;
			font-weight: 500;
			text-align: center;
			line-height: 48px;
			cursor: pointer;
			z-index: 1;
			transition: all 0.6s ease;
		}

		.slide-controls label.signup {
			color: #000;
		}

		.slide-controls .slider-tab {
			position: absolute;
			height: 100%;
			width: 50%;
			left: 0;
			z-index: 0;
			border-radius: 5px;
			background: -webkit-linear-gradient(left, #a445b2, #fa4299);
			transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		}

		input[type="radio"] {
			display: none;
		}

		#signup:checked~.slider-tab {
			left: 50%;
		}

		#signup:checked~label.signup {
			color: #fff;
			cursor: default;
			user-select: none;
		}

		#signup:checked~label.login {
			color: #000;
		}

		#login:checked~label.signup {
			color: #000;
		}

		#login:checked~label.login {
			cursor: default;
			user-select: none;
		}

		.wrapper .form-container {
			width: 100%;
			overflow: hidden;
		}

		.form-container .form-inner {
			display: flex;
			width: 200%;
		}

		.form-container .form-inner form {
			width: 50%;
			transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		}

		.form-inner form .field {
			height: 50px;
			width: 100%;
			margin-top: 20px;
		}

		.form-inner form .field input {
			height: 100%;
			width: 100%;
			outline: none;
			padding-left: 15px;
			border-radius: 5px;
			border: 1px solid lightgrey;
			border-bottom-width: 2px;
			font-size: 17px;
			transition: all 0.3s ease;
		}

		.form-inner form .field input:focus {
			border-color: #fc83bb;
			/* box-shadow: inset 0 0 3px #fb6aae; */
		}

		.form-inner form .field input::placeholder {
			color: #999;
			transition: all 0.3s ease;
		}

		form .field input:focus::placeholder {
			color: #b3b3b3;
		}

		.form-inner form .pass-link {
			margin-top: 5px;
		}

		.form-inner form .signup-link {
			text-align: center;
			margin-top: 30px;
		}

		.form-inner form .pass-link a,
		.form-inner form .signup-link a {
			color: #fa4299;
			text-decoration: none;
		}

		.form-inner form .pass-link a:hover,
		.form-inner form .signup-link a:hover {
			text-decoration: underline;
		}

		form .btn {
			height: 50px;
			width: 100%;
			border-radius: 5px;
			position: relative;
			overflow: hidden;
		}

		form .btn .btn-layer {
			height: 100%;
			width: 300%;
			position: absolute;
			left: -100%;
			background: -webkit-linear-gradient(right, #a445b2, #fa4299, #a445b2, #fa4299);
			border-radius: 5px;
			transition: all 0.4s ease;
			;
		}

		form .btn:hover .btn-layer {
			left: 0;
		}

		form .btn input[type="submit"] {
			height: 100%;
			width: 100%;
			z-index: 1;
			position: relative;
			background: none;
			border: none;
			color: #fff;
			padding-left: 0;
			border-radius: 5px;
			font-size: 20px;
			font-weight: 500;
			cursor: pointer;
		}

		/*validation*/
		/* Style for the modal */
		.modal {
			display: none;
			position: fixed;
			z-index: 1;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
		}

		/* Style for the modal content */
		.modal-content {
			background: linear-gradient(45deg, #c52db2, transparent);
			/*background-color: #fff;*/
			margin: 2% auto;
			padding: 20px;
			border: 1px solid #888;
			width: 56%;
			/*height: 30%;*/
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
		}

		/* Style for the close button */
		.close {
			color: #aaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
			cursor: pointer;
		}

		.close:hover,
		.close:focus {
			color: black;
			text-decoration: none;
			cursor: pointer;
		}
	</style>

</head>

<body>
	<!--<video src="./img/signupvideo.mp4"></video>-->
	<div class="wrapper">
		<!-- ... Your HTML code ... -->

		<div class="modal" id="message-modal">
			<div class="modal-content">
				<span class="close" onclick="closeModal()">&times;</span>
				<center><p id="message-content"></p></center>
			</div>
		</div>


		<!-- ... Your HTML code ... -->

		



		<div class="title-text">
			<div class="title login">Smile 32</div>
			<div class="title signup">Smile 32</div>
		</div>
		<div class="form-container">
			<div class="slide-controls">
				<input type="radio" name="slide" id="login" checked>
				<input type="radio" name="slide" id="signup">
				<label for="login" class="slide login">Login</label>
				<label for="signup" class="slide signup">Signup</label>
				<div class="slider-tab"></div>
			</div>
			<div class="form-inner">
				<form action="api.php" method="post" class="login">
					<div class="field">
						<input type="text" placeholder="User Name" name="name" required>
					</div>
					<div class="field">
						<input type="password" placeholder="Password" name="password" required>


					</div>
					<div class="pass-link"><a href="#"></a></div>
					<div class="field btn">
						<div class="btn-layer"></div>
						<input type="submit" name="login" value="Login">
					</div>
					<div class="signup-link">Not a member? <a href="">Signup now</a></div>
				</form>
				<form action="api.php" method="post" class="signup">
					<div class="field">
						<input type="text" placeholder="User Name" name="name" required>
					</div>
					<div class="field">
						<input type="text" placeholder="Email Address" name="email" required>
					</div>
					<div class="field">
						<input type="password" placeholder="Password" name="password" required>
					</div>
					<div class="field">
						<input type="password" placeholder="Confirm password" name="confpassword" required>
					</div>
					<div class="field btn">
						<div class="btn-layer"></div>
						<input type="submit" name="signup" value="Signup">
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		// JavaScript for modal execution
		console.log("JavaScript for modal execution");

		function closeModal() {
			var modal = document.getElementById('message-modal');
			modal.style.display = 'none';
		}

		// Check if there's a message to display
		var message1 = "<?php echo $message1; ?>";
		var message2 = "<?php echo $message2; ?>";

		if (message1 || message2) {
			document.getElementById('message-content').innerHTML = message1 || message2;
			document.getElementById('message-modal').style.display = 'block';
		}
	</script>


	<script>
		const loginText = document.querySelector(".title-text .login");
		const loginForm = document.querySelector("form.login");
		const loginBtn = document.querySelector("label.login");
		const signupBtn = document.querySelector("label.signup");
		const signupLink = document.querySelector("form .signup-link a");
		signupBtn.onclick = (() => {
			loginForm.style.marginLeft = "-50%";
			loginText.style.marginLeft = "-50%";
		});
		loginBtn.onclick = (() => {
			loginForm.style.marginLeft = "0%";
			loginText.style.marginLeft = "0%";
		});
		signupLink.onclick = (() => {
			signupBtn.click();
			return false;
		});
	</script>
	
	<?php
	unset($_SESSION['message1']);
	unset($_SESSION['message1']);
	?>

</body>

</html>