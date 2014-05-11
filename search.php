<?php

require_once("includes/init.php");
require_once("includes/functions.php");
require_once("includes/search.php");

define("WELCOME_MESSAGE", "Välkommen till Kollektivkollen! Här kan du både söka efter resvägar och se statistik över tidigare gjorda resor.");
$pageName = "Sökt resa";

$i = 0;
$iMax = count($trips) - 1;
$activeTrip = intval(( $iMax - $i ) / 2);
if (@$_POST['go'] == "go-earliest") {
	$activeTrip = 0;
} elseif (@$_POST['go'] == "go-latest") {
	$activeTrip = $iMax;
}

?><html>
	<head>
		<?php include("includes/head.php"); ?>
		<script type="text/javascript" src="<?php echo HTML_ROOT; ?>/js/search-result.js"></script>
		<script type="text/javascript">
			var activeTrip = <?php echo $activeTrip; ?>;
			var numberOfTrips = <?php echo count($trips); ?>;
			var lastTrip = <?php echo $iMax; ?>;	
		</script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">

		  // Load the Visualization API and the piechart package.
		  google.load('visualization', '1.0', {'packages':['corechart']});

		  // Set a callback to run when the Google Visualization API is loaded.
		  google.setOnLoadCallback(drawChart);

		  // Callback that creates and populates a data table,
		  // instantiates the pie chart, passes in the data and
		  // draws it.
		  function drawChart() {
			// Create the data table.
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Färdmedel');
			data.addColumn('number', 'kg CO2');
			data.addRows([
			  ['Kommunalt', <?php echo floatval(str_replace(",", ".", $slInfo->co2)); ?>],
			  ['Bil', <?php echo $carInfo->co2; ?>],
			]);

			// Set chart options
			var options = {	title: 'Koldioxidutsläpp',
							backgroundColor: "none",
							legend: {position: 'none'},
							hAxis: {title: 'Färdmedel', titleTextStyle: {color: 'Black'}},
							vAxis: {title: 'kg CO2', titleTextStyle: {color: 'Black'}, baseline: 0},
						   	height: 300, width : 300};

			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.ColumnChart(document.getElementById('comparison'));
			chart.draw(data, options);
			
			
			
			
			
			// Create the data table.
			data = new google.visualization.DataTable();
			data.addColumn('string', 'Färdmedel');
			data.addColumn('number', 'Minuter');
			data.addRows([
			  ['Kommunalt', <?php echo ceil($slInfo->seconds / 60); ?>],
			  ['Bil', <?php echo ceil($carInfo->seconds / 60); ?>],
			  ['Cykel', <?php echo ceil($bikeInfo->seconds / 60); ?>],
			  ['Gå', <?php echo ceil($walkInfo->seconds / 60); ?>],
			]);


			// Set chart options
			var options = {	title: 'Tidsåtgång',
							backgroundColor: "none",
							legend: {position: 'none'},
							hAxis: {title: 'Färdmedel', titleTextStyle: {color: 'Black'}},
							vAxis: {title: 'sekunder', titleTextStyle: {color: 'Black'}, baseline: 0},
						   	height: 300, width: 300};

			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.ColumnChart(document.getElementById('timesaving'));
			chart.draw(data, options);





			// TODO WHAT?
			// Create the data table.
			data = new google.visualization.DataTable();
			data.addColumn('string', 'Färdmedel');
			data.addColumn('number', 'kr');
			data.addRows([
			  ['Kommunalt', <?php echo ceil($slInfo->price * 10) / 10; ?>],
			  ['Bil', <?php echo ceil($carInfo->price * 10) / 10; ?>],
			]);

			// Set chart options
			var options = {	title: 'Pris',
							backgroundColor: "none",
							legend: {position: 'none'},
							hAxis: {title: 'Färdmedel', titleTextStyle: {color: 'Black'}},
							vAxis: {title: 'kr', titleTextStyle: {color: 'Black'}, baseline: 0},
						   	height: 300, width: 250};

			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.ColumnChart(document.getElementById('price'));
			chart.draw(data, options);
		  }
		</script>
	</head>
	<body>
		<?php include("includes/top_bar.php"); ?>
		<div class="container">
		<div class="search">
			<div id="top-content">
				<h1>Din resa</h1>
				<a href="#" class="upDown" onclick="showTrip(-1);">Tidigare</a>
				<ul>
					<?php foreach ($trips as $trip) : ?>
						<li id="trip_<?php echo $i; ?>"<?php echo ($activeTrip != $i++) ? ' style="display: none;"' : ""; ?>>
							<ul>
							<?php foreach ($trip["subtrips"] as $subtrip) : ?>
								<li><span class="key"><?php echo date("H:i", strtotime($subtrip["departureTime"])); ?></span> 
								<span class="val"><?php echo ucfirst(verb($subtrip["type"], $subtrip["transport"], $subtrip["towards"])); ?>
								från <?php echo preg_replace("/!/", "", $subtrip["originText"]); ?> till 
								<?php echo $subtrip["destinationText"]; ?>. Du är framme klockan
								<?php echo date("H:i", strtotime($subtrip["arrivalTime"])); ?>.</span><div class="clear"></div></li>
							<?php endforeach; ?>
							</ul>
						</li>
					<?php endforeach; ?>
				</ul>
				<a href="#" class="upDown" onclick="showTrip(1);">Senare</a>
			</div>
		</div>
		</div>
		<div class="info">
			<div id="tree" class="tree">
				<?php for($i = 0; $i < $tree/1000; $i++) : ?>
					<img src="img/tree.png" width="64" height="81">
				<?php endfor; ?>
				<p>Det krävs <br /><span><?php echo number_format($tree, 0, ',', ' '); ?> träd</span><br /> för att ta hand om CO<sub>2</sub>-utsläppen som en motsvarande bilresa genererar, om träden får arbeta lika länge som du kör bil.</p>
			</div>
		</div>
		<div class="info" style="background: none;">
			<div id="comparison" class="graph"></div>
			<div id="timesaving" class="graph"></div>
			<div id="price" class="graph"></div>
			<div class="clear"></div>
			<p>
				<?php 
				$diffCo2 = $carInfo->co2 - $slInfo->co2;
				$diffTime = round(($slInfo->seconds / $carInfo->seconds - 1) * 100);
				?>
				<?php if ($diffCo2 > 0): ?>
					Du sparar alltså <?php echo $diffCo2; ?> kg CO<sub>2</sub> på att åka kommunalt istället för att ta bilen. 
					Om du gör den här resan två gånger varje måndag till fredag i ett år, har du låtit bli att släppa ut 
					<?php echo number_format($diffCo2 * 2 * 5 * 365 / 7, 0, ",", " "); ?> kg CO<sub>2</sub>, vilket är 
					<?php echo number_format(($diffCo2 * 2 * 5 * 365 / 7) / 48, 0, ",", " "); ?> % av en genomsnittlig 
					svensks årliga koldioxidutsläpp. </p><p>
				<?php endif; ?>
				<?php if ($diffTime > 0): ?>
					Det tar <?php echo $diffTime; ?>% längre tid att åka kommunalt i förhållande till att ta bilen.
				<?php elseif ($diffTime < 0) : ?>
					Det går till och med <?php echo -$diffTime; ?>% fortare att åka kommunalt i förhållande till att ta bilen.
				<?php endif; ?>
			</p>
		</div>
		<div class="clear"></div>
  		<script type="text/javascript">
			$("#from-text-box").autocomplete({
  				source: "php/getJSONPlacesfromAPI.php",
  				minLength: 3,
  				select: function(event, ui) {
  					$("#from-val").val(ui.item.id);
  					$(this).addClass('ok');
  					$("#to-text-box").focus();
  				},
  				search: function(){$(this).addClass('working');},
				open: function(){$(this).removeClass('working');}
			});
			
			$("#to-text-box").autocomplete({
  				source: "php/getJSONPlacesfromAPI.php",
  				minLength: 3,
  				select: function(event, ui) {
  					$("#to-val").val(ui.item.id);
  					$(this).addClass('ok');
  					$("#go-now").focus();
  				},
  				search: function(){$(this).addClass('working');},
				open: function(){$(this).removeClass('working');}
			});
  		</script>
	</body>
</html>