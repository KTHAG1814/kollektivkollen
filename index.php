<?php

require_once("includes/init.php");
require_once("includes/functions.php");

$pageName = "Start";

?><html>
	<head>
		<?php include("includes/head.php"); ?>
		<script type="text/javascript">
		$(document).ready(function() {
			$("#adv").hide();
		});
		
		function switcha() {
			$("#simple").slideToggle();
			$("#adv").slideToggle();
		}
		</script>
	</head>
	<body>
		<?php include("includes/top_bar.php"); ?>
		<div class="container">
			<?php if (!isset($_COOKIE['hide'])) : ?>
			<div class="search" style="background: #ccbb77;padding: 5px 15px;" id="mess">
				<p>Välkommen till Kollektivkollen! Här kan du både söka efter resvägar och se statistik över tidigare gjorda resor. Om du registrerar ditt SLAccess-kort <a href="signup.php">här</a> kan du spåra dina resor och tävla mot andra. <a href="#" onclick="$('#mess').slideToggle();document.cookie='hide=true; expires=31 Dec 2015 12:00:00 GMT';">Dölj.</a></p>
			</div>
			<?php endif; ?>
			<div class="search">
				<form id="form" onsubmit="return validateForm();" action="search.php" method="post">
					<h1>Sök resa</h1>
					<div class="place-box">
						<div id="place">
							<input type="text" name="from" class="search-text-box" id="from-text-box" value="<?php echo isset($_POST['from']) ? $_POST['from'] : "Från"; ?>" onfocus="fromFocus();clearField(this, 'Från')" onFocusOut="resetField(this, 'Från')">
							<input type="hidden" id="from-val" name="from-val" value="<?php echo @$_POST['from-val']; ?>">
							<div class="place"><input type="checkbox" id="my-position" onchange="myPositionChanged();" /><label for="my-position">Använd min plats</span></label>
						</div>
					</div>
					<div class="place-box">
						<input type="text" class="search-text-box" name="to" id="to-text-box" value="<?php echo isset($_POST['to']) ? $_POST['to'] : "Till"; ?>" onfocus="clearField(this, 'Till')" onFocusOut="resetField(this, 'Till')">
						<input type="hidden" id="to-val" name="to-val" value="<?php echo @$_POST['to-val']; ?>">
					</div>
					<div class="time-box">
						<input type="radio" name="go" value="go-now" checked="checked" onchange="goNowChanged();" /><label for="go-now">Åka nu</label>
						<input type="radio" name="go" value="go-earliest" onchange="goNowChanged();" /><label for="go-earliest">Åka tidigast...</label>
						<br /><input type="radio" name="go" value="go-latest" onchange="goNowChanged();" /><label for="go-latest">Vara framme senast...</label>
						<div id="time">
							<input type="text" name="time" value="<?php echo date("H:i"); ?>" class="time">
							<div class="select">
								<select name="date">
									<?php for ($i = 0; $i <= 21; $i++) : ?>
										<option value="<?php echo date("Y-m-d", strtotime("+" . $i . " days")); ?>"><?php echo date("Y-m-d", strtotime("+" . $i . " days")); ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="place-box" style="margin: 10px 0;">
						<span id="simple">Jämför min resa med
						<div class="select" style="background-color: inherit;border: 0;width: 230px;">
							<select name="car">
								<option value="JEEP">en s.k. jeep.</option>
								<option value="BIG">en stor bil.</option>
								<option value="MEDIUM" selected="selected">en genomsnittlig bil.</option>
								<option value="SMALL">en liten bil.</option>
							</select>
						</div>
						</span>
						<br><a href="#" onclick="switcha()">Eller jämför mer avancerat.</a>
						<table id="adv">
							<tr>
								<td>Drivmedel</td>
								<td>
									<div class="select" style="width: 220px;">
										<select name="fuel">
											<option value="0">- Välj drivmedel -</option>
											<?php
											foreach($fuel as $key => $val) {
											if ($key == "HVO")
												echo '<option value="' . $key . '">' . $key . '</option>';
											else 
												echo '<option value="' . $key . '">' . ucfirst(strtolower($key)) . '</option>';
											}
											?>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>Bränsleförbrukning</td>
								<td>
									<div class="place-box">
										<input type="text" class="search-text-box" style="width: 70px;" name="fuelConsumption" id="tfuelConsumption" value="<?php echo isset($_POST['fuelConsumption']) ? $_POST['fuelConsumption'] : "0,7"; ?>"> l/mil / kWh/mil / Nm<sup>3</sup>/mil
									</div>
								</td>
							</tr>
						</table>
					</div>
					<div class="search-button-box">
						<input onsubmit="return validateForm();" type="submit" class="submit-button" name="submit-search" id="search-button" value="Sök">
					</div>
				</form>
			</div>
		</div>
	</body>
</html>