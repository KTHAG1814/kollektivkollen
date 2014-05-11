<?php
require_once("includes/init.php");
require_once("includes/functions.php");
if (isset($_POST['submit-login'])) {
	$stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
	$stmt->execute(array($_POST['username'], md5($_POST['password'])));
	$user = $stmt->fetch();
	if ($user !== false) {
		$_SESSION['user_id'] = $user["id"];
		header("location:trips.php");
	}
}
?><html>
	<head>
		<title>Kollektivkollen - registrera dig</title>
		<meta charset="utf-8">
		<meta name="viewport" content="target-densitydpi=device-dpi, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="styles/style.css" media="all">
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="js/onclick.js"></script>
  		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  		<script type="text/javascript" src="js/jquery.js"></script>
  		<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
  		<script type="text/javascript" src="js/onclick.js"></script>
  		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
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
				<form id="form" onsubmit="return validateForm();" action="signin.php" method="post">
					<h1>Logga in</h1>
					<div class="place-box">
						<span>Användarnamn:</span><br />
						<input type="text" class="search-text-box" name="username" id="username">
					</div>
					<div class="place-box">
						<span>Lösenord:</span><br />
						<input type="password" class="search-text-box" name="password" id="password">
					</div>
					<div class="search-button-box">
						<input onsubmit="return validateForm();" type="submit" class="submit-button" name="submit-login" id="login-button" value="Logga in">
					</div>
				</form>
			</div>
		</div>
		</div>
	</body>
</html>