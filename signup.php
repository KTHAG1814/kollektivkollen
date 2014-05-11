<?php

require_once("includes/init.php");
require_once("includes/functions.php");

$pageName = "Registrera dig";

if (isset($_POST['submit-register'])) {
	$stmt = $db->prepare("INSERT INTO users SET sl_access = ?, username = ?, password = ?");
	$stmt->execute(array($_POST['access'], $_POST['username'], md5($_POST['password'])));
	$_SESSION['user_id'] = $db->lastInsertId();
	
	header("location:trips.php");
	exit;
}

?><html>
	<head>
		<?php include("includes/head.php"); ?>
  		<script type="text/javascript">
  		function validateForm() {
  			return true;
  		}
  		</script>
	</head>
	<body>
		<?php include("includes/top_bar.php"); ?>
		<div class="container">
		<div class="search">
			<div id="top-content">
				<form id="form" onsubmit="return validateForm();" action="signup.php" method="post">
					<h1>Registrera dig</h1>
					<div class="place-box">
						<span>SLAccess-kortnummer:</span><br />
						<input type="text" class="search-text-box" name="access" id="access" value="<?php echo isset($_POST['access']) ? $_POST['access'] : "NNNNNN-NNNNNN"; ?>" onfocus="clearField(this, 'NNNNNN-NNNNNN')" onFocusOut="resetField(this, 'NNNNNN-NNNNNN')">
					</div>
					<div class="place-box">
						<span>Användarnamn:</span><br />
						<input type="text" class="search-text-box" name="username" id="username">
					</div>
					<div class="place-box">
						<span>Lösenord:</span><br />
						<input type="password" class="search-text-box" name="password" id="password">
					</div>
					<div class="place-box">
						<span>Upprepa lösenord:</span><br />
						<input type="password" class="search-text-box" name="retype-password" id="retype-password">
					</div>
					<div class="search-button-box">
						<input onsubmit="return validateForm();" type="submit" class="submit-button" name="submit-register" id="register-button" value="Registrera dig">
					</div>
				</form>
			</div>
		</div>
		</div>
	</body>
</html>