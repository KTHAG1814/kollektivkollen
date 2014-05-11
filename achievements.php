<?php
require_once("includes/init.php");
require_once("includes/functions.php");
if (isset($_POST['submit-login'])) {
	$stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
	$stmt->execute(array($_POST['username'], md5($_POST['password'])));
	$user = $stmt->fetch();
	if ($user !== false) {
		$_SESSION['user_id'] = $user["id"];
	}
}
?><html>
	<head>
		<title>Kollektivkollen - Prestationer</title>
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
				<h1>Prestationer</h1>
				<p>För att samla prestationer måste du använda Stockholms kollektivtrafik. Kollektivkollen mäter resor med hjälp av
				de platser där du använder ditt SLAccess-kort. Därför måste du hålla det över läsaren både när du passerar in och ut genom spärren.</p>
				<p>Nedan syns en lista över prestationer som du uppnått. Åk mer kollektivt så kommer du få fler prestationer!</p>
				<p style="text-align: center;margin-bottom: 0;margin-top: 20px;">Totalt har du samlat prestationer för</p>
				<p style="text-align: center;font-size: 3em;margin: 0;">55</p>
				<p style="text-align: center;margin-top: 0;margin-bottom: 20px;">poäng!</p>
				<ul style="margin-left: 10px;">
					<li style="list-style-type: disc;">Åkt alla tunnelbanelinjer! (15p)</li>
					<li style="list-style-type: disc;">Åkt buss 73 hela vägen från Ropsten till Tomteboda! (10p)</li>
					<li style="list-style-type: disc;">Rest med SL:s alla olika färdmedel! (20p)</li>
					<li style="list-style-type: disc;">Gjort 10 resor på samma dag! (10p)</li>
				</ul>
			</div>
		</div>
		</div>
	</body>
</html>