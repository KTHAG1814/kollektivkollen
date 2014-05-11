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
		<title>Kollektivkollen - dina resor</title>
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
				<h1>Dina resor</h1>
					<p>Nedan syns det antal resor du gjort mellan två platser med ditt SLAccess-kort. <span style="color: #923;">Notera att funktionen än så länge endast genererar mockup-data.</span></p>
					<table>
						<tr>
							<th>Antal</th>
							<th>Från</th>
							<th>Till</th>
							<th>Total sparad CO<sub>2</sub> (kg)</th>
						</tr>
						<?php
						// TODO Add user_id
						$stmt = $db->prepare("SELECT COUNT(trips.id) AS number, departure_time, arrival_time, toS.name AS to_s, fromS.name AS from_s FROM trips LEFT JOIN stations AS fromS ON fromS.sl_id = trips.from_station LEFT JOIN stations AS toS ON toS.sl_id = trips.to_station GROUP BY toS.sl_id, fromS.sl_id ORDER BY number DESC");
						$stmt->execute();
						$total = 0;
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						?>
						<tr>
							<td><?php echo $row['number']; ?></td>
							<td><?php echo unbracket($row['from_s']); ?></td>
							<td><?php echo unbracket($row['to_s']); ?></td>
							<td><?php echo ($tal = $row['number'] * rand(50,70)/10); ?></td>
							<?php $total += $tal; ?>
						</tr>
						<?php
						}
						?>
					</table>
					<p>Totalt har du sparat <?php echo $total; ?> kg koldioxid på dessa resor istället för att ta bilen. Det är mer än bland annat <a href="#">Simon</a>, <a href="#">Agnes</a>, <a href="#">Karl</a> och <a href="#">Stina</a>.</p>
			</div>
		</div>
		</div>
	</body>
</html>