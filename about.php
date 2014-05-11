<?php

require_once("includes/init.php");
require_once("includes/functions.php");

$pageName = "Om";

?><html>
	<head>
		<?php include("includes/head.php"); ?>
	</head>
	<body>
		<?php include("includes/top_bar.php"); ?>
		<div class="container">
			<div class="search">
				<h1>Om kollektivkollen</h1>
				<p>Kollektivkollen är en tjänst som ska underlätta för Stockholms invånare att välja ett miljövänligt sätt att transportera sig på. Kollektivkollen är gjord som ett skolprojekt. Alla beräkningar är approximationer och nedan presenteras hur vi kommit fram till dessa.</p>
				<h2>Hur räknar vi?</h2>	
				<p>För att du, och vi, ska kunna lita på att den information som presenteras är korrekt är det viktigt att vi redovisar hur vi kommit fram till den. Här presenteras hur vi har räknat på de olika värdena.</p>
				<p>Alla sträckor tas från Google Maps, och räknas från den station, alternativt angiven plats, som SL-resan använder.</p>
				<h3>Träd</h3>
				<p>Antalet träd som visas när man sökt efter en resa baseras på koldioxidutsläppen samt tiden motsvarande bilresa tar. Formeln ser ut så här:</p>
				<p>Antal träd = CO<sub>2</sub>-utsläpp i kg * 5 256 000 / tiden i sekunder</p>
				<p>Källa: <a href="http://www.reklamfritt.se/tag/trad/">Reklamfritt</a></p>
				<h3>Tid</h3>
				<p>Tidsåtgång för bil tillhandahålls av Google Maps. Till denna tid adderas en parkeringstid på 2 minuter.</p>
				<p>Tidsåtgång för gång tas från Google Maps.</p>
				<p>Tidsåtgång för kollektivtrafik tas direkt från SL.</p>
				<p>Tidsåtgången med cykel räknar med att multiplicera gång-tiden med 4, eftersom genomsnittlig gångfart är 4 km/h och genomsnittlig cykelfart är 16 km/h.</p>
				<h3>CO<sub>2</sub>-utsläpp</h3>
				<p>Koldioxidutsläpp räknar vi ut med formeln C * B * S, där bokstäverna står för följande:<br />
				<ul>
					<li>C = drivmedlets CO<sub>2</sub>-utsläpp WTW (well to wheel) (standardvärde Bensin)
						<ul>
							<li style="margin: 0 20px;"><i>(<a href="http://www.miljofordon.se/fordon/miljopaverkan/sa-raknar-vi-miljopaverkan">Källa WTW-värden</a>)</i></li>
						</ul>
					</li>
					<li>B = bilens bränsleförbrukning (standardvärde 0.7l/mil)</li>
					<li>S = sträckan som bilen kör från startstation till slutstation.</li>
				</ul></p>
				<p>Standardbilarna bygger utsläpp i g/km, och således multipliceras denna faktor enbart med sträckan i det fallet.</p>
				<h3>Pris</h3>
				<p>Priset för kollektivtrafik räknas ut baserat på att man använder reskassa till helt pris.</p>
				<p>Priset för bilresan räknas ut genom bränsleförbrukningen * bränslepris. Bränslepriset är ungefärligt (&plusmn;1 kr) då det varierar kraftigt.</p>
			</div>
		</div>
	</body>
</html>